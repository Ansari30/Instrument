<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common {

    public $CI;
    var $language_id = '1';
    var $uid;
    var $atten_id;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->database();
        
        $this->language_id = $this->CI->session->userdata('sess_language_id');

        
    }
    public  function print_block($page_url){
        $this->CI->general->set_table("pages");
        if($page_content = $this->CI->general->get("", array("page_url" => $page_url)))
        {
            echo html_entity_decode($page_content['0']['content']);
        }
    }
    /**
     * Sent Mail function
     * @access public
     * @return true or false
     * @author Rajnish
     */
    public function send_mail($to, $subject, $message, $from_email = "",$ccmail='') {
      
        if ($from_email) {
            $from = $from_email;
        } else {
            $from = INFO_EMAIL;
        }
        $config['charset'] = 'UTF-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        
        $this->CI->load->library('email');
        $this->CI->email->initialize($config);
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->set_mailtype("html");
        $this->CI->email->from($from,'TheFlamelily');
        $this->CI->email->to($to);
        if($ccmail){
            $this->CI->email->cc($ccmail);
        }
        $this->CI->email->Bcc('bliss.dipesh@gmail.com');
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        
        if ($this->CI->email->send()) {
            return true;
        } else
            return false;
    }

    /**
     * Total minutes function
     * @access public
     * @return total of minutes is current user and current date
     * @author Mahek
     */
    public function total_minutes() {
        $condition['emp_id'] = $this->uid;
        $condition['attendence_id'] = $this->atten_id;
        $condition['end_time !='] = "00:00:00";

        $this->CI->db->select('*, TIMESTAMPDIFF(MINUTE, start_time, end_time) as total_mins')->from('employees_attendance_session')->where($condition);
        
        $query = $this->CI->db->get();
        $result = $query->result_array();
        
        $total_mini = 0;
        foreach ($result as $key => $value) {
            $start_time = $value['start_time'];
            $end_time = $value['end_time'];

            $total_mini += $value['total_mins'];
        }
        return $total_mini;
    }

    
    
} 