<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Assets {
	
	var $javascript = array();
	var $css = array();
	
	function __construct($config=array()){
		// Get CI instance
		$this->ci =& get_instance();
		if(!empty($config)){
			foreach($config as $k => $v){
				$this->$k = $v;
			}
		}else{
			show_error("Assets Config File is Missing or is Empty");
		}
	}
	
	function load($file,$level="",$params=array()){
		if(!$level){
			$level = $this->default_level;
		}
		$type = strtolower(substr(strrchr($file, '.'), 1));
		if($type=="jpg" || $type=="png" || $type=="gif" || $type=="ico"){
			// Generate Image Link
			$image_link = base_url()."$this->assets_folder/$level/images"."/".$file;
			// Generate the Paramaters
			$image_params = $this->generate_params($params);
			// Create Image Tag
			$output = "<img src='$image_link'$image_params />";
			return $output;
		}elseif($type=="js"){
			if(array_key_exists("extra",$params) && $params["extra"] != ""){
				$this->javascript[] = "$level/$type/$file/{$params["extra"]}";
			}else{
				$this->javascript[] = "$level/$type/$file";
			}
		}elseif($type=="css"){
			$this->css[] = "$level/$type/$file";
		}else{
			return false;
		}
	}
	
	function url($file,$level=""){
		if(!$level){
			$level = $this->default_level;
		}
		$type = strtolower(substr(strrchr($file, '.'), 1));
		if(array_key_exists($type,$this->asset_types)){
			foreach($this->asset_types as $asset_type => $folder){
				if($type == $asset_type){
					$output = base_url()."$this->assets_folder/$level/$folder"."/".$file;
					return $output;
					break;
				}
			}
		}else{
			show_error("$type is not a valid asset type");
		}
	}
		
	function generate_params($params){
		$output = '';
		if(!empty($params)){
			foreach($params as $k => $v){
				$output .= ' '.$k.'="'.$v.'"';
			}
		}
		return $output;
	}
	
	function display_header_assets(){
		$output = '';
		foreach($this->javascript as $file){
			$output .= "<script type='text/javascript' src='".base_url().$this->assets_folder."/".$file."'></script>\n";
		}
		foreach($this->css as $file){
			$output .= "<link type='text/css' rel='stylesheet' href='".base_url().$this->assets_folder."/".$file."' />\n";
		}
		
		//flushing out javascript & css variables
		$this->javascript = array();
		$this->css = array();
		
		return $output;
	}
}

?>