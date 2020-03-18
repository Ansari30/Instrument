<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CronjobCategory extends CI_Controller{
	
	function __construct() {
        parent::__construct();  

    }

	public function index(){

		 $this->table = 'categories';
		 $this->general->set_table($this->table);

		 // Trancate table
		 $this->general->trancate_table($this->table);
		 
         $product_api = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY;
         $data_count = $this->utility->curl_response($product_api,'GET');

         $pagination_count = $data_count['meta']['pagination'];

         $total_product = $pagination_count['total'];
         $total_pages =	$pagination_count['total_pages'];
         $current_page = $pagination_count['current_page'];

         if($current_page >=1){

         	for($i=1;$i<=$total_pages;$i++){
         		
         		$product_api = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'?page='.$i;
         		$data = $this->utility->curl_response($product_api,'GET');

         		if(!empty($data['data'])){
	             $product_cat = $data['data'];
	             
	             foreach ($product_cat as $pkey => $pval) {

	             	$id = trim($product_cat[$pkey]['id']);
	             	$parent_id = trim($product_cat[$pkey]['parent_id']);


	             	$name = $product_cat[$pkey]['name'];
	             	if(!empty($name)){
	             		$name = trim($name);
	             	}

	             	$image_url = $product_cat[$pkey]['image_url'];
	             	if(!empty($image_url)){
	             		$image_url = trim($image_url);
	             	}

	             	$page_title = $product_cat[$pkey]['page_title'];
	             	if(!empty($page_title)){
	             		$page_title = trim($page_title);
	             	}

	             	$description = $product_cat[$pkey]['description'];
	             	if(!empty($description)){
	             		$description = trim($description);
	             	}

	             	$meta_keywords = $product_cat[$pkey]['meta_keywords'];
	             	if(!empty($meta_keywords)){
	             		$meta_keywords = implode(',', $meta_keywords);
	             	}

	             	$meta_description = $product_cat[$pkey]['meta_description'];
	             	if(!empty($meta_description)){
	             		$meta_description = trim($meta_description);
	             	}

	             	$custom_url = $product_cat[$pkey]['custom_url']['url'];
	             	if(!empty($custom_url)){
	             		$custom_url = trim($custom_url);
	             	}
	             	
	                $savearray['cat_id'] = $id;
	                $savearray['parent_id'] = $parent_id;
	                $savearray['name'] = $name;
	                $savearray['image'] = $image_url;
	                $savearray['page_title'] = $page_title;
	                $savearray['description'] = $description;
	                $savearray['meta_keywords'] = $meta_keywords;
	                $savearray['meta_description'] = $meta_description;
	                $savearray['custom_url'] = $custom_url;
	                $savearray['created_at'] = DATE_TIME;
	                $savearray['status'] = '0';
	                
	                $result = $this->general->save($savearray);
	                if(!$result){
	                    print_r($this->db->_error_message());
	                    die();
	                }  
	             }
	        }
         	
         	}
        die;
         }
			
	}

}
?>