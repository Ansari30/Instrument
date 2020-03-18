<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CronjobProduct extends CI_Controller{
	
	function __construct() {
        parent::__construct();  

    }

	public function index(){
		 
		 $this->table = 'products';
		 $this->general->set_table($this->table);
	
         $product_api = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST;
         $data_count = $this->utility->curl_response($product_api,'GET');

         $pagination_count = $data_count['meta']['pagination'];

         $total_product = $pagination_count['total'];
         $total_pages =	$pagination_count['total_pages'];
         $current_page = $pagination_count['current_page'];

         if($current_page >=1){

         	for($i=1;$i<=$total_pages;$i++){
         		//$this->wh_log('Loop '.$i);
         		$product_api = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'?'.PRODUCT_VARIANT.'&page='.$i;
         		$data = $this->utility->curl_response($product_api,'GET');
         		
         		if(!empty($data)){
	             $product_data = $data['data'];
	             
	             foreach ($product_data as $pkey => $pval) {
	                $pid = $product_data[$pkey]['id'];
	                $name = $product_data[$pkey]['name'];
	                $sku = $product_data[$pkey]['sku'];

			 		$categories_array =  $product_data[$pkey]['categories'];
			 		$categories = array();
			 		if(!empty($categories_array)){
			 			$categories = implode(',', $categories_array);
			 		}else{
			 			$categories = '';
			 		}

	               $result = $this->general->save(
	               		array('product_id'=>$pid,
	               			'name'=>$name,
	               			'sku'=>$sku,
	               			'categories'=>$categories,
	               			'created_at' => date('Y-m-d H:i:s')
	               		));
	                
	             }
	            // $this->wh_log('Loop Close'.$i);
	        	}
         	}
         	//$this->wh_log('Loop end - '.date('Y-m-d h:i:s'));
         	die;
         // switch table after cron job complete
         //$this->db->query("ALTER TABLE products RENAME products_1");
		 //$this->db->query("ALTER TABLE products_temp RENAME products");
		 //$this->db->query("ALTER TABLE products_1 RENAME products_temp");die;
         }
			
	}

}
?>