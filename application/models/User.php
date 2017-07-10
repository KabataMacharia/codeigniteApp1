<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model
{
	public function __construct(){
		parent::__construct();
		//$this->load->library('encrypt');
		$this->table = 'users';
	}

	public function create($user)
	{
		$result = $this->db->insert($this->table, $user);
		if($result){
			return $this->db->insert_id();
		} else{
			return false;
		}
	}

	public function verify($user){
		//$password = $this->encrypt->encode($user['password']);
		$result['status'] = false;
		$query = $this->db->get_where($this->table, ['email'=>$user['username'], 'password'=>$user['password']]);
		if($query->row()){
			$result['status'] = true;
			$result['user'] = $query->row();
			return $result;
		}

		return $result;
	}

	public function email_exists($email){
		$query = $this->db->get_where($this->table, ['email'=>$email]);
		if($query->row()){
			return true;
		}
		return false;
	}

	public function reset_password($password, $token){
		$query = $this->db->update($this->table, ['password'=>$password], ['password_reset_token'=>$token]);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function get_username_by_email($email){
		$query = $this->db->get_where($this->table, ['email'=>$email]);
		$user = $query->row();
		return $user->firstname." ".$user->lastname;
	}

	public function get_user_by_remember_me_token($token){
		$query = $this->db->get_where($this->table, ['remember_me'=>$token]);
		if($query->row()){
			return $query->row();
		}else{
			return false;
		}
	}

	public function token_exists($token){
		$query = $this->db->get_where($this->table, ['password_reset_token'=>$token]);
		if($query->row()){
			return true;
		}else{
			return false;
		}
	}

	public function destroy_token($token){
		$this->db->update($this->table, ['password_reset_token'=>''], ['password_reset_token'=>$token]);
	}

	public function create_reset_link($email){
		if($this->db->field_exists('password_reset_token', $this->table)){
			$factory = new RandomLib\Factory;
			$generator = $factory->getLowStrengthGenerator();
			$token = $generator->generateString(32, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
			$query = $this->db->update($this->table, ['password_reset_token'=>$token], ['email'=>$email]);

			if($query){
				$link = base_url("reset-password/$token");
				return $link;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function create_remember_me_token($email, $token){
		$this->db->update($this->table, ['remember_me'=>$token], ['email'=>$email]);
	}

	public function check_phone($phone){
		$query = $this->db->get_where($this->table, ['phone'=>$phone]);
		if($query->row()){
			return true;
		} else{
			return false;
		}
	}
}