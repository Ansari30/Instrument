<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {

    public $controllerName = 'home';
    public $user_id;
    var $table = "pages";
    var $tblUsers = "user"; 
    var $tblUsersMeta = "usermeta";
    var $tblCategory = "category";
    var $tblJobpost = "jobpost";
    var $tblApplJobs = "applied_jobs";
    var $tblLocation = "location";
    var $tblWishlist = "wishlist_log";
    var $page_title = "";

    function __construct() {
        parent::__construct(); 
        $this->load->model('home_model', 'home');
        $this->page_title = "Welcome To The Flamlily";  
        $this->load->helper('cookie');
        $this->load->helper('path');
        $this->load->library('user_agent');
        $this->load->library('utility');
        if ($this->session->userdata('user_logged_in')) {
            $this->user_id = $this->session->userdata['user_logged_in']['user_id'];
        } 
    }

    private function set_page_title($title) {
        if (!empty($title)) {
            $this->page_title = $title;
        }
    }

    public function index() { 
        
        $data = array(); 
        
        $this->home->set_table($this->table);
        $data['blog_lists'] = $this->home->get('*',array('cms_type'=>'BLOG'),array('created_at'=>'DESC'),'4');
        
        $data['metas'] = array();
        $this->set_page_title((!empty($data['metas'])) ? $data['metas']['0']['title'] : 'Welcome to the flamelily');


        $this->home->set_table('client_image');
        $data['client_image'] = $this->home->get('*',array(),array('id'=>'DESC'));
        $latest_jobs = $this->home->latestjob();
        $mainarray = array();
        if($latest_jobs){
            foreach ($latest_jobs as $locations) {
                $explodearray = explode(',', $locations['categoryname']);
                foreach ($explodearray as $keys) {
                    array_push($mainarray, $keys );
                } 
            }
        }
        $data['latestjobs'] = array_filter(array_unique($mainarray)); 
        
        $this->home->set_table('settings');
        $data['twitterlink'] = $this->home->get('value',array('key'=>'TWITTER'));

        $facebooklink = $this->home->get('value',array('key'=>'FACEBOOK'));
      //  print_r($facebooklink);die();
        $facebook_accesstoken = $this->home->get('value',array('key'=>'FACEBOOK_ACCESS_TOKEN'));
        //$json_link = "https://graph.facebook.com/{$fb_page_id}/feed?access_token={$access_token}&fields={$fields}&limit={$limit}";
        $json_link = $facebooklink[0]['value'];
        $json_link .= '?access_token='.$facebook_accesstoken[0]['value'];
        $json_link .= '&fields=message,link,created_time&limit=1';
        //echo $json_link;die();
        //"https://graph.facebook.com/317881151620708/feed?access_token=826269190824728|30b394fef374a6bbc9bba36f0b81b23c&fields=message,link,created_time&limit=1";
        //'https://graph.facebook.com/317881151620708/posts?&access_token=826269190824728|30b394fef374a6bbc9bba36f0b81b23c';
        /*$json = file_get_contents($json_link); 
        $data['facebook_obj'] = json_decode($json, true); */

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $json_link);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($ch);
        if (curl_errno($ch)) {
          /*echo curl_error($ch);
          echo "\n<br />";*/
          $contents = '';
        } else {
          curl_close($ch);
        }

        if (!is_string($contents) || !strlen($contents)) {
            //echo "Failed to get contents.";
            $contents = '';
        }

        
        $data['facebook_obj'] = json_decode($contents, true);


        


        //print_r($json);die();
        $this->load->view('home_view', $data);
    }


    public function jobsearch(){
        $data = array(); 
        
        $this->home->set_table($this->table);  
        $data['metas'] = $this->home->get("", array("page_url" => "job-search"));
        $this->set_page_title((!empty($data['metas'])) ? $data['metas']['0']['title'] : 'Jobsearch');


        $this->load->library('pagination');
        
        $searchdata = $this->input->post();
        if(isset($searchdata['action']) && $searchdata['action']=='top_search'){
            $search_param['location'] = (isset($searchdata['location']) ? $this->home->get_locationid($searchdata['location']) : '');  
            //$locationquery = " "

        }else{
            $search_param['location'] = (isset($searchdata['location']) ? implode($searchdata['location'], ',') : '');    
              
        }
        
        $search_param['sector'] = (isset($searchdata['sector']) ? implode($searchdata['sector'], ',') : ''); 
        $search_param['seach_text'] = (isset($searchdata['keyword_search']) ? $searchdata['keyword_search'] : ''); 
        $config["base_url"] = base_url()."home/ajaxpagination";
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
        }
        else{
            $page = 0;
        }
      //  print_r($search_param['location']);die();
        if(isset($search_param['location']) || isset($search_param['sector']) || isset($search_param['keyword_search']) ){
            $session_backdata['location'] = $search_param['location'];
            $session_backdata['sector'] = $search_param['sector'];
            $session_backdata['keyword_search'] = $search_param['seach_text'];
            $this->session->set_userdata('session_back', $session_backdata);
        }
       // print_r($this->session->userdata('session_back'));die();
        // Number of items you intend to show per page.
        $config["per_page"] = 6;
        $search_param['Offset'] =  $page;
        $search_param['Limit'] = $config["per_page"];

        

        $config['full_tag_open'] = '<ul >';
        $config['full_tag_close'] = '</ul>'; 
        $config['cur_tag_open'] = '&nbsp;<li  ><a class="current">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="first_tags">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="last_tags">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="next-img" > ';
       
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li class="prev-img">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['page_query_string'] = false;
        //$config['num_links'] = 2;
         
 

        $result = $this->home->jobsearch($search_param);
        $data['jobresult'] = $result['result']; 
        $data['total_rows'] = $result['pages'];
        // Set total rows in the result set you are creating pagination for.
        $config["total_rows"] = $data['total_rows'];
         

        // To initialize "$config" array and set to pagination library.
        $this->pagination->initialize($config);


        $this->load->view('job-search', $data);

    }
    public function search_ajax() {
        $response['type'] = 'error';
        $response['msg'] = '';
        $data = $this->input->get();
        $response['search_result'] = array();
        $response['total_row'] = 0;
        if (isset($data)) {
            $country = '';
            $region = '';
            $county = '';             
            
            foreach ($data as $key => $value) {
                  
                 
                if($key == 'country') {
                    $country = implode($data[$key], ',');
                }
                if($key == 'region') {
                    $region = implode($data[$key], ',');
                }
                if($key == 'county') {
                    $county = implode($data[$key], ',');
                }
            }
             
            $search_param['country'] = $country;
            $search_param['region'] = $region;
            $search_param['county'] = $county;
            $search_param['sector'] = $data['sector'];
            $search_param['seach_text'] = $data['search_medical'];
            $response['type'] = 'success';
            /*if( !empty($search_param['country']) || !empty($search_param['region']) || !empty($search_param['county']) )
            {*/
                if (isset($data) && !empty($data['page_no'])) {
                    $search_param['PageNo'] = $data['page_no'];
                }
                $search_param = $this->get_offset_limit($search_param);
                $result = $this->home->ajaxjobsearch($search_param);
                if (!empty($result['result'])) {
                    foreach ($result['result'] as $key => $value) {

                        
                        if (!empty($value['description'])) {
                            $stringdesc = strip_tags(html_entity_decode($value['description']));
                            $stringdesc=str_ireplace('<p>','',$stringdesc);
                            $stringdesc=str_ireplace('</p>','',$stringdesc); 
                            $replacedec = $this->utility->add3dots($stringdesc, "...", 300);
                            $result['result'][$key]['description'] = $replacedec;
                        } 
                        if (!empty($value['salary_type'])) {
                            $salarytype = $this->utility->getsalarytype($value['salary_type']);
                            $result['result'][$key]['salary_type'] = $salarytype;

                        }
                        if($this->session->userdata('user_logged_in')){ 
                           
                            array_push($result['result'][$key] , array('logged_in',true));
                            
                            $this->general->set_table('applied_jobs');
                            $checkapply = $this->general->get("*", array("userid" => $this->user_id,'jobid'=> $result['result'][$key]['jobpost_id']));

                            if($this->session->userdata('user_logged_in') && !empty($checkapply)){
                                array_push($result['result'][$key] , array('applied',true));
                            }else{
                                array_push($result['result'][$key] , array('applied',false));
                            }
                        }else{
                            array_push($result['result'][$key] , array('logged_in',false));
                            array_push($result['result'][$key] , array('applied',false));
                        }

                    }
                } 
                 
            /*}else{
                $result['result'] = array();
                $result['pages'] = 0;
            }*/
            $response['search_result'] = $result['result'];
            $response['total_row'] = $result['pages'];
        }

        echo json_encode($response);
    }

    private function get_offset_limit($data) {
        $perPageRecord = '6';
        $offset = '0';
        if (isset($data['PageNo']) && !empty($data['PageNo']) && $data['PageNo'] > 1) {
            $offset = (($data['PageNo'] - 1) * $perPageRecord);
        }
        unset($data['PageNo']);
        $data['Offset'] = $offset;
        $data['Limit'] = $perPageRecord;
        return $data;
    }

   /* public function ajaxpagination(){
        $this->load->library('pagination');

        $config["base_url"] = base_url()."home/ajaxpagination";
        $config["per_page"] = 6;
        $data = $this->input->get();
        $page = $data['page_no'] ;
        
         
        if (isset($data)) {
            $country = '';
            $region = '';
            $county = '';             
            
            foreach ($data as $key => $value) {
                 
                if($key == 'country') {
                    $country = implode($data[$key], ',');
                }
                if($key == 'region') {
                    $region = implode($data[$key], ',');
                }
                if($key == 'county') {
                    $county = implode($data[$key], ',');
                }
            }
             
            $search_param['country'] = $country;
            $search_param['region'] = $region;
            $search_param['county'] = $county;
             
             
             
            if(isset($data) && !empty($data['page_no'])) {
                $search_param['PageNo'] = $data['page_no'];
            }
            //$search_param = $this->get_offset_limit($search_param);
            $search_param['Offset'] =  $page;
            $search_param['Limit'] = $config["per_page"];
            
            $result = $this->home->ajaxjobsearch($search_param); 
               
             
        $config['full_tag_open'] = '<ul >';
        $config['full_tag_close'] = '</ul>'; 
        $config['cur_tag_open'] = '&nbsp;<li  ><a class="current">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="first_tags">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="last_tags">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="next-img" > ';
       
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li class="prev-img">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['page_query_string'] = false;
        //$config['num_links'] = 2;
         
   
        // Set total rows in the result set you are creating pagination for.
        $config["total_rows"] = $result['pages'];
         
        
        // To initialize "$config" array and set to pagination library.
        $this->pagination->initialize($config);

        //$datas["links"] = $this->pagination->create_links(); 
        echo  $this->pagination->create_links();

        }

    }*/
    public function jobdetail($id) { 
        
        $data = array();  

        $data['details'] = $jobdetails = $this->home->jobdetaildata($id);

        $this->home->set_table($this->tblJobpost);  
        $data['metas'] = $this->home->get("meta_title as title , meta_keyword,meta_description", array("joburl" => $id));
        $this->set_page_title((!empty($data['metas']['0']['title'])) ? $data['metas']['0']['title'] : $data['details']->jobtittle );

        $this->home->set_table($this->tblWishlist);
        $dupCondition["jobpost_id"] =  $jobdetails->jobpost_id;
        if ($this->session->userdata('user_logged_in')) {
            $dupCondition['user_id'] = $this->user_id;
        }else{
            $dupCondition["ip_address"] = $this->input->ip_address();
        }
        $data['check_shortlist'] = $this->home->get('wishlist_id',$dupCondition);


        $this->load->view('job_detail', $data);
    }

    public function addtowishlist(){    
        $data = $this->input->post();
        $stordata['jobpost_id'] = $data['postid'];
        $stordata['ip_address'] = $this->input->ip_address();
        if ($this->session->userdata('user_logged_in')) {
            $stordata['user_id'] = $this->user_id;
        }
        $this->home->set_table($this->tblWishlist);

        $dupCondition["jobpost_id"] =  $data['postid'];
        if ($this->session->userdata('user_logged_in')) {
            $dupCondition['user_id'] = $this->user_id;
        }else{
            $dupCondition["ip_address"] = $stordata['ip_address'];
        }
        $dupResult_question = $this->home->get('wishlist_id',$dupCondition);
        if($dupResult_question){
            $checkedRowValue['wishlist_id'] = $dupResult_question[0]['wishlist_id']; 
            $this->home->delete($checkedRowValue);
            $response['type'] = 'deletes';
            $response['msg'] = "Remove from shortlist Successfully.";
            $response['replace_text'] = "Wishlist";
        }else{  
            $result = $this->home->save($stordata);
            $response['type'] = 'stores';
            $response['msg'] = "Add to shortlist Successfully.";
            $response['replace_text'] = "Remove from Wishlist";
        } 
        echo json_encode($response);

    }

     

    public function jobapply($jobid){

        $this->home->set_table($this->tblApplJobs);
        $data['jobid'] = $jobid;
        $data['userid'] = $this->user_id;
        $data['job_status'] = JOB_PENDING;
        $result = $this->home->save($data);
        
        if($result){
            
            $this->home->set_table($this->tblJobpost);
            $jobdetails = $this->home->get('jobpost_id, jobtittle, jobreference_id, created_by, is_idibu',array('jobpost_id'=>$jobid));

            $this->home->set_table($this->tblUsers);
            $consultantdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$jobdetails[0]['created_by']));

            $userdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$this->user_id));
            $admindata = $this->home->get('user_id, email',array('role_id'=>1));

            $msg = "New Job Application for - ";
                
            $subject = $msg. ''.$jobdetails[0]['jobtittle'];
            $email_data['email_title'] = "Hello ".$userdata[0]['first_name'];
            $email_data['email_content'] = "One user has applied for this job post. Please check.";

            $email_data['email_description'] = '<p> Below is user details.</p>
                                <table>
                                <tbody>
                                    <tr>
                                        <td><b>User Name: </b> '.$userdata[0]['first_name'].'</td>
                                    <tr>
                                    </tbody> ';
            $email_template = $this->load->view('email/basic_mail', $email_data, true);
            
            $adminemail = $admindata[0]['email'];
            if($jobdetails[0]['is_idibu']==1){
                $tomail = 'bliss.sanjay91@gmail.com';
            }else{
                $tomail = $consultantdata[0]['email'];
             }    
            $this->common->send_mail($tomail, $subject, $email_template,'',$adminemail);  


            $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><p> Job Applied Successfully.</p></div>');
            redirect('account');
        }else{
            $this->session->set_flashdata('dispMessage', '<div class="alert common-error"><i class="icon-ok"></i> Error occured.</div>');
            redirect('account');
        } 
    }
    
    public function commonjobapply(){
        $jobid = $this->input->post('postid');
        if($this->session->userdata('user_logged_in')){

 
            $this->home->set_table($this->tblApplJobs); 
                $data['jobid'] = $jobid;
                $data['userid'] = $this->user_id;
                $data['job_status'] = JOB_PENDING;
                $result = $this->home->save($data);
            
                if($result){
                    
                    $this->home->set_table($this->tblJobpost);
                    $jobdetails = $this->home->get('jobpost_id, jobtittle, jobreference_id, created_by, is_idibu',array('jobpost_id'=>$jobid));

                    $this->home->set_table($this->tblUsers);
                    $consultantdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$jobdetails[0]['created_by']));

                    $userdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$this->user_id));
                    $admindata = $this->home->get('user_id, email',array('role_id'=>1));

                    $msg = "New Job Application for - ";
                        
                    $subject = $msg. ''.$jobdetails[0]['jobtittle'];
                    $email_data['email_title'] = "Hello ".$userdata[0]['first_name'];
                    $email_data['email_content'] = "One user has applied for this job post. Please check.";

                    $email_data['email_description'] = '<p> Below is user details.</p>
                                        <table>
                                        <tbody>
                                            <tr>
                                                <td><b>User Name: </b> '.$userdata[0]['first_name'].'</td>
                                            <tr>
                                            </tbody> ';
                    $email_template = $this->load->view('email/basic_mail', $email_data, true);
                    
                    $adminemail = $admindata[0]['email'];
                    if($jobdetails[0]['is_idibu']==1){
                        $tomail = 'bliss.sanjay91@gmail.com';
                    }else{
                        $tomail = $consultantdata[0]['email'];
                     } 
                    $this->common->send_mail($tomail, $subject, $email_template,'',$adminemail);  
                    
                    $response['type'] = 'success'; 
                    $response['msg'] = 'Job Applied Successfully.'; 
                 
                    
                }else{ 
                    $response['type'] = 'error'; 
                    $response['msg'] = 'Error occured.'; 
                } 
           

        }else{

             /* This cookie check during register */
            $value = "program_".time();
            // set cookie
            $expirtime = time() + (10 * 365 * 24 * 60 * 60) ;
            $cookie = array(
                    'name'   => 'jobapply_id',
                    'value'  => $value,
                    'expire' => $expirtime
                ); 
            set_cookie($cookie);

            // set cookie
            $cookies = array(
                    'name'   => 'jobpostid',
                    'value'  => $jobid,
                    'expire' => $expirtime
                ); 
            set_cookie($cookies);

            $response['type'] = 'not_loggedin'; 
             
        }
        echo json_encode($response);
    }

    public function afterlogin_apply($jobid){
        
            $this->home->set_table($this->tblJobpost);
            $jobdetails = $this->home->get('jobpost_id, joburl , jobtittle, jobreference_id, created_by, is_idibu',array('jobpost_id'=>$jobid));

            $this->home->set_table($this->tblApplJobs);
            $checkapply = $this->home->get("*", array("userid" => $this->user_id,'jobid'=>$jobid));
            if(empty($checkapply)){

                $data['jobid'] = $jobid;
                $data['userid'] = $this->user_id;
                $data['job_status'] = JOB_PENDING;
                $result = $this->home->save($data);
                
                if($result){
                    
                    

                    $this->home->set_table($this->tblUsers);
                    $consultantdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$jobdetails[0]['created_by']));

                    $userdata = $this->home->get('user_id, email, first_name, last_name, role_id',array('user_id'=>$this->user_id));
                    $admindata = $this->home->get('user_id, email',array('role_id'=>1));

                    $msg = "New Job Application for - ";
                        
                    $subject = $msg. ''.$jobdetails[0]['jobtittle'];
                    $email_data['email_title'] = "Hello ".$userdata[0]['first_name'];
                    $email_data['email_content'] = "One user has applied for this job post. Please check.";

                    $email_data['email_description'] = '<p>Below is user details.</p>
                                        <table>
                                        <tbody>
                                            <tr>
                                                <td><b>User Name: </b> '.$userdata[0]['first_name'].'</td>
                                            <tr>
                                            </tbody> ';
                    $email_template = $this->load->view('email/basic_mail', $email_data, true);
                    
                    $adminemail = $admindata[0]['email']; 
                    if($jobdetails[0]['is_idibu']==1){
                        $tomail = 'bliss.sanjay91@gmail.com';
                    }else{
                        $tomail = $consultantdata[0]['email'];
                     } 
                    $this->common->send_mail($tomail, $subject, $email_template,'',$adminemail);  

                    
                    $msg = '<div class = "alert common-success"><p> Job Applied Successfully.</p></div>';
                    $this->session->set_flashdata('returnError', $msg);
                    

                }else{ 
                    $msg = '<div class = "alert common-error"><p> Some error occured.</p></div>';
                    $this->session->set_flashdata('returnError', $msg);
                   
                } 
            }else{ 
                    $msg = '<div class = "alert common-error"><p> You have already applied to this jobpost.</p></div>';
                    $this->session->set_flashdata('returnError', $msg);
            } 
            if($this->input->cookie('jobapply_id') != null){ 
                delete_cookie('jobapply_id');
                delete_cookie('jobpostid'); 
            }
            redirect('job/'.$jobdetails[0]['joburl']);
         
    }

    public function register(){

        if ($this->session->userdata('user_logged_in')) {
            redirect('account');
        } 
        $this->home->set_table($this->table);
        $data['row'] = $this->home->get("", array("page_url" => "Join"));
        $this->set_page_title((!empty($data['row'])) ? $data['row']['0']['title'] : 'Join');

        $this->home->set_table($this->tblLocation);
        $data['country_list'] = $this->home->get("*", array("l_type" => "0"));

        $this->home->set_table('enquiry_source');
        $data['enquiry_list'] = $this->home->get("*");
        $data['salary_type'] = $this->utility->get_salarytype();

        $this->general->set_table('category');
        $data['main_category'] = $this->general->get("category_id,name", array("parent_id" => '0','cat_type'=>'0'),array('display_order'=>'asc'));

        $this->home->set_table('availability');
        $data['available_list'] = $this->home->get("id,available_name", array(),array('available_name'=>'asc'));

        $this->home->set_table('shift_type');
        $data['shift_list'] = $this->home->get("id,shift_name", array(),array('shift_name'=>'asc'));

        $this->home->set_table('operationl_level');
        $data['operational_list'] = $this->home->get("id,operational_name", array(),array('operational_name'=>'asc'));

        $this->load->view('registration', $data);
   

    }

    public function signup() { 

        $data = $this->input->post(); 

       

        $data = $this->input->post();
        //print_r($data);die();
        $form_field = array(
            'email' => $data['email'],
            'password'=>$data['password'],
            'role_id'=>'3',
            'first_name'=>$data['first_name'],
            'title'=>$data['title'],
            'last_name'=>$data['last_name']
        );
        
        $meta_field = array(
            'country'=>$data['country'],
            'state'=>$data['state'],
            'location'=>$data['location'],
            'sector'=> (!empty($data['sector']) ? implode(',',$data['sector']) : ''), 
            'salary'=>$data['salary'],
            'salary_type'=>$data['salary_type'],
            'qualification'=>$data['qualification'],
            'notice_period'=>$data['notice_period'],
            'shift_type'=> (!empty($data['shift_type']) ? implode(',',$data['shift_type']) : ''),  
            'availability'=> (!empty($data['availability']) ? implode(',',$data['availability']) : ''),  
            'operationl_level'=>$data['operationl_level'],
            'currency_type'=>$data['currency_type'],
            'enquiry_source'=> $data['enquiry_source'],
             
            'other_enquirysource'=> (isset($data['other_enquirysource']) ? $data['other_enquirysource'] : '')  ,
             
            'daytime_tel' => $data['daytime_tel'],
            'mobile' => $data['mobile'],
            'resume_docs'=>$data['resume_docs']  // Document is uploded through ajax functionality
        ); 
        $ja_data = array(
            'is_alert' => (isset($data['is_alert']) ? $data['is_alert'] : '0'),
            'country'=>$data['country_ja'],
            'state'=>$data['state_ja'],
            'city'=> (isset($data['location_ja']) ? $data['location_ja'] : ''), 
            'contract_type'=>$data['contract_type_ja'],
            'sectors'=> (!empty($data['sector_ja']) ? implode(',',$data['sector_ja']) : ''),
            'currency_type'=>$data['currency_type_ja'],
            'min_salary'=>$data['salary_ja'],
            'salary_type'=>$data['salary_type_ja']
        );
        
         
     
        //print_r($ja_data);die();
        /* Check Uploded Doc files */
        /*if ($_FILES['resume_docs']['name'] != '') {
            $config['upload_path'] = './uploads/users/resumes/';
            $config['allowed_types'] = ALLOW_FILE_TYPE;
            $config['max_size']             = 10240; // 10 MB Max size 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('resume_docs')) {
                 
                $image_data = $this->upload->data();
                $meta_field['resume_docs'] = $image_data['file_name'];
            } else {

                $error = $this->upload->display_errors();
                $this->session->set_flashdata('dispMessage', '<div class="alert common-error">' . $error . '</div>'); 
                redirect('home/register');
            }
        }*/
        $form_field['created_at'] = DATE_TIME;
        $form_field['last_login'] = DATE_TIME;  
        $form_field['updated_at'] = DATE_TIME;  
        
        $this->home->set_table($this->tblUsers);
        $result = $this->home->save($form_field);
        $cannum = 'CAN-'.($result);
        $this->home->update(array('userreference_id'=>$cannum), array("user_id" => $result));


        /*$parent = array(); 
        if(!empty($meta_field['sector'])){
            for($k=0;$k<count($meta_field['sector']);$k++){
                $check =  $this->home->getparentid($meta_field['sector'][$k]);        
                if($check!=0){
                    $parent[] = $check;
                }
            }
            $parent = array_unique($parent);
            $final = array_merge($parent,$meta_field['sector']);
            $final =  array_unique($final);
            $meta_field['sector']  =  implode(',', $final);
        }*/
         
        if($result){

            /* Store this user in training portal section table */
            $this->update_userin_trainingportal($form_field);
            /* Store this user in training portal section table */


            /* Save user Document */
            $doc_data['user_id'] = $result;
            $doc_data['doc_type'] = 'resume_docs';
            $doc_data['document'] = $data['resume_docs'];  // Document is uploded through ajax functionality 
            $this->home->set_table('user_documents');
            $this->home->save($doc_data);  
            /* End */

            /* Job alerrt setting */
            $ja_data['user_id'] = $result;
            $this->home->set_table('job_alert_setting');
            $this->home->save($ja_data);  
            /* Job alert setting complete */
            /* User meta add */
            $this->home->set_table('usermeta');
            foreach ($meta_field as $key=>$val) {
                $savedata['user_id'] = $result;
                $savedata['meta_key'] = $key;
                $savedata['meta_value'] = $val; 
                $this->home->save($savedata);  
            }

           


            $this->home->set_table($this->tblUsers);
            $check_auth_data['user_id'] =  $result;  
            $valid_user = $this->home->get("*", $check_auth_data); 
            $userdata = $valid_user['0']; 
            $this->session->set_userdata('user_logged_in', $userdata); 
            
            $userdatas = $this->session->userdata['user_logged_in'];
            $titlmsg = "Register successfully.";
            $linksend = base_url('training/account'); 
            $subject = SITE_TITLE . " - ".$titlmsg;
            $email_data['email_title'] = "Hello ".$userdatas['first_name']."";
            $email_data['email_content'] = "You have succesfully Register with The Flamlily.Please check your details. ";
            $email_data['email_description'] = "<table><tbody>
                        <tr><td>Name:</td> <td> ".$userdatas['first_name']."</td></tr>
                        <tr><td>Email:</td> <td> ".$userdatas['email']."</td></tr>
                        <tr><td>Password:</td> <td> ".$userdatas['password']."</td></tr>
                                </tbody></table>
                        <br>
                        <p> <a href='".$linksend."'>Click here</a> view your Courses </p>";

            $email_template = $this->load->view('email/basic_mail', $email_data, true);
            $tomail = $userdatas['email'];
            $this->common->send_mail($tomail, $subject, $email_template);   



            $msg = 'Register Successfully.';
            $this->session->set_flashdata('returnError', $msg);

            if($this->input->cookie('jobapply_id') != null){ 
                redirect("home/afterlogin_apply/".$this->input->cookie('jobpostid')); 
            }

            redirect('account');
         
        } else {
            $msg = '<div class = "alert common-error"><p> Some error occured.</p></div>';
            $this->session->set_flashdata('returnError', $msg);
            redirect('home/register');
        }  
    }

    public function update_userin_trainingportal($traingdata){

        $this->home->set_table('ft_users');
        
        $savetraingdata['user_login'] = $traingdata['first_name']; 
        $savetraingdata['user_pass'] = md5($traingdata['password']);
        $savetraingdata['user_nicename'] = $traingdata['first_name'];
        $savetraingdata['user_email'] = $traingdata['email']; 
        $savetraingdata['user_registered'] = $traingdata['created_at']; 


        $traininguserid = $this->home->save($savetraingdata);

        if($traininguserid){
            
            $this->home->set_table('ft_usermeta');
            $usermetaportal['first_name'] = $traingdata['first_name']; 
            $usermetaportal['last_name'] = $traingdata['last_name'];
            $usermetaportal['ft_capabilities'] = 'a:1:{s:10:"subscriber";b:1;}'; 

            //print_r($usermetaportal);die();
            foreach ($usermetaportal as $key=>$val) {
                $savedata['user_id'] = $traininguserid;
                $savedata['meta_key'] = $key;
                $savedata['meta_value'] = $val; 
                $this->home->save($savedata);  
            }

        }else{

            $msg = '<div class = "alert common-error"><p> User is not updated in training portal.</p></div>';
            $this->session->set_flashdata('returnError', $msg);
            redirect('home/register');
        }

    }

    public function uploadresume(){
         /* Check Uploded Doc files */
        if ($_FILES['resumedocs']['name'] != '') {
            $config['upload_path'] = './uploads/users/resumes/';
            $config['allowed_types'] = ALLOW_FILE_TYPE;
            $config['max_size']             = 10240; // 10 MB Max size 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('resumedocs')) {
                 
                $image_data = $this->upload->data();
                $response['filename'] = $image_data['file_name'];
                $response['type'] = 'success'; 
                $response['msg'] = 'You file upload Successfully.';
                $response['uplod_filename'] = $_FILES['resumedocs']['name'];  
            } else {

                $error = $this->upload->display_errors();
                $response['type'] = 'error';
                $response['msg'] = $error;  
            }
        }else{
            $response['type'] = 'error'; 
            $response['msg'] = 'Please select your doc.'; 
        }
        echo json_encode($response);

          
        
    }



    
    public function email_refer($jobid) {

        $this->home->set_table($this->tblJobpost);
        $datas = $this->home->get("*", array("jobpost_id" => $jobid));
        $urllink = base_url('job/'.$datas['0']['joburl']);
        $datass['postdetails'] = $datas['0']; 
        $sendmessage  =  "I saw this vacancy at ".base_url()." and thought you might be interested. <br >

                                <b>Job Tilte: </b>". $datass['postdetails']['jobtittle']." <br>

                                For more information on this vacancy, please visit the website at: <a href='$urllink' >
                                ".base_url('job/').$datas['0']['joburl']." </a>";  


        if($this->input->post('action')=='send_refere'){

            $data = $this->input->post(); 
            $msg = "Job reffered by - ";
                
            $subject = $msg. ''.$data['sender_name'];
            $email_data['email_title'] = "Hello ".$data['sender_name'];
            $email_data['email_content'] = "One of your friend has sent you a new job vacancy details. Pleas check below details.";

            $email_data['email_description'] = '<p> <b>Friend details:</b></p>
                                <table>
                                <tbody>
                                    <tr>
                                        <td> Name:  </td>
                                        <td>'.$data['sender_name'].'</td>
                                    <tr>
                                    <tr>
                                        <td> Email:  </td>
                                        <td>'.$data['sender_email'].'</td>
                                    <tr>
                                    <tr>
                                        <td> Phone:  </td>
                                        <td>'.$data['sender_contact'].'</td>
                                    <tr>
                                    </tbody>
                                    </table>
                                    <br/> 
                                 <p><b> Job Description: </b></p>
                                 <p> '.$sendmessage.'</p> ';
            $email_template = $this->load->view('email/basic_mail', $email_data, true);
            //print_r($email_template);die();
            $tomail = $data['refered_email'];
            $this->common->send_mail($tomail, $subject, $email_template);   
            $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><p>Your job has been successfully sent!</p></div>');
            redirect($urllink); 

        } 
        $this->home->set_table($this->table);
        $data['metas'] = $this->home->get("", array("page_url" => "refer_afriend"));
        $this->set_page_title((!empty($data['metas'])) ? $data['metas']['0']['title'] : 'Refer a friend');

        $this->home->set_table($this->tblJobpost);
        $data = $this->home->get("*", array("jobpost_id" => $jobid));
        $data['postdetails'] = $data['0']; 
        $data['sendmessage']  =  "I saw this vacancy at ".base_url()." and thought you might be interested.

                                Job Tilte: ". $data['postdetails']['jobtittle']."

                                For more information on this vacancy, please visit the website at:  ".$urllink;  

        
        $this->load->view('refere_friend', $data);
        
    }


    public function load_cms_page($page_url) {
        
        $page_url = urldecode($page_url);
        $this->home->set_table($this->table);
        $query = " ( page_url = '".$page_url."' or page_new_url = '".$page_url."' )  and cms_type = 'content'  ";
        $data['metas'] = $this->home->get("*", $query);
         
        $this->set_page_title((!empty($data['metas'])) ? $data['metas']['0']['meta_title'] : 'CMS Page');

        $data = $this->home->get("", $query);
        if ($data) {
            
            $data['cms'] = $datas =  $data['0'];

            $this->set_page_title((!empty($datas)) ? $datas['meta_title'] : 'Welcome to the flamelily');

            if($datas['newurl']=='1' &&  $datas['page_new_url']!=$page_url ){
                redirect(base_url($datas['page_new_url']));
            }
            
            $this->home->set_table('pages_gallery');
            if($datas['gallery_type']=='0'){
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'0'),array('base_image'=>'desc')); 
                $data['videocount'] = '0';  
            }else{
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'1'),array('is_video'=>'desc')); 
                $data['videocount'] = $this->home->count_record(array("page_id" => $datas['pages_id'] , "is_video" => 1 ) );  
            }
            

            
            $this->load->view('load_cms_view', $data);
        } else {
            show_404();
        }
    }
    
    public function send_us_job() {

        if($this->input->post('action')=='send_job'){

            $data = $this->input->post(); 
            
            $config['upload_path'] = './uploads/sendjobs_resume/';
            $config['allowed_types'] = ALLOW_FILE_TYPE;
            $config['max_size']             = 10240; // 10 MB Max size 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('check_file')) {
                 
                $image_data = $this->upload->data();
                $response['filename'] = $image_data['file_name'];

                $msg = "New Job - ".$data['job_title'];
                
                $subject = $msg. ' from '.$data['name'];
                $email_data['email_title'] = "Hello Admin ";
                $email_data['email_content'] = "One user sent a new job details. Please check below details.";

                $email_data['email_description'] = '<p> <b> Details:</b></p>
                                    <table>
                                    <tbody>
                                        <tr>
                                            <td> Name:  </td>
                                            <td>'.$data['name'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Company Name:  </td>
                                            <td>'.$data['company_name'].'</td>
                                        <tr>

                                        <tr>
                                            <td> Email:  </td>
                                            <td>'.$data['email'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Phone:  </td>
                                            <td>'.$data['telephone'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Job Title:  </td>
                                            <td>'.$data['job_title'].'</td>
                                        <tr>
                                        
                                        <tr>
                                            <td> Location:  </td>
                                            <td>'.$data['location'].'</td>
                                        <tr>
                                        
                                        

                                        <tr>
                                            <td> Contract Type:  </td>
                                            <td>'.$data['contract_type'].'</td>
                                        <tr>

                                        <tr>
                                            <td> Salary Range:  </td>
                                            <td>'.$data['salary_range'].'</td>
                                        <tr>
                                        
                                        <tr>
                                            <td> Industry Sector:  </td>
                                            <td>'.$data['industry_sector'].'</td>
                                        <tr>

                                        <tr>
                                            <td> Benefits/Package:  </td>
                                            <td>'.$data['benefits_package'].'</td>
                                        <tr>

                                        <tr>
                                            <td> Other Notes:  </td>
                                            <td>'.$data['other_notes'].'</td>
                                        <tr>

                                        </tbody>
                                        </table>
                                        <br/> 
                                    <p><b> Job Description: </b></p>
                                    <p> '.$data['jobdescription'].'</p> ';
                $email_template = $this->load->view('email/basic_mail', $email_data, true);
                //print_r($email_template);die();
                
                //$this->common->send_mail($data['refered_email'], $subject, $email_template);   
                $this->home->set_table($this->tblUsers);
                $admindata = $this->home->get('user_id, email',array('role_id'=>1));
                $to = $admindata[0]['email']; // Admin Mail

                $from = INFO_EMAIL; 
                $config['charset'] = 'UTF-8';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';
                
                $this->load->library('email');
                $this->email->initialize($config);
                //$this->email->set_newline("\r\n");
                $this->email->set_mailtype("html");
                $this->email->from($from);
                $this->email->to($to);
                $this->email->bcc('bliss.dipesh@gmail.com');
                $this->email->subject($subject);
                $this->email->message($email_template); 
                //$this->email->attach(base_url().'uploads/sendjobs_resume/'.$response['filename']);
                $path = set_realpath('uploads/sendjobs_resume/'); // Please load path helper  
                $this->email->attach($path . $response['filename']);
                $this->email->send();
                /*if($this->email->send())
                {
                    echo 'Email send.';
                }
                else
                {
                    show_error($this->email->print_debugger());
                }*/ 
                $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><p> Your Job details sent successfully.</p></div>');
                redirect('home/send_us_job');
            
            } else {
                $error = $this->upload->display_errors(); 
                $this->session->set_flashdata('dispMessage', '<div class="alert common-error"><i class="icon-ok"></i> '.$error.'</div>');
            }

        } 
        $this->home->set_table($this->table);
        $data['metas'] = $this->home->get("", array("page_url" => "send_us_job"));
        $this->set_page_title((!empty($data['metas'])) ? $data['metas']['0']['title'] : 'Job-send');
        $data['contracttype'] = $this->utility->getcontacttype(); 
        $this->load->view('send-us-job', $data);
        
    }

    public function contactus(){

        if($this->input->post('name')){
 
            $maildata = $this->input->post(); 
            
            $config['upload_path'] = './uploads/contactus_attchment/';
            $config['allowed_types'] = ALLOW_FILE_TYPE;
            $config['max_size']             = 10240; // 10 MB Max size 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if($_FILES['attachment']['name']){
                if ($this->upload->do_upload('attachment')) {

                    $image_data = $this->upload->data();
                    $response['filename'] = $image_data['file_name']; 

                } else {

                    $error = $this->upload->display_errors(); 
                    $this->session->set_flashdata('dispMessage', '<div class="alert common-error"><p> '.$error.'</p></div>');
                    $data = array();
                    $this->load->view('contactus',$data);
                }
            }
                $msg = "Contact Us";
                   
                $subject = $msg. ' from '.$maildata['name'];
                $email_data['email_title'] = "Hello Admin ";
                $email_data['email_content'] = "One user wants to contact you. Please check below details.";

                $email_data['email_description'] = '<p> <b> Details:</b></p>
                                    <table>
                                    <tbody>
                                        <tr>
                                            <td> Name:  </td>
                                            <td>'.$maildata['name'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Email:  </td>
                                            <td>'.$maildata['email'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Phone:  </td>
                                            <td>'.$maildata['phone'].'</td>
                                        <tr>
                                        <tr>
                                            <td> Company:  </td>
                                            <td>'.$maildata['compnay'].'</td>
                                        <tr> 
                                       
                                        </tbody>
                                        </table>
                                        <br/> 
                                    <p><b> Message: </b></p>
                                    <p> '.$maildata['message'].'</p> ';
                $email_template = $this->load->view('email/basic_mail', $email_data, true); 
                
                
                $this->home->set_table($this->tblUsers);
                $admindata = $this->home->get('user_id, email',array('role_id'=>1));
                $to = $admindata[0]['email']; // Admin Mail
                $from = INFO_EMAIL;
                 
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';
                
                $this->load->library('email');
                $this->email->clear(TRUE);
                $this->email->initialize($config); 
                $this->email->set_newline("\r\n");
                $this->email->set_mailtype("html");
                $this->email->from($from,'TheFlamelily');
                $this->email->to($to);
                $this->email->bcc('bliss.dipesh@gmail.com');
                $this->email->subject($subject);
                $this->email->message($email_template);
                $path = set_realpath('uploads/contactus_attchment/'); // Please load path helper  
               
                if($_FILES['attachment']['name']){
                     $this->email->attach($path . $response['filename']);
                }
                $this->email->send();
                
                /*if($this->email->send())
                {
                    $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><strong><p> Mail sent successfully.</p></strong></div>');
                    redirect('home/contactus'); 
                }
                else
                { 
                    $error = $this->email->print_debugger(); 
                    $this->session->set_flashdata('dispMessage', '<div class="alert common-error"><p> '.$error.'</p></div>');
                    redirect('home/contactus');
                }*/
                
                $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><strong><p> Mail sent successfully.</p></strong></div>');
                redirect('home/contactus'); 

        }
        $data = array();
        $this->load->view('contactus',$data);

    }

    public function blog($categoryname = '') {
        

        $this->home->set_table($this->table);  
        $data['metas'] = array();
        $this->set_page_title('Blog');


        $this->load->library('pagination');
        $data = array(); 
        $searchdata = $this->input->get();

        $querypara = $_SERVER['QUERY_STRING']; 
        
        $search_param['seach_text'] = (isset($searchdata['keyword_search']) ? $searchdata['keyword_search'] : ''); 
        $search_param['category_name'] = (isset($categoryname) ? urldecode($categoryname) : ''); 
        //print_r($search_param);
        if($categoryname){
            $config["base_url"] = base_url()."category/".$categoryname."?keyword_search=".$search_param['seach_text'];
        }else{
            $config["base_url"] = base_url()."blogs?keyword_search=".$search_param['seach_text'];
        }
        if(isset($searchdata['per_page'])){
            $page = $searchdata['per_page'] ;
        } else{
            $page = 0;
        }
        

        // Number of items you intend to show per page.
        $config["per_page"] = 10;
        $search_param['Offset'] =  $page;
        $search_param['Limit'] = $config["per_page"];

        $config['attributes'] = array('class' => 'page-numbers');


        $config['full_tag_open'] = '<div class="pagination-blog" >';
        $config['full_tag_close'] = '</div>'; 
        $config['cur_tag_open'] = '<a class="page-numbers active">';
        $config['cur_tag_close'] = '</a>';
       /* $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="first_tags">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="last_tags">';
        $config['last_tag_close'] = '</li>';*/
        $config['next_link'] = 'Next<span>→</span>';
        $config['next_tag_open'] = '<div class="page-numbers next">'; 
        $config['next_tag_close'] = '</div>'; 
       
        $config['prev_link'] = '<span>←</span>Previous';
        /*$config['prev_tag_open'] = '<div class="page-numbers previous">'; 
        $config['prev_tag_close'] = '</div>'; */
        
        $config['num_tag_open'] = '';
        $config['num_tag_close'] = '';
        $config['page_query_string'] = true;
      

        $result = $this->home->blog_search($search_param);
        //print_r($result);die(); 
        $data['blogresult'] = $result['result']; 
        $data['total_rows'] = $result['pages'];
        // Set total rows in the result set you are creating pagination for.
        $config["total_rows"] = $data['total_rows'];
         

        // To initialize "$config" array and set to pagination library.
        $this->pagination->initialize($config);

        
         /* Display #news tags blogs it means News category blog displays */
        $this->home->set_table($this->table);
        $data['recent_posts'] = $this->home->custom_query(' select pages_id, title, page_url, content , ( select img_name from pages_gallery where page_id = pages_id and base_image = 1) as blogimage from pages where cms_type = "blog" and category = "24" order by pages_id  desc limit 3 ');

        $data['slider_blogs'] = $this->home->custom_query(' select created_at, pages_id,page_url, title, content , ( select img_name from pages_gallery where page_id = pages_id and base_image = 1) as blogimage ,( select name from category where category_id = category ) as categoryname , ( select count(0) from blog_view where blog_id = pages_id ) as totalviews 
from pages where cms_type = "blog" order by pages_id  desc limit 6 ');


        $this->home->set_table($this->tblCategory);
        $data['blogcategory'] = $this->home->get("*", array("cat_type" => '1'),array('display_order'=>'asc'));

        $data['blogrightcomment']= "Noteworthy";

        $this->load->view('blog', $data);
    }

     

    //Check email is unique
    public function is_unique_email() {
        $condition['email'] = $this->input->post('email');
        $this->home->set_table($this->tblUsers);
        $result = $this->home->get('*', $condition);
        
        if($result){ 
            $response['type'] = 'exists'; 
            
        }else{  
            $response['type'] = 'new'; 
        } 
        echo json_encode($response);
    }
           
    public function logout() {
        //$this->_isLogin();
        
         

        $user_id = $this->session->userdata['user_logged_in']['user_id'];
        $update_data = array('last_login' => DATE_TIME);
        $this->general->set_table($this->tblUsers);
        $this->general->update($update_data, array('user_id' => $user_id));
        $this->session->sess_destroy();
        $response['type'] = 'success'; 
        echo json_encode($response);
        /*$refer =  $this->agent->referrer();
        $refer_replace = str_replace(base_url(), '', $refer);
        $refer_expl = explode('/', $refer_replace);
        if(isset($refer_expl) && $refer_expl[0] =='account'){
            redirect('/home');    
        }
        redirect($refer);*/
    } 
    
    public function getstate()
    {
        $country = $this->input->post('country');
        $this->general->set_table('location'); 
        $result = $this->general->get("id,locationname", array("parent_id" => $country)); 
        $html = "";
        $html .=" <select name='state' id='state' onchange='showlocation(this.value);'  >";
        $html .=" <option value=''>Select State </option>";
        if($country!=''){
            if($result){
                foreach ($result as $state) {
                    $html .= '<option value="'.$state['id'].'">'.$state['locationname'].'</option>';
                }
            }
        }
        $html .= '</select>'; 
        echo $html;  
    }
    public function getlocation()
    {
        $state = $this->input->post('state');
        $this->general->set_table('location'); 
        $result = $this->general->get("id,locationname", array("parent_id" => $state)); 
        $html = "";
        $html .=" <select name='location' id='location'   class='form-control select2'>";
        $html .=" <option value=''>Select City </option>";
        if($state!=''){
            if($result){ 
                foreach ($result as $state) {
                    $html .= '<option value="'.$state['id'].'">'.$state['locationname'].'</option>';
                }
            }
        }
        $html .= '</select>'; 
        echo $html;  
    } 

    public function getstate_ja()
    {
        $country = $this->input->post('country');
        $this->general->set_table('location'); 
        $result = $this->general->get("id,locationname", array("parent_id" => $country)); 
        $html = "";
        $html .=" <select name='state_ja' id='state' onchange='showlocation_ja(this.value);'  >";
        $html .=" <option value=''>Select State </option>";
        if($country!=''){
            if($result){
                foreach ($result as $state) {
                    $html .= '<option value="'.$state['id'].'">'.$state['locationname'].'</option>';
                }
            }
        }
        $html .= '</select>'; 
        echo $html;  
    }
    public function getlocation_ja()
    {
        $state = $this->input->post('state');
        $this->general->set_table('location'); 
        $result = $this->general->get("id,locationname", array("parent_id" => $state)); 
        $html = "";
        $html .=" <select name='location_ja' id='location'   class='form-control select2'>";
        $html .=" <option value=''>Select City </option>";
        if($state!=''){
            if($result){ 
                foreach ($result as $state) {
                    $html .= '<option value="'.$state['id'].'">'.$state['locationname'].'</option>';
                }
            }
        }
        $html .= '</select>'; 
        echo $html;  
    }


    public function blogcomment($id){
        $data = $this->input->post(); 
        $data['blogpost_id'] = $id;
        $this->home->set_table('blog_comments'); 

        $result = $this->home->save($data);

        $msg = '<div class = "alert common-success"><p> Comment Added Successfully.</p></div>';
        $this->session->set_flashdata('successmessag', $msg);

        $this->home->set_table('pages');
        $getblogurl = $this->home->get('*',array('pages_id'=>$id));
        redirect('blog/'.$getblogurl[0]['page_url']); 

    }

    public function managebookmark(){    
        $data = $this->input->post();
        $stordata['jobpost_id'] = $data['postid'];
        $stordata['ip_address'] = $this->input->ip_address();
         
        $this->home->set_table('bookmark_manage');

        $dupCondition["jobpost_id"] =  $data['postid']; 
        $dupCondition["ip_address"] = $stordata['ip_address'];
         
        $dupResult_question = $this->home->get('bookmark_id',$dupCondition);
        if($dupResult_question){
            $checkedRowValue['bookmark_id'] = $dupResult_question[0]['bookmark_id']; 
            $this->home->delete($checkedRowValue);
            $response['type'] = 'deletes';
            $response['replace_text'] = "Bookmark this article";
        }else{  
            $result = $this->home->save($stordata);
            $response['type'] = 'stores';
            $response['replace_text'] = "Article Bookmarked";
        } 
        echo json_encode($response);

    }

    // Blog page URL check
    public function blogreview($page_url){

        $page_url = urldecode($page_url);
        $this->home->set_table($this->table); 
        $query = " page_url = '".$page_url."' or page_new_url = '".$page_url."'  and cms_type = 'blog' ";
        $data['metas'] = $checkpage = $this->home->get("*", $query); 
        $this->set_page_title( (isset($data['metas']['0']['meta_title'])) ? $data['metas']['0']['meta_title'] : $data['metas']['0']['title']);
         
        //echo $checkpage[0]['cms_type'];die();
        if(!empty($checkpage) && $checkpage[0]['cms_type']=='blog'){
            
            // print_r($checkpage);die();
            if($checkpage[0]['newurl']=='1' &&  $checkpage[0]['page_new_url']!=$page_url ){
                redirect(base_url('blogs/'.$checkpage[0]['page_new_url']));
            }

            //$this->blogdetail(); 
            $id = $checkpage[0]['pages_id'];

            $dupCondition["ip_address"] =  $this->input->ip_address();
            $dupCondition["blog_id"] = $id;
            $this->home->set_table('blog_view');
            $dupResult_question = $this->home->checkDuplicate($dupCondition); 
            if(!$dupResult_question){
                $stordata['ip_address'] = $this->input->ip_address();
                $stordata['blog_id'] = $id;
                $this->home->save($stordata);
            }
            
            $data['blogdetails'] = $this->home->blog_detail($id); 
           // print_r($data['blogdetails']->catid);die();
            $data['trandingblogs'] = $this->home->tranding_blogs($data['blogdetails']->catid,$id); 
           
            /* Display #news tags blogs it means News category blog displays */
            $this->home->set_table($this->table);
            $data['recent_posts'] = $this->home->custom_query(' select pages_id, title, page_url, content , ( select img_name from pages_gallery where page_id = pages_id and base_image = 1) as blogimage from pages where cms_type = "blog"  order by pages_id  desc limit 3 ');

            $this->home->set_table($this->tblCategory);
            $data['blogcategory'] = $this->home->get("*", array("cat_type" => '1'),array('display_order'=>'asc'));

            $this->home->set_table('blog_comments');
            $data['blogcomments'] = $this->home->get("*", array("blogpost_id" => $id),array('comment_id'=>'desc'));

            $bokmarkCondition["jobpost_id"] =  $id; 
            $bokmarkCondition["ip_address"] = $this->input->ip_address();
            $this->home->set_table('bookmark_manage');
            $data['bookmark_set'] = $this->home->get('bookmark_id',$bokmarkCondition);

            $this->set_page_title($data['blogdetails']->title);
            
            $data['blogrightcomment']= "Recent Post";

            $this->load->view('blog-detail', $data);



        }   else {
            show_404('error_404.php');
        } 
    } 

    // Candidate landing page
    public function candidate($page_url){
        $page_url = urldecode($page_url); 
       
        $this->home->set_table($this->table); 
        $query = " page_url = '".$page_url."' or page_new_url = '".$page_url."' and cms_type = 'candidate landing'  ";
        $data['metas'] = $checkdata= $this->home->get("*", $query);

        $this->set_page_title((isset($data['metas']['0']['meta_title'])) ? $data['metas']['0']['meta_title'] : $data['metas']['0']['title']);
   
          
        if ($checkdata) {
            $data = $datas =  $checkdata['0']; 

            $this->set_page_title((!empty($data['meta_title'])) ? $data['meta_title'] : 'Welcome to the flamelily');
            
            if($data['newurl']=='1' &&  $data['page_new_url']!=$page_url ){
                redirect(base_url('candidates/'.$data['page_new_url']));
            }

            $this->home->set_table('pages_gallery');
            if($datas['gallery_type']=='0'){
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'0'),array('base_image'=>'desc')); 
                $data['videocount'] = '0';  
            }else{
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'1'),array('is_video'=>'desc')); 
                $data['videocount'] = $this->home->count_record(array("page_id" => $datas['pages_id'] , "is_video" => 1 ) );  
            }

            $this->load->view('candidate-landing', $data);
        } else {
            show_404('error_404.php');
        } 
    }

    // Normal landing page which are only display formated html without header and footer
    public function load_landing_page($page_url,$secondpageurl) {
        $page_url = urldecode($page_url);
        $secondpageurl = urldecode($secondpageurl);
        $newurl = $page_url.'/'.$secondpageurl;
        $this->home->set_table($this->table);
        $query = " page_url = '".$newurl."' or page_new_url = '".$newurl."'  "; 
        $data = $this->home->get("*", $query); 
       
        if ($data) {
            $data = $data['0'];
             
            if($data['newurl']=='1' &&  $data['page_new_url']!=$newurl ){
                redirect(base_url('lp/'.$data['page_new_url']));
            }

            $this->load->view('load_landing_view', $data);

        } else {
            show_404();
        }
    }

    // Normal landing page which are only display formated html without header and footer
    public function clients($page_url) {

        $page_url = urldecode($page_url);
        
        $this->home->set_table($this->table);
        $query = " ( page_url = '".$page_url."' or page_new_url = '".$page_url."' ) and cms_type = 'clients'   ";
        $data = $this->home->get("*", $query); 
       
        if ($data) {
            $data = $datas =  $data['0'];

            $this->set_page_title((!empty($data)) ? $data['meta_title'] : 'Welcome to the flamelily');

            if($data['newurl']=='1' &&  $data['page_new_url']!=$page_url ){
                redirect(base_url('clients/'.$data['page_new_url']));
            }

            $this->home->set_table('pages_gallery');
            if($datas['gallery_type']=='0'){
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'0'),array('base_image'=>'desc')); 
                $data['videocount'] = '0';  
            }else{
                $data['cmsimages'] = $this->home->get("", array("page_id" => $datas['pages_id'],'is_video'=>'1'),array('is_video'=>'desc')); 
                $data['videocount'] = $this->home->count_record(array("page_id" => $datas['pages_id'] , "is_video" => 1 ) );  
            }

            $this->load->view('load_clients_view', $data);

        } else {
            show_404();
        }
    }


    // Ajax location by Dipesh Shah. 
    // Date 4 Sep 
    public function getajaxlocation(){
        $this->home->set_table('location');
        $html = "";
        $return_arr  = array();
        if(!empty($_GET["term"])) {
            $html .= '<ul id="country-list">';

            $country_query ="SELECT * FROM location WHERE locationname like '%" . $_GET["term"] . "%' and l_type = 0 ORDER BY locationname  ";
            $country_result = $this->home->custom_query($country_query);
            
            if(!empty($country_result)) {
                foreach($country_result as $country) {  
                    $click = "selectCountry('".$country["locationname"]."')";
                    $return_arr[] = $country["locationname"];
                    $html .= '<li onClick="'.$click.'" >'.$country["locationname"].'</li>';
                } 
            }

            $region_query ="SELECT * FROM location WHERE locationname like '%" . $_GET["term"] . "%' and l_type = 1 ORDER BY locationname  ";
            $region_result = $this->home->custom_query($region_query);
            
            if(!empty($region_result)) {
                foreach($region_result as $country) {  

                    $contryname = $this->home->get_locationname($country["parent_id"]);
                    $name = $country["locationname"] .','.$contryname; 
                    $click = "selectCountry('".$name."')";
                    $return_arr[] = $name;
                    $html .= '<li onClick="'.$click.'"  >'.$name.'</li>';
                } 
            }

            $county_query ="SELECT * FROM location WHERE locationname like '%" . $_GET["term"] . "%' and l_type = 2 ORDER BY locationname  ";
            $county_result = $this->home->custom_query($county_query);
            
            if(!empty($county_result)) {
                foreach($county_result as $country) {  
                      
                    $region = $this->home->get('*', array('id'=>$country["parent_id"]));
                    $contryname = $this->home->get_locationname($region[0]["parent_id"]);

                    $name = $country["locationname"] .','.$region[0]["locationname"].','.$contryname; 
                    $click = "selectCountry('".$name."')";
                    $return_arr[] = $name;
                    $html .= '<li onClick="'.$click.'" >'.$name.'</li>';
                } 
            }


            $html .= '</ul>';
        }
        echo json_encode($return_arr);
        //echo $html;
    }


     // Testimonial page
    public function testimonial() {
        $this->home->set_table('testimonials');

        if($this->input->post('action')=='submit'){
            $data = $this->input->post();
            $data['testimonial_type'] = 'candidate';
            unset($data['action']); 
            $result = $this->home->save($data);
            $this->session->set_flashdata('dispMessage', '<div class="alert common-success"><p> Thanks for your views about The Flamelily.</p></div>');
            redirect('home/testimonial');

        } 
        $query = " testimonial_type = 'client' and is_promotional = '1' "; 
        $data['employetesti'] = $this->home->get("*", $query,array('id'=>'DESC'));

        $querys = " testimonial_type = 'client' and is_promotional = '0' "; 
        $data['employetesti_slider'] = $this->home->get("*", $querys,array('id'=>'DESC'));
        
        $query = " testimonial_type = 'candidate' and status = 'approved' "; 
        $data['candidatetesti'] = $this->home->get("*", $query,array('id'=>'DESC'));
 
        $this->load->view('testimonial', $data);

    }


}