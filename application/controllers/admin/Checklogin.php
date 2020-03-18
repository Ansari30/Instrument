<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CheckLogin extends CI_Controller {

    //public $tableName = 'user';//table name
    public $controllerName = 'checkLogin';
    private $uid;

    public function __construct() {
        parent::__construct();
        $this->load->model('general_model', 'general');
        $this->general->set_table('user');
        if ($this->session->userdata('admin_logged_in')){
            redirect('admin/dashboard');
        }
    }

    public function index() {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('txtUserEmail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('txtPassword', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('loginError','');
            $this->load->view('admin_login');
        } else {
    

            //Field validation succeeded.  Validate against database
            $username = $this->input->post('txtUserEmail');
            $password = $this->input->post('txtPassword'); 
            
            $condition = " user_id != '0' AND email = '".$username."' AND password = '".$password."' AND ( role_id = '1' OR role_id = '2' )  "; 
            /*$condition['email'] = $username;
            $condition['password'] = $password;
            $condition['role_id'] = '1';*/
            

            $result = $this->general->get('', $condition);
             
            if ($result) {
                $sess_array = array();
                foreach ($result as $row => $value) {
                    $sess_array = array(
                        'id' => $value['user_id'],
                        'username' => $value['first_name'],
                        'role_id'=>$value['role_id'],
                        'email'=>$value['email']
                    ); 
                    $this->general->update(array('last_login' => date('Y-m-d H:i:s')), array('user_id' => $value['user_id']));
                    $this->session->set_userdata('admin_logged_in', $sess_array);
                    
                }
                redirect('admin/dashboard');
            } else { 
                $this->session->set_flashdata('loginError', '<div class="alert alert-error"><strong><i class="icon-remove"></i></strong><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button> Invalid username and password.<br></div>');
                $this->load->view('admin_login');
            }

            
        }
    }

    function check_database($password) {

        //Field validation succeeded.  Validate against database
        $username = $this->input->post('txtUserEmail');
         
        //$result = $this->user->checkLogin($username, $password);
        $condition['email'] = $username;
        $condition['password'] = $password;

        $result = $this->general->get('', $condition);
         
        if ($result) {
            $sess_array = array();
            foreach ($result as $row => $value) {
                $sess_array = array(
                    'id' => $value['user_id'],
                    'username' => $value['first_name'],
                	'role_id'=>$value['role_id'],
                	'email'=>$value['email']
                ); 
                $this->general->update(array('last_login' => date('Y-m-d H:i:s')), array('user_id' => $value['user_id']));
                $this->session->set_userdata('admin_logged_in', $sess_array);
                
            }
            return TRUE;
        } else {
            /*$this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong><i class="icon-remove"></i></strong><button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button> ', '<br></div>');*/
            $this->form_validation->set_message('check_database', "Invalid Email or pasword");
            return false;
        }
    }
}
