<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller{
	
	function __construct() {
        parent::__construct();  

    }

    public function index(){
    	echo "Index function called. CHeck another function for update the product categories.";
    }

	public function updateCategory(){
		 
		 $this->table = 'products';
		 $this->general->set_table($this->table);

		 $productlist = $this->general->get('id,product_id');

		 if(!empty($productlist)){
		 	foreach ($productlist as $key => $value) {
		 		$id = $productlist[$key]['id'];
		 		$pid = $productlist[$key]['product_id'];

		 		$category_update_of_product = json_encode(array('categories' => array('81215')));   
                $update_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$pid;
                $output = $this->utility->curl_response($update_url,'PUT',$category_update_of_product);

                $this->general->update(array('categories' => '81215', 'status' => '0', 'category_assigned' => '0'), array('id' => $id));
		 	}
		 	echo "Data updated successfully";die;
		 }

	}

}
?>