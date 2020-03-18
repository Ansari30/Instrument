<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CronjobProductVariants extends CI_Controller{
	
	function __construct() {
        parent::__construct();  

    }

	public function index(){
		 
		 $this->table = 'products_variants';
		 $this->general->set_table($this->table);
		 //$this->wh_log('Loop start - '.date('Y-m-d h:i:s'));
		 // Trancate table
		 //$this->general->trancate_table($this->table);
		 
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
	                
	                $variants_arr = $product_data[$pkey]['variants'];
	                if(!empty($variants_arr)){
	                	foreach($variants_arr as $variant){
                				$variant_id = $variant['id'];
		                		$variant_product_id = $variant['product_id'];
		                		$variant_sku = $variant['sku'];
		                		$variant_purchasing_disabled = $variant['purchasing_disabled'];
		                		$variant_purchasing_message = $variant['purchasing_disabled_message'];
	                			
		                		$result1 = $this->general->save(array('product_id'=>$variant_product_id,'variant_id'=>$variant_id,'sku'=>$variant_sku,'purchasing_disabled'=>$variant_purchasing_disabled,'purchasing_disabled_message'=>$variant_purchasing_message,'status'=>'0','created_at'=>DATE_TIME));
	                		
	                	}
	                }
	                
	             }
	        }
         	
         	}

         }
			
	}

}
?>