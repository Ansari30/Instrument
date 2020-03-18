<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller{
	function index(){
		$this->session->unset_userdata('admin_logged_in');
	   //$this->session->sess_unset('admin_logged_in');
	   redirect('admin');
	}
}
?>