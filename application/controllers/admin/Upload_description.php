<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_description extends CI_Controller {

    public $folder = 'admin/description'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    public function __construct() {
        parent::__construct();
        
        $this->controllerName = "upload_description";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->load->library('PHPExcel');
    }

    public function index() {
        
        $data['page_title'] = 'Update Categories';
        $data['page_name'] = $this->folder . '/manage'; 
        $this->load->view($this->admin_template,$data);
    }

    public function upload(){

        $file_info = pathinfo($_FILES["upload_file"]["name"]);
        $filename = $file_info['filename'];
        $file_directory = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/";
        $new_file_name = date("Y-m-d")."-".$filename.".". $file_info["extension"];

        
           if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $file_directory . $new_file_name))
            { 

                $file_type  = PHPExcel_IOFactory::identify($file_directory . $new_file_name);
                $objReader  = PHPExcel_IOFactory::createReader('CSV');
                $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                $sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);


                //Old file description update
                /*foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $name = trim($data['B']);

                        $this->general->set_table('categories');
                        $check_name = $this->general->get('cat_id', array('name' => $name));
                        
                        if(!empty($check_name)){
                            foreach($check_name as $chkey => $chval){
                                $catid = $check_name[$chkey]['cat_id'];  
                                $description = trim($data['C']);
                               

                               $update_categories = json_encode(array( 'description' => $description));
                               
                               $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$catid;
                               $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);

                                 if(!empty($output['data'])){
                                    $this->general->set_table('categories');
                                    $updatedata['description'] = $description;
                                    $updatedata['description_updated'] = DATE_TIME;
                                    $updatedata['description_status'] = '1';

                                   $execute = $this->general->update($updatedata, array('cat_id' => $catid));
                               }
                                
                            }
                            
                        }
                    }    
                        
                }*/

                // new file update
                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $name = trim($data['A']);

                        $this->general->set_table('categories');
                        $check_name = $this->general->get('id,cat_id', array('name' => $name));
                        
                        if(!empty($check_name)){
                               $catid = $check_name[0]['cat_id']; 
                               $description = trim($data['B']);
                               $update_categories = json_encode(array( 'description' => $description));
                               
                               $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$catid;
                               $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);

                                 if(!empty($output['data'])){
                                    $this->general->set_table('categories');
                                    $updatedata['description'] = $description;
                                    $updatedata['description_updated'] = DATE_TIME;
                                    $updatedata['description_status'] = '1';

                                   $execute = $this->general->update($updatedata, array('cat_id' => $catid));
                               }
                        }
                    }    
                        
                }
                    
                if($execute){
                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
                }


        }
        else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
       redirect('admin/' . $this->controllerName);      

    }


}
?>