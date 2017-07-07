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
		$this->load->helper('cookie');
		$this->config->load('custom_variables');
	}

	public function get_login(){
		$remember_me = $this->input->cookie('auth_app_remme');
		if($remember_me){
			$user = $this->user->get_user_by_remember_me_token($remember_me);
			if($user){
				$this->session->set_userdata('user', $user);
				$this->session->set_userdata('userLogged', true);
			}
		}

		if($this->session->userdata('user') && $this->session->userdata('userLogged')){
			redirect('home');
		}

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
		$data['user_cookie'] = $this->input->cookie('auth_app_remme');
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

				if($this->input->post('remember-me')){
					$this->session->set_userdata('remember_me', 'set');
				}

				if($result['status']){
					if($this->otp_authenticate($result['user'])){
						$this->session->set_userdata('user', $result['user']);
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
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]', ['is_unique'=>'The email address already exists']);
			$this->form_validation->set_rules('phone', 'Phone number', 'required|is_unique[users.phone]', ['is_unique'=>'The phone number already exists']);
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');
			$this->form_validation->set_rules('g-recaptcha-response', 'Verify you are human', 'required');

			$g_recaptcha = $this->input->post('g-recaptcha-response');
			$g_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lce-icUAAAAAKu6EP4tKa3_bfIk1JN1QewmR8LJ&response=$g_recaptcha");
			$g_response = json_decode($g_response, true);
			if($g_response["success"] !== true){
				echo json_encode(['error'=>'Verify that you are human']);
				return;
			}

			$user_data = array(
				'firstname' => strip_tags($this->input->post('firstname')),
				'lastname'  => strip_tags($this->input->post('lastname')),
				'email' 	=> strip_tags($this->input->post('email')),
				'phone'		=> $this->format_phone_number($this->input->post('phone'), $this->input->post('phone_pref')),
				'password'	=> md5($this->input->post('password'))
			);

			if($this->form_validation->run() == true){
				$insert = $this->user->create($user_data);
				if($insert){
					//$this->session->set_userdata('success', 'Registration Successful');
					//$this->session->set_flashdata('new_reg', 'true');
					//$data['title'] = 'Login';
					//$data['success'] = 'Registration Successful';
					//$page = $this->load->view('user/login', $data, true);
					//echo json_encode(['page'=>$page]);
					echo json_encode(['success'=>'Registration Successful']);
					return;
				} else{
					echo json_encode(['error' => 'A problem occurred. Please try again']);
				}
			}
		}

		$countries = file_get_contents('https://restcountries.eu/rest/v2/all');
		$data['title'] = 'Register';
		$data['countries'] = json_decode($countries);

		$this->load->view('user/register', $data);
	}

	public function format_phone_number($phone, $phone_pref){
		if(strpos($phone, '0') === 0){
			return substr_replace(strip_tags($phone), strip_tags($phone_pref), 0, 1);
		}else{
			return strip_tags($phone_pref.$phone);
		}
	}

	public function get_otp(){
		$csrf = [
			'name'  => $this->security->get_csrf_token_name(),
			'token' => $this->security->get_csrf_hash()
		];
		$btn_text = "Verifying... <i class='fa fa-spinner fa-pulse fa-fw'></i>";
		return '
		<div class="col-md-4 col-md-offset-4 otp-box">
			<div class="well">
				<div class="alert alert-success resend_success" style="display: none;"></div>
				<div class="alert alert-danger resend_error" style="display: none;"></div>
				<form action="'.base_url('otp').'" method="post" id="otp_form">
					<div class="form-group">
						<label>A code has been sent to your phone number. Enter it below.</label>
						<input type="text" name="otp" class="form-control">
					</div>
					<button type="submit" id="otp_submit" data-loading-text="'.$btn_text.'" class="btn btn-primary btn-block btn-raised" autocomplete="off">
					  Verify
					</button>
					<input type="hidden" name="otp_submit" value="otp_submit">
					<input type="hidden" name="'.$csrf['name'].'" value="'.$csrf['token'].'">
				</form>
				<div class="text-center"><a id="resend_code" data-resend="'.base_url('resend-code').'">Resend Code<i style="display:none;" id="resend-progress" class="fa fa-spinner fa-pulse fa-fw"></i></a></div>
			</div>
		</div>
	</div>';
	}

	public function otp(){
		$data = array();
		$user = $this->session->userdata('user');
		//$this->session->unset_userdata('user');
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
				$this->session->set_userdata('userLogged', true);
				//$this->session->set_userdata('user', $user);

				if($this->session->userdata('remember_me')){
					$this->session->unset_userdata('remember_me');
					$factory = new RandomLib\Factory;
					$generator = $factory->getLowStrengthGenerator();
					$token = $generator->generateString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
					$domain = base_url();
					$this->user->create_remember_me_token($user->email, $token);
					$cookie = array(
					        'name'   => 'auth_app_remme',
					        'value'  => $token,
					        'expire' => '315360000',
					        'path'   => '/'
					);
					$this->input->set_cookie($cookie);
				}
				if($this->session->userdata('redirect_url_login')){
					$redirect = $this->session->userdata('redirect_url_login');
					$this->session->unset_userdata('redirect_url_login');
				}else{
					$redirect = base_url('home');
				}
				echo json_encode(['success'=>'Authentication successful', 'redirect'=>$redirect]);
			}else{
				$this->session->set_userdata('otp_error', 'Authentication failed');
				echo json_encode(['error'=>'Authentication failed']);
			}
		}
	}

	public function resend_code(){
		$user = $this->session->userdata('user');
		if($this->otp_authenticate($user)){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function otp_authenticate($user){

		$customer_id = "FBF971D8-454D-44A8-BAAE-34444A7EDCDD";
		$api_key = "vdsJxNRsHlTWWZzJDXQopHeEWKvEmYab0BUXnCl6yytva5eksBKaHiq3+xcmR1st16RVZiR+c/R0tuqKSIsYcA==";

		$verify_code = randomWithNDigits(5);

		$message = "Your verification code is $verify_code";
		$message_type = "OTP";

		$messaging_client = new MessagingClient($customer_id, $api_key);
		$response = $messaging_client->message($user->phone, $message, $message_type);

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

		$cookie = array(
		        'name'   => 'auth_app_remme',
		        'value'  => '',
		        'expire' => '',
		        'path'   => '/'
					);
		$this->input->set_cookie($cookie);

		redirect('welcome');
	}

	public function forgot_password(){
		if($this->input->post('reset_email_submit')){
			$this->form_validation->set_rules('reset_email', 'reset_email', 'required|valid_email');

			if($this->form_validation->run() == true){
				$email = $this->input->post('reset_email');

				if($this->user->email_exists($email)){
					$username = $this->user->get_username_by_email($email);
					$link  = $this->user->create_reset_link($email);
					$sendgrid_key = $this->config->item('sendgrid_key');

					if($link !== false){
						$data['username'] = $username;
						$data['link'] = $link;
						$email_page = $this->load->view('emails/password_reset',$data, true);
						$from = new SendGrid\Email("Telesign two factor auth app", "anthony.g@mambo.co.ke");
						$subject = "Your app password reset request";
						$to = new SendGrid\Email($username, $email);
						$content = new SendGrid\Content("text/html", $email_page);
						$mail = new SendGrid\Mail($from, $subject, $to, $content);
						$sg = new \SendGrid($sendgrid_key);
						$response = $sg->client->mail()->send()->post($mail);
						if($response->statusCode() == 202){
							echo json_encode(['success'=>'Reset password email sent']);
							return;
						}else{
							echo json_encode(['error'=>'Reset password email could not be sent']);
							return;
						}
					}else{
						echo json_encode(['error'=>'An error occurred. Contact the administator.']);
						return;
					}
				} else{
					echo json_encode(['error'=>'The email address is not associated with an account']);
					return;
				}	
			}
		}

		$data['title'] = 'Forgot password';

		return $this->load->view('user/forgotpassword', $data);
	}

	public function get_reset_password($token){
		$data['title'] = 'Forgot password';
		$data['token'] = $token;

		return $this->load->view('user/resetpassword', $data);
	}

	public function reset_password(){
		if($this->input->post('reset_submit')){
			$this->form_validation->set_rules('password', 'password', 'required');
			$this->form_validation->set_rules('password_confirm', 'password', 'required|matches[password]');
			//$this->form_validation->set_rules('r_token', 'password reset token', 'required');

			if($this->form_validation->run() == true){
				$token = strip_tags($this->input->post('r_token'));
				if($this->user->token_exists($token)){
					$return = $this->user->reset_password(md5($this->input->post('password')), $token);
					if($return){
						$this->user->destroy_token($token);
						echo json_encode(['success'=>'Password saved']);
					}else{
						echo json_encode(['error'=>'Your password could not be changed']);
					}
				}else{
					echo json_encode(['error'=>'Invalid password reset token']);
				}
			}
		}
	}
}