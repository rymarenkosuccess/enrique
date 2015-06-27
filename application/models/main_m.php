<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_m extends CI_Model
{
	public function __construct()
	{
        $this->errors = array();
		parent::__construct();
        $this->controller = get_instance();
        $this->chanel = $this->controller->session->userdata('chanel');
	//	$this->load->database();
	}
    /**
     * errors as array
     *
     * Get the error messages as an array
     *
     * @return array
     * @author Raul Baldner Junior
     **/
    public function get_errors()
    {
        return $this->errors;
    }
    function getChanels($searchvalue=false){
        $where = '';
        if($searchvalue){
            $where = " where t1.name like '%{$searchvalue}%'";
        }
        
        $sql = "select t1.*, t2.email chanel_admin from enr_chanel t1 left join users t2 on t1.user_id=t2.id {$where}  
        ";
        $query = $this->db->query($sql);
        $rows = $query->result();
        return $rows;
    }
    function get_chanel($id){
        $id = $this->db->escape_str($id);
        $strSql = "
            SELECT t1.*, t2.username chanel_admin
            FROM enr_chanel t1 left join users t2 on t1.user_id=t2.id
            where 
                t1.id={$id}
        ";
        $query = $this->db->query($strSql);
        $row = $query->row_array();
        return $row;
    }
    function isExistChanel($chanel){
        $chanel['name'] = $this->db->escape_str($chanel['name']);
        $strSql = "SELECT * FROM enr_chanel where id!={$chanel['id']} and  name='{$chanel['name']}'";
        $query = $this->db->query($strSql);
        $rows = $query->result();
        if(count($rows) > 0){
            return true;
        }else{
            return false;
        }
    }
    function addChanel($chanel){
        if($_FILES['image']['size']){
            $image_path = $this->_uploadPhoto();
            $image_mime = $_FILES['image']['type'];
        }else{
            $image_path = "";
            $image_mime = "";
        }
        
        $res = $this->db->insert('enr_chanel', array('name'=>$chanel['name'], 'url'=>$chanel['url'], 'is_publish'=>$chanel['is_publish'], 'user_id'=>'-2', 'image_path'=>$image_path, 'image_mime'=>$image_mime));
        $cid = $this->db->insert_id();
        if($chanel['chanel_admin']){
            $additional_data = array(
                "cid" => $cid
            );
            $user_id = $this->controller->ion_auth->register($chanel['chanel_admin'], $chanel['password'], $chanel['chanel_admin'], $additional_data);
            $this->db->update('enr_chanel', array('user_id'=>$user_id), array('id'=>$cid));
        }else{
            $user_id = 0;
        }
        return $res;
    }
    public function getUser($id){
        $sql = "
            select *
            from users
            where id='{$id}' 
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    function updateChanel($chanel){
        if($v = $this->get_chanel($chanel['id'])){
            $user_id = $v['user_id'];
            if($_FILES['image']['size']){
                $destination = UPLOAD_DIR.$v['image_path'];
                if(is_file($destination)){
                    unlink($destination);
                }
                $image_path = $this->_uploadPhoto();
                $image_mime = $_FILES['image']['type'];
            }
        }
        if($chanel['chanel_admin']){
            if(!$this->getUser($user_id)){
                $user_id = $this->controller->ion_auth->register($chanel['chanel_admin'], $chanel['password'], $chanel['chanel_admin']);
            }else{
                $this->controller->ion_auth->update($user_id, array('username'=>$chanel['chanel_admin'], 'password'=>$chanel['password'], 'email'=>$chanel['chanel_admin'], 'cid'=>$chanel['id']));
            }
        }

        $this->db->where("id", $chanel['id']);
        $res = $this->db->update('enr_chanel', array('name'=>$chanel['name'], 'url'=>$chanel['url'], 'is_publish'=>$chanel['is_publish'], 'user_id'=>$user_id, 'image_path'=>$image_path, 'image_mime'=>$image_mime));
        return $res;
    }
    /**
    * content
    */
    function getSubmenus($chanel=0){
        $sql = "
            select t1.*, if(t1.alter_name<>'', t1.alter_name, t2.name) alter_name, t2.url url
            from enr_submenu t1, enr_section t2
            where 
                t1.cid='{$chanel}' and t1.section_id=t2.id
            order by 
                t1.ordering, t2.ordering
        ";
        
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    function addFeedText($feed){
        $post = array(
            'cid' => $feed['cid'],
            'fan_id' => -1,
            'description' => $feed['description'],
            'tags' => $feed['tags'],
            'credit' => $feed['credit'],
            'time_stamp' => time(),
            'is_publish' => $feed['is_publish']
        );
        $res = $this->db->insert("enr_text", $post);
        return $res;
    }
    
    function updateFeedText($feed){
        $id = ltrim($feed['feed_id'], "text_");
        $post = array(
            'cid' => $feed['cid'],
            'fan_id' => -1,
            'description' => $feed['description'],
            'tags' => $feed['tags'],
            'credit' => $feed['credit'],
            'time_stamp' => time(),
            'is_publish' => $feed['is_publish']
        );
        $this->db->where("id", $id);
        $res = $this->db->update("enr_text", $post);
        return $res;
    }
    
    function getHomeFeedList(){
        $sql = "select t1.*
            from 
                (
                    (
                        select concat('text_',id) id,'Text Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, tags, description caption, time_stamp, fan_id
                        from enr_text
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."'
                    ) 
                    union
                    (
                        select concat('photo_',id) id,'Photo Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, destination image_path, tags, description caption, time_stamp, fan_id
                        from enr_photo
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."'
                    )  
                    union
                    (
                        select concat('video_',id) id,'Video Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, image_path image_path, tags, description caption, time_stamp, fan_id
                        from enr_video
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."'
                    )  
                    union
                    (
                        select concat('poll_',id) id,'Poll Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, tags, description caption, time_stamp, fan_id
                        from enr_poll
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."'
                    )  
                    union
                    (
                        select concat('quiz_',id) id,'Quiz Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, tags, description caption, time_stamp, fan_id
                        from enr_quiz
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."'
                    )  
                ) t1 
            where
                t1.fan_id=-1
            order by t1.time_stamp desc
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    function getFeedText($id){
        $sql = "
            select *
            from enr_text
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    /**
    * feed photo
    */

    function addFeedPhoto($feed){
        $designinfo = $this->getDesignInfo();


        if($filepath = $this->_uploadPhoto()){
            $file = UPLOAD_DIR.$filepath;
            $watermark = UPLOAD_DIR.$designinfo['watermark'];
            $position = array('x'=>10, 'y'=>10);
            $class = new Watermark();
            $class->apply($file, $file, $watermark, $position);

            $post = array(
                'cid' => $feed['cid'],
                'fan_id' => -1,
                'description' => $feed['description'],
                'tags' => $feed['tags'],
                'credit' => $feed['credit'],
                'destination' => $filepath,
                'image_mime' => $_FILES['image']['type'],
                'time_stamp' => time(),
                'is_publish' => $feed['is_publish']
            );
            $res = $this->db->insert("enr_photo", $post);
            return $res;
        }
        return false;
    }
    
    function getFeedPhoto($id){
        $sql = "
            select *
            from enr_photo
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    function updateFeedPhoto($feed){
        $id = ltrim($feed['feed_id'], "photo_");
        $oFeed = $this->getFeedPhoto($id);
        if($_FILES['image']['size']){
            $destination = UPLOAD_DIR.$oFeed['destination'];
            if(is_file($destination)){
                unlink($destination);
            }
            $filepath = $this->_uploadPhoto();
            $designinfo = $this->getDesignInfo();

            $file = UPLOAD_DIR.$filepath;
            $watermark = UPLOAD_DIR.$designinfo['watermark'];
            $position = array('x'=>10, 'y'=>10);

            $class = new Watermark();
            $class->apply($file, $file, $watermark, $position);

            if(!$filepath){
                return false;
            }
        }else{
            $filepath = $oFeed['destination'];
        }
        $post = array(
            'cid' => $feed['cid'],
            'fan_id' => -1,
            'description' => $feed['description'],
            'tags' => $feed['tags'],
            'credit' => $feed['credit'],
            'destination' => $filepath,
            'image_mime' => $_FILES['image']['type'],
            'time_stamp' => time(),
            'is_publish' => $feed['is_publish']
        );
        $this->db->where("id", $id);
        $res = $this->db->update("enr_photo", $post);
        return $res;
    }
    function deleteFeedText($id){
        $this->db->where('id', $id);
        $this->db->delete("enr_text");
    }
    function deleteFeedPhoto($id){
        $oFeed = $this->getFeedPhoto($id);
        $destination = UPLOAD_DIR.$oFeed['destination'];
        if(is_file($destination)){
            unlink($destination);
        }

        $this->db->where('id', $id);
        $this->db->delete("enr_photo");
    }
    function deleteFeedVideo($id){
        $oFeed = $this->getFeedVideo($id);
        $destination = UPLOAD_DIR.$oFeed['destination'];
        if(is_file($destination)){
            unlink($destination);
        }
        $image_path = UPLOAD_DIR.$oFeed['image_path'];
        if(is_file($image_path)){
            unlink($image_path);
        }

        $this->db->where('id', $id);
        $this->db->delete("enr_video");
    }
    
    private function _uploadPhoto($name='image'){
        $year = date('Y');
        $month = date('m');
        $fileinfo = $_FILES[$name];
        if(!$fileinfo['size']){
            $this->controller->data['show_errors'][] = "Please select a photo file.";
            return false;
        }
        $filenames = explode(".", $fileinfo['name']);
        $ext = $filenames[count($filenames)-1];
        if(stripos("jpg, png, bmp, jpeg, gif", $ext) === false){
            $this->controller->data['show_errors'][] = "Only jpg, png, gif, bmp files can be uploaded.";
            return false;
        }
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."photo/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".".$ext;
        move_uploaded_file($fileinfo['tmp_name'], $filepath);
        return trim($filepath, UPLOAD_DIR);
    }
    
    /**
    * video
    */
    function getFeedVideo($id){
        $sql = "
            select *
            from enr_video
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    function getYoutubeThumbnail($url){
        if(stripos($url, 'youtu') === false){
            return false;
        }
        $urls = explode("/", $url);
        $thumbnail = "http://img.youtube.com/vi/".$urls[count($urls)-1]."/1.jpg";
        $ch = curl_init($thumbnail);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        $year = date('Y');
        $month = date('m');
        
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."photo/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".jpg";
        $fp = fopen($filepath, 'w');
        fwrite($fp, $rawdata);
        fclose($fp);
        return trim($filepath, UPLOAD_DIR);
    
    }
    
    function getThumbnailFromVideo($path){
        $year = date('Y');
        $month = date('m');
        
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."photo/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".jpg";
        $cmd = "avconv -i {$path} -ss 2 -s 120x90 -f image2  -frames:v 100 {$filepath}";

        $return = `$cmd`;
        return trim($filepath, UPLOAD_DIR);
    }
    
    function addFeedVideo($feed){
        $video_path = $this->input->post('destination'); 
//        $video_path = "video/2013/12/b957b64b4e9090a061b2f4a681546459.mp4";

        if($image_path = $this->getYoutubeThumbnail($feed['video_url'])){
        }else{
            $image_path = $this->getThumbnailFromVideo(UPLOAD_DIR.$video_path);
        }
                
        if($feed['destination']){
            $post = array(
                'cid' => $feed['cid'],
                'fan_id' => -1,
                'description' => $feed['description'],
                'tags' => $feed['tags'],
//                'credit' => $feed['credit'],
                'video_url' => $feed['video_url'],
                'image_path' => $image_path,
                'image_mime' => $_FILES['image']['type'],
                'destination' => $this->input->post('destination'),
                'video_mime' => $this->input->post('video_mime'),
                'video_size' => $this->input->post('video_size'),
                'video_mime' => $_FILES['video']['type'],
                'time_stamp' => time(),
                'is_publish' => $feed['is_publish']
            );
            $res = $this->db->insert("enr_video", $post);
            return $res;
        }
        return false;
    }    
    private function _uploadVideo($name='video'){
        $year = date('Y');
        $month = date('m');
        $fileinfo = $_FILES[$name];
        if(!$fileinfo['size']){
            $this->controller->data['show_errors'][] = "Please select a correct file.";
            return false;
        }
        $filenames = explode(".", $fileinfo['name']);
        $ext = $filenames[count($filenames)-1];
        if(stripos("flv,mpg,mp4", $ext) === false){
            $this->controller->data['show_errors'][] = "Only flv, mpg files can be uploaded.";
            return false;
        }
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."video/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".".$ext;
        move_uploaded_file($fileinfo['tmp_name'], $filepath);
        return str_replace(UPLOAD_DIR, "", $filepath);
    }
    private function _uploadMusic($name='music'){
        $year = date('Y');
        $month = date('m');
        $fileinfo = $_FILES[$name];
        if(!$fileinfo['size']){
            $this->controller->data['show_errors'][] = "Please select a correct file.";
            return false;
        }
        $filenames = explode(".", $fileinfo['name']);
        $ext = $filenames[count($filenames)-1];
        if(stripos("mp3", $ext) === false){
            $this->controller->data['show_errors'][] = "Only mp3 files can be uploaded.";
            return false;
        }
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."music/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".".$ext;
        move_uploaded_file($fileinfo['tmp_name'], $filepath);
        return str_replace(UPLOAD_DIR, "", $filepath);
    }
    function _uploadPhoto_video(){
        $img_values = explode(",", $this->input->post('video_img_value'));
        $header = $img_values[0];
        $data = base64_decode($img_values[1]);
        $ext = str_replace(array("data:image/", ";base64"), "", $header);
        $year = date('Y');
        $month = date('m');
        if(!$data){
            $this->controller->data['show_errors'][] = "Please select a photo file.";
            return false;
        }
        if(stripos("jpeg,png,gif,jpg,bmp", $ext) === false){
            $this->controller->data['show_errors'][] = "Only jpg, png, gif, bmp files can be uploaded.";
            return false;
        }
        $filename = md5(time().rand(0.1, 99.9));
        $filepath = UPLOAD_DIR."photo/".$year;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$month;
        if(!is_dir($filepath)){
            mkdir($filepath, 0700);
        }
        $filepath .= "/".$filename.".".$ext;
        $fp = fopen($filepath, 'w');
        fwrite($fp, $data);
        fclose($fp);
        return trim($filepath, UPLOAD_DIR);
    }
    function updateFeedVideo($feed){
        $id = ltrim($feed['feed_id'], "video_");
        $oFeed = $this->getFeedVideo($id);
//        if(!$image_path){
//            $image_path = $oFeed['image_path'];
//        }
        if($image_path = $this->getYoutubeThumbnail($feed['video_url'])){
            $oimage_path = UPLOAD_DIR.$oFeed['image_path'];
            if(is_file($oimage_path)){
                unlink($oimage_path);
            }
        }else{
            $oimage_path = UPLOAD_DIR.$oFeed['image_path'];
            if(is_file($oimage_path)){
                unlink($oimage_path);
            }
            $video_path = $this->input->post('destination'); 
            $image_path = $this->getThumbnailFromVideo(UPLOAD_DIR.$video_path);
        }

        if($this->input->post('destination') != $oFeed['destination']){
            $destination = UPLOAD_DIR.$oFeed['destination'];
            if(is_file($destination)){
                unlink($destination);
            }
        }
        $post = array(
            'cid' => $feed['cid'],
            'fan_id' => -1,
            'description' => $feed['description'],
            'tags' => $feed['tags'],
            'credit' => $feed['credit'],
//            'video_url' => $feed['video_url'],
            'image_path' => $image_path,
            'image_mime' => $_FILES['image']['type'],
//            'destination' => $this->input->post('destination'),
//            'video_mime' => $this->input->post('video_mime'),
//            'video_size' => $this->input->post('video_size'),
            'time_stamp' => time(),
            'is_publish' => $feed['is_publish']
        );
        $this->db->where("id", $id);
        $res = $this->db->update("enr_video", $post);
        return $res;
    }
    
    /**
    * feed poll
    */
    function addFeedPoll(){
        $post = $this->input->post();
        if(!$post['question'][0]){
            $this->controller->data['show_errors'] = "At least one question is required.";
            return false;
        }
        $pollSql = array(
            "cid" => $this->controller->chanel['id'],
            "fan_id" => "-1",
            "description" => $post['description'],
            "tags" => $post['tags'],
            "credit" => $post['credit'],
            "end_description" => $post['end_description'],
            "is_publish" => (isset($post['is_publish']) && $post['is_publish']=='on') ? 1 : 0,
            "time_stamp" => time()
        );
        if($_FILES['image1']['size']){
            $image_path = $this->_uploadPhoto('image1');
            $pollSql['image_path'] = $image_path;
        } 
        if($_FILES['image2']['size']){
            $end_image_path = $this->_uploadPhoto('image2');
            $pollSql['end_image_path'] = $end_image_path;
        } 
//        print_r($post);exit;
        $this->db->insert('enr_poll', $pollSql);
        $poll_id = $this->db->insert_id();
        foreach($post['question'] as $key => $question){
            if(!$question)
                continue;
            $pollQuestionSql = array(
                'poll_id' => $poll_id,
                'name' => $question
            );
            $this->db->insert('enr_poll_question', $pollQuestionSql);
            $question_id = $this->db->insert_id();
            foreach($post['answer'][$key] as $akey => $answer){
                if(!$answer)
                    continue;
                $pollAnswerSql = array(
                    'question_id' => $question_id,
                    'name' => $answer,
                    'is_correct' => (isset($post["is_correct_".$key."_".$akey]) && $post["is_correct_".$key."_".$akey]=='on') ? 1 : 0
                );
                $this->db->insert('enr_poll_answer', $pollAnswerSql);
            }
        }
        return true;
    }
    function getFeedPoll($id){
        $sql = "
            select *
            from enr_poll
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    function getFeedPollQuestion($id){
        $sql = "
            select *
            from enr_poll_question
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    function deleteFeedPoll($id){
        $oFeed = $this->getFeedPoll($id);
//        print_r($oFeed);exit;
        $image_path = UPLOAD_DIR.$oFeed['image_path'];
        if(is_file($image_path)){
            unlink($image_path);
        }
        $end_image_path = UPLOAD_DIR.$oFeed['end_image_path'];
        if(is_file($end_image_path)){
            unlink($end_image_path);
        }
        $this->db->where('id', $id);
        $this->db->delete("enr_poll");
        
        $this->_deleteQuestionAndAnswer($id);
   }
   
   private function _deleteQuestionAndAnswer($poll_id){
       $sql = "select * from enr_poll_question where poll_id='{$poll_id}'";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       foreach($rows as $row){
           $this->db->where('question_id', $row['id']);
           $this->db->delete("enr_poll_answer");
       }
        $this->db->where('poll_id', $poll_id);
        $this->db->delete("enr_poll_question");
   }
   
   function updateFeedPoll($id){
        $oFeed = $this->getFeedPoll($id);
        
        $post = $this->input->post();
        if(!$post['question'][0]){
            $this->controller->data['show_errors'] = "At least one question is required.";
            return false;
        }
        $pollSql = array(
            "cid" => $this->controller->chanel['id'],
            "fan_id" => "-1",
            "description" => $post['description'],
            "tags" => $post['tags'],
            "credit" => $post['credit'],
            "end_description" => $post['end_description'],
            "is_publish" => (isset($post['is_publish']) && $post['is_publish']=='on') ? 1 : 0,
            "time_stamp" => time()
        );
        if($_FILES['image1']['size']){
            $image_path = UPLOAD_DIR.$oFeed['image_path'];
            if(is_file($image_path)){
                unlink($image_path);
            }
            
            $image_path = $this->_uploadPhoto('image1');
            $pollSql['image_path'] = $image_path;
        } 
        if($_FILES['image2']['size']){
            $end_image_path = UPLOAD_DIR.$oFeed['end_image_path'];
            if(is_file($end_image_path)){
                unlink($end_image_path);
            }
            
            $end_image_path = $this->_uploadPhoto('image2');
            $pollSql['end_image_path'] = $end_image_path;
        } 
//        print_r($post);exit;
        $this->db->where('id', $id);
        $this->db->update('enr_poll', $pollSql);
        
        $this->_deleteQuestionAndAnswer($id);

        foreach($post['question'] as $key => $question){
            if(!$question)
                continue;
            $pollQuestionSql = array(
                'poll_id' => $id,
                'name' => $question
            );
            $this->db->insert('enr_poll_question', $pollQuestionSql);
            $question_id = $this->db->insert_id();
            foreach($post['answer'][$key] as $akey => $answer){
                if(!$answer)
                    continue;
                $pollAnswerSql = array(
                    'question_id' => $question_id,
                    'name' => $answer,
                    'is_correct' => (isset($post["is_correct_".$key."_".$akey]) && $post["is_correct_".$key."_".$akey]=='on') ? 1 : 0
                );
                $this->db->insert('enr_poll_answer', $pollAnswerSql);
            }
        }
        return true;
   }
   
   function getFeedPoll_question_answer($id){
       $sql = "
            select t1.* , t2.id question_id, t2.name question_name, t3.id answer_id, t3.name answer_name, t3.is_correct
            from 
                enr_poll t1 inner join enr_poll_question t2 on t1.id=t2.poll_id
                left join enr_poll_answer t3 on t2.id=t3.question_id
            where
                t1.id='{$id}'
            order by
                t2.id
       ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        
        $results = array();
        $result = array();
        $qid = 0;
        foreach($rows as $row){
            if($qid != $row['question_id']){
                if($result){
                    $results[] = $result;
                }
                $result = array();
                $result = $row;
//                $result['question_id'] = $row['question_id'];
//                $result['question_name'] = $row['question_name'];
                $result['answer'] = array();
            }
            $arr = array();
            $arr['name'] = $row['answer_name'];
            $arr['is_correct'] = $row['is_correct'];
            $result['answer'][] = $arr;
            $qid = $row['question_id'];
        }
        if($result){
            $results[] = $result;
        }
        return $results;
   }
    
    /**
    * feed quiz
    */
    function addFeedQuiz(){
        $post = $this->input->post();
        if(!$post['question'][0]){
            $this->controller->data['show_errors'] = "At least one question is required.";
            return false;
        }
        $pollSql = array(
            "cid" => $this->controller->chanel['id'],
            "fan_id" => "-1",
            "description" => $post['description'],
            "tags" => $post['tags'],
            "credit" => $post['credit'],
            "end_description" => $post['end_description'],
            "is_publish" => (isset($post['is_publish']) && $post['is_publish']=='on') ? 1 : 0,
            "time_stamp" => time()
        );
        if($_FILES['image1']['size']){
            $image_path = $this->_uploadPhoto('image1');
            $pollSql['image_path'] = $image_path;
        } 
        if($_FILES['image2']['size']){
            $end_image_path = $this->_uploadPhoto('image2');
            $pollSql['end_image_path'] = $end_image_path;
        } 
//        print_r($post);exit;
        $this->db->insert('enr_quiz', $pollSql);
        $poll_id = $this->db->insert_id();
        foreach($post['question'] as $key => $question){
            if(!$question)
                continue;
            $pollQuestionSql = array(
                'quiz_id' => $poll_id,
                'name' => $question
            );
            $this->db->insert('enr_quiz_question', $pollQuestionSql);
            $question_id = $this->db->insert_id();
            foreach($post['answer'][$key] as $akey => $answer){
                if(!$answer)
                    continue;
                $pollAnswerSql = array(
                    'question_id' => $question_id,
                    'name' => $answer,
                    'is_correct' => (isset($post["is_correct_".$key."_".$akey]) && $post["is_correct_".$key."_".$akey]=='on') ? 1 : 0
                );
                $this->db->insert('enr_quiz_answer', $pollAnswerSql);
            }
        }
        return true;
    }
    function getFeedQuiz($id){
        $sql = "
            select *
            from enr_quiz
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    function getFeedQuizQuestion($id){
        $sql = "
            select *
            from enr_quiz_question
            where
                id='{$id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    function deleteFeedQuiz($id){
        $oFeed = $this->getFeedQuiz($id);
//        print_r($oFeed);exit;
        $image_path = UPLOAD_DIR.$oFeed['image_path'];
        if(is_file($image_path)){
            unlink($image_path);
        }
        $end_image_path = UPLOAD_DIR.$oFeed['end_image_path'];
        if(is_file($end_image_path)){
            unlink($end_image_path);
        }
        $this->db->where('id', $id);
        $this->db->delete("enr_quiz");
        
        $this->_deleteQuizQuestionAndAnswer($id);
   }
   
   private function _deleteQuizQuestionAndAnswer($poll_id){
       $sql = "select * from enr_quiz_question where quiz_id='{$poll_id}'";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       foreach($rows as $row){
           $this->db->where('question_id', $row['id']);
           $this->db->delete("enr_quiz_answer");
       }
        $this->db->where('quiz_id', $poll_id);
        $this->db->delete("enr_quiz_question");
   }
   
   function updateFeedQuiz($id){
        $oFeed = $this->getFeedQuiz($id);
        
        $post = $this->input->post();
        if(!$post['question'][0]){
            $this->controller->data['show_errors'] = "At least one question is required.";
            return false;
        }
        $pollSql = array(
            "cid" => $this->controller->chanel['id'],
            "fan_id" => "-1",
            "description" => $post['description'],
            "tags" => $post['tags'],
            "credit" => $post['credit'],
            "end_description" => $post['end_description'],
            "is_publish" => (isset($post['is_publish']) && $post['is_publish']=='on') ? 1 : 0,
            "time_stamp" => time()
        );
        if($_FILES['image1']['size']){
            $image_path = UPLOAD_DIR.$oFeed['image_path'];
            if(is_file($image_path)){
                unlink($image_path);
            }
            
            $image_path = $this->_uploadPhoto('image1');
            $pollSql['image_path'] = $image_path;
        } 
        if($_FILES['image2']['size']){
            $end_image_path = UPLOAD_DIR.$oFeed['end_image_path'];
            if(is_file($end_image_path)){
                unlink($end_image_path);
            }
            
            $end_image_path = $this->_uploadPhoto('image2');
            $pollSql['end_image_path'] = $end_image_path;
        } 
//        print_r($post);exit;
        $this->db->where('id', $id);
        $this->db->update('enr_quiz', $pollSql);
        
        $this->_deleteQuizQuestionAndAnswer($id);

        foreach($post['question'] as $key => $question){
            if(!$question)
                continue;
            $pollQuestionSql = array(
                'quiz_id' => $id,
                'name' => $question
            );
            $this->db->insert('enr_quiz_question', $pollQuestionSql);
            $question_id = $this->db->insert_id();
            foreach($post['answer'][$key] as $akey => $answer){
                if(!$answer)
                    continue;
                $pollAnswerSql = array(
                    'question_id' => $question_id,
                    'name' => $answer,
                    'is_correct' => (isset($post["is_correct_".$key."_".$akey]) && $post["is_correct_".$key."_".$akey]=='on') ? 1 : 0
                );
                $this->db->insert('enr_quiz_answer', $pollAnswerSql);
            }
        }
        return true;
   }
   
   function getFeedQuiz_question_answer($id){
       $sql = "
            select t1.* , t2.id question_id, t2.name question_name, t3.id answer_id, t3.name answer_name, t3.is_correct
            from 
                enr_quiz t1 inner join enr_quiz_question t2 on t1.id=t2.quiz_id
                left join enr_quiz_answer t3 on t2.id=t3.question_id
            where
                t1.id='{$id}'
            order by
                t2.id
       ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        
        $results = array();
        $result = array();
        $qid = 0;
        foreach($rows as $row){
            if($qid != $row['question_id']){
                if($result){
                    $results[] = $result;
                }
                $result = array();
                $result = $row;
//                $result['question_id'] = $row['question_id'];
//                $result['question_name'] = $row['question_name'];
                $result['answer'] = array();
            }
            $arr = array();
            $arr['name'] = $row['answer_name'];
            $arr['is_correct'] = $row['is_correct'];
            $result['answer'][] = $arr;
            $qid = $row['question_id'];
        }
        if($result){
            $results[] = $result;
        }
        return $results;
   }
   /**
   * photo gallery
   */
   public function getPhotos(){
       $cid = $this->controller->chanel['id'];
       $sql = "select * 
            from enr_photo
            where cid='{$cid}'
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       foreach($rows as &$row){
           $row['destination'] = UPLOAD_URL.$row['destination'];
       }
       return $rows;
   }
   /**
   * video gallery
   */
   public function getVideos(){
       $cid = $this->controller->chanel['id'];
       $sql = "select * 
            from enr_video
            where cid='{$cid}'
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
   }
   public function getVideosByTags($id=false){
       if(!$id){
           $where = ' 1 ';
       }else{
           $where = " t1.tags in (select tags from enr_video where id='{$id}') ";
       }
       $cid = $this->controller->chanel['id'];
       $sql = "select t1.* 
            from enr_video t1 
            where t1.cid='{$cid}' and {$where}
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
   }
   public function getPhotosByTags($id=false){
       if(!$id){
           $where = ' 1 ';
       }else{
           $where = " t1.tags in (select tags from enr_photo where id='{$id}') ";
       }
       $cid = $this->controller->chanel['id'];
       $sql = "select t1.* 
            from enr_photo t1 
            where t1.cid='{$cid}' and {$where}
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
   }
    public function getVideosTagOtions($cid = false){
        $rows = $this->getVideos();
        $results = array(''=>'All');
        foreach($rows as $row){
            if(!$row['tags'] || in_array($row['tags'], $results) ){
                continue;
            }
            $results[$row['id']] = $row['tags'];
        }
        return $results;
    }
    public function getPhotoTagOtions($cid = false){
        $rows = $this->getPhotos();
        $results = array(''=>'All');
        foreach($rows as $row){
            if(!$row['tags'] || in_array($row['tags'], $results) ){
                continue;
            }
            $results[$row['id']] = $row['tags'];
        }
        return $results;
    }
   /**
   * community
   */
   public function getCommunityFeedList(){
        $sql = "select t1.*, t2.username username
            from 
                (
                    (
                        select concat('text_',id) id,'Text Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, fan_id, tags, description caption, time_stamp
                        from enr_text
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."' and fan_id>0
                    ) 
                    union
                    (
                        select concat('photo_',id) id,'Photo Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, destination image_path, fan_id, tags, description caption, time_stamp
                        from enr_photo
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."' and fan_id>0
                    )  
                    union
                    (
                        select concat('video_',id) id,'Video Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, image_path image_path, fan_id, tags, description caption, time_stamp
                        from enr_video
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."' and fan_id>0
                    )  
                    union
                    (
                        select concat('poll_',id) id,'Poll Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, fan_id, tags, description caption, time_stamp
                        from enr_poll
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."' and fan_id>0
                    )  
                    union
                    (
                        select concat('quiz_',id) id,'Quiz Post' post_type, date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') date, credit, is_publish, '' image_path, fan_id, tags, description caption, time_stamp
                        from enr_quiz
                        where 
                            /* is_publish=1 and */
                            cid='".$this->chanel['id']."' and fan_id>0
                    )  
                ) t1 inner join users t2 on t1.fan_id = t2.id
            order by t1.date desc
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    public function getTourdateList(){
       $cid = $this->controller->chanel['id'];
       $sql = "select *,date_format(FROM_UNIXTIME( start_time ) , '%Y-%m-%d') start_date ,date_format(FROM_UNIXTIME( end_time ) , '%Y-%m-%d') end_date
            from enr_event
            where cid='{$cid}'
            order by start_time desc, end_time desc
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
    }
    public function getTourdateListByDate($date=false, $searchvalue=''){
       if(!$date){
           $where = ' 1 ';
       }else{
           $where = " start_time ='{$date}' ";
       }
       if($searchvalue){
           $where .= " and title like '%{$searchvalue}%' ";
       }
       $cid = $this->controller->chanel['id'];
       $sql = "select *,date_format(FROM_UNIXTIME( start_time ) , '%Y-%m-%d') start_date ,date_format(FROM_UNIXTIME( end_time ) , '%Y-%m-%d') end_date
            from enr_event  
            where cid='{$cid}' and {$where}
            order by start_time desc, end_time desc
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;


    }
    public function getEventdateOptions($cid = false){
        $rows = $this->getTourdateList();
        $results = array(''=>'All');
        foreach($rows as $row){
            if(!$row['start_time'] || in_array($row['start_time'], $results) ){
                continue;
            }
            $results[$row['start_time']] = $row['start_date'];
        }
        return $results;
    }
    
    public function getTourdate($id){
       $sql = "select *,date_format(FROM_UNIXTIME( start_time ) , '%Y-%m-%d') start_date ,date_format(FROM_UNIXTIME( end_time ) , '%Y-%m-%d') end_date, date_format(FROM_UNIXTIME( concert_time ) , '%Y-%m-%d') concert_date
            from enr_event
            where id='{$id}'
       ";
       $query = $this->db->query($sql);
       $row = $query->row_array();
       return $row;
    }

    public function addTourdate($post){
        $concert_time = strtotime($post['concert_date']);
        $start_time = strtotime($post['start_date']);
        $end_time = strtotime($post['end_date']);
        if($start_time > $end_time){
            $this->controller->data['show_errors'][] = "End date can't be before the start date.";
            return false;
        }
        $sql = array(
            "cid" => $post['cid'],
            "title" => $post['title'],
            "position" => $post['position'],
            "concert_time" => $concert_time,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "price" => $post['price'],
            "url" => $post['url'],
        );
        $this->db->insert("enr_event", $sql);
        return true;
    }
    
    public function updateTourdate($post){
        $concert_time = strtotime($post['concert_date']);
        $start_time = strtotime($post['start_date']);
        $end_time = strtotime($post['end_date']);
        if($start_time > $end_time){
            $this->controller->data['show_errors'][] = "End date can't be before the start date.";
            return false;
        }
        $sql = array(
            "cid" => $post['cid'],
            "title" => $post['title'],
            "position" => $post['position'],
            "concert_time" => $concert_time,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "price" => $post['price'],
            "url" => $post['url'],
        );
        $this->db->where("id", $post['id']);
        $this->db->update("enr_event", $sql);
        return true;
    }
    
    public function getMusicList(){
       $cid = $this->controller->chanel['id'];
       $sql = "select *
            from enr_music
            where cid='{$cid}'
            order by time_stamp desc
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
    }
    
    public function getMusic($id){
       $sql = "select *
            from enr_music
            where id='{$id}'
       ";
       $query = $this->db->query($sql);
       $row = $query->row_array();
       return $row;
    }
    
    public function addMusic($post){
        if($filepath = $this->_uploadPhoto()){
            $post = array(
                'cid' => $post['cid'],
                'fan_id' => "-1",
                'title' => $post['title'],
                'album' => $post['album'],
                'destination' => $_POST['destination'],
                'music_size' => $_POST['music_size'],
                'url' => $post['url'],
                'image_path' => $filepath,
                'time_stamp' => time(),
                'is_publish' => $post['is_publish'] == "on" ? 1 : 0
            );
            $res = $this->db->insert("enr_music", $post);
            return $res;
        }
        return false;
     }
    
    public function updateMusic($post){
        $id = $post['id'];
        $music = $this->getMusic($id);
        if($_FILES['image']['size']){
            $destination = UPLOAD_DIR.$music['image_path'];
            if(is_file($destination)){
                unlink($destination);
            }
            $filepath = $this->_uploadPhoto();
            if(!$filepath){
                return false;
            }
        }else{
            $filepath = $music['image_path'];
        }
        $post = array(
            'cid' => $post['cid'],
            'fan_id' => "-1",
            'title' => $post['title'],
            'album' => $post['album'],
            'destination' => $_POST['destination'],
            'music_size' => $_POST['music_size'],
            'url' => $post['url'],
            'image_path' => $filepath,
            'time_stamp' => time(),
            'is_publish' => $post['is_publish']
        );
        $this->db->where("id", $id);
        $res = $this->db->update("enr_music", $post);
        return $res;
    }
    /**
    * category
    */
    public function getCategoryList($id = false){
        $cid = $this->controller->chanel['id'];
        if ($id === false){
            $sql = "select *
                from enr_category
                where cid='{$cid}'
            ";
            $query = $this->db->query($sql);
            $rows = $query->result_array();
            return $rows;
        }else{
            $sql = "select *
                from enr_category
                where id='{$id}'
            ";
            $query = $this->db->query($sql);
            $row = $query->row_array();
            return $row;
        }
    }
    public function addCategory($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->insert("enr_category", $sql);
        return true;
    }
    
    public function updateCategory($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->where("id", $post['id']);
        $this->db->update("enr_category", $sql);
        return true;
    }
    /**
    * color
    */
    public function getColorList($id = false){
        $cid = $this->controller->chanel['id'];
        if ($id === false){
            $sql = "select *
                from enr_color
                where cid='{$cid}'
            ";
            $query = $this->db->query($sql);
            $rows = $query->result_array();
            return $rows;
        }else{
            $sql = "select *
                from enr_color
                where id='{$id}'
            ";
            $query = $this->db->query($sql);
            $row = $query->row_array();
            return $row;
        }
    }
    public function addColor($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->insert("enr_color", $sql);
        return true;
    }
    
    public function updateColor($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->where("id", $post['id']);
        $this->db->update("enr_color", $sql);
        return true;
    }
    /**
    * Size
    */
    public function getSizeList($id = false){
        $cid = $this->controller->chanel['id'];
        if ($id === false){
            $sql = "select *
                from enr_size
                where cid='{$cid}'
            ";
            $query = $this->db->query($sql);
            $rows = $query->result_array();
            return $rows;
        }else{
            $sql = "select *
                from enr_size
                where id='{$id}'
            ";
            $query = $this->db->query($sql);
            $row = $query->row_array();
            return $row;
        }
    }
    public function addSize($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->insert("enr_size", $sql);
        return true;
    }
    
    public function updateSize($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            "name" => $post['name']
        );
        $this->db->where("id", $post['id']);
        $this->db->update("enr_size", $sql);
        return true;
    }
    /**
    * attribute
    */
    public function getCategoryOptions($cid = false){
        $cid===false && $cid = $this->controller->chanel['id'];
        $sql = "select *
            from enr_category
            where cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        $results = array();
        foreach($rows as $row){
            $results[$row['name']] = $row['name'];
        }
        return $results;
    }
    public function getColorOptions($cid = false){
        $cid===false && $cid = $this->controller->chanel['id'];
        $sql = "select *
            from enr_color
            where cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        $results = array();
        foreach($rows as $row){
            $results[$row['name']] = $row['name'];
        }
        return $results;
    }
    public function getSizeOptions($cid = false){
        $cid===false && $cid = $this->controller->chanel['id'];
        $sql = "select *
            from enr_size
            where cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        $results = array();
        foreach($rows as $row){
            $results[$row['name']] = $row['name'];
        }
        return $results;
    }
    /**
    * Product
    */
    public function getProductList($id = false){
        $cid = $this->controller->chanel['id'];
        if ($id === false){
            $sql = "select *
                from enr_product
                where cid='{$cid}'
            ";
            $query = $this->db->query($sql);
            $rows = $query->result_array();
            return $rows;
        }else{
            $sql = "select *
                from enr_product
                where id='{$id}'
            ";
            $query = $this->db->query($sql);
            $row = $query->row_array();
            return $row;                                        
        }
    }
    public function addProduct($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            'title' => $post['title'],
            'category' => $post['category'],
            'color' => $post['color'],
            'size' => $post['size'],
            'tags' => $post['tags'],
            'price' => $post['price'],
            'is_publish' => $post['is_publish'],
            'images' => serialize($post['images'])
        );
        $this->db->insert("enr_product", $sql);
        return true;
    }
    
    public function updateProduct($post){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            "cid" => $cid,
            'title' => $post['title'],
            'category' => $post['category'],
            'color' => $post['color'],
            'size' => $post['size'],
            'tags' => $post['tags'],
            'price' => $post['price'],
            'is_publish' => $post['is_publish'],
            'images' => serialize($post['images'])
        );
        $this->db->where("id", $post['id']);
        $this->db->update("enr_product", $sql);
        return true;
    }
    public function uploadTempVideo(){
        $path = $this->_uploadVideo('video');
        return $path;
    }
    public function uploadTempMusic(){
        $path = $this->_uploadMusic('music');
        return $path;
    }
    public function uploadTempPhoto(){
        $path = $this->_uploadPhoto('image');
        return $path;
    }
    public function getFans($conds=false){
        if(!$conds){
            $where = "";
        }else{
            $where = "";
            foreach($conds as $key=>$value){
                $where .= " and {$key}='{$value}'";
            }
        }
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *,date_format(FROM_UNIXTIME( last_login  ) , '%Y-%m-%d') last_login, date_format(FROM_UNIXTIME( joined ) , '%Y-%m-%d') join_date
            from enr_fan
            where cid='{$cid}' {$where}
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    public function getDeactiveFans(){
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *,date_format(FROM_UNIXTIME( last_login  ) , '%Y-%m-%d') last_login, date_format(FROM_UNIXTIME( joined ) , '%Y-%m-%d') join_date
            from enr_fan
            where cid='{$cid}' and (is_block=1 or is_suspend=1)
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    public function getUsers($conds=false){
        if(!$conds){
            $where = "";
        }else{
            $where = "";
            foreach($conds as $key=>$value){
                $where .= " and {$key}='{$value}'";
            }
        }
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *
            from users
            where cid='{$cid}' {$where}
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    public function getDeactiveUsers(){
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *
            from users
            where cid='{$cid}' and (active=0 or suspend=1) and fan=1
        ";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    public function checkFanUser($user_id){
        $sql = "
            select *
            from enr_fan
            where id='{$user_id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        if($row && $row['is_block'] == 0 && $row['is_suspend'] == 0){
            $res = true;
        }else{
            $res = false;
        }
        return $res;
    }
    
    public function getChanelOptions(){
        $chanels = $this->getChanels();
        $result = array();
        foreach($chanels as $chanel){
            $result[$chanel->id] = $chanel->name;
        }
        return $result;
    }
    
    public function getDefaultSections(){
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *
            from enr_section
            where 
                id not in (select section_id from enr_submenu where cid='{$cid}')
            order by 
                ordering
        ";
        
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        return $rows;
    }
    
    public function getSubmenuInfo($id){
        $id = $this->db->escape_str($id);
        $cid = $this->controller->chanel['id'];
        $strSql = "
            SELECT t1.*, if(t1.alter_name<>'',t1.alter_name,t2.name) alter_name
            FROM enr_submenu t1 inner join enr_section t2 on t1.section_id=t2.id
            where 
                t1.id={$id} 
        ";
        $query = $this->db->query($strSql);
        $row = $query->row_array();
        return $row;
    }
    public function _reOrderingSubmenu($id, $ordering){
        if(!$id)
            return false;
        $cid = $this->controller->chanel['id'];
        $sql = "
            select *
            from enr_submenu
            where 
                cid='{$cid}'
            order by 
                ordering
        ";
        
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        $i = 1;
        foreach($rows as $key=>$row){
            if($id == $row['id'])
                continue;
            if($ordering == $i)
                $i++;
            $this->db->where('id', $row['id']);
            $this->db->update('enr_submenu', array('ordering'=>$i));
            $i++;
        }
        
        $this->db->where('id', $id);
        !$ordering && $ordering=count($rows);
        $this->db->update('enr_submenu', array('ordering'=>$ordering));
        return true;
        
    }
    
    public function getDesignInfo(){
        $cid = $this->controller->chanel['id'];
        $strSql = "
            SELECT *
            FROM enr_design 
            where 
                cid='{$cid}'
        ";
        $query = $this->db->query($strSql);
        $row = $query->row_array();
        return $row;
    }
    
    public function saveDesignInfo($infos){
        $cid = $this->controller->chanel['id'];
        $sql = array(
            'cid' => $cid,
            'link_color' => $infos['link_color'],
            'button_color' => $infos['button_color'],
            'back_color' => $infos['back_color'],
            'text_color' => $infos['text_color']
        );
        $row = $this->getDesignInfo();
        if($row){
            $this->db->where('cid', $cid);
            $this->db->update('enr_design', $sql);
        }else{
            $this->db->insert('enr_design', $sql);
        }
        if(isset($_FILES['image1']) && $_FILES['image1']['size'] && $path = $this->_uploadPhoto('image1')){
            $this->db->where('cid', $cid);
            $this->db->update('enr_design', array('header_image'=>$path));
        }
        if(isset($_FILES['image2']) && $_FILES['image2']['size'] && $path = $this->_uploadPhoto('image2')){
            $this->db->where('cid', $cid);
            $this->db->update('enr_design', array('watermark'=>$path));
        }
        return true;
    }
    
    public function delete_header_image(){
        $cid = $this->controller->chanel['id'];
        $row = $this->getDesignInfo();
        if(is_file(UPLOAD_DIR.$row['header_image'])){
            unlink(UPLOAD_DIR.$row['header_image']);
        }
        $this->db->where('cid', $cid);
        $this->db->update('enr_design', array('header_image'=>''));
    }
    
    public function delete_watermark_image(){
        $cid = $this->controller->chanel['id'];
        $row = $this->getDesignInfo();
        if(is_file(UPLOAD_DIR.$row['watermark'])){
            unlink(UPLOAD_DIR.$row['watermark']);
        }
        $this->db->where('cid', $cid);
        $this->db->update('enr_design', array('watermark'=>''));
    }
    
    public function getCurrencyInfo(){
        $cid = $this->controller->chanel['id'];
        $strSql = "
            SELECT *
            FROM enr_currency 
            where 
                cid='{$cid}'
            order by
                id
        ";
        $query = $this->db->query($strSql);
        $rows = $query->result_array();
        return $rows;
    }
    public function saveCurrencyInfo($post){
        $cid = $this->controller->chanel['id'];
        $this->db->where('cid', $cid);
        $this->db->delete('enr_currency');
        foreach($post['package'] as $key=>$package){
            $sql = array(
                'cid' => $cid,
                'package' => $package,
                'cost' => $post['cost'][$key],
                'credit' => $post['credit'][$key]
            );
            $this->db->insert('enr_currency', $sql);
        }
        return true;
    }
    
    public function getSocial($cid){
        $sql = "
            select *
            from  enr_social
            where 
                cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    
    public function getSocialAccount($social_type, $cid){
        $sql = "
            select *
            from enr_social_account
            where
                social_type = '{$social_type}' and cid='{$cid}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row;
    }
    public function updateSocialAccount($post){
        
        $this->db->where('cid', $post['cid']);
        $this->db->where('social_type', $post['social_type']);
        $this->db->delete('enr_social_account');
        $sql = array(
            'cid' => $post['cid'],
            'social_type' => $post['social_type'],
            'username' => $post['username'],
            'password' => $post['password']
        );
        $this->db->insert('enr_social_account', $sql);
        return true;
    }
    
    public function getStatsPost($start_time, $end_time, $cid){
        if(!$start_time || !$end_time){
            return false;
        }
        $where = " time_stamp>{$start_time} and time_stamp<{$end_time} ";
        $sql = "
            select count(t1.id) stat_cnt, date_format(FROM_UNIXTIME( t1.time_stamp ) , '%Y-%m-%d') stat_date
            from 
                (
                    (
                        select concat('text_',id) id,'Text Post' post_type, time_stamp 
                        from enr_text
                        where 
                            /* is_publish=1 and */
                            cid='".$cid."' and {$where}
                    ) 
                    union
                    ( 
                        select concat('photo_',id) id,'Photo Post' post_type, time_stamp 
                        from enr_photo
                        where 
                            /* is_publish=1 and */
                            cid='".$cid."' and {$where}
                    )  
                    union
                    (
                        select concat('video_',id) id,'Video Post' post_type, time_stamp
                        from enr_video
                        where 
                            /* is_publish=1 and */
                            cid='".$cid."' and {$where}
                    )  
                    union
                    (
                        select concat('poll_',id) id,'Poll Post' post_type, time_stamp 
                        from enr_poll 
                        where 
                            /* is_publish=1 and */
                            cid='".$cid."' and {$where}
                    )  
                    union
                    (
                        select concat('quiz_',id) id,'Quiz Post' post_type, time_stamp 
                        from enr_quiz
                        where 
                            /* is_publish=1 and */
                            cid='".$cid."' and {$where}
                    )  
                ) t1
            group by
                date_format(FROM_UNIXTIME( t1.time_stamp ) , '%Y-%m-%d') 
            order by
                date_format(FROM_UNIXTIME( t1.time_stamp ) , '%Y-%m-%d') 
        ";
        $query = $this->db->query($sql);
        $rows = $query->result();
        return $rows;
    }
    public function getStatsGender($start_time, $end_time, $cid){
        if($start_time && $end_time){
            $where = " and joined>{$start_time} and joined<{$end_time} ";
        }
        $sql = "
            select count(id) stat_cnt, if(gender, 'Female', 'Male') stat_label
            from  enr_fan
            where
                cid='{$cid}' {$where}
            group by
                gender
        ";
        $query = $this->db->query($sql);
        $rows = $query->result();
        return $rows;
    }
    public function getStatsAge($start_time, $end_time, $cid){
        if($start_time && $end_time){
            $where = " and joined>{$start_time} and joined<{$end_time} ";
        }
        $year = date('Y');
        $sql = "
            select count(id) stat_cnt, ({$year}-YEAR(birthday)) stat_label
            from  enr_fan
            where 
                cid='{$cid}' {$where}
            group by
                YEAR(birthday)
        ";
        $query = $this->db->query($sql);
        $rows = $query->result();
        return $rows;
    }
    public function getStatsPurchase($start_time, $end_time, $cid){
        if(!$start_time || !$end_time){
            $where = " and time_stamp>{$start_time} and time_stamp<{$end_time} ";
        }
        $sql = "
            select sum(value) stat_value, date_format(FROM_UNIXTIME( time_stamp ) , '%m/%d') stat_label
            from  enr_purchase
            where 
                cid='{$cid}' {$where}
            group by
                date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d') 
            order by
                date_format(FROM_UNIXTIME( time_stamp ) , '%Y-%m-%d')
        ";
        $query = $this->db->query($sql);
        $rows = $query->result();
        return $rows;
    }
}

class Watermark {
        
        /**
         *
         * Erros
         * @var array
         */
        public $errors = array();

        /**
         *
         * Image Source
         * @var img
         */
        private $imgSource = null;

        /**
         *
         * Image Watermark
         * @var img
         */
        private $imgWatermark = null;

        /**
         *
         * Positions watermark
         * 0: Centered
         * 1: Top Left
         * 2: Top Right
         * 3: Footer Right
         * 4: Footer left
         * 5: Top Centered
         * 6: Center Right
         * 7: Footer Centered
         * 8: Center Left
         * @var number
         */
        private $watermarkPosition = 0;
        
        /**
         *
         * Check PHP GD is enabled
         */
        public function __construct(){
                if(!function_exists("imagecreatetruecolor")){
                        if(!function_exists("imagecreate")){
                                $this->error[] = "You do not have the GD library loaded in PHP!";
                        }
                }
        }

        /**
         *
         * Get function name for use in apply
         * @param string $name Image Name
         * @param string $action |open|save|
         */
        private function getFunction($name, $action = 'open') {
                if(preg_match("/^(.*)\.(jpeg|jpg)$/", $name)){
                        if($action == "open")
                                return "imagecreatefromjpeg";
                        else
                                return "imagejpeg";
                }elseif(preg_match("/^(.*)\.(png)$/", $name)){
                        if($action == "open")
                                return "imagecreatefrompng";
                        else
                                return "imagepng";
                }elseif(preg_match("/^(.*)\.(gif)$/", $name)){
                        if($action == "open")
                                return "imagecreatefromgit";
                        else
                                return "imagegif";
                }else{
                        $this->error[] = "Image Format Invalid!";
                }
        }

        /**
         *
         * Get image sizes
         * @param object $img Image Object
         */
        public function getImgSizes($img){
                return array('width' => imagesx($img), 'height' => imagesy($img));
        }

        /**
         * Get positions for use in apply
         * Enter description here ...
         */
        public function getPositions(){
                $imgSource = $this->getImgSizes($this->imgSource);
                $imgWatermark = $this->getImgSizes($this->imgWatermark);
                $positionX = 0;
                $positionY = 0;

                # Centered
                if($this->watermarkPosition == 0){
                        $positionX = ( $imgSource['width'] / 2 ) - ( $imgWatermark['width'] / 2 );
                        $positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
                }

                # Top Left
                if($this->watermarkPosition == 1){
                        $positionX = 0;
                        $positionY = 0;
                }

                # Top Right
                if($this->watermarkPosition == 2){
                        $positionX = $imgSource['width'] - $imgWatermark['width'];
                        $positionY = 0;
                }

                # Footer Right
                if($this->watermarkPosition == 3){
                        $positionX = ($imgSource['width'] - $imgWatermark['width']) - 5;
                        $positionY = ($imgSource['height'] - $imgWatermark['height']) - 5;
                }

                # Footer left
                if($this->watermarkPosition == 4){
                        $positionX = 0;
                        $positionY = $imgSource['height'] - $imgWatermark['height'];
                }

                # Top Centered
                if($this->watermarkPosition == 5){
                        $positionX = ( ( $imgSource['height'] - $imgWatermark['width'] ) / 2 );
                        $positionY = 0;
                }

                # Center Right
                if($this->watermarkPosition == 6){
                        $positionX = $imgSource['width'] - $imgWatermark['width'];
                        $positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
                }

                # Footer Centered
                if($this->watermarkPosition == 7){
                        $positionX = ( ( $imgSource['width'] - $imgWatermark['width'] ) / 2 );
                        $positionY = $imgSource['height'] - $imgWatermark['height'];
                }

                # Center Left
                if($this->watermarkPosition == 8){
                        $positionX = 0;
                        $positionY = ( $imgSource['height'] / 2 ) - ( $imgWatermark['height'] / 2 );
                }
                
                if(is_array($this->watermarkPosition)){
                    $positionX = $this->watermarkPosition['x'];
                    $positionY = $this->watermarkPosition['y'];
                }

                return array('x' => $positionX, 'y' => $positionY);
        }

        /**
         *
         * Apply watermark in image
         * @param string $imgSource Name image source
         * @param string $imgTarget Name image target
         * @param string $imgWatermark Name image watermark
         * @param number $position Position watermark
         */
        public function apply($imgSource, $imgTarget, $imgWatermark, $position = 0){
                # Set watermark position
                $this->watermarkPosition = $position;

                # Get function name to use for create image
                $functionSource = $this->getFunction($imgSource, 'open');
                $this->imgSource = $functionSource($imgSource);

                # Get function name to use for create image
                $functionWatermark = $this->getFunction($imgWatermark, 'open');
                $this->imgWatermark = $functionWatermark($imgWatermark);
                
                # Get watermark images size
                $sizesWatermark = $this->getImgSizes($this->imgWatermark);

                # Get watermark position
                $positions = $this->getPositions();

                # Apply watermark
                imagecopy($this->imgSource, $this->imgWatermark, $positions['x'], $positions['y'], 0, 0, $sizesWatermark['width'], $sizesWatermark['height']);

                # Get function name to use for save image
                $functionTarget = $this->getFunction($imgTarget, 'save');

                # Save image
                $functionTarget($this->imgSource, $imgTarget, 100);

                # Destroy temp images
                imagedestroy($this->imgSource);
                imagedestroy($this->imgWatermark);
        }
}
