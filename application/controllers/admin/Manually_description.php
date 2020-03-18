<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  error_reporting('E_ALL');
class Manually_description extends CI_Controller {

    public $folder = 'admin/manually-description'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";
    function __construct() {
        parent::__construct();
        $this->table = "categories";
        $this->controllerName = "manually_description";
        
        if (!$this->session->userdata('admin_logged_in')){
            redirect('admin');
        }
       
    }

    public function index($id='') {
        $data['page_title'] = 'Manually Update Categories';
        $data['page_name'] = $this->folder . '/manage'; 
        $config = array();
        $search = '';
        $search_str = '';
      
        $search_str = " description_status='0'";
        if(isset($_GET['keyword']) && $_GET['keyword']!=''){
         $search = $_GET['keyword'];
         
            $word= $_GET['keyword'];
            if(preg_match('#^(\'|").+\1$#', $word) == 1){
              $word = str_replace('"', '', $word);
              $word = str_replace("'", "\\'", $word);
          
              $search_str .= " AND ( name like '%".$word."%'  )  ";

            }elseif (strpos($word, ' ') !== FALSE) {
                $wordArr = explode(' ', $word);
                foreach ($wordArr AS $search) {
                    $search = str_replace("'", "\\'", $search);
                    $search_str .= " AND ( name like '%".$search."%') ";
                }
                $search_str = trim($search_str, 'AND');
            } else {
              $word = str_replace("'", "\\'", $word);
               
               $search_str .= " AND ( name like '%".$word."%' )  ";
            }

              $query = "SELECT * FROM categories WHERE $search_str";
              $result = $this->general->custom_query($query);
              $data['results'] = $result;
          
        }
        
        // View data according to array.
        $this->load->view($this->admin_template,$data);
    }
     

    public function manuallyUpdate(){
      
      $cat_id = $this->input->post('cat_id');
      $description_array = $this->input->post('description');
      
      foreach($cat_id as $chkey => $val){
                  $catid = $cat_id[$chkey];
                  $description = trim($description_array[$chkey]);
                  

                 $update_categories = json_encode(array( 'description' => $description));
                 $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$catid;
                 $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);
                
                 if(!empty($output['data'])){
                      $this->general->set_table('categories');
                      $updatedata['description'] = $description;
                      $updatedata['description_updated'] = DATE_TIME;
                      $updatedata['description_status'] = '1';

                      $execute = $this->general->update($updatedata, array('cat_id' => $catid));
                 }
        }
        if($execute){
         $this->session->set_flashdata('dispMessage', '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Categories updates successfully.</strong></div>');
            }else{
                $this->session->set_flashdata('dispMessage', '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button><strong><i class="icon-ok"></i>Something is wrong. Please try again!</strong></div>');
            } 
          redirect('admin/' . $this->controllerName);  

    }


}
?>
