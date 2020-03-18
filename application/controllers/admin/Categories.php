<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    error_reporting('E_ALL');
class Categories extends CI_Controller {

    public $folder = 'admin/match-category'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    public function __construct() {
        parent::__construct();
        
        $this->controllerName = "categories";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->load->library('PHPExcel');
    }


    // TEST
    public function test_cat(){
            $this->general->set_table('categories');

            $description = '<p><strong class=”descruption_bold”>We can source many different parts for this unit that may not be listed below. If you are looking for a particular&nbsp;part you dont see or need help finding something,&nbsp;please contact us at <a href="mailto:sales@instrumentalparts.com?subject=Part%20Avail">sales@instrumentalparts.com</a></strong></p>';
                $i = 82301;
               for($i; $i<=82311; $i++){
                       $update_categories = json_encode(array('description' => $description ));
                       $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$i;
                       $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);
                                
                       if($output){
                            $updatedata['description'] = $description;
                            $this->general->update($updatedata, array('cat_id' => $i));
                       }
               } 
               
    }

    public function match_category() {
        $data['page_title'] = 'Categories';
        $data['page_name'] = $this->folder . '/manage'; 
        $this->load->view($this->admin_template,$data);
    }
    public function create_category() {
        $data['page_title'] = 'Categories';
        $data['page_name'] = $this->folder . '/create-category'; 
        $this->load->view($this->admin_template,$data);
    }

    public function assign_product() {
        $data['page_title'] = 'Categories';
        $data['page_name'] = $this->folder . '/assign-product-category'; 
        $this->load->view($this->admin_template,$data);
    }

    public function category_8999_match() {
        $data['page_title'] = 'Categories';
        $data['page_name'] = $this->folder . '/category-8999-match'; 
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

                $remaing_match_array = [];
                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $name = trim($data['A']);
                        $sku = trim($data['B']);

                        $this->general->set_table('categories');
                        $check_name = $this->general->get('cat_id', array('name' => $name));

                        if(!empty($check_name)){
                            foreach($check_name as $chkey => $chval){
                                    $catid = $check_name[$chkey]['cat_id'];
                                    
                                    $this->general->set_table('categories');
                                    $updatedata['match_updated_at'] = DATE_TIME;
                                    $updatedata['match_status'] = '1';
                                    $this->general->update($updatedata, array('cat_id' => $catid));

                            }
                        }else{
                            $remaing_match_array[] = array($name, 'Not Found', $sku);
                        }   
                    }  
                        
                }
                    
                // output headers so that the file is downloaded rather than displayed
                header('Content-type: text/csv');
                header('Content-Disposition: attachment; filename="output(6).csv"');
                 
                // do not cache the file
                header('Pragma: no-cache');
                header('Expires: 0');
                 
                // create a file pointer connected to the output stream
                $file = fopen('php://output', 'w');
                 
                // send the column headers
                fputcsv($file, array('Category Name', 'SKU'));
                 
                // Sample data. This can be fetched from mysql too


                // output each row of the data
                foreach ($remaing_match_array as $key => $val)
                {       
                        $rowData = $val;
                        fputcsv($file, $rowData);
                }
                 


                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName.'/match_category');      

    }


    // Create new category in BigCommerce Using API
    public function create_new_category()
    {
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

                $remaing_match_array = [];

                 
                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){

                        $name = trim($data['A']);
                        $image_url = trim($data['B']);
                        $page_title = trim($data['C']);
                        $meta_description = trim($data['D']);
                        $meta_keywords = explode(',' , trim($data['E']));
                        $parent_category = trim($data['F']);
                        $description = trim($data['G']);

                        /*$this->general->set_table('categories');
                        $check_name = $this->general->get('parent_id', array('name' => $parent_category));
                        if(!empty($check_name)){
                            $parent_id = $check_name[0]['parent_id'];
                        }else{
                            $parent_id = 0;
                        }*/

                        // code change according new file 27-04-2019
                        $parent_id = $parent_category;

                        $update_categories = json_encode(array('parent_id' => $parent_id, 'name' => $name, 'page_title' => $page_title, 'description' => $description, 'image_url' => $image_url, 'meta_keywords' => $meta_keywords, 'meta_description' => $meta_description ));
                               $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY;
                               $output = $this->utility->curl_response($update_category_url,'POST',$update_categories);
                                
                       if($output){
                            $this->general->set_table('categories');

                            $updatedata['parent_id'] = $parent_id;
                            $updatedata['name'] = $name;
                            $updatedata['image'] = $image_url;
                            $updatedata['page_title'] = $page_title;
                            $updatedata['description'] = $description;
                            $updatedata['meta_keywords'] = trim($data['E']);
                            $updatedata['meta_description'] = $meta_description;
                            $updatedata['new_category_at'] = DATE_TIME;
                            $updatedata['new_category_status'] = '1';

                            $this->general->save($updatedata);
                       }

                    }  
                        
                }
                    
                
                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName.'/create_category'); 
    }

    // Assigned a catorgory in particular product
    public function assigned_category_to_product(){
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

                $remaing_match_array = [];
                
                foreach($sheet_data as $key => $data)
                {
                    if($key > '1'){
                        $name = trim($data['A']);    // Category name
                        //$sku = $this->filter_by_comma($data['B']);     // product SKU  // // previous file showing with comma seperated value so change according your data
                        $sku = trim($data['B'], '|');

                        $this->general->set_table('categories');
                        $category_id_arry = $this->general->get('cat_id', array('name' => $name));

                        $cat_id = [];
                        if(!empty($category_id_arry)){

                            foreach($category_id_arry as $catkey => $catval){
                               $cat_id[] = $category_id_arry[$catkey]['cat_id'];
                            }

                            $cat_id_BC = $cat_id;
                            $cat_id_DB = implode(',',$cat_id);

                            $sku_array = explode('|', $sku);  

                            foreach($sku_array as $skukey => $skuval){
                                $sku_name = trim($skuval);

                                $this->general->set_table('products');
                                $product_sku_arry = $this->general->get('product_id,categories', array('sku' => $sku_name));

                                if(!empty($product_sku_arry)){
                                    $categories_id = $product_sku_arry[0]['categories'];
                                    $product_id = $product_sku_arry[0]['product_id']; 

                                    // For previous file 
                                    
                                    /*if($categories_id!='81215'){
                                       $final_cat_id_DB = $categories_id.','.$cat_id_DB;
                                       $final_cat_id_BC = explode(',',$final_cat_id_DB);
                                    }else{
                                        $final_cat_id_DB = $cat_id_DB;
                                        $final_cat_id_BC = $cat_id_BC;
                                    }*/

                                    // new file 24/09/2018
                                    $final_cat_id_DB = $categories_id.','.$cat_id_DB;
                                    $final_cat_id_BC = explode(',',$final_cat_id_DB);


                                    // Update BigCommerce CategoryID under Products
                                    $category_update_of_product = json_encode(array('categories' => $final_cat_id_BC));   
                                    $update_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$product_id;
                                    $output = $this->utility->curl_response($update_url,'PUT',$category_update_of_product);

                                    $this->general->update(array('categories' => $final_cat_id_DB, 'category_assigned' => '1', 'update_at' => DATE_TIME), array('product_id' => $product_id));
                                }

                            }

                        }

                        
                    }  
                        
                }
                    
                
                    $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName.'/assign_product'); 


    }


    public function category_8999_match_data(){
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

                $this->general->set_table('products');
                $product_list = $this->general->get('product_id,sku', array('category_assigned' => '0'));

                foreach($product_list as $key => $val){
                    $product_id = $product_list[$key]['product_id'];
                    $sku = $product_list[$key]['sku'];

                        foreach($sheet_data as $key => $data)
                        {
                            if($key > '1'){

                                 $name = trim($data['A']);    // Category name
                                 $skulist = $this->filter_by_comma($data['B']);     // product SKU

                                 if (strpos($skulist, $sku) !== false) {
                                        $this->general->set_table('products');
                                        $result = $this->general->update(array('category_name' => $name, 'status' => '1'), array('product_id' => $product_id));
                                 }
                                
                             }   
                         }    

                }
                redirect('admin/' . $this->controllerName.'/category_8999_match'); 

            }    
    }



    public function product_653_match(){
        $this->general->set_table('products');
        $productlist = $this->general->get('product_id,category_name', array('status' => '1'));
        
        foreach($productlist as $key => $val){
            $product_id = $productlist[$key]['product_id'];
            $category_name = $productlist[$key]['category_name'];

            $this->general->set_table('categories');
            $category_id_arry = $this->general->get('cat_id', array('name' => $category_name));

            $cat_id = [];
            if(!empty($category_id_arry)){

                foreach($category_id_arry as $catkey => $catval){
                   $cat_id[] = $category_id_arry[$catkey]['cat_id'];
                }

                $cat_id_BC = $cat_id;
                $cat_id_DB = implode(',',$cat_id);

                // Update BigCommerce CategoryID under Products
                $category_update_of_product = json_encode(array('categories' => $cat_id_BC));   
                $update_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$product_id;
                $output = $this->utility->curl_response($update_url,'PUT',$category_update_of_product);

                $this->general->set_table('products');
                $this->general->update(array('categories' => $cat_id_DB, 'category_assigned' => '1', 'status' => '0', 'update_at' => DATE_TIME), array('product_id' => $product_id));
            }

        }
    }


    public function single_update(){
        $sql = "SELECT `product_id` FROM `products` WHERE `categories` LIKE '%81441%' ";
        $query = $this->general->custom_query($sql);

        foreach($query as $key => $val){
                $category = '81441';
                echo $product_id = $query[$key]['product_id'];

                $category_update_of_product = json_encode(array('categories' => array(81441)));   
                $update_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$product_id;
                $output = $this->utility->curl_response($update_url,'PUT',$category_update_of_product);

                $this->general->set_table('products');
                $this->general->update(array('categories' => $category, 'category_assigned' => '1', 'status' => '1', 'update_at' => DATE_TIME), array('product_id' => $product_id));
        }

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