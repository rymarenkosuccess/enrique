<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
            return;
        }
        $this->load->helper('language');
        $this->load->model('main_m');
        
        $this->form_validation->set_error_delimiters(
            $this->config->item('error_start_delimiter'), 
            $this->config->item('error_end_delimiter')
        );
        $this->chanel = $this->session->userdata('chanel');
        $this->data['chanel'] = $this->chanel;
        $this->data['show_errors'] = array();
        $user = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->session->set_userdata('email', $user->email);
        
    }
    
    function _remap($method) {
        $this->load->view('header_v');
        $this->load->view('sidebar_v');
        $this->{$method}();
        $this->load->view('footer_v');
    }
    
    function index(){
        $start_time = $this->getOption('start');
        $end_time = $this->getOption('end');
        $cid = $this->chanel['id'];
        
//        $statsLogin = $this->main_m->getStatsLogin($start_time, $end_time, $cid);
        $statsPost = $this->main_m->getStatsPost($start_time, $end_time, $cid);

        $this->data['start_time'] = $start_time;
        $this->data['end_time'] = $end_time;
        $this->load->view('stats', $this->data);
    }
    
    public function getOption($key){
        $i = 2;
        $value = "";
        while($val = $this->uri->segment($i)){
            if(stripos($val, $key."_") !== false){
                $value = str_replace($key."_", "", $val);
                break;
            }
            $i++;
        }
        return $value;
    }
    

}

