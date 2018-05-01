<?php  
    /**
    * columnist_model class is model of columnist controller it performs 
    * basic CRUD operations
    * Methods: insert_columnist
    *          update_columnist
    *          delete_columnist
    *          get_columnist_by_id
    *          get_attachments
    *          record_count
    *          fetch_columnists
    */

    class Columnist_model extends CI_Model {

        private $name,
                $introduction,
                $dob,
                $newspaper,
                $image,
                $thumbnail,
                $city,
                $profile_image;

        public function __construct()
        {
            parent::__construct();          
            $this->load->model('admin/newspaper_model');
            $this->load->model('admin/autocomplete_model');
        }

        /**
         * [get_columnists Returns all details of columnist 
         * e.g id = 1, name = Nawaz Sharif) ................]
         * @return [array] [return all details of columnists]
         */
        public function get_columnists()
        {
            $query = $this->db->get('columnist');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_doctor_specialization Fetchs a columnist details record from the database by columnist id]
         * @param  [type]  $id   [columnist id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [columnist record is returned]
         */
        public function get_columnist_details($columnist_id)
        {
            $this->db->select('columnist_details.id, columnist_details.columnist_id, columnist_details.newspaper_id, 
                            columnist_details.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
            $this->db->from('columnist_details');
            $this->db->where('columnist_details.columnist_id', $columnist_id);
            $this->db->join('halqa', 'halqa.id = columnist_details.halqa_id', 'inner');
            $this->db->join('user_designation', 'user_designation.id = columnist_details.newspaper_id', 'inner');
            $q = $this->db->get();
            $result = $q->result_array();

            // to delete $this->no_newspaper_id
            for ($i = 0, $count = count($result); $i < $count; $i++)
            {
                if($result[$i]['newspaper_id'] == $this->no_newspaper_id)
                {
                    unset($result[$i]['newspaper_id']);
                    unset($result[$i]['designation']);
                }
                if($result[$i]['halqa_id'] == $this->no_halqa_id)
                {
                    unset($result[$i]['halqa_id']);
                    unset($result[$i]['halqa']);
                }
            }

            return $result;
        }

        /**
         * [get_columnists_dropdown Returns all columnists name and if for dropdown 
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all columnists]
         */
        public function get_columnists_dropdown()
        {
            $this->db->select('`columnist`.`id`, `columnist`.`name`');
            $this->db->order_by('`columnist`.`name`');
            $query = $this->db->get('`columnist`');
            $result = $query->result_array();
            return $result;
        }         

        /**
         * [get_columnist_by_id Fetchs a columnist record from the database by columnist id]
         * @param  [type]  $id   [columnist id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [columnist record is returned]
         */
        public function get_columnist_by_id($id)
        {
            $this->db->select('`columnist`.`id`, `columnist`.`name`, `columnist`.`image`, `columnist`.`profile_image`,
            , `columnist`.`likes`, `columnist`.`dislikes`, `columnist`.`introduction`, `columnist`.`dob`
            , `city`.`name` AS city');

            $this->db->from('`columnist`');
        
            $this->db->join('`city`', '`columnist`.`city` = `city`.`id`', 'left');

            $this->db->where(array('`columnist`.`id`' => $id)); 
            
            $q = $this->db->get();

            $result = $q->result_array();
    
            return $result[0];
        }

        public function get_likes_dislikes()
        {

            $q = $this->db->get_where('like-dislike', array('user_id' => $this->input->post('user_id'), 'action' => 'like', 
                                        'entity_id' =>  $this->input->post('entity_id'), 'entity' => 'columnist')); 

            $q2 = $this->db->get_where('like-dislike', array('user_id' => $this->input->post('user_id'), 'action' => 'dislike', 
                                        'entity_id' =>  $this->input->post('entity_id'), 'entity' => 'columnist')); 

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
         * [get_attachments: Attachs additional attachments to the columnist record e.g newspaper_id
         *  is passed to get the designation name against the newspaper_id of columnist]
         * @param  [array] $attachments [columnist record]
         * @return [array]              [columnist record with attachments e.g. newspaper_id designation name 
         * against newspaper_id of columnist]
         */
        public function get_attachments($attachments, $attach_columnist_details = TRUE)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                // TRUE is passed 
                $attachments[$i]['city'] = $this->autocomplete_model->get_city_by_id($attachments[$i]['city'], FALSE, TRUE); 
                $attachments[$i]['newspaper_id'] = $this->newspaper_model->get_newspapers_by_columist($attachments[$i]['id'], FALSE, TRUE);
            }

            return $attachments;
        }

        public function record_count() 
        {
            return $this->db->count_all("columnist");
        }

        public function fetch_columnists($limit, $start, $attach_specialization = TRUE) 
        {
            $this->db->select('`columnist`.`id`, `columnist`.`name`, `columnist`.`profile_image`, `columnist`.`likes`, `columnist`.`dislikes`,
                                `columnist`.`city`, `columnist`.`dob`');
                  
            $this->db->limit($limit, $start);
         
            $this->db->order_by('`name`');

            $query = $this->db->get('columnist');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $this->get_attachments($result, TRUE); 
                return $result;
            }
            return FALSE;
        }


        /**
         * [get_columnist_readme_by_id Fetchs a columnist record from the database by columnist id]
         * @param  [type]  $id   [columnist id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [columnist record is returned]
         */
        public function get_columnist_readme_by_id($id, $action)
        {
            if($action == 'intro')
            {
                $this->db->select('columnist.introduction');                
            }
            else
            {
                $this->db->select('columnist.election_history');                
            }            

            $this->db->from('columnist');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
        
            // $result = $this->get_attachments($result);

            return $result[0]; 
        }

        /**
         * [insert_like_dislike: Inserts a columnist record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function like_dislike_action_exists($opposite_action = '')
        {   
            unset($q);     
            
            $this->delete_columnist('like-dislike',$this->user_id ,$this->main_entity_id, $this->opposite_action);

            $counter = empty($opposite_action) ? 2 : 1;

            for ($i = 0; $i < $counter; $i++)
            { 
                $field = ($i == 0) ? $this->opposite_action_field : $this->action_field;
        
                $this->db->select('columnist.' . $field);        

                $this->db->where(array('id' => $this->main_entity_id)); 
                
                $q = $this->db->get('columnist'); 

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
        
                $this->db->where('id', $this->main_entity_id);

                if ($this->db->update('columnist', $data)) 
                {
                    $this->delete_columnist('like-dislike',$this->user_id ,$this->main_entity_id, $this->action);
                }
            }
            // echo json_encode(array('already_' . $this->action . 'd'  => 'already-' . $this->action . 'd_' . $count));
            // return TRUE;
        }
        
        /**
         * [insert_like_dislike: Inserts a columnist record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike()
        {
            $this->user_id = strtolower(trim($this->db->escape($this->input->post('user_id')), "' "));
            $this->action = strtolower(trim($this->db->escape($this->input->post('action')), "' "));
            $this->main_entity_id = strtolower(trim($this->db->escape($this->input->post('main_entity_id')), "' "));

            // to cross check if $this->action = like then assign $this->opposite_action = dislike to remove it id needed
            $this->opposite_action = $this->action == 'like' ? 'dislike' : 'like';

            $action_query = $this->db->get_where('like-dislike', array('user_id' => $this->user_id, 'action' => $this->action, 
                                        'entity_id' => $this->main_entity_id, 'entity' => 'columnist')); 
            $opposite_action_query = $this->db->get_where('like-dislike', array('user_id' => $this->user_id, 'action' => $this->opposite_action, 
                                        'entity_id' => $this->main_entity_id, 'entity' => 'columnist')); 

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
                // $this->delete_columnist('like-dislike',$this->user_id ,$this->main_entity_id, $this->opposite_action);
                $this->insert_like_dislike_details($this->action, $this->action_field);
                    $this->db->select("columnist.$this->action_field, columnist.$this->opposite_action_field");        

                $this->db->where(array('id' => $this->main_entity_id)); 
                
                $q = $this->db->get('columnist'); 

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
         * [insert_like_dislike: Inserts a columnist record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike_details($action = '', $action_field = '')
        {
            unset($q);

            $this->action = empty($action) ? $this->action : $action;

            $this->action_field = empty($action_field) ? $this->action_field : $action_field;

            $this->db->select('columnist.' . $this->action_field);

            $this->db->where(array('id' => $this->main_entity_id)); 
            
            $q = $this->db->get('columnist'); 
            $result = $q->result_array()[0];

            $this->count = $result[$this->action_field];
            
            $this->count++;

            $data = array(

                $this->action_field => $this->count

            );

            $this->db->where('id', $this->main_entity_id);

            if ($this->db->update('columnist', $data)) 
            {        
                unset($data);         
                $data = array(

                    'user_id' => $this->user_id,

                    'entity_id' => $this->main_entity_id,
                    
                    'action' => $this->action, 
                    
                    'entity' => 'columnist' 

                );

                if($this->db->insert('like-dislike', $data))
                {
                    return TRUE;
                }
                else
                {
                    return FALSE;
                }
            }
        }
        
        /**
         * [delete_columnist: Delete a columnist record from the database]
         * @param  [int] $id    [columnist id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_columnist($table, $user_id = '', $columnist_id = '', $action = '')
        {
            if(!empty($columnist_id) && !empty($user_id))
            {
                if ($this->db->delete($table, array('entity_id' => $columnist_id, 'user_id' => $user_id, 'action' => $action))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete('columnist', array('id' => $columnist_id))) 
                {
                    return TRUE;
                }
            }
        }    

    }