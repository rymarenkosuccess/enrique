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
    function getChanels(){
        $sql = "select t1.*, t2.username chanel_admin from enr_chanel t1 left join users t2 on t1.user_id=t2.id
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
        
        $res = $this->db->insert('enr_chanel', array('name'=>$chanel['name'], 'url'=>$chanel['url'], 'is_publish'=>$chanel['is_publish'], 'user_id'=>$user_id, 'image_path'=>$image_path, 'image_mime'=>$image_mime));
        $cid = $this->db->insert_id();
        if($chanel['chanel_admin']){
            $additional_data = array(
                "cid" => $cid
            );
            $user_id = $this->controller->ion_auth->register($chanel['chanel_admin'], $chanel['password'], $chanel['chanel_admin'], $additional_data);
        }else{
            $user_id = 0;
        }
        return $res;
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
            if(!$user_id){
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
            select t1.*, t2.name name, t2.url url
            from enr_submenu t1, enr_section t2
            where 
                t1.cid='{$chanel}' and t1.section_id=t2.id
            order by 
                t2.ordering
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
        if($filepath = $this->_uploadPhoto()){
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
        if(strpos("jpg,png", $ext) === false){
            $this->controller->data['show_errors'][] = "Only jpg, png files can be uploaded.";
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
        return str_replace(UPLOAD_DIR, "", $filepath);
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
    
    function addFeedVideo($feed){
        $image_path = $this->_uploadPhoto_video();
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
        if(strpos("flv,mpg,mp4", $ext) === false){
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
        if(strpos("jpeg,png", $ext) === false){
            $this->controller->data['show_errors'][] = "Only jpg, png files can be uploaded.";
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
        return str_replace(UPLOAD_DIR, "", $filepath);
    }
    function updateFeedVideo($feed){
        $id = ltrim($feed['feed_id'], "video_");
        $oFeed = $this->getFeedVideo($id);
//        if(!$image_path){
//            $image_path = $oFeed['image_path'];
//        }
        if(strpos($this->input->post('video_img_value'), 'data:image')!==false){
            $image_path = $this->_uploadPhoto_video();
            if(!$image_path){
                return false;
            }
            $oimage_path = UPLOAD_DIR.$oFeed['image_path'];
            if(is_file($oimage_path)){
                unlink($oimage_path);
            }
        }else{
            $image_path = $oFeed['image_path'];
        }

//        if($_FILES['video']['size']){
//            $destination = UPLOAD_DIR.$oFeed['destination'];
//            if(is_file($destination)){
//                unlink($destination);
//            }
//            $destination = $this->_uploadVideo();
//            if(!$destination){
//                return false;
//            }
//        }else{
//            $destination = $oFeed['destination'];
//        }
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
       ";
       $query = $this->db->query($sql);
       $rows = $query->result_array();
       return $rows;
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
    public function uploadTempPhoto(){
        $path = $this->_uploadPhoto('image');
        return $path;
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
    
    public function checkActiveUser($user_id){
        $sql = "
            select *
            from users
            where id='{$user_id}'
        ";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        if($row && $row['active'] == 1 && $row['suspend'] == 0){
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
    
	
}