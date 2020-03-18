<?php
ini_set('default_charset', 'UTF-8');

class Utility {

    //default construction
    var $skey = "vaningo.co.uk//TheFoxLab"; // you can change it

    public function __construct() {
        $CI =& get_instance();
        
    }

    public function safe_b64encode($string) {

        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value) {

        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value) {

        if (!$value) {
            return false;
        }
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    //encrypt values
    function encryptWord($word) {
        return addslashes($word);
    }

    //descrypt values
    function decryptWord($word) {
        return stripcslashes($word);
    }

    function dateFromat($date, $format) {
        return date($format, strtotime($date));
    }

    //	public function uniqueCode($tableName,$fieldName){
    public function uniqueCode($random_id_length = 10) {
        $stamp = date("Ymdhis");
        $ip = $_SERVER['REMOTE_ADDR'];
        $orderid = "$stamp-$ip";
        $orderid = str_replace(".", "", "$orderid"); //CODE--------1
        //set the random id length
        //generate a random id encrypt it and store it in $rnd_id
        $rnd_id = crypt(uniqid(rand(), 1));

        //to remove any slashes that might have come
        $rnd_id = strip_tags(stripslashes($rnd_id));

        //Removing any . or / and reversing the string
        $rnd_id = str_replace(".", "", $rnd_id);
        $rnd_id = strrev(str_replace("/", "", $rnd_id));

        //finally I take the first 10 characters from the $rnd_id
        $rnd_id = substr($rnd_id, 0, $random_id_length); //CODE--------2;
        //addition of the two code (CODE 1 + CODE 2);
        return $code = $orderid . $rnd_id;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function checklocationtype($id){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname,l_type,parent_id")->where(array('id = '=>$id))->get( 'location' );
        return $query->result_array();
    }

    public function getmenu($parent=0){
        $CI =& get_instance();
        $strQuery = "SELECT child_table.*,(
           SELECT COUNT(0)
           FROM menu_management
           WHERE parent_id = child_table.menuid) AS child_count
           FROM menu_management AS child_table
           WHERE child_table.parent_id = '".$parent."' ORDER BY menu_order asc "; 
         
        $query = $CI->db->query($strQuery );
        return $query->result_array();
    }
    
    public function get_menuimage(){
        $CI =& get_instance();
        $query = $CI->db->select("menuimage")->where(array('base_image '=>'1'))->get( 'menu_image' );
        
        if($query->num_rows() > 0 ){
            return $query->row()->menuimage;   
        }else{
            return false;
        }
        
    }

    public function header_country(){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname")->where(array('l_type = '=>'0'))->get( 'location' );
        return $query->result_array();
    }
    public function allstate(){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname,parent_id")->where(array('l_type = '=>'1'))->get( 'location' );
        return $query->result_array();
    }
    public function header_state($countryid){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname")->where(array('l_type = '=>'1','parent_id'=>$countryid))->get( 'location' );
        return $query->result_array();
    }
    public function header_area($countryid){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname")->where(array('l_type = '=>'2','parent_id'=>$countryid))->get( 'location' );
        return $query->result_array();
    }

    public function header_location(){
        $CI =& get_instance();
        $query = $CI->db->select("id as locationid,locationname")->where(array('l_type != '=>'0'))->get( 'location' );
        return $query->result_array();
    }
    public function header_sector(){
        $CI =& get_instance();
        $query = $CI->db->select("category_id,name as catname")->where(array('category_id != '=>'0','cat_type'=>'0'))->get( 'category' );
        return $query->result_array();
    }

    public function getsalarytype($type){
        
        $salarytype = array('1'=>'per annum','2'=>'per hour','3'=>'per day','4'=>'per week','5'=>'per month');
        foreach ($salarytype as $key => $value) {
            if($key==$type){
                return $value;
            }
        }
        return "";
    }

    public function order_status(){
        
        $salarytype = array('Pending'=>'Pending','Received'=>'Received','Approved'=>'Approved');
        return $salarytype;
    }

    public function get_salarytype(){
        
        $salarytype = array('1'=>'per annum','2'=>'per hour','3'=>'per day','4'=>'per week','5'=>'per month');
        return $salarytype;
    }
    // Call in cms module which cms page is added like blog ,content etc...
    public function page_types(){
        
        $salarytype = array('content'=>'Content','blog'=>'Blog','candidate landing'=>'Candidate Landing','clients'=>'Clients');
        return $salarytype;
    }
    
     public function getcontacttype(){
        
        $contract = array('Contract' ,'Permanent' ,'Temporary');
        return $contract;
    }

    public function user_documents(){
        
        $documenttype = array('application_form'=>'Application Form','crb_certy'=>'CRB Cert.','resume_docs'=>'CV','occupational_health_certy'=>'Occupational Health Certificate','passoprt_doc'=>'Passport','proof_address1_doc'=>'Proof of Address 1','proof_address2_doc'=>'Proof of Address 2','idproof_doc'=>'Proof Of ID','national_insuarance_doc'=>'Proof of National Insurance Number','nmc_doc'=>'Proof Of NMC','qualification_doc'=>'Qualifications','reference_detail1_doc'=>'Reference Details 1','reference_detail2_doc'=>'Reference Details 2','training_doc'=>'Training Documents','visa_doc'=>'Visa');
        return $documenttype;
    }

    function add3dots($string, $repl, $limit) 
    {
      if(strlen($string) > $limit) 
      {
        return substr($string, 0, $limit) . $repl; 
      }
      else 
      {
        return $string;
      }
    }

   public function curl_response($api_url, $method = '', $data = ''){

          $arr = array();  
          $curl = curl_init();

          curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            "x-auth-client: ".AUTH_ID,
            "x-auth-token: ".AUTH_TOKEN
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

            if ($err) {
                $arr = "cURL Error #:" . $err;
              
            } else {
               $arr = (json_decode($response,true));
            }
            return $arr;
        }
        
        public function get_purchase_data($str){
        return $str;
    } 

}