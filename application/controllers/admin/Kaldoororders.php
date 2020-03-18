<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    error_reporting('E_ALL');
class Kaldoororders extends CI_Controller {

    public $folder = 'admin/kaldoororders'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";

    public function __construct() {
        parent::__construct();
        
        $this->controllerName = "kaldoororders";
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
        $this->load->library('PHPExcel');

        $orderstatus = array('Incomplete' => '0',
                             'Pending' => '1',
                             'Shipped' => '2',
                             'Partially Shipped' => '3',
                             'Refunded' => '4',
                             'Cancelled' => '5',
                             'Declined' => '6',
                             'Awaiting Payment' => '7',
                             'Awaiting Pickup' => '8',
                             'Awaiting Shipment' => '9',
                             'Completed' => '10',
                             'Awaiting Fulfillment' => '11',
                             'Manual Verification Required' => '12',
                             'Disputed' => '13',
                             'Partially Refunded' => '14'
                         );
    }

    public function index() {
        $data['page_title'] = 'Kaldoororders';
        $data['page_name'] = $this->folder . '/manage'; 
        $this->load->view($this->admin_template,$data);
    }
    

    public function upload(){

        $file_info = pathinfo($_FILES["upload_file"]["name"]);
        $filename = $file_info['filename'];
        $file_directory = $_SERVER['DOCUMENT_ROOT']."/instrumental/uploads/kaldoor";
        $new_file_name = date("Y-m-d")."-".$filename.".". $file_info["extension"];

        
           if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $file_directory . $new_file_name))
            { 

                $file_type  = PHPExcel_IOFactory::identify($file_directory . $new_file_name);
                $objReader  = PHPExcel_IOFactory::createReader($file_type);
                $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                $sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);


                if(!empty($sheet_data)){
                    //$this->general->trancate_table('kaldoors');
                    foreach($sheet_data as $key => $data)
                    {
                        if($key > 1){
                            $save['magento_entity'] = $data['A'];
                            $save['bc_order_id'] = $data['B'];
                            $save['magento_status'] = $data['C'];
                            $save['status'] = '0';
                            $save['created'] = DATE_TIME;

                            $this->general->set_table('kaldoors');
                            if($save['magento_status']!='0'){
                                echo $key." : ".$this->switch_case($save['magento_status']);
                                echo "<br>";
                            }
                            //$this->general->save($save);
                        }
                    }
               } 
                    
            $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>File uploaded successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
        redirect('admin/' . $this->controllerName);      

    }


    function filter_by_comma($str){
        $str = str_replace(' , ', ',', $str);
        $str = trim($str, ' , ');
        $str = trim($str, ' ,');
        $str = trim($str, ',');

        return $str;
    }

    function switch_case($orderstatus){
       
        switch ($orderstatus) {
            case "awaiting_shipping":
                return "Awaiting Shipment";
                break;
            case "canceled":
                return "Cancelled";
                break;
            case "complete":
                return "Completed";
                break;
            case "fraud":
                return "";
                break;
            case "holded":
                return "";
                break;
            case "payment_review":
                return "";
                break;
            case "pending":
                return "Pending";
                break;
            case "processing":
                return "Awaiting Fulfillment";
                break;
            case "waiting":
                return "";
                break;
            case "closed":
                return "";
                break;

            default:
                return "0";
        }
    }

    function order_curl_execution($orderid, $postbody){
        $curl = curl_init();

        $url = "https://api.bigcommerce.com/stores/8h4wyq5kwq/v2/orders/".$orderid;
        curl_setopt_array($curl, array(
          CURLOPT_URL => "",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_POSTFIELDS => $postbody,
          CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "content-type: application/json",
            "x-auth-client: 8m4n1e3m11stfyegeegfzr0mh5cogx0",
            "x-auth-token: 16dcjz2mp0ad8fb9z53um5pr3vwutk0"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg = "cURL Error #:" . $err;
        } else {
          $msg = $response;
        }

        return $msg;
    }

}
?>