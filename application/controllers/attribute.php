<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attribute extends CI_Controller {

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
        redirect('attribute/category');
    }
    
    /**
    * Category
    * 
    */
    public function category(){
        $category = $this->uri->segment(3,'list');
        $this->data['categories'] = $this->main_m->getCategoryList();
        if($category && method_exists($this, "category_{$category}"))
            call_user_func_array(array($this, "category_{$category}"), array());
        else
            $this->load->view('category_list', $this->data);
    }
    public function category_add(){  
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'name' => $this->input->post('name')
        );
        $this->_proc_category_add();
        $this->load->view('category_add', $this->data);
    }
    public function _proc_category_add(){
        $this->form_validation->set_rules('name', 'category name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addCategory($this->data['post'])){
                redirect("attribute/category", '');
            }
        }
        return $data;
    }
    public function category_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a category to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $post = $this->main_m->getCategoryList($id);
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'name' => $this->input->post('name')
            );
        } 
        $this->_proc_category_edit();
        $this->load->view('category_edit', $this->data);
    }
    public function _proc_category_edit(){
        $this->form_validation->set_rules('name', 'category name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateCategory($this->data['post'])){
                redirect("attribute/category", '');
            }
        }
        return $data;
    }
    public function category_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_category");
        
        redirect('attribute/category', '');
    }
    
    /**
    * Color
    */
    public function color(){
        $color = $this->uri->segment(3,'list');
        $this->data['colors'] = $this->main_m->getColorList();
        if($color && method_exists($this, "color_{$color}"))
            call_user_func_array(array($this, "color_{$color}"), array());
        else
            $this->load->view('color_list', $this->data);
    }
    public function color_add(){  
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'name' => $this->input->post('name')
        );
        $this->_proc_color_add();
        $this->load->view('color_add', $this->data);
    }
    public function _proc_color_add(){
        $this->form_validation->set_rules('name', 'color name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addColor($this->data['post'])){
                redirect("attribute/color", '');
            }
        }
        return $data;
    }
    public function color_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a color to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $post = $this->main_m->getColorList($id);
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'name' => $this->input->post('name')
            );
        } 
        $this->_proc_color_edit();
        $this->load->view('color_edit', $this->data);
    }
    public function _proc_color_edit(){
        $this->form_validation->set_rules('name', 'color name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateColor($this->data['post'])){
                redirect("attribute/color", '');
            }
        }
        return $data;
    }
    public function color_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_color");
        
        redirect('attribute/color', '');
    }
    
    /**
    * Size
    */
    public function size(){
        $size = $this->uri->segment(3,'list');
        $this->data['sizes'] = $this->main_m->getSizeList();
        if($size && method_exists($this, "size_{$size}"))
            call_user_func_array(array($this, "size_{$size}"), array());
        else
            $this->load->view('size_list', $this->data);
    }
    public function size_add(){  
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'name' => $this->input->post('name')
        );
        $this->_proc_size_add();
        $this->load->view('size_add', $this->data);
    }
    public function _proc_size_add(){
        $this->form_validation->set_rules('name', 'size name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addSize($this->data['post'])){
                redirect("attribute/size", '');
            }
        }
        return $data;
    }
    public function size_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a size to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $post = $this->main_m->getSizeList($id);
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'name' => $this->input->post('name')
            );
        } 
        $this->_proc_size_edit();
        $this->load->view('size_edit', $this->data);
    }
    public function _proc_size_edit(){
        $this->form_validation->set_rules('name', 'size name', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateSize($this->data['post'])){
                redirect("attribute/size", '');
            }
        }
        return $data;
    }
    public function size_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_size");
        
        redirect('attribute/size', '');
    }
    

}

