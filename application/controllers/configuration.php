<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');
        $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        $this->email->initialize($config);
        		
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
		
	}
	
	function _remap($method) {
			$this->load->view('header_v');
			$this->load->view('sidebar_v');
			$this->{$method}();
			$this->load->view('footer_v');

	}
	
	public function index()
	{
        $this->data['configs'] = $this->main_m->getConfigurations();
        
        $this->load->view('configuration', $this->data);
	}
    
    public function updateConfiguration(){
        $vals = $this->input->post('vals');
        foreach($vals as $key => $config){
            $this->db->where("name", $key);
            $this->db->update("msp_configuration", array("value"=>$config));
        }
        redirect("configuration");
    }
    
    public function email(){
        $this->data = array();
        $this->data['post'] = array(
            'message' => $this->input->post('message'),
            'subject' => $this->input->post('subject'),
            'state' => $this->input->post('state'),
            'city' => $this->input->post('city'),
            'gender' => $this->input->post('gender'),
            'position' => $this->input->post('position'),
            'email_date' => $this->input->post('email_date'),
            'is_soon' => $this->input->post('is_soon')
        );
        $this->data['stateOptions'] = $this->main_m->getStateOptions();
        $this->data['cityOptions'] = $this->main_m->getCityOptions();
        $this->data['positionOptions'] = $this->main_m->getPositionOptions();
        $this->_proc_add_email();

        $this->load->view("autoemail", $this->data);
    }
    private function _proc_add_email(){
        $this->form_validation->set_rules('message', 'required|xss_clean');
        $data = array();
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addAutoemail($this->data['post'])){
                redirect("configuration/email_list", '');
            }
        }
        return $data;
    }
    
    public function email_list(){
        $this->data = array();
        $this->data['events'] = $this->main_m->getEvents();
        $this->load->view("email_list", $this->data);
    }
    public function email_edit(){
        $id = $this->uri->segment(3);
        $this->data = array();
        
        if($this->input->post('submit')){
            $this->data['post'] = array(
                'id' => $id,
                'message' => $this->input->post('message'),
                'subject' => $this->input->post('subject'),
                'state' => $this->input->post('state'),
                'city' => $this->input->post('city'),
                'gender' => $this->input->post('gender'),
                'position' => $this->input->post('position'),
                'email_date' => $this->input->post('email_date'),
                'is_soon' => $this->input->post('is_soon')
            );
        }else{
            $this->data['post'] = $this->main_m->getAutoemail($id);
        }
        $this->data['stateOptions'] = $this->main_m->getStateOptions();
        $this->data['cityOptions'] = $this->main_m->getCityOptions();
        $this->data['positionOptions'] = $this->main_m->getPositionOptions();
        $this->_proc_edit_email();

        $this->load->view("autoemail_edit", $this->data);
    }
    public function email_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete('msp_autoemail');
        redirect('configuration/email_list');
    }
    
    private function _proc_edit_email(){
        $this->form_validation->set_rules('message', 'required|xss_clean');
        $data = array();
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->editAutoemail($this->data['post'])){
                redirect("configuration/email_list", '');
            }
        }
        return $data;
    }
    
    
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */