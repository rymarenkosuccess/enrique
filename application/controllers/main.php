<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

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
		
	//	$this->load->view('main_v');
		redirect('main/company_list', 'refresh');
	}
	
	public function company_list()
	{
		$data['comps'] = $this->main_m->get_company_list();
		$this->load->view('company_v', $data);
	}
 	
	public function company_create()
	{
		$this->data = $this->_proc_comp_add();
		
		$this->data['comp_name'] = array(
				'name'  => 'comp_name',
				'id'    => 'comp_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('comp_name'),
			);
			
		$this->data['comp_detail'] = array(
				'name'  => 'comp_detail',
				'id'    => 'comp_detail',
				'type'  => 'textarea',
				'value' => $this->form_validation->set_value('comp_detail'),
			);
			
		$this->load->view('company_add_v', $this->data);
	}
	
	public function company_edit()
	{
		$comp_id = $this->uri->segment(3, 0);
		if (empty($comp_id)) {
			show_error("select id !");
			return;
		}
		
		$this->data = $this->_proc_comp_edit($comp_id);
		$this->data['comp'] = $this->main_m->get_company_info($comp_id);
		
		$this->data['comp_name'] = array(
				'name'  => 'comp_name',
				'id'    => 'comp_name',
				'type'  => 'text',
				'value' => $this->data['comp']['name'],
			);
			
		$this->data['comp_detail'] = array(
				'name'  => 'comp_detail',
				'id'    => 'comp_detail',
				'type'  => 'textarea',
				'value' => $this->data['comp']['detail'],
			);
			
		$this->load->view('company_edit_v', $this->data);
	}

	public function company_del()
	{
		$comp_id = $this->uri->segment(3, 0);
		if (empty($comp_id)) {
			show_error("select id !");
			return;
		}
		$strSql = "DELETE FROM tbl_company WHERE idx='$comp_id' ";
		$this->db->query($strSql);
		
		$this->main_m->delTree($this->main_m->get_upload_path(0,"company",$comp_id));
		
		redirect("main/company_list", 'refresh');
	}
	
	private function &_proc_comp_add() {
		
		$this->load->library('upload');
		
		//validate form input
		$this->form_validation->set_rules('comp_name', "Copmpany Name", 'required|xss_clean');
		$this->form_validation->set_rules('comp_detail', "Copmpany Description", 'xss_clean');
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run() == true)
		{
			$new_idx = $this->main_m->get_next_insert_idx("tbl_company");

			if (isset($_FILES['comp_img']) && $_FILES['comp_img']['name'] != '') {
				$new_fname = str_replace(' ', '_', strtolower($_FILES['comp_img']['name']));
				
				$conf = array();
				$conf['upload_path'] 	= $this->main_m->get_upload_path(0,"company",$new_idx);
				$conf['allowed_types']	= $this->config->item('upload_imgtype');
				$conf['max_size']		= $this->config->item('upload_imgsize');
				$conf['overwrite']		= TRUE;
				$conf['remove_spaces']	= TRUE;
				$conf['file_name']		= $new_fname;
				
				if (!file_exists($conf['upload_path'])) {
					mkdir($conf['upload_path']);
				}
				$this->upload->initialize($conf);
				if ($this->upload->do_upload('comp_img')) {
					$fileinfo = $this->upload->data();
					if ($fileinfo['file_size'] > 0) {
						$qry['logo'] = $new_fname;
					//	unlink($fileinfo['full_path']);
					}
				} else {
					show_error("Error !");
					$data['show_errors']['comp_img'] = $this->upload->display_errors();
				}
				if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
					$qry = array_merge(	
						$qry,
						array(
							'idx'				=> $new_idx,
							'name'			=> $this->input->post('comp_name'),
							'detail'			=> $this->input->post('comp_detail',FALSE),
							'create_time'	=> date('Y-m-d H:i:s'),
						//	'banned'			=> $this->input->post('fband'),
						)
					);
					
					if($this->db->insert('tbl_company', $qry)){
						redirect("main/company_list", 'refresh');
					}
				}
				
			}
			
		}
		return $data;
	}
	
	
	private function &_proc_comp_edit($comp_id) {
		
		$this->load->library('upload');
		
		//validate form input
		$this->form_validation->set_rules('comp_name', "Copmpany Name", 'required|xss_clean');
		$this->form_validation->set_rules('comp_detail', "Copmpany Description", 'xss_clean');
		
		$qry = array();
		$data = array();
		
		if ($this->form_validation->run() == true)
		{

			if (isset($_FILES['comp_img']) && $_FILES['comp_img']['name'] != '') {
				$new_fname = str_replace(' ', '_', strtolower($_FILES['comp_img']['name']));
				
				$conf = array();
				$conf['upload_path'] 	= $this->main_m->get_upload_path(0,"company",$comp_id);
				$conf['allowed_types']	= $this->config->item('upload_imgtype');
				$conf['max_size']		= $this->config->item('upload_imgsize');
				$conf['overwrite']		= TRUE;
				$conf['remove_spaces']	= TRUE;
				$conf['file_name']		= $new_fname;
				
				if (!file_exists($conf['upload_path'])) {
					mkdir($conf['upload_path']);
				}
				$this->upload->initialize($conf);
				if ($this->upload->do_upload('comp_img')) {
					$fileinfo = $this->upload->data();
					if ($fileinfo['file_size'] > 0) {
						$qry['logo'] = $new_fname;
					//	unlink($fileinfo['full_path']);
					}
				} else {
					show_error("Error !");
					$data['show_errors']['comp_img'] = $this->upload->display_errors();
				}
			}
			if ( empty($data['show_errors']) || count($data['show_errors'])==0 ) {
					$qry = array_merge(	
						$qry,
						array(
						//	'idx'				=> $comp_id,
							'name'			=> $this->input->post('comp_name'),
							'detail'			=> $this->input->post('comp_detail',FALSE),
							'create_time'	=> date('Y-m-d H:i:s'),
						//	'banned'			=> $this->input->post('fband'),
						)
					);
					
					$this->db->where('idx', $comp_id);
					$this->db->update('tbl_company', $qry);
					
				//	$data['show_message'] = "Successfully updated!";
					redirect("main/company_list", 'refresh');
			}			
		}
		return $data;
	}
	
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */