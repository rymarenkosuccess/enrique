<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social_account extends CI_Controller {

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
        $social_type = $this->uri->segment(3);
        if(empty($social_type)){
            show_error("Select a social type!");
            return;
        }
        if($this->input->post('submit', false) === false){
//            $post = $this->main_m->getCategoryList($id);
//            $this->data['post'] = $post;
//            $this->data['post']['id'] = $id;
            $social_account = $this->main_m->getSocialAccount($social_type, $this->chanel['id']);
            $this->data['post'] = $social_account;
        }else{
            $this->data['post'] = $_POST;
        }
        $this->data['post']['social_type'] = $social_type;
        $this->data['post']['cid'] = $this->chanel['id'];
        $this->_proc_social_account();
        $this->load->view('social_account', $this->data);
    }
    
    public function _proc_social_account(){
        $this->form_validation->set_rules('username', 'username', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->input->post('password') && $this->input->post('password') != $this->input->post('confirm_password')){
                $this->data['show_errors'] = "The password doesn't match.";
            }elseif($this->main_m->updateSocialAccount($this->data['post'])){
                redirect("social", '');
            }
        }
        return $data;
    }
}

