<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('user')){
			//redirect('login');
		}
	}

	public function home(){
		$data['title'] = 'Home';
		
		$this->load->view('home', $data);
	}

	public function passemail(){
		$data['username'] = 'Anthony Githinji';
		$data['link'] = 'http://localhost/codeapp1';
		
		$this->load->view('emails/password_reset', $data);
	}
}