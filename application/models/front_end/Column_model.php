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
                $column,
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
         * [get_columns Returns all post's e.g na-1, na-2]
         * @return [array] [return all post's]
         */
        public function get_columns()
        {
            $query = $this->db->get('column');
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
        public function get_column_by_columnist_id($id)
        {
            $this->db->select('`column`.`id`, `column`.`title`');
            $this->db->from('column');
            $this->db->where(array('`column`.`columnist_id`' => $id));
            $this->db->order_by('`column`.`posted_time`', 'desc');
            $q = $this->db->get(); 
            $result = $q->result_array();
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
            $this->db->select('`column`.`id`, `column`.`title`, `column`.`column`, `column`.`columnist_id`, `column`.`likes`, 
                                `column`.`dislikes`, `column`.`posted_date`, `columnist`.`name` AS columnist_name,
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
        public function set_column_feature($column_id, $entity, $featured, $entity_id)
        {

            /**
             * To update featured to 0 of all the posts with the arguments $entity $entity_id 
             */

            $this->db->where(array('entity_id' => $entity_id, 'entity' => $entity));

            $data = array(

                'featured' => 0
            );

            $this->db->update('column_details', $data);

            $featured = $featured == 0 ? 1 : 0 ;

            $data = array(

                'featured' => $featured,
            );

            $this->db->where(array('column_id' => $column_id, 'entity' => $entity, 'entity_id' => $entity_id));

            if ($this->db->update('column_details', $data))
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
                $this->db->select('`column`.`id`');
            }
            else
            {
                $this->db->select('`column`.`id`, `column`.`post`, `column`.`likes`, `column`.`dislikes`, 
                        `column`.`posted_by`, `column`.`posted_date`, `column`.`posted_time`,`column_details`.`entity_id`, 
                        `column_details`.`entity`, `column_details`.`featured`');
            }

            $this->db->from('column');
            $this->db->join('column_details', 'column.id = column_details.column_id', 'inner');
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
            $type = $this->post_type_model->get_id_by_key($type);
            $type = $type['id'];
            $q = $this->db->get_where('column', array('type' => $type)); 
            
            return $q->result_array();
        }

        /**
         * [get_by_id_by_key Returns a post id from the database by key e.g. NA-1 id is 1]
         * @param  [type]  $key   [post key whose id is to returned]
         * @return [array] [post record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('column.id');
            $this->db->where('name', $key);
            $q = $this->db->get('column');
            
            return $q->result_array()[0];
        }    

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count() 
        {
            return $this->db->count_all("column_details");
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
         * [get_attachments: Attachs additional attachments to the column record e.g designation_id
         *  is passed to get the designation name against the designation_id of column]
         * @param  [array] $attachments [column record]
         * @return [array]              [column record with attachments e.g. designation_id designation name 
         * against designation_id of column]
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
                    if($attachments[$i]['entity'] == 'column')
                    {
                        $attachments[$i]['entity_name'] = $this->post_model->get_column_by_id($attachments[$i]['entity_id'], FALSE,array() , TRUE);
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
            $query = $this->db->get('column');
            $result = $query->result_array();
            return $result[0];
        }

        /**
         * [insert_like_dislike: Inserts a column record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function like_dislike_action_exists($opposite_action = '')
        {   
            unset($q);     
            
            $this->delete_column('column-like-dislike', $this->post_id, $this->user_id, $this->opposite_action);

            $counter = empty($opposite_action) ? 2 : 1;

            for ($i = 0; $i < $counter; $i++)
            { 
                $field = ($i == 0) ? $this->opposite_action_field : $this->action_field;
        
                $this->db->select('column.' .  $field);        

                $this->db->where(array('id' => $this->post_id)); 
                
                $q = $this->db->get('column'); 

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
        
                $this->db->where('id', $this->post_id);

                if ($this->db->update('column', $data)) 
                {
                    $this->delete_column('column-like-dislike',$this->post_id ,$this->user_id, $this->action);
                }
            }
            // echo json_encode(array('already_' . $this->action . 'd'  => 'already-' . $this->action . 'd_' . $count));
            // return TRUE;
        }
        
        /**
         * [insert_like_dislike: Inserts a column record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike()
        {
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $this->action = strtolower(trim($this->db->escape($this->input->post('action')), "' "));
            $this->post_id = strtolower(trim($this->db->escape($this->input->post('column_id')), "' "));
            $this->main_entity_name = strtolower(trim($this->db->escape($this->input->post('main_entity_name')), "' "));

            // to cross check if $this->action = like then assign $this->opposite_action = dislike to remove it id needed
            $this->action = $this->action == 'like' ? 'like' : 'dislike';

            $this->opposite_action = $this->action == 'like' ? 'dislike' : 'like';

            $action_query = $this->db->get_where('column-like-dislike', array('user_id' => $this->user_id, 'action' => $this->action, 
                                        'column_id' => $this->post_id)); 
            $opposite_action_query = $this->db->get_where('column-like-dislike', array('user_id' => $this->user_id, 'action' => $this->opposite_action, 
                                        'column_id' => $this->post_id)); 

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
                $this->db->select('column.' . $this->action_field . ',' . 'column.' . $this->opposite_action_field);        

                $this->db->where(array('id' => $this->post_id)); 
                
                $q = $this->db->get('column');

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
         * [insert_like_dislike: Inserts a column record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike_details($action = '', $action_field = '')
        {
            unset($q);

            $this->action = empty($action) ? $this->action : $action;

            $this->action_field = empty($action_field) ? $this->action_field : $action_field;

            $this->db->select('column.' .  $this->action_field);

            $this->db->where(array('id' => $this->post_id)); 
            
            $q = $this->db->get('column');
            $result = $q->result_array()[0];

            $this->count = $result[$this->action_field];
            
            $this->count++;

            $data = array(

                $this->action_field => $this->count

            );

            $this->db->where('id', $this->post_id);

            if ($this->db->update('column', $data)) 
            {        
                unset($data);         
                $data = array(

                    'user_id' => $this->user_id,

                    'column_id' => $this->post_id,
                    
                    'action' => $this->action, 
                );

                if($this->db->insert('column-like-dislike', $data))
                {
                    return TRUE;
                }
                
                return FALSE;
            }
        }

        public function get_likes_dislikes()
        {
            $this->column_id = strtolower(trim($this->db->escape($this->input->post('column_id')), "' "));
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));

            $q = $this->db->get_where('column-like-dislike', array('user_id' => $this->user_id, 'action' => 'like', 
                                        'column_id' => $this->column_id)); 

            $q2 = $this->db->get_where('column-like-dislike', array('user_id' => $this->user_id, 'action' => 'dislike', 
                                        'column_id' =>  $this->column_id)); 
            if($q->num_rows() > 0)
            {
                echo json_encode(array('like' => 'liked'));
            }
            else if($q2->num_rows() > 0)
            {
                echo json_encode(array('dislike' => 'disliked'));
            }
        }


        /**
         * [delete_column: Delete a column record from the database]
         * @param  [int] $id    [column id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_column($table, $column_id = '', $user_id = '', $action = '')
        {
            if(!empty($column_id) && !empty($user_id))
            {
                if ($this->db->delete($table, array('column_id' => $column_id, 'user_id' => $user_id, 'action' => $action))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete('column', array('id' => $column_id))) 
                {
                    return TRUE;
                }
            }
        } 

        public function get_rating($column_id)
        {
            
            $this->db->select('`column`.`rating`');

            $this->db->where(array('id' => $column_id));
            
            $q = $this->db->get('column');

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

        public function add_rating($column_id = '')
        {
            $this->post_id = strtolower(trim($this->db->escape($this->input->post('column_id')), "' "));
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $this->rating = strtolower(trim($this->db->escape($this->input->post('rating')), "' "));
            
            $this->db->select('`column`.`rating`');

            $this->db->where(array('id' => $this->post_id));
            
            $q = $this->db->get('column');

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
        
                $this->db->where('id', $this->post_id);

                if ($this->db->update('column', $data)) 
                {
                    unset($data);
        
                    $data = array(

                        'rating' => $this->rating

                    );

                    $this->db->where(array('column_id' => $this->post_id, 'user_id' => $this->user_id));

                    $this->db->update('column-rating', $data);    
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
        
                $this->db->where('id', $this->post_id);

                if ($this->db->update('column', $data)) 
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

                'column_id' => $this->post_id,
                
                'rating' => $this->rating
            );

            if($this->db->insert('`column-rating`', $data))
            {
                return TRUE;
            }
            
            return FALSE;
        }

        public function get_rating_details()
        {
            $this->db->select('`column-rating`.`rating`');        

            $this->db->where(array('column_id' => $this->post_id, 'user_id' => $this->user_id)); 
            
            $q = $this->db->get('column-rating'); 

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
        public function get_total_ratings_count($column_id)
        {
            $this->db->where('column_id', $column_id);
            $this->db->from('column-rating');
            return $this->db->count_all_results();
        }
	} 