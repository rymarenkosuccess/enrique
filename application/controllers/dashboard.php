<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
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
        $user = $this->ion_auth->user($this->session->userdata('user_id'))->row();
        $this->session->set_userdata('email', $user->email);
        
    }
    
    function _remap($method) {

            $this->load->view('header_v');
            $this->load->view('sidebar_v');
            $this->{$method}();
            $this->load->view('footer_v');

    }
    
    public function index()
    {
        $data['searchvalue'] = $this->input->post('searchvalue');
        
        $chanels = $this->main_m->getChanels($data['searchvalue']);
        $user_id = $this->session->userdata('user_id');
        $user = $this->ion_auth_model->user($user_id)->row();
        $results = array();
        
        if(!$user->superadmin){
            foreach($chanels as $chanel){
                if($chanel->user_id == $user->id){
                    $results[] = $chanel;
                }
            }
        }else{
            $results = $chanels;
        }
        
        $data['chanels'] = $results;
        $data['user'] = $user;
        $this->load->view('dashboard_view', $data);
    }
    
    public function content(){
        $id = $this->uri->segment(3, 0);
        if (empty($id)) {
            show_error("Select a channel!");
            return;
        }
        $user_id = $this->session->userdata('user_id');
        $user = $this->ion_auth_model->user($user_id)->row();
        if(!$user->superadmin && $user->cid!=$id){
            show_error("You have not permission for this channel!");
            return;
        }
        
        $chanel = $this->main_m->get_chanel($id);
        $this->session->set_userdata('chanel', $chanel);
        redirect("content/feed", '');
    }
    
    public function chanel_add(){
        $qry = 
            array(
                'id' => '0',
                'name' => $this->db->escape_str($this->input->post('chanel_name')),
                'url' => $this->db->escape_str($this->input->post('chanel_url')),
                'chanel_admin' => $this->db->escape_str($this->input->post('chanel_admin')),
                'password' => $this->db->escape_str($this->input->post('chanel_password')),
                'confirm_password' => $this->db->escape_str($this->input->post('chanel_confirm_password')),
                'is_publish' => $this->db->escape_str($this->input->post('is_publish')=='on' ? 1 : 0)
            );
            
        $this->data = array();
        $this->data = $this->_proc_add($qry);
        $this->data['chanel'] = $qry;
            
        $this->load->view('chanel_add', $this->data);
    }
    private function _proc_add($qry=array()) {
        //validate form input
        $this->form_validation->set_rules('chanel_name', "Channel Name", 'required|xss_clean');
        $this->form_validation->set_rules('chanel_password', "channel password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[chanel_confirm_password]');
//        $this->form_validation->set_rules('chanel_admin', "ss",'required|xss_clean');
//        $this->form_validation->set_rules('chanel_password', "ss",'required|xss_clean');
//        $this->form_validation->set_rules('chanel_confirm_password', "ss",'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if(!$this->email->valid_email($this->input->post('chanel_admin'))){
                $data['show_errors'] = "The Email Address field must contain a valid email address.";
            }elseif($qry['password'] != $qry['confirm_password']){
                $data['show_errors'][] = 'The password does not match.';
            }elseif($this->main_m->isExistChanel($qry)){
                $data['show_errors'][] = 'This channel name already exists.';
            }else{
                if($this->main_m->addChanel($qry)){
                    redirect("dashboard", '');
                }
            }
        }
        return $data;
    }
    public function chanel_edit(){
        $id = $this->uri->segment(3, 0);
        if (empty($id)) {
            show_error("Select a channel to edit!");
            return;
        }
        if($this->input->post('delimg')){
            $this->db->where('id', $id);
            $this->db->update('enr_chanel', array('image_path'=>'', 'image_mime'=>''));
            redirect("dashboard/chanel_edit/".$id, '');
        }
        if(isset($_POST['chanel_name'])){
            $qry = 
                array(
                    'id' => $id,
                    'name' => $this->db->escape_str($this->input->post('chanel_name')),
                    'url' => $this->db->escape_str($this->input->post('chanel_url')),
                    'chanel_admin' => $this->db->escape_str($this->input->post('chanel_admin')),
                    'password' => $this->db->escape_str($this->input->post('chanel_password')),
                    'confirm_password' => $this->db->escape_str($this->input->post('chanel_confirm_password')),
                    'is_publish' => $this->db->escape_str($this->input->post('is_publish')=='on' ? 1 : 0)
                );
        }else{
            $qry = $this->main_m->get_chanel($id);
        }
        $this->data = $this->_proc_edit($id, $qry);
        $this->data['chanel'] = $qry;
            
        $this->load->view('chanel_edit', $this->data);
    }
    private function _proc_edit($id, $qry=array()) {
        //validate form input
        $this->form_validation->set_rules('chanel_name', "Channel Name", 'required|xss_clean');
        $this->form_validation->set_rules('chanel_password', "channel password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[chanel_confirm_password]');

//        $this->form_validation->set_rules('chanel_admin', "ss",'required|xss_clean');
//        $this->form_validation->set_rules('chanel_password', "ss",'required|xss_clean');
//        $this->form_validation->set_rules('chanel_confirm_password', "ss",'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
//            echo $this->input->post('chanel_admin');exit;
            if(!$this->email->valid_email($this->input->post('chanel_admin'))){
                $data['show_errors'] = "The Email Address field must contain a valid email address.";
            }elseif($qry['password'] != $qry['confirm_password']){
                $data['show_errors'][] = 'The password does not match.';
            }elseif($this->main_m->isExistChanel($qry)){
                $data['show_errors'][] = 'This channel name already exists.';
            }else{
                if($this->main_m->updateChanel($qry)){
                    redirect("dashboard", '');
                }
            }
        }
        return $data;
    }
    public function chanel_del(){
        $id = $this->uri->segment(3, 0);
        if (empty($id)) {
            show_error("Select a channel to delete!");
            return;
        }
        $chanel = $this->main_m->get_chanel($id);
        $destination = UPLOAD_DIR.$chanel['image_path'];
        if(is_file($destination)){
            unlink($destination);
        }
        
        $strSql = "DELETE FROM enr_chanel WHERE id='{$id}' ";
        $this->db->query($strSql);
        $strSql = "DELETE FROM users WHERE id='{$chanel['user_id']}' ";
        $this->db->query($strSql);
        $strSql = "DELETE FROM users_groups WHERE user_id='{$chanel['user_id']}' ";
        $this->db->query($strSql);
        redirect("dashboard", '');
    }
    
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */