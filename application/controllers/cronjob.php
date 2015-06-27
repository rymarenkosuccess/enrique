<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        $this->email->initialize($config);
        
        $this->load->model('main_m');
    }
    
    public function index(){
        if(!defined('CRONJOB'))
        {
            echo "greet my only be accessed from the command line";
            return;
        }

        $evenList = $this->main_m->getPossibleAutoemailList();
        foreach($evenList as $event){
            $this->main_m->sendEmail($event);
        }
            
    }    
}
?>