<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirm extends REST_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->library('ion_auth');
//        echo $token;exit;
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
        $this->load->model('main_m');
		
		$this->form_validation->set_error_delimiters(
			$this->config->item('error_start_delimiter'), 
			$this->config->item('error_end_delimiter')
		);
	}
    
    function _remap($method) {
        $this->{$method}();
    }
    
    function index(){
        
    }
    public function confirm_verification(){
        $_confirm_verification = $this->uri->segment(3);
        $user = $this->main_m->confirmVerification($_confirm_verification);
        
        $data['user'] = $user;
        $data['status'] = true;
        if($user !== false){
            $this->load->view('welcome_verification', $data);
        }else{
            $data['status'] = false;
            $this->load->view('welcome_verification', $data);
        }
    }

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */