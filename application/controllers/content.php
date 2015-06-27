<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller {

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
        $this->data['feeds'] = $this->main_m->getHomeFeedList();
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
        redirect('content/feed');
    }
    function getThumbNail($image_path, $width=100, $height=100, $default_url=''){
        if(!is_file(UPLOAD_DIR.$image_path)){
            return $default_url;
        }
        if(!in_array(strtolower(substr(strrchr($image_path,'.'),1)),array('gif','jpg','jpeg','png', 'bmp'))) {
            return UPLOAD_URL.$image_path;
        }
        $path = BASE_URL."phpThumb.php?src=assets/ufile/".$image_path."&w={$width}&h={$height}";
        return $path;
    }
    public function feed()
    {
        
        $feed = $this->uri->segment(3,false);
        if($feed && method_exists($this, "feed_{$feed}"))
            call_user_func_array(array($this, "feed_{$feed}"), array());
        else{
            foreach($this->data['feeds'] as &$feed){
                $feed['image_path'] = $this->getThumbNail($feed['image_path'], 50, 50);
            }
            $this->load->view('homefeed', $this->data);
        }
            
        
    } 
    
    public function feed_text(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'description' => $this->input->post('description'),
            'tags' => $this->input->post('tags'),
            'credit' => $this->input->post('credit'),
            'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
        );
        $this->_proc_feed_text();
        $this->load->view('feed_text', $this->data);
    }
    
    public function feed_text_edit(){
        $feed_id = $this->uri->segment(4);
        $id = ltrim($feed_id, "text_");
        if(empty($id)){
            show_error("Select a post to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $FeedText = $this->main_m->getFeedText($id);
            $this->data['post'] = array(
                'feed_id' => $feed_id,
                'cid' => $this->chanel['id'],
                'description' => $FeedText['description'],
                'tags' => $FeedText['tags'],
                'credit' => $FeedText['credit'],
                'is_publish' => $FeedText['is_publish']
            );
        }else{
            $this->data['post'] = array(
                'feed_id' => $feed_id,
                'cid' => $this->chanel['id'],
                'description' => $this->input->post('description'),
                'tags' => $this->input->post('tags'),
                'credit' => $this->input->post('credit'),
                'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
            );
        }
        $this->_proc_feed_text_edit();
        $this->load->view('feed_text_edit', $this->data);
    }
    
    public function _proc_feed_text(){
        $this->form_validation->set_rules('description','description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addFeedText($this->data['post'])){
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function _proc_feed_text_edit(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateFeedText($this->data['post'])){
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function feed_photo(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'description' => $this->input->post('description'),
            'tags' => $this->input->post('tags'),
            'credit' => $this->input->post('credit'),
            'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
        );
        $this->_proc_feed_photo();
        $this->load->view('feed_photo', $this->data);
    }
    public function feed_photo_edit(){
        $feed_id = $this->uri->segment(4);
        $id = ltrim($feed_id, "photo_");
        if(empty($id)){
            show_error("Select a post to edit!");
            return;
        }
        if($this->input->post('delimg')){
            $this->db->where('id', $id);
            $this->db->update('enr_photo', array('destination'=>'', 'image_mime'=>''));
            redirect("content/feed/photo_edit/photo_".$id, '');
        }
        if($this->input->post('submit', false) === false){
            $FeedPhoto = $this->main_m->getFeedPhoto($id);
            $this->data['post'] = $FeedPhoto;
            $this->data['post']['feed_id'] = $feed_id;
            $this->data['post']['cid'] = $this->chanel['id'];
        }else{
            $this->data['post'] = array(
                'feed_id' => $feed_id,
                'cid' => $this->chanel['id'],
                'description' => $this->input->post('description'),
                'tags' => $this->input->post('tags'),
                'credit' => $this->input->post('credit'),
                'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
            );
        }
        $this->_proc_feed_photo_edit();
        $this->load->view('feed_photo_edit', $this->data);
    }
    public function _proc_feed_photo_edit(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateFeedPhoto($this->data['post'])){
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    private function _proc_feed_photo(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->input->post("submit", false)!==false && (!isset($_FILES['image']) or !$_FILES['image']['size'])){
                $this->data['show_errors'][] = "Please select a photo.";
            }elseif($this->main_m->addFeedPhoto($this->data['post'])){
                redirect("content/feed", '');
            }
        }
        return $data;
    }
/**
* feed video
* 
*/
    public function feed_video(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'description' => $this->input->post('description'),
            'destination' => $this->input->post('description'),
            'tags' => $this->input->post('tags'),
            'video_url' => $this->input->post('video_url'),
            'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
        );
        $this->_proc_feed_video();
        $this->load->view('feed_video', $this->data);
    }
    private function _proc_feed_video(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addFeedVideo($this->data['post'])){
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function feed_video_edit(){
//        print_r($_FILES);exit;
        $feed_id = $this->uri->segment(4);
        $id = ltrim($feed_id, "video_");
        if(empty($id)){
            show_error("Select a post to edit!");
            return;
        }
        if($this->input->post('delimg')){
            $this->db->where('id', $id);
            $this->db->update('enr_video', array('image_path'=>'', 'image_mime'=>''));
            redirect("content/feed/video_edit/video_".$id, '');
        }
        $FeedVideo = $this->main_m->getFeedVideo($id);
        if($this->input->post('submit', false) === false){
            $FeedVideo['video_size'] = $FeedVideo['video_size'];
            $FeedVideo['format_size'] = $this->_makeSizeFormat($FeedVideo['video_size']);
            $this->data['post'] = $FeedVideo;
            $this->data['post']['feed_id'] = $feed_id;
            $this->data['post']['cid'] = $this->chanel['id'];
        }else{
            $this->data['post'] = array(
                'feed_id' => $feed_id,
                'cid' => $this->chanel['id'],
                'description' => $this->input->post('description'),
                'tags' => $this->input->post('tags'),
                'credit' => $this->input->post('credit', ''),
                'video_url' => $this->input->post('video_url', ''),
                'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
            );
            $this->data['post']['destination'] = $FeedVideo['destination'];
            $this->data['post']['image_path'] = $FeedVideo['image_path'];
            $this->data['post']['format_size'] = $this->_makeSizeFormat($FeedVideo['video_size']) ;
        } 
        $this->_proc_feed_video_edit();
        
        $this->load->view('feed_video_edit', $this->data);
    }
    
    public function _proc_feed_video_edit(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateFeedVideo($this->data['post'])){
                redirect("content/feed", '');
            }
        }
//         print_r($this->form_validation->run());exit;
        return $data;
    }
