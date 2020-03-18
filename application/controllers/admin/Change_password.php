<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Change_password extends CI_Controller {

    public $folder = 'admin/change-password'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";
    public function __construct() {
        parent::__construct();
        $this->table = "user";
        $this->controllerName = "change_password";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
    }

    public function index() {
        $data['page_title'] = 'Change Password';
        $data['page_name'] = $this->folder . '/change-password'; 
        $this->load->view($this->admin_template,$data);
       // $this->load->view('change_password');
    }

    public function changePassword(){
        $this->general->set_table($this->table);

        $currentPassword = $this->input->post('currentPassword');
        $newPassword = $this->input->post('newPassword');
        

        $data = $this->general->get("*",array('password'=>$currentPassword));
        if(!empty($data)){
            $result = $this->general->update(array('password'=>$newPassword), array("user_id" => $data[0]['user_id'])); 

             $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Password updated successfully.</strong></div>');
        
        }else{
             $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Current password does not match. Please try again!</strong></div>');
        
        }
        redirect('admin/' . $this->controllerName);
    }
    
}
?>