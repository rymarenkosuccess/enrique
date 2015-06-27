<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fan extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', '');
            return;
        }
        $this->load->helper('language');
        $this->load->model('main_m');
        $this->lang->load('auth');
        
        $this->form_validation->set_error_delimiters(
            $this->config->item('error_start_delimiter'), 
            $this->config->item('error_end_delimiter')
        );
        $this->chanel = $this->session->userdata('chanel');
        $this->data['chanel'] = $this->chanel;
        $this->data['community_feeds'] = $this->main_m->getCommunityFeedList();
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
        redirect('fan/active');
    }
    
    public function active()
    {
        $fans = $this->main_m->getFans(array('is_block'=>0, 'is_suspend'=>0));
//        $fans = array();
//        foreach($users as &$user){
//            if($this->ion_auth->is_admin($user['id'])){
//                continue;
//            }
//            $user['last_login'] = date('m/d/Y', $user['last_login']);
//            $user['join_date'] = date('m/d/Y', $user['created_on']);
//            $fans[] = $user;
//        }
        $data['users'] = $fans;
        $this->load->view('fans_active', $data);
        
    } 
    
    public function block()
    {
        $data['users'] = $this->main_m->getDeactiveFans();
        $this->load->view('fans_block', $data);
        
    } 
    
    function suspend($id=null, $code=false){
        if(empty($id)) {
            $id    = $this->uri->segment(3);
        }
        $this->db->where("id", $id);
        $this->db->update("enr_fan", array('is_suspend'=>1));
        if($this->isActiveFan($id)){
            redirect("fan/active", '');
        }else{
            redirect("fan/block", '');
        }
    }
    
    function isActiveFan($id){
        $res = $this->main_m->checkFanUser($id);
        return $res;
    }

    function unsuspend($id=null, $code=false){
        if(empty($id)) {
            $id    = $this->uri->segment(3);
        }
        $this->db->where("id", $id);
        $this->db->update("enr_fan", array('is_suspend'=>0));
        if($this->isActiveFan($id)){
            redirect("fan/active", '');
        }else{
            redirect("fan/block", '');
        }
    }

    //activate the user
    function activate($id=null, $code=false)
    {
        if(empty($id)) {
            $id    = $this->uri->segment(3);
        }
        $this->db->where("id", $id);
        $this->db->update("enr_fan", array('is_block'=>0));
        if($this->isActiveFan($id)){
            redirect("fan/active", '');
        }else{
            redirect("fan/block", '');
        }

    }
    
    function deactivate($id = NULL)
    {

        if(empty($id)) {
            $id    = $this->uri->segment(3);
        }
        $this->db->where("id", $id);
        $this->db->update("enr_fan", array('is_block'=>1));
        if($this->isActiveFan($id)){
            redirect("fan/active", '');
        }else{
            redirect("fan/block", '');
        }
    }

    //edit a user
    function edit_user($id=NULL)
    {
        if(empty($id)) {
            $id    = $this->uri->segment(3);
        }
        //echo "id=".$id;
        
        $this->data['title'] = "Edit User";

//        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
//        {
//            redirect('auth', '');
//        }
        
        $user = $this->ion_auth->user($id)->row();
        $groups=$this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required|xss_clean');
//        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
        $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST))
        {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
            {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
//                'username' => $this->input->post('username'),
                'first_name'  => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'phone'      => $this->input->post('phone'),
            );
            $data['email'] = $this->input->post('email');
            $data['company'] = $this->input->post('company');

            //Update the groups user belongs to
            $groupData = $this->input->post('groups');

            if (isset($groupData) && !empty($groupData)) {

                $this->ion_auth->remove_from_group('', $id);

                foreach ($groupData as $grp) {
                    $this->ion_auth->add_to_group($grp, $id);
                }

            }

            //update the password if it was posted
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->session->set_flashdata('message', "User Saved");
                if($this->isActiveFan($id)){
                    redirect("fan/active", '');
                }else{
                    redirect("fan/block", '');
                }
            }
        }

        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['currentGroups'] = $currentGroups;
        
        //var_dump($user);

        $this->data['username'] = array(
            'name'  => 'username',
            'id'    => 'username',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('username', $user->username),
        );
        $this->data['first_name'] = array(
            'name'  => 'first_name',
            'id'    => 'first_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name'  => 'last_name',
            'id'    => 'last_name',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email', $user->email),
            );
        
        $this->data['phone'] = array(
            'name'  => 'phone',
            'id'    => 'phone',
            'type'  => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        
        $this->data['password'] = array(
            'name' => 'password',
            'id'   => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id'   => 'password_confirm',
            'type' => 'password'
        );

        $this->_render_page('fan/edit_user', $this->data);
    }
    
    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }
    function _valid_csrf_nonce()
    {
        /*
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        */
        return true;    // ADDED BY PGR
    }
    function _render_page($view, $data=null, $render=false)
    {

        $this->viewdata = (empty($data)) ? $this->data: $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render) return $view_html;
    }
    function delete_user(){
        $id    = $this->uri->segment(3);
        if(empty($id)) {
            show_error("Select a fan to delete!");
        }
        if($this->isActiveFan($id)){
            $is_active = true;
        }else{
            $is_active = false;
        }
        $this->db->where('id', $id);
        $this->db->delete('enr_fan');
        if($is_active){
            redirect('fan/active');
        }else{
            redirect('fan/block');
        }
    }
    function view(){
        $id    = $this->uri->segment(3);
        if(empty($id)) {
            show_error("Select a fan to preview!");
        }
        $users = $this->main_m->getUsers(array('id'=>$id));
        $users[0]['last_login'] = date('m/d/Y', $users[0]['last_login']);
        $users[0]['join_date'] = date('m/d/Y', $users[0]['created_on']);
        $this->data['user'] = $users[0];
        $feeds = array();
        foreach($this->data['community_feeds'] as &$feed){
            if($feed['fan_id'] == $id){
                $feeds[] = $feed;
            }
        }
        $this->data['community_feeds'] = $feeds;
        if($this->isActiveFan($id)){
            $this->data['is_active'] = true;
        }else{
            $this->data['is_active'] = false;
        }
        $this->_render_page('fan/view', $this->data);
    }

}

