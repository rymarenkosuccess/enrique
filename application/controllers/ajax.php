<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AJAX extends REST_Controller {

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
	}
    function _remap($method) {
        $this->load->view('header_v');
        $this->load->view('sidebar_v');
        $this->{$method}();
        $this->load->view('footer_v');
    }
    
    public function getMenuInfo(){
        $id = $this->input->post('submenu_id');
        $row = $this->main_m->getSubmenuInfo($id);
        $this->response($row);
    }
    
    public function addSubmenu(){
        $alter_name = $this->input->post('alter_name');
        $ordering = $this->input->post('ordering');
        $section_id = $this->input->post('section_id');
        if(!$section_id){
            $result['status'] = false;
            $this->response($result);
        }
        $is_publish = $this->input->post('is_publish');
        $where = array(
            'cid' => $this->chanel['id'],
            'section_id' => $section_id
        );
        $this->db->delete('enr_submenu', $where);
        
        $sql = array(
            'cid' => $this->chanel['id'],
            'alter_name' => $alter_name,
            'ordering' => $ordering,
            'section_id' => $section_id,
            'is_publish' => $is_publish
        );
        $res = $this->db->insert('enr_submenu', $sql);
        $result['status'] = $res;
        $this->main_m->_reOrderingSubmenu($this->db->insert_id(), $ordering);
        $this->response($result);
    }
    
    public function editSubmenu(){
        $id = $this->input->post('submenu_id');
        $alter_name = $this->input->post('alter_name');
        $ordering = $this->input->post('ordering');
        $is_publish = $this->input->post('is_publish');
        
        $sql = array(
            'cid' => $this->chanel['id'],
            'alter_name' => $alter_name,
            'ordering' => $ordering,
            'is_publish' => $is_publish
        );
        $res = $this->db->update('enr_submenu', $sql, array('id'=>$id));
        $result['status'] = $res;
        $this->main_m->_reOrderingSubmenu($id, $ordering);
        $this->response($result);
    }
    
    public function deleteSubmenu(){
        $id = $this->input->post('submenu_id');
        $this->db->where('cid', $this->chanel['id']);
        $this->db->where('id', $id);
        $res = $this->db->delete('enr_submenu');
        $result['status'] = $res;
        $this->response($result);
    }
    
    public function setSocialValue(){
        $cid = $this->chanel['id'];
        $name = $_POST['name'];
        $value = $_POST['value'];

        $sql = "
            select *
            from  enr_social
            where 
                cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        foreach($row as $key=>&$val){
            if($name == $key){
                $val = $value;
            }
        }
        $this->db->where('cid', $cid);
        $this->db->delete('enr_social');
        
        $this->db->insert('enr_social', $row);
        exit;
    }
    
    public function getStatsPost(){
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        if(!$start_time)
            $start_time = 1000;
        if(!$end_time){
            $end_time = time();
        }
        $cid = $this->chanel['id'];
        
        $statsPost = $this->main_m->getStatsPost($start_time, $end_time, $cid);
        echo json_encode($statsPost);
        exit;
    }
    
    public function getStatsGender(){
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        if(!$start_time)
            $start_time = 1000;
        if(!$end_time){
            $end_time = time();
        }
        $cid = $this->chanel['id'];
        
        $statsPost = $this->main_m->getStatsGender($start_time, $end_time, $cid);
        echo json_encode($statsPost);
        exit;
    }
    
    public function getStatsAge(){
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        if(!$start_time)
            $start_time = 1000;
        if(!$end_time){
            $end_time = time();
        }
        $cid = $this->chanel['id'];
        
        $statsPost = $this->main_m->getStatsAge($start_time, $end_time, $cid);
        echo json_encode($statsPost);
        exit;
    }
    
    public function getStatsPurchase(){
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        if(!$start_time)
            $start_time = 1000;
        if(!$end_time){
            $end_time = time();
        }
        $cid = $this->chanel['id'];
        
        $statsPost = $this->main_m->getStatsPurchase($start_time, $end_time, $cid);
        echo json_encode($statsPost);
        exit;
    }
    
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */