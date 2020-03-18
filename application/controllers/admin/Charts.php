<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Charts extends CI_Controller {
    
    public $folder = 'admin'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    function __Construct() {
        parent::__Construct();
 
        $this->load->helper(array('form', 'url'));
        //$this->load->model('data_model');
       
    }
    /**
     * @desc: This method is used to load view
     */
    public function index()
    {
        $data['page_title'] = 'Categories';
        $data['page_name'] = $this->folder.'/linechart'; 
        $this->load->view($this->admin_template,$data);
    }
    /**
     * @desc: This method is used to get data to call model and print it into Json
     * This method called by Ajax
     */
    function getdata(){
        $this->general->set_table('company_performance');
        $data  = $this->general->get('*');
        print_r(json_encode($data, true));
    }
}