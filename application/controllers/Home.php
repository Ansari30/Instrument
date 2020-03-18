<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {

    public $controllerName = 'home';
    public $user_id; 
    var $page_title = "";

    function __construct() {
        parent::__construct(); 
        //$this->load->model('home_model', 'home');
        $this->page_title = "Welcome To Peachcouture";  
        $this->load->helper('cookie');
        $this->load->helper('path');
        $this->load->library('user_agent');
        $this->load->library('utility');
          
    }

     

    public function index() { 
        
        $data = array(); 
        
        $this->general->set_table('poduct_deal');
        $curdatetime = date('Y-m-d H:i:s');
        
        $sql = " '".$curdatetime."' between `start_date` and `end_date` ";
        $data['currentdeal_active'] = $this->general->get('*',$sql);
        //print_r($this->db->last_query());die(); 
        $futuresql = " '".$curdatetime."'  < start_date ";
        $data['future_deal'] = $this->general->get('*',$futuresql,array('start_date'=>'ASC'));

        $this->load->view('home_view', $data);
    }

    public function getdate() { 
        
        $data = array(); 
        
        $this->general->set_table('poduct_deal');
        $curdatetime = date('Y-m-d H:i:s');
        
        $sql = " '".$curdatetime."' between `start_date` and `end_date` ";
        $currentdeal_active = $this->general->get('*',$sql);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        //header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

        if($currentdeal_active){
            echo $currentdeal_active[0]['end_date'];
        }else{
            echo "nodate";
        }
    }


    public function dealproducts() { 
        
        $productid = $_POST['productid'];
        
        $this->general->set_table('poduct_deal');
        $curdatetime = date('Y-m-d H:i:s');
        
        $sql = " FIND_IN_SET( '".$productid."' , products   ) and '".$curdatetime."' between `start_date` and `end_date` ";
         
        $currentdeal_active = $this->general->get('*',$sql);
        //print_r($this->db->last_query());die();  
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        //header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        //print_r($currentdeal_active);die();
        if($currentdeal_active){
            echo $currentdeal_active[0]['end_date'];
        }else{
            echo "nodate";
        }
    }

    public function dealproductprice() { 
        
        $productid = $_POST['productid'];
        
        $this->general->set_table('poduct_deal');
        $curdatetime = date('Y-m-d H:i:s');
        
        $sql = " FIND_IN_SET( '".$productid."' , products   ) and '".$curdatetime."' between `start_date` and `end_date` ";
         
        $currentdeal_active = $this->general->get('*',$sql);

        //print_r($this->db->last_query());die();  
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        //header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        //print_r($currentdeal_active);die();
        if($currentdeal_active){
            /*$this->general->set_table('products');
            $productdata = $this->general->get('*',array('product_id'=>$productid));
            //print_r($this->db->last_query());die();  
            echo ( $productdata[0]['price'] - $productdata[0]['deal_price']);*/

            $this->general->set_table('products');
            $productdata = $this->general->get('*',array('product_id'=>$productid));
            if($productdata[0]['deal_price']!='0'){
                $Discount_Price = ( $productdata[0]['price'] - $productdata[0]['deal_price']);
                $Discount_Price = round($Discount_Price,2);
                $html = '<input type="hidden" name="Deal Price" value="(-$'.$Discount_Price.')">';
                 
                $response['htmlcode'] = $html;
                $response['deal_price'] = number_format($productdata[0]['deal_price'], 2, '.', '');
                

                $response['status'] = "success";
            }   
            
        }else{ 
            
            $response['status'] = "nodate";

        }

        echo json_encode($response);

    }

     


    

}