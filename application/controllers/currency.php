<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends CI_Controller {

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
        $currencyInfo = $this->main_m->getCurrencyInfo();
        $this->data['rows'] = $currencyInfo;
        $this->_proc_add_currency();
        $this->load->view('currency', $this->data);
    }
    
    private function _proc_add_currency(){
        
        $data = array();
        
        if ($this->input->post('submit', false) !== false)
        {
            if($this->main_m->saveCurrencyInfo($_POST)){
                redirect("currency", '');
            }
        }
        return $data;
    }
    
//        $feed_id = $this->uri->segment(4);
//        $id = ltrim($feed_id, "text_");
//        if(empty($id)){
//            show_error("Select a post to edit!");
//            return;
//        }
//        if($this->input->post('submit', false) === false){
//            $FeedText = $this->main_m->getFeedText($id);
//            $this->data['post'] = array(
//                'feed_id' => $feed_id,
//                'cid' => $this->chanel['id'],
//                'description' => $FeedText['description'],
//                'tags' => $FeedText['tags'],
//                'credit' => $FeedText['credit'],
//                'is_publish' => $FeedText['is_publish']
//            );
//        }else{
//            $this->data['post'] = array(
//                'feed_id' => $feed_id,
//                'cid' => $this->chanel['id'],
//                'description' => $this->input->post('description'),
//                'tags' => $this->input->post('tags'),
//                'credit' => $this->input->post('credit'),
//                'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
//            );
//        }
//        $this->();
//        $this->load->view('feed_text_edit', $this->data);
    

}

