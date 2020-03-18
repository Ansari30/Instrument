<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Setting extends CI_Controller {
	public $tableName = 'settings';
 	public $folder = 'admin'; //Name Of The Folder in View
 	public $admin_template = "admin_dashboard_template";
 	 
 	function __construct() {
        parent::__construct();
         
        $this->controllerName = "setting";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->loggedin_id = $this->session->userdata('admin_logged_in')['id'];
    }

	public function index() {

		$data['page_title'] = "Setting";
		if ($postData = $this -> input -> post('settings_data')) {
			//if data recieved in post
			$this->general->set_table('settings');
			$is_updated = FALSE;

			//Update value based on key
			foreach ($postData as $key => $value) {
				$condition['key'] = $key;
				$udpate_settings['value'] = $value;
				if ($this -> general-> update($udpate_settings, $condition)) {
					$is_updated = TRUE;
				}

				unset($condition);
				unset($udpate_settings);
			}
			$this->session->set_flashdata('page_msg', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i></strong>Setting updated successfully </div>');

			redirect('admin/setting');
		} else {
			$this->general->set_table("settings");
			$data['settings_data'] = $this -> general-> get("");

	        $data['page_name'] = $this->folder . '/website_setting'; 
	        $this->load->view($this->admin_template,$data);

		}
	}
}
?>