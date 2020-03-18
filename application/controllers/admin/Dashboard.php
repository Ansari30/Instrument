<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller{
	
	function __construct() {
        parent::__construct();  
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }

    }

	public function index(){
		
		$data['page_title'] = 'Dashboard';
		 
        //$data['total_product'] = $this->general->count_record( array("product_id != " => '0'),"products"); // For Consultant role_id = 3 #check role table ]
        $sql = 'SELECT count(id) as total FROM categories WHERE status="0"';
        $query = $this->general->custom_query($sql);
        $data['total_product'] = $query[0]['total'];

        $sql = 'SELECT count(id) as total FROM categories WHERE description_status="0"';
        $query = $this->general->custom_query($sql);
        $data['total_product_description'] = $query[0]['total'];


		$data['page_name'] = "admin/admin_dashboard";
		$this->load->view('admin_dashboard_template', $data);
	}

}
?>