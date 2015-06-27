<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Design extends CI_Controller {

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
        $designInfo = $this->main_m->getDesignInfo();
        if($this->input->post('del_header')){
            $this->main_m->delete_header_image();
            redirect("design", '');
        }
        if($this->input->post('del_watermark')){
            $this->main_m->delete_watermark_image();
            redirect("design", '');
        }
        if($this->input->post('submit', false) === false){
            $this->data['post'] = array(
                'link_color' => isset($designInfo['link_color']) ? $designInfo['link_color'] : "",
                'button_color' => isset($designInfo['button_color']) ? $designInfo['button_color'] : "",
                'back_color' => isset($designInfo['back_color']) ? $designInfo['back_color'] : "",
                'text_color' => isset($designInfo['text_color']) ? $designInfo['text_color'] : "",
                'header_image' => isset($designInfo['header_image']) ? $designInfo['header_image'] : "",
                'watermark' => isset($designInfo['watermark']) ? $designInfo['watermark'] : ""
            );            
        }else{
             $this->data['post'] = $_POST;
             $this->data['post']['header_image'] = isset($designInfo['header_image']) ? $designInfo['header_image'] : "";
             $this->data['post']['watermark'] = isset($designInfo['watermark']) ? $designInfo['watermark'] : "";
//             $this->main_m->saveDesignInfo($this->data['post']);
        }
        $this->data['submenus'] = $this->main_m->getSubmenus($this->chanel['id']);
        $defaultSections = $this->main_m->getDefaultSections();
        $defaultSectionOptions = array();
        foreach($defaultSections as $defaultSection){
            $defaultSectionOptions[$defaultSection['id']] = $defaultSection['name'];
        }
        $this->data['defaultSectionOptions'] = $defaultSectionOptions;
        $this->_proc_add_design();
        $this->load->view('design', $this->data);
    }
    
    private function _proc_add_design(){
        
        $data = array();
        
        if ($this->input->post('submit', false) !== false)
        {
            if($path = $_FILES['image2']['name']){
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                if(strtolower($ext)!='png'){
                    $this->data['show_errors'] = "Only png file is possible for watermark.";
                    return $data;
                }
            }
            if($this->main_m->saveDesignInfo($this->data['post'])){
                redirect("design", '');
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

