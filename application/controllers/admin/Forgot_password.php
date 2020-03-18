<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

    public $controllerName = 'forgot_password';
    public $tblAdmin = 'user';
    
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard');
        }
        $this->load->model("general_model", 'general');
        $this->general->set_table($this->tblAdmin);
    }

    public function index() {

        $this->load->view('admin_forgot_password');
    }
    
    /**
     * Forgot Password
     * @access public
     * @return true or false (redirect to view)
     * @author  by Mahek
     */
    public function send_mail() {
            $postData = $this->input->post();
        //if ($postData = $this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email','Email', 'required|valid_email|max_length[250]');
            if ($this->form_validation->run() == FALSE) {

                $this->load->view('admin_forgot_password');

            } else {
                $email = $postData['email'];

                if ($result = $this->general->get("user_id, first_name, email", array('email' => $email,'role_id !='=>'3'))) {
                    
                    $id = $this->utility->encode($result[0]['user_id']);
                    $email = $result[0]['email'];
                    $subject = 'Reset your password?';

                    $email_data['btn_link'] = base_url(). 'admin/'.$this->controllerName.'/reset/'. $id;
                    $email_data['email_title'] = "Hello ".$result[0]['first_name'].' ,';
                    $email_data['email_content'] = "You have requested to reset password. Please click following link to reset password.";
                    $email_data['btn_text'] = "Reset Password";
                    $email_template = $this->load->view('email/common', $email_data, true);
                    //echo $email_template;exit;
                    if ($this->common->send_mail($email, $subject, $email_template)) {
                        $msg = '<div class = "alert alert-success alert-dismissible fade in" role = "alert"><button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close"><span aria-hidden = "true">×</span></button><strong><i class="fa fa-check"></i></strong> Your password was successfully sent to your mail </div>'; 
                        $this->session->set_flashdata('forgotError', $msg);
                        redirect("admin/forgot_password");
                    } else {
                        $this->session->set_flashdata('returnData',$this->input->post());
                        $msg = '<div class = "alert alert-danger alert-dismissible fade in" role = "alert"><button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close"><span aria-hidden = "true">×</span></button><strong><i class="fa fa-check"></i></strong> Sorry, Error occured while sending email. Please try again </div>';
                        $this->session->set_flashdata('forgotError', $msg);
                        redirect("admin/forgot_password");
                    }
                } else {
                    $msg = '<div class = "alert alert-danger alert-dismissible fade in" role = "alert"><button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close"><span aria-hidden = "true">×</span></button><strong><i class="fa fa-check"></i></strong> Sorry, No email found. </div>';
                    $this->session->set_flashdata('forgotError', $msg);
                    redirect("admin/forgot_password");
                }
            }
            
        /*} 
        redirect("admin/forgot_password");*/
    }
    /**
     * Reset Password
     * @param id - encodeed id
     * @access public
     * @return true or false (redirect to view)
     * @author  by Mahek
     */
    public function reset($id) {
        $data['id'] = $id;
        
        $response['type'] = 'error';
        $response['msg'] = '';
        
        if ($result = $this->general->get("user_id, first_name, email", array('user_id'=>$this->utility->decode($id)))) {
            
            if ($postData = $this->input->post()) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]');
                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('admin_reset_password',$data);
                } else {
                    $update_password['password'] = $postData['password'];
                    $this->general->update($update_password, array('user_id' => $result[0]['user_id']));

                    $msg = '<div class = "alert alert-success alert-dismissible fade in" role = "alert"><button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close"><span aria-hidden = "true">×</span></button><strong><i class="fa fa-check"></i></strong> Your password is reset. </div>';
                    $this->session->set_flashdata('loginError', $msg);

                    redirect("admin");
                }
            } else {
                $this->load->view('admin_reset_password', $data);
            }
        } 
        else {
            redirect("admin");
        }
    }
}
?>