<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Variant extends CI_Controller{
	
	public $folder = 'admin/variant'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

	function __construct() {
        parent::__construct();  

        $this->controllerName = "variant";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->load->library('PHPExcel');
    }

    public function index(){
    	$data['page_title'] = 'Variant';
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
                        $sku = trim($data['A']);

                        $this->general->set_table('products');
                        $product_id = $this->general->get('product_id', array('sku' => $sku));

                        if(!empty($product_id)){
                        	$pid = $product_id[0]['product_id'];
	                        $this->general->set_table('products_variants');
	                        $variant_id = $this->general->get('variant_id', array('product_id' => $pid));

	                        if(!empty($variant_id)){

	                            foreach($variant_id as $vkey => $vval){
	                            		$vid = $variant_id[$vkey]['variant_id'];

	                            		$current_stock_josn = json_encode(array('purchasing_disabled' => true, 'purchasing_disabled_message'=>'Email for pricing')); 
                    					$updt_current_stock = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_LIST.'/'.$pid.'/'.VARIANTS.'/'.$vid;
                    					$output = $this->utility->curl_response($updt_current_stock,'PUT',$current_stock_josn);
	                                    
	                                    $updatedata['purchasing_disabled'] = '1';
	                                    $updatedata['purchasing_disabled_message'] = 'Email for pricing';
	                                    $updatedata['status'] = '1';

	                                    $this->general->update($updatedata, array('product_id' => $pid));

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

}
?>