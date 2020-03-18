<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		if ($this->session->userdata('admin_logged_in')){
			redirect('admin/dashboard');
		}
	}
	public function index(){
		$this->load->view('admin_login');
	}
	
	function login(){ 
		$data['page_title'] = "login";
		$this->load->view('login',$data);
	} 
	
	function logout(){
		
		$this->session->sess_destroy();
		redirect($this->config->item('base_url').'admin/login');
		
	}
} 