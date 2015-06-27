<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$config['upload_path']		= "./assets/ufile";
	
	$config['upload_imgtype']	= 'gif|jpg|png|bmp|jpeg|jpe';
	$config['upload_imgsize']	= 10 * 1024; // 10M
	$config['upload_img_w']	= '200';
	$config['upload_img_h']	= '150';

	$config['max_count_per_page'] = 5;
	
	$config['error_start_delimiter']   = '<div class="alert alert-error"><button class="close" data-dismiss="alert"></button><strong>Error!</strong> ';		// Error mesage start delimiter
	$config['error_end_delimiter']     = '</div>';	// Error mesage end delimiter

	$config['thumb_name'] = "thumb";
	$config['photo_name'] = "photo";
	


