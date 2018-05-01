<?php  
    /**
    * post_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_column
    *          update_column
    *          delete_column
    *          get_column_by_id
    *          get_attachments
    *          record_count
    *          fetch_columns
    */

	class Column_model extends CI_Model {

        private $post,
                $politician,
                $political_party,
                $posted_by,
                $date,
                $time,
                $permissions;

        public function __construct()
        {
            parent::__construct(); 
            $this->db->simple_query('SET NAMES "utf-8"');      		
            $this->load->model('admin/political_party_model');
            $this->load->model('admin/user_model');
        }

        /**
         * [insert_column: Inserts a post record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_column()
        {
            $this->column = escape($this->input->post('column')); 
            
            $this->column = str_replace("img", "img class='img img-responsive'", $this->column);
            $this->column = str_replace("&lt;p&gt;", "&lt;p class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h1&gt;", "&lt;h1 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h2&gt;", "&lt;h2 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h3&gt;", "&lt;h3 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h4&gt;", "&lt;h4 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h5&gt;", "&lt;h5 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;h6&gt;", "&lt;h6 class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;strong&gt;", "&lt;strong class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;i&gt;", "&lt;i class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;u&gt;", "&lt;u class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;s&gt;", "&lt;s class='word-break: break-all;'&gt;", $this->column);
            $this->column = str_replace("&lt;sup&gt;", "&lt;sup class='word-break: break-all;'&gt;", $this->column);

            $this->title = escape($this->input->post('title'));
            $this->columnist = strtolower(trim($this->db->escape($this->input->post('columnist')), "' "));
            $this->posted_by = array_column($this->session->userdata['admin_record'], 'id')[0];
                        
            date_default_timezone_set("Asia/Karachi");
            
            $this->date = date("Y-m-d");

            $this->time = date("H:i:s");
            
            $data = array(

                'column' => $this->column,
                'title' => $this->title,
                'columnist_id' => $this->columnist,
                'posted_by'  => $this->posted_by,
                'posted_date' => $this->date,
                'posted_time' => $this->time
            );
            
            if ($this->db->insert('column', $data))
            {
                return TRUE;
            }
        }   

        /**
         * [update_column: Updates a post record into the database]
         * @return [boolean] [if updation is performed successfully 
         * then, returns TRUE.]
         */
        public function update_column($id)
        {

            $this->column = escape($this->input->post('column'));
            $this->columnist = strtolower(trim($this->db->escape($this->input->post('columnist')), "' "));
            $this->posted_by = array_column($this->session->userdata['admin_record'], 'id')[0];
                        
            date_default_timezone_set("Asia/Karachi");
            
            $this->date = date("Y-m-d");

            $this->time = date("H:i:s");

            $data = array(

                'column' => $this->column,
                'columnist_id' => $this->columnist,
                'posted_by'  => $this->posted_by,
                'posted_date' => $this->date,
                'posted_time' => $this->time
            );
            
            $this->db->where('id', $id);

            if ($this->db->update('column', $data))
            {
                return TRUE;
            }


        }

        /**
         * [get_columns Returns all post's e.g na-1, na-2]
         * @return [array] [return all post's]
         */
        public function get_columns()
        {
            $query = $this->db->get('story');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_column_by_id Fetchs a post record from the database by post id]
         * @param  [type]  $id   [post id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [post record is returned]
         */
        public function get_column_by_id($id)
        {
            $this->db->select('`column`.`id`, `column`.`column`, `column`.`likes`, `column`.`dislikes`, 
                                `column`.`posted_date`, `columnist`.`name` AS columnist_name, 
                                CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS user_name,
                                DATE_FORMAT(`posted_time`, "%h:%i:%s %p") AS posted_time');
            $this->db->from('column');
            $this->db->join('user', 'user.id = column.posted_by', 'left');
            $this->db->join('columnist', 'columnist.id = column.columnist_id', 'left');

            $this->db->where(array('`column`.`id`' => $id));
            $q = $this->db->get(); 
            $result = $q->result_array();
            return $result[0];
        }

        /**
         * [set_column_feature Fetchs a post record from the database by post id]
         * @param  [type]  $id   [post id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [post record is returned]
         */
        public function set_column_feature($post_id, $entity, $featured, $entity_id)
        {

            /**
             * To update featured to 0 of all the posts with the arguments $entity $entity_id 
             */

            $this->db->where(array('entity_id' => $entity_id, 'entity' => $entity));

            $data = array(

                'featured' => 0
            );

            $this->db->update('story_details', $data);

            $featured = $featured == 0 ? 1 : 0 ;

            $data = array(

                'featured' => $featured,
            );

            $this->db->where(array('post_id' => $post_id, 'entity' => $entity, 'entity_id' => $entity_id));

            if ($this->db->update('story_details', $data))
            {
                return TRUE;
            }
        }

        /**
         * [set_column_feature Fetchs a post record from the database by post id]
         * @param  [type]  $id   [post id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [post record is returned]
         */
        public function get_featured_column($entity_id, $entity, $specfic = FALSE)
        {
            /**
             * To get only featured post 1 of a $entity & $entity_id 
             */
            if($specfic)
            {
                $this->db->select('`story`.`id`');
            }
            else
            {
                $this->db->select('`story`.`id`, `story`.`post`, `story`.`likes`, `story`.`dislikes`, 
                        `story`.`posted_by`, `story`.`posted_date`, `story`.`posted_time`,`story_details`.`entity_id`, 
                        `story_details`.`entity`, `story_details`.`featured`');
            }

            $this->db->from('story');
            $this->db->join('story_details', 'story.id = story_details.post_id', 'inner');
            $this->db->where(array('entity_id' => $entity_id, 'entity' => $entity, 'featured' => 1));
            $q = $this->db->get(); 

            $result = $q->result_array();
            if(!$specfic)
            {
                $result = $this->get_attachments($result);
            }   

            return $result;
        }

        /**
         * [get_columns_by_type Fetchs a post record from the database by post type id]
         * @param  [string]  $type   [post type id whom record is to be fetched]
         * @return [array] [post records of specfic type are returned]
         */
        public function get_columns_by_type($type)
        {
            $type = $this->column_type_model->get_id_by_key($type);
            $type = $type['id'];
            $q = $this->db->get_where('story', array('type' => $type)); 
            
            return $q->result_array();
        }

        /**
         * [delete_column: Delete a politician record from the database]
         * @param  [int] $id    [politician id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_column($table, $post_id = '', $user_id = '', $action = '')
        {
            if(!empty($post_id) && !empty($action))
            {
                if ($this->db->delete($table, array('post_id' => $post_id, 'user_id' => $user_id, 'action' => $action))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete($table, array('id' => $post_id))) 
                {
                    return TRUE;
                }
            }
        }   

        /**
         * [get_by_id_by_key Returns a post id from the database by key e.g. NA-1 id is 1]
         * @param  [type]  $key   [post key whose id is to returned]
         * @return [array] [post record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('story.id');
            $this->db->where('name', $key);
            $q = $this->db->get('story');
            
            return $q->result_array()[0];
        }    

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count() 
        {
            return $this->db->count_all("story_details");
        }

        /**
         * [fetch_column Returns posts with a $limit defined in post controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any posts then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_columns($limit, $start)
        {
            $this->db->select('`column`.`id`, `column`.`column`, `column`.`likes`, `column`.`dislikes`, 
                                `column`.`posted_date`, `columnist`.`name` AS columnist_name, 
                                CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS user_name,
                                DATE_FORMAT(`posted_time`, "%h:%i:%s %p") AS posted_time');
            $this->db->from('column');
            $this->db->join('user', 'user.id = column.posted_by', 'left');
            $this->db->join('columnist', 'columnist.id = column.columnist_id', 'left');
            $this->db->limit($limit, $start);
            $query = $this->db->get();

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                // $result = $this->get_attachments($result);

                return $result;
            }

            return false;
        }

        /**
         * [get_attachments: Attachs additional attachments to the politician record e.g designation_id
         *  is passed to get the designation name against the designation_id of politician]
         * @param  [array] $attachments [politician record]
         * @return [array]              [politician record with attachments e.g. designation_id designation name 
         * against designation_id of politician]
         */
        public function get_attachments($attachments, $get_by_id = FALSE, $attach_details = TRUE)
        {
            if(!$get_by_id)
            {
                for ($i = 0, $count = count($attachments); $i < $count; $i++) 
                { 
                    if($attachments[$i]['entity'] == 'political_party')
                    {
                        $attachments[$i]['entity_name'] = $this->political_party_model->get_political_party_by_id($attachments[$i]['entity_id'], FALSE, TRUE);
                    }
                    if($attachments[$i]['entity'] == 'politician')
                    {
                        $attachments[$i]['entity_name'] = $this->politician_model->get_politician_by_id($attachments[$i]['entity_id'], FALSE,array() , TRUE);
                    }

                    // TRUE is passed
                    $attachments[$i]['user_details'] = $this->user_model->get_user_by_id($attachments[$i]['posted_by'], FALSE, array('full_name')); 
                }
            }
            else
            {
                $attachments = call_user_func_array('array_merge', $attachments);
                $attachments['user_details'] = $this->user_model->get_user_by_id($attachments['posted_by'], FALSE, array('full_name')); 
                // $attachments['posted_time_24hrs'] = $this->format_columned_time();
            }
            return $attachments;
        }
        public function format_columned_time()
        {
            $this->db->select("DATE_FORMAT(`posted_time`, '%h:%i:%s %p') AS posted_time", FALSE);
            $query = $this->db->get('story');
            $result = $query->result_array();
            return $result[0];
        }

        /**
         * [insert_like_dislike: Inserts a politician record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function like_dislike_action_exists($opposite_action = '')
        {   
            unset($q);     
            
            $this->delete_column('post-like-dislike', $this->column_id, $this->user_id, $this->opposite_action);

            $counter = empty($opposite_action) ? 2 : 1;

            for ($i = 0; $i < $counter; $i++)
            { 
                $field = ($i == 0) ? $this->opposite_action_field : $this->action_field;
        
                $this->db->select('story.' .  $field);        

                $this->db->where(array('id' => $this->column_id)); 
                
                $q = $this->db->get('story'); 

                $result = $q->result_array()[0];

                $this->count = $result[$field];
                
                if($this->count <= 0)
                {
                    $this->count = 0;
                }
                else
                {
                    $this->count--;
                }

                $data = array(

                    $field => $this->count

                );
        
                $this->db->where('id', $this->column_id);

                if ($this->db->update('story', $data)) 
                {
                    $this->delete_column('post-like-dislike',$this->column_id ,$this->user_id, $this->action);
                }
            }
            // echo json_encode(array('already_' . $this->action . 'd'  => 'already-' . $this->action . 'd_' . $count));
            // return TRUE;
        }
        
        /**
         * [insert_like_dislike: Inserts a politician record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike()
        {
            $this->user_id = strtolower(trim($this->db->escape($this->input->column('user_id')), "' "));
            $this->action = strtolower(trim($this->db->escape($this->input->column('action')), "' "));
            $this->column_id = strtolower(trim($this->db->escape($this->input->column('post_id')), "' "));
            $this->main_entity_name = strtolower(trim($this->db->escape($this->input->column('main_entity_name')), "' "));

            // to cross check if $this->action = like then assign $this->opposite_action = dislike to remove it id needed
            $this->action = $this->action == 'like' ? 'like' : 'dislike';

            $this->opposite_action = $this->action == 'like' ? 'dislike' : 'like';

            $action_query = $this->db->get_where('post-like-dislike', array('user_id' => $this->user_id, 'action' => $this->action, 
                                        'post_id' => $this->column_id)); 
            $opposite_action_query = $this->db->get_where('post-like-dislike', array('user_id' => $this->user_id, 'action' => $this->opposite_action, 
                                        'post_id' => $this->column_id)); 

            $this->action_field = $this->action == 'like' ? 'likes' : 'dislikes';
            
            $this->opposite_action_field = $this->opposite_action == 'like' ? 'likes' : 'dislikes';

            if($action_query->num_rows() > 0)
            {
                $this->like_dislike_action_exists();
                echo json_encode(array('already_' . $this->action . 'd'  => 'already-' . $this->action . 'd_' . $this->count));
                return TRUE;
            }
            else if($opposite_action_query->num_rows() > 0)
            {
                $this->like_dislike_action_exists($this->opposite_action);
                $this->insert_like_dislike_details($this->action, $this->action_field);
                $this->db->select('story.' . $this->action_field . ',' . 'story.' . $this->opposite_action_field);        

                $this->db->where(array('id' => $this->column_id)); 
                
                $q = $this->db->get('story');

                $result = $q->result_array()[0];

                echo json_encode(array('action_change'  => $this->action,
                                       $this->action . '_change'  => 'already-' . $this->action . 'd_' . $result[$this->action_field], 
                                       $this->opposite_action . '_change'  => 'already-' . $this->opposite_action . 'd_' . $result[$this->opposite_action_field]));
                return TRUE;
            }
            else
            {
                if($this->insert_like_dislike_details())
                {
                    echo json_encode(array($this->action => $this->action . '_' . $this->count));
                }
            }
        }
        /**
         * [insert_like_dislike: Inserts a politician record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike_details($action = '', $action_field = '')
        {
            unset($q);

            $this->action = empty($action) ? $this->action : $action;

            $this->action_field = empty($action_field) ? $this->action_field : $action_field;

            $this->db->select('story.' .  $this->action_field);

            $this->db->where(array('id' => $this->column_id)); 
            
            $q = $this->db->get('story');
            $result = $q->result_array()[0];

            $this->count = $result[$this->action_field];
            
            $this->count++;

            $data = array(

                $this->action_field => $this->count

            );

            $this->db->where('id', $this->column_id);

            if ($this->db->update('story', $data)) 
            {        
                unset($data);         
                $data = array(

                    'user_id' => $this->user_id,

                    'post_id' => $this->column_id,
                    
                    'action' => $this->action, 
                );

                if($this->db->insert('post-like-dislike', $data))
                {
                    return TRUE;
                }
                
                return FALSE;
            }
        }

        public function get_likes_dislikes()
        {
            $this->column_id = strtolower(trim($this->db->escape($this->input->column('post_id')), "' "));
            $this->user_id = strtolower(trim($this->db->escape($this->input->column('user_id')), "' "));

            $q = $this->db->get_where('post-like-dislike', array('user_id' => $this->user_id, 'action' => 'like', 
                                        'post_id' => $this->column_id)); 

            $q2 = $this->db->get_where('post-like-dislike', array('user_id' => $this->user_id, 'action' => 'dislike', 
                                        'post_id' =>  $this->column_id)); 

            if($q->num_rows() > 0)
            {
                echo json_encode(array('like' => 'liked'));
            }
            else if($q2->num_rows() > 0)
            {
                echo json_encode(array('dislike' => 'disliked'));
            }
        }

        public function get_rating($post_id)
        {
            
            $this->db->select('`story`.`rating`');

            $this->db->where(array('id' => $post_id));
            
            $q = $this->db->get('story');

            $result = $q->result_array()[0];

            $this->count = $result['rating'];

            if($this->count <= 0)
            {
                $this->count = 0;
            }
            else
            {
                return $this->count;
            }
        }

        public function add_rating($post_id = '')
        {
            $this->column_id = strtolower(trim($this->db->escape($this->input->column('post_id')), "' "));
            $this->user_id = strtolower(trim($this->db->escape($this->input->column('user_id')), "' "));
            $this->rating = strtolower(trim($this->db->escape($this->input->column('rating')), "' "));
            
            $this->db->select('`story`.`rating`');

            $this->db->where(array('id' => $this->column_id));
            
            $q = $this->db->get('story');

            $result = $q->result_array()[0];

            $this->count = $result['rating'];

            $old_rating = $this->get_rating_details();
            $old_rating = $old_rating['rating'];
            if($old_rating > 0)
            {
                if($this->count <= 0)
                {
                    $this->count = $this->rating;
                }
                else
                {
                    $this->count -= $old_rating;
                    $this->count += $this->rating;
                }

                $data = array(

                    'rating' => $this->count

                );
        
                $this->db->where('id', $this->column_id);

                if ($this->db->update('story', $data)) 
                {
                    unset($data);
        
                    $data = array(

                        'rating' => $this->rating

                    );

                    $this->db->where(array('post_id' => $this->column_id, 'user_id' => $this->user_id));

                    $this->db->update('story-rating', $data);    
                }
            }
            else
            {    
                if($this->count <= 0)
                {
                    $this->count = $this->rating;
                }
                else
                {
                    $this->count += $this->rating;
                }

                $data = array(

                    'rating' => $this->count

                );
        
                $this->db->where('id', $this->column_id);

                if ($this->db->update('story', $data)) 
                {
                    $this->insert_rating_details();       
                }
            }
                return $this->count;
        }

        public function insert_rating_details()
        {
            unset($data);         
            
            $data = array(

                'user_id' => $this->user_id,

                'post_id' => $this->column_id,
                
                'rating' => $this->rating
            );

            if($this->db->insert('`story-rating`', $data))
            {
                return TRUE;
            }
            
            return FALSE;
        }

        public function get_rating_details()
        {
            $this->db->select('`story-rating`.`rating`');        

            $this->db->where(array('post_id' => $this->column_id, 'user_id' => $this->user_id)); 
            
            $q = $this->db->get('story-rating'); 

            if($q->num_rows() > 0)
            {
                $result = $q->result_array()[0];
                return $result;
            }

            return FALSE;
        }

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function get_total_ratings_count($post_id)
        {
            $this->db->where('post_id', $post_id);
            $this->db->from('story-rating');
            return $this->db->count_all_results();
        }
	} 