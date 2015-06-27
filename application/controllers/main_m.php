<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Main_m extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	//	$this->load->database();
	}
	
	
	function get_next_insert_idx($tbl_name) {

		$next_increment = 0;
		$strSql = "SHOW TABLE STATUS WHERE Name='$tbl_name'";
		$query = $this->db->query($strSql);
		$row = $query->row_array();
		$next_increment = $row['Auto_increment'];
		
		return $next_increment;
	}
	
	function get_upload_path($path_type,$media_type='',$media_idx) {
		// local path
		if($path_type==0) {
			return $this->config->item("upload_path")."/".$media_type."/".$media_idx;
		}
		//http path
		if($path_type==1) { 
			return RT_PATH.substr($this->config->item("upload_path"),1)."/".$media_type."/".$media_idx;
		}
		return "http://".$_SERVER['HTTP_HOST'].RT_PATH.substr($this->config->item("upload_path"),1)."/".$media_type."/".$media_idx;
	}
	
	/*
	function img_resize($mode, $upload, $new_name="")
    {
   		$w = $mode==0? $this->config->item('upload_thumb_mw'):316;
   		$h = $mode==0? $this->config->item('upload_thumb_mh'):178;
   		$fbase_name = $new_name.($mode==0? self::$thumb_name : self::$photo_name);
    	   		
        $this->load->library('image_lib');
        $newpath = $upload['file_path'].$fbase_name.$upload['file_ext'];
        
        $config['image_library'] = 'gd2';
        $config['source_image']  = $upload['full_path'];
        $config['new_image'] = $newpath;
        $config['maintain_ratio'] = TRUE;
        $config['width']     = $w;
        $config['height']    = $h;
        
        $this->image_lib->initialize($config); 
        
        if ( ! $this->image_lib->resize())
        {
            echo $this->image_lib->display_errors();
        }
        
        unset($config);
        $this->image_lib->clear();
    }
	*/
	
	function delTree($dirPath) {

		if (! is_dir($dirPath)) {
	    //    throw new InvalidArgumentException("$dirPath must be a directory");
	    	return;
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::delTree($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}
	
	
	
}