<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use telesign\sdk\messaging\MessagingClient;
use function telesign\sdk\util\randomWithNDigits;
class Users extends CI_Controller
{

	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		//$this->load->library('encrypt');
		$this->load->model('user');
	}

	public function get_login(){
		$data = array();

		if($this->session->userdata('success')){
			$data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}

		if($this->session->userdata('error')){
			$data['error'] = $this->session->userdata('error');
			$this->session->unset_userdata('error');
		}

		$data['title'] = 'Login';
		$this->load->view('user/login', $data);
	}
	
	public function login()
	{
		$user_data = array();
		if($this->input->post('login_submit')){
			$this->form_validation->set_rules('username', 'Email', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			$user_data = array(
				'username' => strip_tags($this->input->post('username')),
				'password' => md5($this->input->post('password'))
			);

			if($this->form_validation->run() == true){
				$result = $this->user->verify($user_data);
				if($result['status']){
					$this->session->set_userdata('userLogged', true);
					$this->session->set_userdata('user', $result['user']);
					
					if($this->otp_authenticate($result['user'])){
						echo json_encode(['page'=>$this->get_otp()]);
					}else{
						echo json_encode(['error'=>'Could not send authentication code']);
					}		
				}else{
					echo json_encode(['error'=>'Invalid username/password']);
				}
			}
		}
	}

	public function get_register(){
		$data = array();

		//$data['user'] = $user_data;
		$data['title'] = 'Register';

		$this->load->view('user/register', $data);
	}

	public function register()
	{	
		$user_data = array();

		if($this->input->post('register_submit')){
			$this->form_validation->set_rules('firstname', 'First name', 'required');
			$this->form_validation->set_rules('lastname', 'Last name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone number', 'required');
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

			$user_data = array(
				'firstname' => strip_tags($this->input->post('firstname')),
				'lastname'  => strip_tags($this->input->post('lastname')),
				'email' 	=> strip_tags($this->input->post('email')),
				'phone'		=> strip_tags($this->input->post('phone')),
				'password'	=> md5($this->input->post('password'))
			);

			if($this->form_validation->run() == true){
				$insert = $this->user->create($user_data);
				if($insert){
					$this->session->set_userdata('success', 'Registration Successful');
					redirect('login');
				} else{
					$data['errormsg'] = 'A problem occurred. Please try again';
				}
			}
		}
	}

	public function check_email($email){
		if($this->user->email_exists($email)){
			$this->form_validation->set_message('check_email', 'The email address already exists');
			return false;
		}
		return true;
	}

	public function get_otp(){
		$csrf = [
			'name'  => $this->security->get_csrf_token_name(),
			'token' => $this->security->get_csrf_hash()
		];
		return '<div class="col-md-4 col-md-offset-4 well otp-box">
			<form action="'.base_url().'index.php/otp" method="post" id="otp_form">
				<div class="form-group">
					<label>A code has been sent to your phone number. Enter it below.</label>
					<input type="text" name="otp" class="form-control">
				</div>
				<input type="submit" name="otp_submit"  id="otp_submit" value="Submit" class="btn btn-primary btn-block btn-raised">
				<input type="hidden" name="otp_submit" value="otp_submit">
				<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['token'].'">
			</form>
		</div>
	</div>';
	}

	public function otp(){
		$data = array();
		if($this->session->userdata('otp_success')){
			$data['success'] = $this->session->userdata('otp_success');
			$this->session->unset_userdata('otp_success');
		}

		if($this->session->userdata('otp_error')){
			$data['error'] = $this->session->userdata('otp_error');
			$this->session->unset_userdata('otp_error');
		}

		if($this->input->post('otp_submit')){
			$this->form_validation->set_rules('otp', 'authentication code', 'required');

			$verify_code = $this->session->userdata('otp_auth');
			$user_code = strip_tags($this->input->post('otp'));

			if($verify_code == $user_code){
				$this->session->set_userdata('otp_success', 'Authentication successful');
				$redirect = base_url().'index.php/home';
				echo json_encode(['success'=>'Authentication successful', 'redirect'=>$redirect]);
			}else{
				$this->session->set_userdata('otp_error', 'Authentication failed');
				echo json_encode(['error'=>'Authentication failed']);
			}
		}
	}

	public function otp_authenticate($user){

		$customer_id = "FBF971D8-454D-44A8-BAAE-34444A7EDCDD";
		$api_key = "vdsJxNRsHlTWWZzJDXQopHeEWKvEmYab0BUXnCl6yytva5eksBKaHiq3+xcmR1st16RVZiR+c/R0tuqKSIsYcA==";

		$user_phone = '';
		if(strpos($user->phone, '0') === 0){
			$user_phone = substr_replace($user->phone, '254', 0, 1);
		} else{
			$user_phone = $user->phone;
		}

		$verify_code = randomWithNDigits(5);

		$phone_number = $user->phone;
		$message = "Your verification code is $verify_code";
		$message_type = "OTP";

		$messaging_client = new MessagingClient($customer_id, $api_key);
		$response = $messaging_client->message($user_phone, $message, $message_type);

		$auth = $response->json;
		if($auth['status']['code'] == 290){
			$this->session->set_userdata('otp_auth', $verify_code);
			return true;
		}

		return false;
	}

	public function logout(){
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('userLogged');

		redirect('welcome');
	}
}