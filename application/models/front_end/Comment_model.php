<?php  
    /**
    * comment_model class is model of comment controller it performs 
    * basic CRUD operations
    * Methods: insert_comment
    *          update_comment
    *          delete_comment
    *          get_comment_by_id
    *          get_attachments
    *          record_count
    *          fetch_comments
    */

	class Comment_model extends CI_Model {

        private $name,
                $political_party_id,
                $halqa_id,
                $user_designation_id,
                $introduction,
                $election_history,
                $user_id,
                $action,
                $count,
                $opposite_action,
                $main_entity_id,
                $action_field,
                $opposite_action_field;

        public function __construct()
        {
            parent::__construct();        	
            $this->load->model('admin/political_party_model');
            $this->load->model('admin/halqa_model');
            $this->load->model('admin/post_model');
        }

        /**
         * [insert_like_dislike: Inserts a comment record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function like_action_exists($opposite_action = '')
        {   
            unset($q);     
                
            $this->db->select($this->table.'.likes');        

            $this->db->where(array('id' => $this->comment_reply_id)); 
            
            $q = $this->db->get($this->table); 

            $result = $q->result_array()[0];

            $this->count = $result['likes'];
            
            if($this->count <= 0)
            {
                $this->count = 0;
            }   
            else
            {
                $this->count--;
            }

            $data = array(

                'likes' => $this->count

            );
    
            $this->db->where('id', $this->comment_reply_id);

            if ($this->db->update($this->table, $data)) 
            {
                $this->delete_comment($this->like_table, $this->user_id ,$this->comment_reply_id);
            }
        }
        
        /**
         * [insert_like_dislike: Inserts a comment record into the databasee]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_comment_reply_like()
        {
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $this->comment_reply_id = strtolower(trim($this->db->escape($this->input->post('comment_reply_id')), "' "));
            $this->entity = strtolower(trim($this->db->escape($this->input->post('entity')), "' "));
            $this->table = ($this->entity == 'comment') ? 'story_comment' : 'story_reply';
            $this->like_table = $this->entity == 'comment' ? 'story_comment_like' : 'story_reply_like';
            $action_query = $this->db->get_where($this->like_table, array('user_id' => $this->user_id, 
                                                $this->entity . '_id' => $this->comment_reply_id)); 

            if($action_query->num_rows() > 0)
            {
                $this->like_action_exists();
                echo json_encode(array('already_liked'  => 'already_liked_' . $this->count));
                return TRUE;
            }
            else
            {
                if($this->insert_like_details())
                {
                    echo json_encode(array('like' => 'like_' . $this->count));
                }
            }
        }

        /**
         * [insert_like_dislike: Inserts a comment record into the databasee]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_column_comment_reply_like()
        {
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $this->comment_reply_id = strtolower(trim($this->db->escape($this->input->post('comment_reply_id')), "' "));
            $this->entity = strtolower(trim($this->db->escape($this->input->post('entity')), "' "));
            $this->table = ($this->entity == 'comment') ? 'column_comment' : 'column_reply';
            $this->like_table = $this->entity == 'comment' ? 'column_comment_like' : 'column_reply_like';
            $action_query = $this->db->get_where($this->like_table, array('user_id' => $this->user_id, 
                                                $this->entity . '_id' => $this->comment_reply_id)); 

            if($action_query->num_rows() > 0)
            {
                $this->like_action_exists();
                echo json_encode(array('already_liked'  => 'already_liked_' . $this->count));
                return TRUE;
            }
            else
            {
                if($this->insert_like_details())
                {
                    echo json_encode(array('like' => 'like_' . $this->count));
                }
            }
        }

        /**
         * [insert_like_dislike: Inserts a comment record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_details($action = '', $action_field = '')
        {
            unset($q);

            $this->action = empty($action) ? $this->action : $action;

            $this->action_field = empty($action_field) ? $this->action_field : $action_field;

            $this->db->select($this->table . '.likes');

            $this->db->where(array('id' => $this->comment_reply_id)); 
            
            $q = $this->db->get($this->table); 
            $result = $q->result_array()[0];

            $this->count = $result['likes'];
            
            $this->count++;

            $data = array(

                'likes' => $this->count

            );

            $this->db->where('id', $this->comment_reply_id);

            if ($this->db->update($this->table, $data)) 
            {        
                unset($data);         
                $data = array(

                    'user_id' => $this->user_id,

                    $this->entity . '_id' => $this->comment_reply_id,
                                        
                );

                if($this->db->insert($this->like_table, $data))
                {
                    return TRUE;
                }
                
                return FALSE;
            }
        }

        public function get_column_comment_likes()
        {            
            $column_id = trim($this->db->escape($this->input->post('column_id')), "' ");
            $user_id = trim($this->db->escape($this->input->post('user_id')), "' ");
            
            $this->db->select('`column_comment`.`id`');

            $this->db->from('`column_comment`');
               
            $this->db->where(array('`column_comment`.`column_id`' => $column_id, '`column_comment_like`.`user_id`' => $user_id));

            $this->db->join('`column_comment_like`', '`column_comment`.`id` = `column_comment_like`.`comment_id`', 'inner');

            $query = $this->db->get();

            if ($query->num_rows() > 0)
            {
                $result['comment_likes_db'] = $query->result_array();
                $result['reply_likes_db'] = $this->get_column_reply_likes($column_id, $user_id);
                echo json_encode($result);
            }
        }

        public function get_comment_likes()
        {            
            $post_id = trim($this->db->escape($this->input->post('post_id')), "' ");
            $user_id = trim($this->db->escape($this->input->post('user_id')), "' ");
            
            $this->db->select('`story_comment`.`id`');

            $this->db->from('`story_comment`');
               
            $this->db->where(array('`story_comment`.`story_id`' => $post_id, '`story_comment_like`.`user_id`' => $user_id));

            $this->db->join('`story_comment_like`', '`story_comment`.`id` = `story_comment_like`.`comment_id`', 'inner');

            $query = $this->db->get();

            if ($query->num_rows() > 0)
            {
                $result['comment_likes_db'] = $query->result_array();
                $result['reply_likes_db'] = $this->get_reply_likes($post_id, $user_id);
                echo json_encode($result);
            }
        }

        public function get_column_reply_likes($column_id, $user_id)
        {                  
            $this->db->select('column_reply.id');

            $this->db->from('column_reply');
               
            $this->db->where(array('column_reply_like.user_id' => $user_id));

            $this->db->join('column_reply_like', 'column_reply.id = column_reply_like.reply_id', 'inner');

            $query = $this->db->get();

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
        }

        public function get_reply_likes($post_id, $user_id)
        {                  
            $this->db->select('story_reply.id');

            $this->db->from('story_reply');
               
            $this->db->where(array('story_reply_like.user_id' => $user_id));

            $this->db->join('story_reply_like', 'story_reply.id = story_reply_like.reply_id', 'inner');

            $query = $this->db->get();

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
        }

        public function get_attachments($attachments, $attach_comment_details = TRUE)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                // TRUE is passed 
                $attachments[$i]['political_party_id'] = $this->political_party_model->get_political_party_by_id($attachments[$i]['political_party_id'], TRUE); 
            }

            if($attach_comment_details)
            {
                //$result['specialization'] = $this->get_doctor_specialization($id);
                for ($i=0, $count = count($attachments); $i < $count; $i++) 
                { 
                    if($attachments[$i])
                    {
                        $attachments[$i]['comment_details'] = $this->get_comment_details($attachments[$i]['id']);
                    }
                }
            }

            return $attachments;
        }

        public function fetch_comments($limit, $start, $attach_specialization = TRUE) 
        {
            $this->db->select('*');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('comment');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $this->get_attachments($result); 
                return $result;
            }

            return FALSE;
        } 

        public function get_comments($entity_id, $entity = '')
        {
            if($entity == '')
            {
                $featured_post = $entity_id;
            }
            else
            {
                $featured_post = $this->post_model->get_featured_post($entity_id, $entity, TRUE);
                $featured_post = array_column($featured_post, 'id');            
            }
            if(!empty($featured_post))
            {
                // $featured_post = $featured_post[0];

                $this->db->select('story_comment.id, story_comment.likes AS comment_likes, story_comment.comment_by, story_comment.comment, story_comment.comment_date, story_comment.comment_time,
                story_comment.comment_edit_date, story_comment.is_edited, story_comment.comment_edit_time, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');

                $this->db->from('story_comment');
                   
                $this->db->where('story_comment.story_id', $featured_post);

                $this->db->order_by('`story_comment.comment_time`, `story_comment.comment_date`, `story_comment.comment_edit_time`, `story_comment.comment_edit_date`', 'desc');

                $this->db->join('user', 'story_comment.comment_by = user.id', 'inner');

                $query = $this->db->get();

                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $this->comment_replys_attachments($result); 
                    return $result;
                }
                else
                {
                    return array();
                }
            }
            return FALSE;
        }

        public function get_column_comments($column_id)
        {
            $this->db->select('column_comment.id, column_comment.likes AS comment_likes, column_comment.comment_by, column_comment.comment, column_comment.comment_date, column_comment.comment_time,
            column_comment.comment_edit_date, column_comment.is_edited, column_comment.comment_edit_time, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');

            $this->db->from('column_comment');
               
            $this->db->where('column_comment.column_id', $column_id);

            $this->db->order_by('`column_comment.comment_time`', 'asc');

            $this->db->join('user', 'column_comment.comment_by = user.id', 'inner');

            $query = $this->db->get();

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $this->column_comment_replys_attachments($result); 
                return $result;
            }
            return FALSE;
        }

        public function get_last_comment_id($story_id)
        {
            $last_row = $this->db->select('id')->where('story_id', $story_id)->order_by('`story_comment.comment_time`, 
                `story_comment.comment_date`, `story_comment.comment_edit_time`, `story_comment.comment_edit_date`', 'asc')
            ->limit(1)->get('story_comment')->row();
            return $last_row->id;
        }

        public function get_column_last_comment_id($column_id)
        {
            $last_row = $this->db->select('id')->where('column_id', $column_id)->order_by('id',"desc")->limit(1)->get('column_comment')->row();
            return $last_row->id;
        }

        public function get_last_reply_id($story_id)
        {
            $last_row = $this->db->select('id')->where('post_id', $story_id)->order_by('id',"desc")->limit(1)->get('story_reply')->row();
            return $last_row->id;
        }

        public function comment_replys_attachments($result, $specific = FALSE)
        {
            for ($i = 0, $count = count($result); $i < $count; $i++) 
            { 
                // TRUE is passed 
                if($specific)
                {
                    return ($this->get_comment_replys($result)); 
                }
                else
                {
                    $result[$i]['comment_reply'] = $this->get_comment_replys($result[$i]['id']);
                }
            }

            return $result;
        }

        public function column_comment_replys_attachments($result, $specific = FALSE)
        {
            for ($i = 0, $count = count($result); $i < $count; $i++) 
            { 
                // TRUE is passed 
                if($specific)
                {
                    return $this->get_column_comment_replys($result); 
                }
                else
                {
                    $result[$i]['comment_reply'] = $this->get_column_comment_replys($result[$i]['id']); 
                }
                
            }

            return $result;
        }

        public function get_column_comment_replys($comment_id)
        {
            $this->db->select('`column_reply`.`id` AS `reply_id`, column_reply.comment_id, column_reply.reply, column_reply.reply_by, column_reply.likes AS reply_likes, column_reply.reply_time
            , column_reply.reply_date, `user`.`id` AS `user_id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');

            $this->db->from('column_reply');
               
            $this->db->order_by('`column_reply.reply_time`', 'asc');

            $this->db->where('column_reply.comment_id', $comment_id);

            $this->db->join('column_comment', 'column_comment.id = column_reply.comment_id', 'inner');
            
            $this->db->join('user', 'column_reply.reply_by = user.id', 'inner');

            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
                return $result;
            }

            return array();
        }

        public function get_comment_replys($comment_id)
        {
            $this->db->select('`story_reply`.`id` AS `reply_id`, story_reply.comment_id, story_reply.reply, story_reply.reply_by, story_reply.likes AS reply_likes, story_reply.reply_time
            , story_reply.reply_date, story_reply.reply_edit_date, story_reply.reply_edit_time, `user`.`id` AS `user_id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');

            $this->db->from('story_reply');
               
            $this->db->order_by('`story_reply.reply_time`', 'asc');

            $this->db->where('story_reply.comment_id', $comment_id);

            $this->db->join('story_comment', 'story_comment.id = story_reply.comment_id', 'inner');
            
            $this->db->join('user', 'story_reply.reply_by = user.id', 'inner');

            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
                return $result;
            }

            return array();
        }

        public function comments_count($entity_id, $entity = '', $home = FALSE) 
        {   
            if($entity == '')
            {
                $featured_post =  $entity_id;
                $q = $this->db->where('story_comment.story_id', $featured_post)->get("story_comment");
                return $q->num_rows();
            }
            else
            {
                if($home === TRUE)
                {
                    $featured_post = $entity_id;
                }
                else
                {
                    $featured_post = $this->post_model->get_featured_post($entity_id, $entity, TRUE);
                    $featured_post = array_column($featured_post, 'id');
                    $featured_post = $featured_post[0]; 
                }

                $q = $this->db->where('story_comment.story_id', $featured_post)->get("story_comment");
                return $q->num_rows();
            }
                return FALSE;            
        }

        public function get_column_comments_count($column_id) 
        {
            $q = $this->db->where('column_comment.column_id', $column_id)->get("column_comment");
            return $q->num_rows();
        }

        public function replyscomments_count() 
        {
            return $this->db->count_all("reply_comment");
        }

        /**
         * [delete_comment: Delete a comment record from the database]
         * @param  [int] $id    [comment id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_comment_reply()
        {
            $comment_reply_id = strtolower(trim($this->db->escape($this->input->post('comment_reply_id')), "' "));
            $entity = strtolower(trim($this->db->escape($this->input->post('entity')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $main_entity_id = strtolower(trim($this->db->escape($this->input->post('main_entity_id')), "' "));
            
            $main_entity_name = strtolower(trim($this->db->escape($this->input->post('main_entity_name')), "' "));
            $table = 'story_' . $entity; 
            $reply_table = 'story_reply';
            $reply_like_table = 'story_reply_like';
            
            if($entity == 'comment')
            {
                $comment_like_table = 'story_comment_like';

                if($this->db->delete($table, array($table.'.id' => $comment_reply_id,  $table.'.comment_by' => $user_id)))
                {
                    $this->db->delete($comment_like_table, array($comment_like_table.'.comment_id' => $comment_reply_id));  
                    
                    $q = $this->db->where($reply_table.'.comment_id', $comment_reply_id)->get($reply_table);

                    if($q->num_rows() > 0)
                    {
                        unset($q);
                        
                        $this->db->select('`story_reply`.`id`');
                        
                        $this->db->where(array('comment_id' => $comment_reply_id));
                        
                        $q = $this->db->get('story_reply');
                        
                        if ($q->num_rows() > 0)
                        {
                            $result = $q->result_array();
                            
                            $reply_ids = array_column($result, 'id');
                            
                            $reply_ids = implode(',', $reply_ids);

                            if ($this->db->delete($reply_table, array('comment_id' => $comment_reply_id))) 
                            {
                                $q = $this->db->query("DELETE FROM $reply_like_table WHERE reply_id IN ($reply_ids)");
                            }
                        }
                    }
                }
                echo json_encode(array('comment_count' => $this->comments_count($main_entity_id, '')));
            }
            if($entity == 'reply')
            {
                if ($this->db->delete($reply_table, array('id' => $comment_reply_id))) 
                {
                    $this->db->delete($reply_like_table, array('reply_id' => $comment_reply_id));
                    echo json_encode(array('comment_count' => $this->comments_count($main_entity_id, '')));
                }
            }
        } 

        /**
         * [delete_column_comment_reply: Delete a comment record from the database]
         * @param  [int] $id    [comment id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_column_comment_reply()
        {
            $comment_reply_id = strtolower(trim($this->db->escape($this->input->post('comment_reply_id')), "' "));
            $column_id = strtolower(trim($this->db->escape($this->input->post('column_id')), "' "));
            $entity = strtolower(trim($this->db->escape($this->input->post('entity')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $main_entity_id = strtolower(trim($this->db->escape($this->input->post('main_entity_id')), "' "));
            $main_entity_name = strtolower(trim($this->db->escape($this->input->post('main_entity_name')), "' "));
            $table = 'column_' . $entity; 
            $reply_table = 'column_reply';
            $reply_like_table = 'column_reply_like';
            
            if($entity == 'comment')
            {
                $comment_like_table = 'column_comment_like';

                if($this->db->delete($table, array($table.'.id' => $comment_reply_id,  $table.'.comment_by' => $user_id)))
                {
                    $this->db->delete($comment_like_table, array($comment_like_table.'.comment_id' => $comment_reply_id));  
                    
                    $q = $this->db->where($reply_table.'.comment_id', $comment_reply_id)->get($reply_table);

                    if($q->num_rows() > 0)
                    {
                        unset($q);
                        
                        $this->db->select('`column_reply`.`id`');
                        
                        $this->db->where(array('comment_id' => $comment_reply_id));
                        
                        $q = $this->db->get('column_reply');
                        
                        if ($q->num_rows() > 0)
                        {
                            $result = $q->result_array();
                            
                            $reply_ids = array_column($result, 'id');
                            
                            $reply_ids = implode(',', $reply_ids);

                            if ($this->db->delete($reply_table, array('comment_id' => $main_entity_id))) 
                            {
                                $q = $this->db->query("DELETE FROM $reply_like_table WHERE reply_id IN ($reply_ids)");
                            }
                        }
                    }
                }
                echo json_encode(array('comment_count' => $this->get_column_comments_count($column_id)));
            }
            if($entity == 'reply')
            {
                if ($this->db->delete($reply_table, array('id' => $comment_reply_id))) 
                {
                    $this->db->delete($reply_like_table, array('reply_id' => $comment_reply_id));
                    echo json_encode(array('comment_count' => $this->get_column_comments_count($column_id)));
                }
            }
        } 

        /**
         * [delete_column_comment: Delete a comment record from the database]
         * @param  [int] $id    [comment id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_column_comment($table, $user_id = '', $comment_reply_id = '', $entity = '')
        {
            if(!empty($comment_reply_id) && !empty($user_id))
            {
                if ($this->db->delete($table, array($this->entity . '_id' => $comment_reply_id, 'user_id' => $user_id))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete($table, array('id' => $comment_reply_id))) 
                {
                    return TRUE;
                }
            }
        }

        /**
         * [delete_comment: Delete a comment record from the database]
         * @param  [int] $id    [comment id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_comment($table, $user_id = '', $comment_reply_id = '', $entity = '')
        {
            if(!empty($comment_reply_id) && !empty($user_id))
            {
                if ($this->db->delete($table, array($this->entity . '_id' => $comment_reply_id, 'user_id' => $user_id))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete($table, array('id' => $comment_reply_id))) 
                {
                    return TRUE;
                }
            }
        }

        public function post_a_comment($story_id, $user_id)
        {
            $user_id = trim($this->db->escape($user_id), "' ");
            $story_id = trim($this->db->escape($story_id), "' ");
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
            $entity = strtolower(trim($this->db->escape($this->input->post('entity')), "' "));
            $entity_id = strtolower(trim($this->db->escape($this->input->post('entity_id')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'comment_by' => $user_id,

                'story_id' => $story_id,
                
                'comment' => $comment,

                'comment_date' => $date, 
                
                'comment_time' => $time
                
            );
            
            if ($this->db->insert('story_comment', $data))
            {
                $comment_id = $this->db->insert_id();
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                }
                
                $post_comments_count = $this->comments_count($story_id, '');
                
                return array('time' => $time, 'date' => $date, 'full_name' => ucwords($result['full_name']), 'comment' => $comment, 
                    'comment_id' => $comment_id, 'post_comments_count' => $post_comments_count);
            }
        }
        
        public function post_a_column_comment($column_id, $user_id)
        {
            $user_id = trim($this->db->escape($user_id), "' ");
            $column_id = trim($this->db->escape($column_id), "' ");
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
         
            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'comment_by' => $user_id,

                'column_id' => $column_id,
                
                'comment' => $comment,

                'comment_date' => $date, 
                
                'comment_time' => $time
                
            );
            
            if ($this->db->insert('column_comment', $data))
            {
                $comment_id = $this->db->insert_id();
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                }
      
                $column_comments_count = $this->get_column_comments_count($column_id);
                
                return array('time' => $time, 'date' => $date, 'full_name' => ucwords($result['full_name']), 'comment' => $comment, 
                    'comment_id' => $comment_id, 'post_comments_count' => $column_comments_count);
            }
        }

        public function edit_a_column_comment()
        {
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
            $comment_id = strtolower(trim($this->db->escape($this->input->post('comment_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'comment' => $comment,

                'comment_edit_date' => $date, 
                
                'comment_edit_time' => $time
                
            );

            $this->db->where('id', $comment_id);

            if ($this->db->update('column_comment', $data)) 
            {
            
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $full_name = ucwords($result['full_name']);
                }

                $this->db->select('column_comment.id, column_comment.comment_by, column_comment.likes, column_comment.column_id' ); 
                $this->db->where(array('id' => $comment_id)); 
                $query = $this->db->get('column_comment'); 

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $likes = $result['likes'];
                    $column_id = $result['column_id'];
                    $comment_by = $result['comment_by'];
                    $id = $result['id'];
                }

                $this->db->select('column_comment_like.id'); 
                $this->db->where(array('comment_id' => $comment_id, 'user_id' => $user_id)); 
                $query = $this->db->get('column_comment_like');

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $has_liked = $result['id'];
                }
                else
                {
                    $has_liked = 0;
                }
                      
                $last_row = $this->get_column_last_comment_id($column_id);
                      
                $replys = $this->column_comment_replys_attachments($comment_id, TRUE);

                return array('time' => $time, 'date' => $date, 'full_name' => $full_name, 'comment' => $comment, 'comment_by' => $comment_by,
                    'comment_reply' => $replys, 'comment_id' => $comment_id, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row, 'id' => $id);
            }
        }
        public function edit_a_comment()
        {
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
            $comment_id = strtolower(trim($this->db->escape($this->input->post('comment_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'comment' => $comment,

                'comment_edit_date' => $date, 
                
                'comment_edit_time' => $time
                
            );

            $this->db->where('id', $comment_id);

            if ($this->db->update('story_comment', $data)) 
            {
            
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $full_name = ucwords($result['full_name']);
                }

                $this->db->select('story_comment.id ,story_comment.comment_by, story_comment.likes, story_comment.story_id' ); 
                $this->db->where(array('id' => $comment_id)); 
                $query = $this->db->get('story_comment'); 

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $likes = $result['likes'];
                    $story_id = $result['story_id'];
                    $comment_by = $result['comment_by'];
                    $id = $result['id'];
                }

                $this->db->select('story_comment_like.id'); 
                $this->db->where(array('comment_id' => $comment_id, 'user_id' => $user_id)); 
                $query = $this->db->get('story_comment_like');

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $has_liked = $result['id'];
                }
                else
                {
                    $has_liked = 0;
                }
                      
                $last_row = $this->get_last_comment_id($story_id);      

                $replys = $this->comment_replys_attachments($comment_id, TRUE);

                return array('id' => $id, 'time' => $time, 'date' => $date, 'full_name' => $full_name, 'comment' => $comment, 'comment_by' => $comment_by, 'comment_reply' => $replys,
                    'comment_id' => $comment_id, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row);
            }
        }

        public function no_change_comment_lookup()
        {
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
            $comment_id = strtolower(trim($this->db->escape($this->input->post('comment_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
            $this->db->where(array('id' => $user_id));
            $query = $this->db->get('user');
            
            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $result[0];
                $full_name = ucwords($result['full_name']);
            }

            $this->db->select('story_comment.id, story_comment.likes, story_comment.comment_by ,story_comment.story_id, 
                story_comment.comment_date, story_comment.comment_time, story_comment.comment_edit_date, story_comment.comment_edit_time'); 
            $this->db->where(array('id' => $comment_id)); 
            $query = $this->db->get('story_comment'); 

            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $likes = $result['likes'];
                $story_id = $result['story_id'];
                $date = $result['comment_date'];
                $time = $result['comment_time'];
                $edit_date = $result['comment_edit_date'];
                $edit_time = $result['comment_edit_time'];
                $comment_by = $result['comment_by'];
                $id = $result['id'];
            }

            $this->db->select('story_comment_like.id'); 
            $this->db->where(array('comment_id' => $comment_id, 'user_id' => $user_id)); 
            $query = $this->db->get('story_comment_like');

            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $has_liked = $result['id'];
            }
            else
            {
                $has_liked = 0;
            }

            $last_row = $this->get_last_comment_id($story_id);
            
            $replys = $this->comment_replys_attachments($comment_id, TRUE); 

            return (array('time' => $time, 'date' => $date, 'full_name' => $full_name, 'comment' => $comment, 'comment_reply' => $replys,
            'id' => $id, 'comment_id' => $comment_id, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row, 
            'comment_by' => $comment_by, 'edit_date' => $edit_date, 'edit_time' => $edit_time));
        }

        public function edit_a_reply()
        {        
            $reply = strtolower(trim($this->db->escape($this->input->post('reply')), "' "));
            $reply_id = strtolower(trim($this->db->escape($this->input->post('reply_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'reply' => $reply,

                'reply_edit_date' => $date, 
                
                'reply_edit_time' => $time
                
            );

            $this->db->where('id', $reply_id);

            if ($this->db->update('story_reply', $data)) 
            {
            
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) 
                    AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $full_name = ucwords($result['full_name']);
                }

                $this->db->select('story_reply.id ,story_reply.reply_by, story_reply.likes, story_reply.post_id' ); 
                $this->db->where(array('id' => $reply_id)); 
                $query = $this->db->get('story_reply'); 

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $likes = $result['likes'];
                    $story_id = $result['post_id'];
                    $reply_by = $result['reply_by'];
                    $id = $result['id'];
                }

                $this->db->select('story_reply_like.id'); 
                $this->db->where(array('reply_id' => $reply_id, 'user_id' => $user_id)); 
                $query = $this->db->get('story_reply_like');

                if($query->num_rows() > 0)
                {
                    $result = $query->result_array();
                    $result = $result[0];
                    $has_liked = $result['id'];
                }
                else
                {
                    $has_liked = 0;
                }
                      
                $last_row = $this->get_last_reply_id($story_id);

                return array('time' => $time, 'date' => $date, 'full_name' => $full_name, 'reply' => $reply, 
                    'reply_id' => $reply_id, 'reply_by' => $reply_by, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row);                  
            }
        }


        public function no_change_reply_lookup()
        {
            $reply = strtolower(trim($this->db->escape($this->input->post('reply')), "' "));
            $reply_id = strtolower(trim($this->db->escape($this->input->post('reply_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
            $this->db->where(array('id' => $user_id));
            $query = $this->db->get('user');
            
            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $result[0];
                $full_name = ucwords($result['full_name']);
            }

            $this->db->select('story_reply.likes, story_reply.post_id, story_reply.comment_id, story_reply.reply_date, story_reply.reply_time, story_reply.reply_by'); 
            $this->db->where(array('id' => $reply_id)); 
            $query = $this->db->get('story_reply'); 
    
            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $likes = $result['likes'];
                $story_id = $result['post_id'];
                $date = $result['reply_date'];
                $time = $result['reply_time'];
                $reply_by = $result['reply_by'];
                $comment_id = $result['comment_id'];
            }

            $this->db->select('story_reply_like.id'); 
            $this->db->where(array('reply_id' => $reply_id, 'user_id' => $user_id)); 
            $query = $this->db->get('story_reply_like');

            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $has_liked = $result['id'];
            }
            else
            {
                $has_liked = 0;
            }

            $last_row = $this->get_last_reply_id($story_id);
                  
            return array('time' => $time, 'date' => $date, 'full_name' => $full_name, 'reply' => $reply, 'comment_id' => $comment_id, 
                'reply_id' => $reply_id, 'reply_by' => $reply_by, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row);
        }

        public function no_change_column_comment_lookup()
        {
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));
            $comment_id = strtolower(trim($this->db->escape($this->input->post('comment_id')), "' "));
            $user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
            $this->db->where(array('id' => $user_id));
            $query = $this->db->get('user');
            
            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $result[0];
                $full_name = ucwords($result['full_name']);
            }

            $this->db->select('column_comment.id, column_comment.comment_by, column_comment.likes, column_comment.column_id, column_comment.comment_date, column_comment.comment_time'); 
            $this->db->where(array('id' => $comment_id)); 
            $query = $this->db->get('column_comment'); 

            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $likes = $result['likes'];
                $column_id = $result['column_id'];
                $date = $result['comment_date'];
                $time = $result['comment_time'];
                $comment_by = $result['comment_by'];
                $id = $result['id'];
            }

            $this->db->select('column_comment_like.id'); 
            $this->db->where(array('comment_id' => $comment_id, 'user_id' => $user_id)); 
            $query = $this->db->get('column_comment_like');

            if($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $result[0];
                $has_liked = $result['id'];
            }
            else
            {
                $has_liked = 0;
            }

            $last_row = $this->get_column_last_comment_id($column_id);      
            $replys = $this->column_comment_replys_attachments($comment_id, TRUE); 
                  
            return array('time' => $time, 'date' => $date, 'full_name' => $full_name, 'comment' => $comment, 'comment_reply' => $replys, 'id' => $id,
                'comment_id' => $comment_id, 'has_liked' => $has_liked, 'likes' => $likes, 'last_row' => $last_row, 'comment_by' => $comment_by);
        }

        public function post_a_column_reply($comment_id, $user_id, $column_id)
        {
            $user_id = trim($this->db->escape($user_id), "' ");
            $column_id = trim($this->db->escape($column_id), "' ");
            $comment_id = trim($this->db->escape($comment_id), "' ");
            $reply = strtolower(trim($this->db->escape($this->input->post('reply')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'reply_by' => $user_id,

                'comment_id' => $comment_id,

                'column_id' => $column_id,
                
                'reply' => $reply,

                'reply_date' => $date, 
                
                'reply_time' => $time
                
            );
            
            if ($this->db->insert('column_reply', $data))
            {
                $reply_id = $this->db->insert_id();
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                }
                return array('time' => $time, 'date' => $date, 'full_name' => ucwords($result['full_name']), 'reply' => $reply, 'reply_id' => $reply_id, 
                    'comment_id' => $comment_id);
            }
        }

        public function post_a_reply($comment_id, $user_id, $post_id)
        {
            $user_id = trim($this->db->escape($user_id), "' ");
            $post_id = trim($this->db->escape($post_id), "' ");
            $comment_id = trim($this->db->escape($comment_id), "' ");
            $reply = strtolower(trim($this->db->escape($this->input->post('reply')), "' "));

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d");

            $time = date("H:i:s");

            $data = array(

                'reply_by' => $user_id,

                'comment_id' => $comment_id,

                'post_id' => $post_id,
                
                'reply' => $reply,

                'reply_date' => $date, 
                
                'reply_time' => $time
                
            );
            
            if ($this->db->insert('story_reply', $data))
            {
                $reply_id = $this->db->insert_id();
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
                $this->db->where(array('id' => $user_id));
                $query = $this->db->get('user');
                
                if ($query->num_rows() > 0) 
                {
                    $result = $query->result_array();
                    $result = $result[0];
                }
                return array('time' => $time, 'date' => $date, 'full_name' => ucwords($result['full_name']), 'reply' => $reply, 
                    'reply_id' => $reply_id, 'comment_id' => $comment_id, 'reply_by' => $user_id);
            }
        }
	}