/**
* feed poll
* 
*/
    public function feed_poll(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'description' => $this->input->post('description'),
            'end_description' => $this->input->post('end_description'),
            'tags' => $this->input->post('tags'),
            'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
        );
        $this->_proc_feed_poll();
        $this->load->view('feed_poll', $this->data);
    }
    private function _proc_feed_poll(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
//            if($this->input->post("submit", false)!==false && !$_FILES['image1']['size']){
//                $this->data['show_errors'][] = "Please select a opening page image.";
//            }elseif($this->input->post("submit", false)!==false && !$_FILES['image2']['size']){
//                $this->data['show_errors'][] = "Please select a closing page image.";
//            }else
            if($this->main_m->addFeedPoll()){
//                redirect("content/feed/poll", '');
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function feed_poll_edit(){
        $feed_id = $this->uri->segment(4);
        $id = ltrim($feed_id, "poll_");
        if(empty($id)){
            show_error("Select a post to edit!");
            return;
        }

        $this->_proc_feed_poll_edit($id);
        $feedPoll = $this->main_m->getFeedPoll($id);
        $this->data['post'] = $feedPoll;
        $pollQuestion = $this->main_m->getFeedPoll_question_answer($id);
        $this->data['pollQuestions'] = $pollQuestion;
        $this->data['post']['feed_id']= $feed_id;
        
        
        $this->load->view('feed_poll_edit', $this->data);
    }
    private function _proc_feed_poll_edit($id){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
//            if($this->input->post("submit", false)!==false && !$_FILES['image1']['size']){
//                $this->data['show_errors'][] = "Please select a opening page image.";
//            }elseif($this->input->post("submit", false)!==false && !$_FILES['image2']['size']){
//                $this->data['show_errors'][] = "Please select a closing page image.";
//            }else
            if($this->main_m->updateFeedPoll($id)){
//                redirect("content/feed/poll", '');
                redirect("content/feed", '');
            }
        }
        return $data;
    }
/**
* Feed Quiz
* 
*/
    public function feed_quiz(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'description' => $this->input->post('description'),
            'end_description' => $this->input->post('end_description'),
            'tags' => $this->input->post('tags'),
            'is_publish' => $this->input->post('is_publish') == "on" ? 1 : 0
        );
        $this->_proc_feed_quiz();
        $this->load->view('feed_quiz', $this->data);
    }
    private function _proc_feed_quiz(){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
//            if($this->input->post("submit", false)!==false && !$_FILES['image1']['size']){
//                $this->data['show_errors'][] = "Please select a opening page image.";
//            }elseif($this->input->post("submit", false)!==false && !$_FILES['image2']['size']){
//                $this->data['show_errors'][] = "Please select a closing page image.";
//            }else
            if($this->main_m->addFeedQuiz()){
//                redirect("content/feed/poll", '');
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function feed_quiz_edit(){
        $feed_id = $this->uri->segment(4);
        $id = ltrim($feed_id, "quiz_");
        if(empty($id)){
            show_error("Select a post to edit!");
            return;
        }

        $this->_proc_feed_quiz_edit($id);
        $feedPoll = $this->main_m->getFeedQuiz($id);
        $this->data['post'] = $feedPoll;
        $pollQuestion = $this->main_m->getFeedQuiz_question_answer($id);
        $this->data['pollQuestions'] = $pollQuestion;
        $this->data['post']['feed_id']= $feed_id;
        
        
        $this->load->view('feed_quiz_edit', $this->data);
    }
    private function _proc_feed_quiz_edit($id){
        $this->form_validation->set_rules('description', 'description', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
//            if($this->input->post("submit", false)!==false && !$_FILES['image1']['size']){
//                $this->data['show_errors'][] = "Please select a opening page image.";
//            }elseif($this->input->post("submit", false)!==false && !$_FILES['image2']['size']){
//                $this->data['show_errors'][] = "Please select a closing page image.";
//            }else
            if($this->main_m->updateFeedQuiz($id)){
//                redirect("content/feed/poll", '');
                redirect("content/feed", '');
            }
        }
        return $data;
    }
    public function delete_feed(){
        $id = $this->uri->segment(3);
        $ids = explode("_", $id);
        $feed_type = $ids[0];
        $id = $ids[1];
        call_user_func_array(array($this->main_m, "deleteFeed".$feed_type), array("id"=>$id));
        
        redirect('content/feed', '');
    }
    /**
    * Community
    * 
    */

    public function community(){
        $this->data['feeds'] = $this->main_m->getCommunityFeedList();
        foreach($this->data['feeds'] as &$feed){
            $feed['image_path'] = $this->getThumbNail($feed['image_path'], 50, 50);
        }
        $this->load->view('community', $this->data);
    }
    /**
    * photo gallery
    */
    public function photo_gallery(){
        $this->data['selectedTags'] = $this->uri->segment(3);
        $this->data['photos'] = $this->main_m->getPhotosByTags($this->uri->segment(3));
        $this->data['tagOptions'] = $this->main_m->getPhotoTagOtions();
        foreach($this->data['photos'] as &$photo){
            $photo['destination'] = $this->getThumbNail($photo['destination'], 262, 262, ASSETS_DIR.'/img/no-image-found.jpg');

        }

        $this->load->view('photo_gallery', $this->data);
    }
    public function video_gallery(){
        $this->data['selectedTags'] = $this->uri->segment(3);
        $this->data['videos'] = $this->main_m->getVideosByTags($this->uri->segment(3));
        foreach($this->data['videos'] as &$video){
            $video['image_path'] = $this->getThumbNail($video['image_path'], 262, 262, ASSETS_DIR.'/img/no-image-found.jpg');

        }
        $this->data['tagOptions'] = $this->main_m->getVideosTagOtions();
        
        $this->load->view('video_gallery', $this->data);
    }
    public function tour_date(){
        $this->data['searchvalue'] = $this->input->post('searchvalue');
        $this->data['selectedDate'] = $this->uri->segment(3);
        $this->data['events'] = $this->main_m->getTourdateListByDate($this->uri->segment(3), $this->data['searchvalue']);
        $this->data['dateOptions'] = $this->main_m->getEventdateOptions();

        $event = $this->uri->segment(3,'list');
//        $this->data['events'] = $this->main_m->getTourdateList();
        if($event && method_exists($this, "tourdate_{$event}"))
            call_user_func_array(array($this, "tourdate_{$event}"), array());
        else
            $this->load->view('tourdate_list', $this->data);
    }
    public function tourdate_add(){  
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'title' => $this->input->post('title'),
            'position' => $this->input->post('position'),
            'concert_date' => $this->input->post('concert_date'),
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'price' => $this->input->post('price'),
            'url' => $this->input->post('url')
        );
        $this->_proc_tourdate_add();
        $this->load->view('tourdate_add', $this->data);
    }
    public function _proc_tourdate_add(){
        $this->form_validation->set_rules('title', 'title', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addTourdate($this->data['post'])){
                redirect("content/tour_date", '');
            }
        }
        return $data;
    }
    public function tourdate_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a tour date to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $post = $this->main_m->getTourdate($id);
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
            $this->data['post']['concert_date'] = date("m/d/Y", $post['concert_time']);
            $this->data['post']['start_date'] = date("m/d/Y", $post['start_time']);
            $this->data['post']['end_date'] = date("m/d/Y", $post['end_time']);
            $this->data['post']['cid'] = $this->chanel['id'];
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'title' => $this->input->post('title'),
                'position' => $this->input->post('position'),
                'concert_date' => $this->input->post('concert_date'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'price' => $this->input->post('price'),
                'url' => $this->input->post('url')
            );
        } 
        $this->_proc_tourdate_edit();
        $this->load->view('tourdate_edit', $this->data);
    }
    public function _proc_tourdate_edit(){
        $this->form_validation->set_rules('title', 'title', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateTourdate($this->data['post'])){
                redirect("content/tour_date", '');
            }
        }
        return $data;
    }
    public function tourdate_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_event");
        
        redirect('content/tour_date', '');
    }
    public function music_player(){
        $event = $this->uri->segment(3,'list');
        $this->data['musics'] = $this->main_m->getMusicList();
        if($event && method_exists($this, "music_{$event}"))
            call_user_func_array(array($this, "music_{$event}"), array());
        else
            $this->load->view('music_list', $this->data);
    }
    public function music_add(){
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'title' => $this->input->post('title'),
            'album' => $this->input->post('album'),
            'destination' => $this->input->post('destination'),
            'music_size' => $this->input->post('music_size'),
            'url' => $this->input->post('url'),
            'is_publish' => $this->input->post('is_publish')
        );
        $this->_proc_music_add();
        $this->load->view('music_add', $this->data);
    }
    public function _proc_music_add(){
        $this->form_validation->set_rules('title', 'title', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->input->post("submit", false)!==false && !$_FILES['image']['size']){
                $this->data['show_errors'][] = "Please select a photo.";
            }elseif($this->main_m->addMusic($this->data['post'])){
                redirect("content/music_player", '');
            }
        }
        return $data;
    }
    public function music_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a song to edit!");
            return;
        }
        $post = $this->main_m->getMusic($id);
        if($this->input->post('submit', false) === false){
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
            $this->data['post']['cid'] = $this->chanel['id'];
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'title' => $this->input->post('title'),
                'album' => $this->input->post('album'),
                'destination' => $this->input->post('destination'),
                'music_size' => $this->input->post('music_size'),
                'url' => $this->input->post('url'),
                'image_path' => $post['image_path'],
                'is_publish' => $this->input->post('is_publish')=="on" ? 1 : 0
            );
        } 
        $this->_proc_music_edit();
        $this->load->view('music_edit', $this->data);
    }
    public function _proc_music_edit(){
        $this->form_validation->set_rules('title', 'title', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateMusic($this->data['post'])){
                redirect("content/music_player", '');
            }
        }
        return $data;
    }
    public function music_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_music");
        
        redirect('content/music_player', '');
    }
    /**
    * shop
    * 
    */
    public function product(){
        $product = $this->uri->segment(3,'list');
        $this->data['products'] = $this->main_m->getProductList();
        foreach($this->data['products'] as &$row){
            $row['imageArr'] = unserialize($row['images']);
        }
        if($product && method_exists($this, "product_{$product}"))
            call_user_func_array(array($this, "product_{$product}"), array());
        else
            $this->load->view('product_list', $this->data);
    }
    public function product_add(){  
        $this->data['post'] = array(
            'cid' => $this->chanel['id'],
            'title' => $this->input->post('title'),
            'category' => $this->input->post('category'),
            'color' => $this->input->post('color'),
            'size' => $this->input->post('size'),
            'tags' => $this->input->post('tags'),
            'price' => $this->input->post('price'),
            'images' => $this->input->post('photos'),
            'imageArr' => $this->input->post('photos'),
            'is_publish' => $this->input->post('is_publish')=='on' ? 1 : 0
        );
        $this->data['categoryOptions'] = $this->main_m->getCategoryOptions($this->chanel['id']);
        $this->data['colorOptions'] = $this->main_m->getColorOptions($this->chanel['id']);
        $this->data['sizeOptions'] = $this->main_m->getSizeOptions($this->chanel['id']);
        $this->_proc_product_add();
        $this->load->view('product_add', $this->data);
    }

