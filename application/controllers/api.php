<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends REST_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('email');
        $config = array (
                  'mailtype' => 'html',
                  'charset'  => 'utf-8',
                  'priority' => '1'
                   );
        date_default_timezone_set('UTC');
        $this->email->initialize($config);

        $this->_getOptions();
        $this->result = array('status'=>true);
//        echo $token;exit;
        if(!($this->_user = $this->_checkToken())){
            $this->result['status'] = false;
            $this->result['message'] = 'token_error';
            $this->response($this->result);
        }
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('language');
        $this->load->model('main_m');
		
        $email_config = $this->config->item('email_config', 'ion_auth');

//        if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config))
//        {
            $this->email->initialize($email_config);
//        }

		$this->form_validation->set_error_delimiters(
			$this->config->item('error_start_delimiter'), 
			$this->config->item('error_end_delimiter')
		);
        $this->router_func();
        exit;
	}
    private function _request($name){
        $value = $this->input->post($name, false);
        if($value === false){
            $value = $this->input->get($name);
        }
        return $value;
    }
    private function _getOptions(){

        $this->_option = $this->db->escape_str($this->_request('option'));
        $this->_token = $this->db->escape_str($this->_request('token'));
        $this->_name = $this->_request('name');
        $this->_password = $this->_request('password');
        
        //register
        $this->_username = $this->_request('username');
        $this->_email = $this->_request('email');
        $this->_gender = $this->_request('gender');
        $this->_phone = $this->_request('phone');
        $this->_country = $this->_request('country');
        $this->_state = $this->_request('state');
        $this->_city = $this->_request('city');
        $this->_position = $this->_request('position');
        $this->_accesscode = $this->_request('accesscode');
        $this->_accessflag = $this->_request('accessflag');
        $this->_description = $this->_request('description');

        $this->_user_id = $this->_request('user_id');
        
        //search user
        $this->_username = $this->_request('username');

        //verification code
        $this->_confirm_verification = $this->_request('confirm_verification');
        
        //Add Mail content
        $this->_sender = $this->_request('sender');
        $this->_receiver = $this->_request('receiver');
        $this->_message = $this->_request('message');
        $this->_mail_id = $this->_request('mail_id');
        $this->_time_zone = $this->_request('time_zone');
        $this->_current_time = $this->_request('current_time');
        
        
    }
    
    public function response_register(){
        $result = array();
        if(!$this->_password ){
            $this->result['message'] = "The password is required.";
            $this->result['status'] = false;
            return false;
        }
        if(!$this->_username ){
            $this->result['message'] = "The username is required.";
            $this->result['status'] = false;
            return false;
        }
        if(!$this->_email ){
            $this->result['message'] = "The email is required.";
            $this->result['status'] = false;
            return false;
        }
        $additional_data = array(
            'gender' => $this->_gender,
            'phone' => $this->_phone,
            'country' => $this->_country,
            'state' => $this->_state,
            'city' => $this->_city,
            'first_name' => $this->_username,
            'position' => $this->_position
        );
        if($id = $this->ion_auth->register($this->_username, $this->_password, $this->_email, $additional_data)){
            $this->result['status'] = true;
            $this->result['user_id'] = $id;
            $verification_code = $this->_createToken();
            $sql = array("user_id"=>$id, "verification_code"=>$verification_code);
            $this->db->insert('msp_confirm', $sql);
            $this->_sendEmail($id, $verification_code);
        }else{
            $this->result['status'] = false;
            $this->result['error_code'] = $this->session->userdata('error_code');
            $this->result['message'] =  $this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message');
            $this->result['message'] =  str_replace(array("Error!", "Unable to Create Account"), "", strip_tags($this->result['message'])) ;
        }
        $this->response($this->result);
    }
    
    public function response_photoupdate(){
        $result = $this->main_m->updateProfilePhoto($this->_user_id);
        $this->result['status'] = $result;
        $this->response($this->result);       
    }
    
    private function _sendEmail($id, $verification_code=''){
        $user = $this->main_m->getUser($id);
        $verification_url = $this->_makeActivationUrl($verification_code);
        $message = $this->main_m->getConfiguration('email_verification_message');
        $message .= "\n".$verification_url;

        $this->email->clear();
        $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $this->email->to($user->email);
        $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
        $this->email->message($message);
        
        if ($this->email->send() == TRUE){
            
        }
    }
    
    private function _makeActivationUrl($verification_code=''){
        $url = site_url('confirm/confirm_verification');
        $url .= "/".$verification_code;
        return $url;
    }
    
    private function _createToken(){
        $len = rand(1,1000);
        $token = md5(time().$len);
        $query = $this->db->query("select * from msp_token where token='{$token}'");
        $row = $query->result();
        if($row){
            $token = $this->_createToken();
        }
        return $token;
    }
    private function _insertToken($user_id, $token){
        $sql = array('user_id'=>$user_id, 'token'=>$token);
        $this->db->where('user_id', $user_id);
        
        if($this->db->update('msp_token', $sql) && !$this->db->affected_rows()){
            $this->db->insert('msp_token', $sql);
        }
    }
    
    private function _checkToken(){
//        if($this->_option == "login" || $this->_option == "register" ){
        if($this->_option == "login" || $this->_option == "register" || $this->_option == "photoupdate" || $this->_option == "forgotpassword" || $this->_option == "getCountries" || $this->_option == "getStates" || $this->_option == "getCities"){
            return true;
        }
        if(!$this->_token)
            return false;
        $query = $this->db->query("
            select t1.token, t2.* 
            from msp_token t1 join users t2 on t1.user_id=t2.id
            where 
                t1.token='{$this->_token}'
        ");
        $user = $query->row_object();
        if(is_object($user)){
            return $user;
        }else{
            return false;
        }
    }
    
    public function router_func()
    {
        $option = $this->_option;
        call_user_func_array(array($this, "response_{$option}"), array());
        $this->response($this->result);
    }
    
    public function getUserImageUrl($img_path){
        $img_url = "";
        if(is_file(UPLOAD_DIR.$img_path)){
            $img_url = UPLOAD_URL.$img_path;
        }else{
            $img_url = "";
        }
        return $img_url;
    }

    public function response_login(){
        $this->load->library('ion_auth');
        $result = array();
        if($this->ion_auth->login($this->_name, $this->_password)){
            $user_id = $this->ion_auth->get_user_id();
            $user = $this->main_m->getUser($user_id);
            if(!$this->ion_auth->is_admin()){
                $this->ion_auth->logout();
            }
            if(!$user->verification){
                $this->result['status'] = false;
                $this->result['message'] = "You need to verificate using the code you received.";
                $this->response($this->result);
            }
            $token = $this->_createToken();
            $this->result['token'] = $token;
            $user->img_url = $this->getUserImageUrl($user->img_url);
            $this->result['user'] = $user;
            $this->_insertToken($user_id, $token);
        }else{
            $this->result['message'] = strip_tags($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'));
            $this->result['status'] = false;
        }
        $this->response($this->result);
    }
    
    public function response_search_users(){
        if(!$this->_user_id){
            $this->result['status'] = false;
            $this->result['error_code'] = 1;
            $this->result['message'] = "The user id is required.";
            $this->response($this->result);
        }
        $users = $this->main_m->getUsersByUsername($this->_username, $this->_user_id);
        $users || $users=array();
        $this->result['status'] = true;
        foreach($users as &$user){
            $user['img_url'] = $this->getUserImageUrl($user['img_url']);
        }
        $this->result['users'] = $users;
        $this->response($this->result);
    }
    
    public function response_update_user(){
        $this->result['status'] = true;
        $data = array(
            'email' => $this->_email,
            'username' => $this->_username,
            'first_name' => $this->_username,
            'position' => $this->_position,
            'phone' => $this->_phone,
            'country' => $this->_country,
            'city' => $this->_city,
            'state' => $this->_state,
            'gender' => $this->_gender,
            'description' => $this->_description,
            'accesscode' => $this->_accesscode,
            'accessflag' => $this->_accessflag
            
        );
        if($this->_password ){
            $data['password'] = $this->_password;
        }
        if(!$this->_user_id ){
            $this->result['message'] = "The user ID is required.";
            $this->result['error_code'] = 3;
            $this->result['status'] = false;
            return false;
        }
        if(!$this->_username ){
            $this->result['message'] = "The username is required.";
            $this->result['error_code'] = 4;
            $this->result['status'] = false;
            return false;
        }
        if(!$this->_email ){
            $this->result['message'] = "The email is required.";
            $this->result['error_code'] = 5;
            $this->result['status'] = false;
            return false;
        }
        $user = $this->main_m->getUser($this->_user_id);
        if(!$user){
            $this->result['message'] = "Wrong user ID.";
            $this->result['error_code'] = 6;
            $this->result['status'] = false;
            return false;
        }
        $res = $this->ion_auth->update($this->_user_id, $data);
        if(!$res){
            $this->result['message'] = "Other error.";
            $this->result['error_code'] = 7;
            $this->result['status'] = false;
            return false;
        }
        $user->img_url = $this->getUserImageUrl($user->img_url);
        $user = $this->main_m->getUser($this->_user_id);
        $this->result['user'] = $user;
        $this->response($this->result);
    }
    
    public function response_add_mail(){
//        $this->_current_time;
        $data['sender_id'] = $this->_sender;
        $sender = $this->main_m->getUser($this->_sender);
        if(!$sender){
            $this->result['status'] = false;
            $this->result['message'] = "Incorrect Sender";
            $this->result['error_code'] = "3";
            return;
        }
        $data['receiver_id'] = $this->_receiver;
        $receiver = $this->main_m->getUser($this->_receiver);
        if(!$receiver){
            $this->result['status'] = false;
            $this->result['message'] = "Incorrect Receiver";
            $this->result['error_code'] = "4";
            return;
        }
        if($blockFlag = $this->main_m->is_blocked_user($this->_sender, $this->_receiver)){
            $this->result['status'] = false;
            if($blockFlag == '1'){
                $this->result['message'] = "You blocked him.So you can't send a message to him.";
                $this->result['error_code'] = "5";
            }else{
                $this->result['message'] = "He blocked you.So you can't send a message to him.";
                $this->result['error_code'] = "6";
            }
            $this->response($this->result);
            return;
        }
        $data['message'] = $this->_message;
        $mail_id = $this->main_m->insertMail($data, $this->_current_time);
        
//        if ($this->email->send() == TRUE){
            $frommail = "noreply@straight-drop.com";
            $message = "You have a drop or drop request waiting for you. Please open the Straight Drop™ app on your device and check your inbox";
            $this->email->clear();
            $this->email->from($frommail, $this->config->item('site_title', 'ion_auth'));
            $this->email->to($receiver->email);
            $this->email->subject("Straght Drop" );
            $this->email->message($message);
            $this->email->send();
//        }

        if($mail_id)
            $this->result['status'] = true;
        else
            $this->result['status'] = false;;
        $this->response($this->result);
    }
    
    public function response_get_mail(){
//        echo date('Y-m-d H:i:s', 1385360355);exit;
        if(!$this->_user_id){
            $this->result['status'] = false;
            $this->result['error_code'] = 1;
            $this->result['message'] = 'User ID is required';
        }else{
            $mails = $this->main_m->getMailList($this->_user_id, $this->_time_zone, $this->_current_time);
            foreach($mails['send_mails'] as &$row){
                if($row['song_path'])
                    $row['song_path'] = UPLOAD_URL.$row['song_path'];
            }
            foreach($mails['receiver_mails'] as &$row){
                if($row['song_path'])
                    $row['song_path'] = UPLOAD_URL.$row['song_path'];
            }
            if($mails){
                $this->result['status'] = true;
                $this->result['mails'] = $mails;
            }else{
                $this->result['status'] = false;
                $this->result['error_code'] = 2;
                $this->result['message'] = "Other Error";
            }
        }
        $this->response($this->result);
    }
    
    public function response_get_user(){
        if(!$this->_user_id){
            $this->result['status'] = false;
            $this->result['error_code'] = 1;
            $this->result['message'] = 'User ID is required';
        }else{
            $user = $this->main_m->getUser($this->_user_id);
            if($user){
                $this->result['status'] = true;
                $user->img_url = $this->getUserImageUrl($user->img_url);
                $this->result['user'] = $user;
            }else{
                $this->result['status'] = false;
                $this->result['error_code'] = 2;
                $this->result['message'] = "Other Error";
            }
        }
        $this->response($this->result);
    }
    
    public function response_delete_mail(){
        if(!$this->_mail_id){
            $this->result['status'] = false;
            $this->result['message'] = "Mail ID is required";
            $this->result['error_code'] = 1;
        }else{
            $this->db->where('id', $this->_mail_id);
            $res = $this->db->update('msp_mail', array('is_delete'=>1));
            
            if($res && $this->db->affected_rows()){
                $this->result['status'] = true;
            }else{
                $this->result['status'] = false;
                $this->result['message'] = "Other Error";
                $this->result['error_code'] = 2;
            }
        }
        $this->response($this->result);
    }
    
//    public function response_delete_mail(){
//        if(!$this->_mail_id){
//            $this->result['status'] = false;
//            $this->result['message'] = "Mail ID is required";
//            $this->result['error_code'] = 1;
//        }else{
//            $this->db->where('id', $this->_mail_id);
//            $res = $this->db->update('msp_mail', array('is_delete'=>1));
//            
//            if($res && $this->db->affected_rows()){
//                $this->result['status'] = true;
//            }else{
//                $this->result['status'] = false;
//                $this->result['message'] = "Other Error";
//                $this->result['error_code'] = 2;
//            }
//        }
//        $this->response($this->result);
//    }
    
    public function response_update_seen(){
        if(!$this->_mail_id){
            $this->result['status'] = false;
            $this->result['message'] = "Mail ID is required";
            $this->result['error_code'] = 1;
        }else{
            $this->db->where('id', $this->_mail_id);
            $res = $this->db->update('msp_mail', array('is_seen'=>1));
            
            if($res && $this->db->affected_rows()){
                $this->result['status'] = true;
            }else{
                $this->result['status'] = false;
                $this->result['message'] = "Other Error";
                $this->result['error_code'] = 2;
            }
        }
        $this->response($this->result);
    }
    
    public function response_forgotpassword(){
        $this->_password = substr(md5(rand(0, 1000)), 0, 12);
        if(!$this->_email){
            $this->result['status'] = false;
            $this->result['message'] = "The email is required";
            $this->result['error_code'] = 1;
        }
        $change = $this->ion_auth->reset_password($this->_email, $this->_password);
        if(!$change){
            $this->result['status'] = false;
            $this->result['message'] = "Other error";
            $this->result['error_code'] = 2;
        }
        if($this->result['status']){
            $this->email->clear();
            $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
            $this->email->to($this->_email);
            $this->email->subject("Reset password" );
            $this->email->message("Please try to login this password.<br>".$this->_password);
            $this->email->send();
        }
        $this->response($this->result);
    }
    
    public function response_blockuser(){
        if($this->_sender == $this->_receiver){
            $this->result['message'] = "You can't block yourself.";
            $this->result['error_code'] = 3;
            $this->result['status'] = false;
        }else{
            $this->main_m->blockUser($this->_sender, $this->_receiver);
        }
        $this->response($this->result);
    }
    
    public function response_unblockuser(){
//        if($this->_sender == $this->_receiver){
//            $this->result['message'] = "You can't unblock yourself.";
//            $this->result['error_code'] = 3;
//            $this->result['status'] = false;
//        }else{
            $this->main_m->unblockUser($this->_sender, $this->_receiver);
//        }
        $this->response($this->result);
    }
    
    public function response_getBlockUsers(){
        if(!$this->_user_id){
            $this->result['message'] = "User ID is required.";
            $this->result['error_code'] = 1;
            $this->result['status'] = false;
            $this->response($this->result);
        }
        $rows = $this->main_m->getBlockedUsersOfMail($this->_user_id);
        $this->result['users'] = $rows;
        $this->response($this->result);
    }
    
    public function response_getCountries(){
        $countries = $this->main_m->getCountries();
        $this->result['status'] = true;
        $this->result['countries'] = $countries;
        $this->response($this->result);
    }
    
    public function response_getStates(){
        if(!$this->_country){
            $this->result['message'] = "Country ID is required.";
            $this->result['error_code'] = 1;
            $this->result['status'] = false;
            $this->response($this->result);
        }
        $regions = $this->main_m->getRegions($this->_country);
        $this->result['status'] = true;
        $this->result['states'] = $regions;
        $this->response($this->result);
    }
    
    public function response_getCities(){
        if(!$this->_country){
            $this->result['message'] = "Country ID is required.";
            $this->result['error_code'] = 1;
            $this->result['status'] = false;
            $this->response($this->result);
        }
        if(!$this->_state){
            $this->result['message'] = "State ID is required.";
            $this->result['error_code'] = 2;
            $this->result['status'] = false;
            $this->response($this->result);
        }
        $cities = $this->main_m->getCities($this->_country, $this->_state);
        $this->result['status'] = true;
        $this->result['cities'] = $cities;
        $this->response($this->result);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */