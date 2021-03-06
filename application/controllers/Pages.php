<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('user') || !$this->session->userdata('userLogged')){
			$this->session->set_userdata('redirect_url_login', current_url());
			redirect('login');
		}
	}

	public function home(){
		$data['title'] = 'Home';
		
		$this->load->view('home', $data);
	}
}