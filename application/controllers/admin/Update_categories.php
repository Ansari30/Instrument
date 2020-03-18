<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_categories extends CI_Controller {

    public $folder = 'admin/schema'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    public function __construct() {
        parent::__construct();
        
        $this->controllerName = "update_categories";
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
                $objReader  = PHPExcel_IOFactory::createReader($file_type);
                $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                $sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $name = trim($data['C']);

                        $this->general->set_table('categories');
                        $check_name = $this->general->get('cat_id,name', array('name' => $name));

                        if(!empty($check_name)){
                            foreach($check_name as $chkey => $chval){
                                $catid = $check_name[$chkey]['cat_id'];
                                $name = $check_name[$chkey]['name'];
                                $page_title = trim($data['E']);
                                $image_url = trim($data['D']);
                                $description = '';
                                $meta_keywords = explode(',' , trim($data['G']));
                                $meta_description = trim($data['F']);

                               $update_categories = json_encode(array('page_title' => $page_title, 'description' => $description, 'image_url' => $image_url, 'meta_keywords' => $meta_keywords, 'meta_description' => $meta_description ));
                               $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$catid;
                               $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);

                               if($output){
                                    $this->general->set_table('categories');
                                    $updatedata['image'] = $image_url;
                                    $updatedata['page_title'] = $page_title;
                                    $updatedata['description'] = $description;
                                    $updatedata['meta_keywords'] = trim($data['G']);
                                    $updatedata['meta_description'] = $meta_description;
                                    $updatedata['updated_at'] = DATE_TIME;
                                    $updatedata['status'] = '1';

                                    $this->general->update($updatedata, array('cat_id' => $catid));
                               }

                            }
                            /*   
                            */
                        }
                    }    
                        
                }
                    
                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName);      

    }

    public function upload_pending_product() {
        
        $data['page_title'] = 'Update Pending Product';
        $data['page_name'] = $this->folder . '/product-update'; 
        $this->load->view($this->admin_template,$data);
    }
    public function update_pending_product(){

        $file_info = pathinfo($_FILES["upload_file"]["name"]);
        $filename = $file_info['filename'];
        $file_directory = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/";
        $new_file_name = date("Y-m-d")."-".$filename.".". $file_info["extension"];

        
           if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $file_directory . $new_file_name))
            { 

                $file_type  = PHPExcel_IOFactory::identify($file_directory . $new_file_name);
                $objReader  = PHPExcel_IOFactory::createReader($file_type);
                $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                $sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

                
                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $id = trim($data['A']);
                        $name = trim($data['B']);
                        $url = trim($data['C']);
                        $product_array = array('name' => $name, "custom_url" =>array('url' => $url));
                        
                       $update_categories = json_encode($product_array);
                       $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$id;
                       $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);

                       if($output){
                           $this->general->set_table('products_duplicate');
                           $savearray['p_id'] = $id;
                           $savearray['p_name'] = $name;
                           $savearray['p_url'] = $url;
                           $savearray['status'] = '1';
                           $savearray['created_at'] = DATE_TIME;
                           $result = $this->general->save($savearray);
                       }
                    }    
                        
                }
                    
                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName.'/upload_pending_product');      

    }


}
?>