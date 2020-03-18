<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  error_reporting('E_ALL');
class Manually_update extends CI_Controller {

    public $folder = 'admin/product'; //Name Of The Folder in View
    public $admin_template = "admin_dashboard_template";
    function __construct() {
        parent::__construct();
        $this->table = "categories";
        $this->controllerName = "manually_update";
        $this->load->library('pagination');
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
      
        $search_str = " status='0'";
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
      $name_array = $this->input->post('name');
      $image_array = $this->input->post('image');
      $page_title_array = $this->input->post('page_title');
      $meta_keywords_array = $this->input->post('meta_keywords');
      $meta_description_array = $this->input->post('meta_description');

      foreach($cat_id as $chkey => $val){
                  $catid = $cat_id[$chkey];
                  $name = trim($name_array[$chkey]);
                  $page_title = trim($page_title_array[$chkey]);
                  $image_url = trim($image_array[$chkey]);
                  $meta_keywords = explode(',' , trim($meta_keywords_array[$chkey]));
                  $meta_description = trim($meta_description_array[$chkey]);

                 $update_categories = json_encode(array('page_title' => $page_title,  'image_url' => $image_url, 'meta_keywords' => $meta_keywords, 'meta_description' => $meta_description ));
                 $update_category_url = API_URL.STORE_ID.'/'.API_VER.'/'.PRODUCT_CATEGORY.'/'.$catid;
                 $output = $this->utility->curl_response($update_category_url,'PUT',$update_categories);
                
                 if(!empty($output['data'])){
                      $this->general->set_table('categories');
                      $updatedata['image'] = $image_url;
                      $updatedata['page_title'] = $page_title;
                      //$updatedata['description'] = $description;
                      $updatedata['meta_keywords'] = trim($meta_keywords_array[$chkey]);
                      $updatedata['meta_description'] = $meta_description;
                      $updatedata['updated_at'] = DATE_TIME;
                      $updatedata['status'] = '1';

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
