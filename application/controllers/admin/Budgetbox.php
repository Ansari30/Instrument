<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    error_reporting('E_ALL');
class Budgetbox extends CI_Controller {

    public $folder = 'admin/budgetbox'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    public function __construct() {
        parent::__construct();
        
        $this->controllerName = "budgetbox";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->load->library('PHPExcel');
    }

    public function index() {
        $data['page_title'] = 'Budgetbox';
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

                $file_directory_Yykwcf = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/ykc";
                foreach($sheet_data as $key => $data)
                {
                    if($key > 1){
                        $array_data = $sheet_data[$key];
                        foreach($array_data as $data){
                            $data = trim($data);
                            if($data!=''){
                                if(!file_exists($file_directory_Yykwcf.'/'.basename($data))){
                                file_put_contents(
                                    $file_directory_Yykwcf.'/'.basename($data), // where to save file
                                    file_get_contents($data)
                                );
                                }
                            }
                        }
                    }
                }
                    
            $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName);      

    }


    public function relatedview() {
        $data['page_title'] = 'Budgetbox';
        $data['page_name'] = $this->folder . '/manage-related'; 
        $this->load->view($this->admin_template, $data);
    }

    public function relatedupload(){

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

                
                $file_directory_Yykwcf = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/realated";
                foreach($sheet_data as $key => $data)
                {
                    if($key > 1){
                        $array_data = $sheet_data[$key];
                        foreach($array_data as $data){
                            
                            if($data!=''){
                                $data = trim($data);
                                $data_explode = explode(' ', $data);
                                $file_name = trim($data_explode[0]);

                                if(!file_exists($file_directory_Yykwcf.'/'.basename($file_name))){
                                    file_put_contents(
                                        $file_directory_Yykwcf.'/'.basename($file_name), // where to save file
                                        file_get_contents($file_name)
                                    );
                                }
                            }
                        }
                    }
                }
                    
            $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName);      

    }

    public function zurclrview() {
        $data['page_title'] = 'Budgetbox';
        $data['page_name'] = $this->folder . '/manage-zurclr'; 
        $this->load->view($this->admin_template, $data);
    }

    public function zurclrupload(){

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

                $file_directory_Yykwcf = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/zurclr";

                
                foreach($sheet_data as $key => $data)
                {
                    if($key > 1){
                        $array_data = $sheet_data[$key];
                        foreach($array_data as $data){
                            if($data!=''){
                                $data = str_replace("'", '', $data);
                                libxml_use_internal_errors(true);
                                $dom = new domdocument;
                                $dom->loadHTML($data);

                                foreach ($dom->getElementsByTagName("a") as $a) {
                                   
                                    $file_name = $a->getAttribute("href");

                                    if(strpos($file_name, "https://") === false){
                                    } else{
                                        if(!file_exists($file_directory_Yykwcf.'/'.basename($file_name))){
                                        file_put_contents(
                                            $file_directory_Yykwcf.'/'.basename($file_name), // where to save file
                                            file_get_contents($file_name)
                                        );
                                    }
                                    } 
                                   
                                }
                            }
                        }
                    }
                }
 
                
            $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName.'/zurclrview');      

    }
    

    function filter_by_comma($str){
        $str = str_replace(' , ', ',', $str);
        $str = trim($str, ' , ');
        $str = trim($str, ' ,');
        $str = trim($str, ',');

        return $str;
    }

}
?>