//    {"files": [
//      {
//        "name": "picture1.jpg",
//        "size": 902604,
//        "url": "http:\/\/example.org\/files\/picture1.jpg",
//        "thumbnailUrl": "http:\/\/example.org\/files\/thumbnail\/picture1.jpg",
//        "deleteUrl": "http:\/\/example.org\/files\/picture1.jpg",
//        "deleteType": "DELETE"
//      },
//      {
//        "name": "picture2.jpg",
//        "size": 841946,
//        "url": "http:\/\/example.org\/files\/picture2.jpg",
//        "thumbnailUrl": "http:\/\/example.org\/files\/thumbnail\/picture2.jpg",
//        "deleteUrl": "http:\/\/example.org\/files\/picture2.jpg",
//        "deleteType": "DELETE"
//      }
//    ]}
    public function upload_video(){
        $files = $_FILES['video'];
        $result = array();
        $file = array();
        $size = $files['size'];
        if($path = $this->main_m->uploadTempVideo()){
            $file['name'] = $files['name'];
            $file['size'] = $size;
            $file['video_size'] = $size;
            $file['video_mime'] = $files['type'];
            $file['url'] =  UPLOAD_URL.$path;
            $file['thumbnail_url'] =  UPLOAD_URL.$path;
            $file['path'] = $path;
            $result['files'][] = $file;
        }else{
            $file['name'] = $files['name'];
            $file['size'] = $size;
            $file['error'] = $this->data['show_errors'];
            $result['files'][] = $file;
        }
        echo json_encode($result);exit;
    }
    public function upload_music(){
        $files = $_FILES['music'];
        $result = array();
        $file = array();
        $size = $files['size'];
        if($path = $this->main_m->uploadTempMusic()){
            $file['name'] = $files['name'];
            $file['size'] = $size;
            $file['music_size'] = $size;
            $file['music_mime'] = $files['type'];
            $file['url'] =  UPLOAD_URL.$path;
            $file['thumbnail_url'] =  UPLOAD_URL.$path;
            $file['path'] = $path;
            $result['files'][] = $file;
        }else{
            $file['name'] = $files['name'];
            $file['size'] = $size;
            $file['error'] = $this->data['show_errors'];
            $result['files'][] = $file;
        }
        echo json_encode($result);exit;
    }
    private function _makeSizeFormat($size){
        $kbSize = round($size / 1024, 2);
        $mbSize = round($size / 1024 / 1024, 2);
        $gbSize = round($size / 1024 / 1024 / 1024, 2);
        if($mbSize < 1)
            return $kbSize.'KB';
        elseif($gbSize<1)
            return $mbSize.'MB';
        else
            return $gbSize.'GB';
    }
    public function upload_product_photo(){
        $files = $_FILES['image'];
        $result = array();
        $file = array();
        if($path = $this->main_m->uploadTempPhoto()){
            $file['name'] = $files['name'];
            $file['size'] = $files['size'];
            $file['url'] =  UPLOAD_URL.$path;
            $file['thumbnail_url'] =  UPLOAD_URL.$path;
            $file['path'] = $path;
            $result['files'][] = $file;
        }else{
            $file['name'] = $files['name'];
            $file['size'] = $files['size'];
            $file['error'] = $this->data['show_errors'];
            $result['files'][] = $file;
        }
        echo json_encode($result);exit;
    }
    public function _proc_product_add(){
        $this->form_validation->set_rules('title', 'product title', 'required|xss_clean');
        
        $data = array();
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->addProduct($this->data['post'])){
                redirect("content/product", '');
            }
        }
        return $data;
    }
    public function product_edit(){  
        $id = $this->uri->segment(4);
        if(empty($id)){
            show_error("Select a product to edit!");
            return;
        }
        if($this->input->post('submit', false) === false){
            $post = $this->main_m->getProductList($id);
            $this->data['post'] = $post;
            $this->data['post']['id'] = $id;
            $this->data['post']['imageArr'] = unserialize($post['images']);
        }else{
            $this->data['post'] = array(
                'id' => $id,
                'cid' => $this->chanel['id'],
                'title' => $this->input->post('title'),
                'category' => $this->input->post('category'),
                'color' => $this->input->post('color'),
                'size' => $this->input->post('size'),
                'tags' => $this->input->post('tags'),
                'price' => $this->input->post('price'),
                'images' => $this->input->post('photos'),
                'imageArr' => $this->input->post('photos'),
                'is_publish' => $this->input->post('is_publish')=='on' ? 1 : 0
            );
        } 
        $this->data['categoryOptions'] = $this->main_m->getCategoryOptions($this->chanel['id']);
        $this->data['colorOptions'] = $this->main_m->getColorOptions($this->chanel['id']);
        $this->data['sizeOptions'] = $this->main_m->getSizeOptions($this->chanel['id']);

        $this->_proc_product_edit();
        $this->load->view('product_edit', $this->data);
    }
    public function _proc_product_edit(){
        $this->form_validation->set_rules('title', 'product title', 'required|xss_clean');
        
        $data = array();
        
        if ($this->form_validation->run() == true)
        {
            if($this->main_m->updateProduct($this->data['post'])){
                redirect("content/product", '');
            }
        }
        return $data;
    }
    public function product_delete(){
        $id = $this->uri->segment(3);
        $this->db->where('id', $id);
        $this->db->delete("enr_product");
        
        redirect('content/product', '');
    }
    

}

