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
}