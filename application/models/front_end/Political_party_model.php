<?php  
    /**
    * Politicial_party class is model of political_party controller it performs 
    * basic CRUD operations
    * Methods: insert_political_party
    *          update_political_party
    *          delete_political_party
    *          get_political_party_by_id
    *          get_attachments
    *          record_count
    *          fetch_political_parties
    */

	class Political_party_model extends CI_Model 
    {

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
            $this->load->model('admin/designation_model');
        }

        /**
         * [insert_like_dislike: Inserts a political_party record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function like_dislike_action_exists($opposite_action = '')
        {   
            unset($q);     
            
            $this->delete_political_party('like-dislike',$this->user_id ,$this->main_entity_id, $this->opposite_action);

            $counter = empty($opposite_action) ? 2 : 1;

            for ($i = 0; $i < $counter; $i++)
            { 
                $field = ($i == 0) ? $this->opposite_action_field : $this->action_field;
        
                $this->db->select('political_party.' . $field);        

                $this->db->where(array('id' => $this->main_entity_id)); 
                
                $q = $this->db->get('political_party'); 

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

                if ($this->db->update('political_party', $data)) 
                {
                    $this->delete_political_party('like-dislike',$this->user_id ,$this->main_entity_id, $this->action);
                }
            }
            // echo json_encode(array('already_' . $this->action . 'd'  => 'already-' . $this->action . 'd_' . $count));
            // return TRUE;
        }
        
        /**
         * [insert_like_dislike: Inserts a political_party record into the database]
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
                                        'entity_id' => $this->main_entity_id, 'entity' => 'political_party')); 
            $opposite_action_query = $this->db->get_where('like-dislike', array('user_id' => $this->user_id, 'action' => $this->opposite_action, 
                                        'entity_id' => $this->main_entity_id, 'entity' => 'political_party')); 

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
                // $this->delete_political_party('like-dislike',$this->user_id ,$this->main_entity_id, $this->opposite_action);
                $this->insert_like_dislike_details($this->action, $this->action_field);
                    $this->db->select("political_party.$this->action_field, political_party.$this->opposite_action_field");        

                $this->db->where(array('id' => $this->main_entity_id)); 
                
                $q = $this->db->get('political_party'); 

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
         * [insert_like_dislike: Inserts a political_party record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_like_dislike_details($action = '', $action_field = '')
        {
            unset($q);

            $this->action = empty($action) ? $this->action : $action;

            $this->action_field = empty($action_field) ? $this->action_field : $action_field;

            $this->db->select('political_party.' . $this->action_field);

            $this->db->where(array('id' => $this->main_entity_id)); 
            
            $q = $this->db->get('political_party'); 
            $result = $q->result_array()[0];

            $this->count = $result[$this->action_field];
            
            $this->count++;

            $data = array(

                $this->action_field => $this->count

            );

            $this->db->where('id', $this->main_entity_id);

            if ($this->db->update('political_party', $data)) 
            {        
                unset($data);         
                $data = array(

                    'user_id' => $this->user_id,

                    'entity_id' => $this->main_entity_id,
                    
                    'action' => $this->action, 
                    
                    'entity' => 'political_party' 

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
         * [get_doctor_specialization Fetchs a political_party details record from the database by political_party id]
         * @param  [type]  $id   [political_party id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [political_party record is returned]
         *//*
        public function get_political_party_details($political_party_id)
        {
            $this->db->select('political_party_details.id, political_party_details.political_party_id, political_party_details.designation_id, 
                            political_party_details.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
            $this->db->from('political_party_details');
            $this->db->where('political_party_details.political_party_id', $political_party_id);
            $this->db->join('halqa', 'halqa.id = political_party_details.halqa_id', 'inner');
            $this->db->join('user_designation', 'user_designation.id = political_party_details.designation_id', 'inner');
            $q = $this->db->get();
            return $q->result_array();
        }*/

        /**
         * [get_political_party_by_id Fetchs a political_party record from the database by political_party id]
         * @param  [type]  $id   [political_party id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [political_party record is returned]
         */
        public function get_political_party_by_id($id, $specific_cols = '')
        {

            if($id == '')
            {
                return array();
            }
            
            if($specific_cols)
            {
                $this->db->select('political_party.id, political_party.name');
            }
            else
            {
                $this->db->select('*');            
            }

            $this->db->from('political_party');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
            
            if(!$specific_cols)
            {
                $result = $this->get_attachments($result);
            }
            $result = (!(empty($result))) ? $result[0] : $result;
            return $result;
        }

        /**
         * [get_political_party_by_id Fetchs a political_party record from the database by political_party id]
         * @param  [type]  $id   [political_party id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [political_party record is returned]
         */
        /*public function get_political_party_votes_id($id)
        {

            $this->db->select('political_party_vote.halqa_id AS casted_halqa_id, political_party_vote.vote_type AS casted_vote_type');                

            $this->db->from('political_party_vote');

            $this->db->join('political_party', 'political_party.id = political_party_vote.political_party_id', 'inner');
        
            $this->db->where(array('political_party.id' => $id)); 
            
            $q = $this->db->get(); 
            
            $result = $q->result_array();
            
            $count_halqas = array_column($result, 'casted_halqa_id');
            
            $count_vote_types = array_column($result, 'casted_vote_type');

            $count_halqas = array_count_values($count_halqas);
            $count_halqas_keys = array_keys($count_halqas);
            $count_halqas_values = array_values($count_halqas);

            for ($i = 0, $count = count($count_halqas_keys); $i < $count; $i++)
            { 
                $current_i_halqa_id = $count_halqas_keys[$i];
                $halqa_details = $this->halqa_model->get_halqa_by_id($current_i_halqa_id);
                $halqa_name[] = $halqa_details['name'];
            }

            // Array ( [na-60] => 2 [pp-7] => 2 )
            $halqa_keys = array_combine($halqa_name, $count_halqas_values);

            // Array([national assembly] => 2[province of punjab] => 2)
            $count_vote_types = array_count_values($count_vote_types);
                  
            return array('halqa_keys' => $halqa_keys, 'count_vote_types' => $count_vote_types);
        }
*/
        /**
         * [get_political_party_readme_by_id Fetchs a political_party record from the database by political_party id]
         * @param  [type]  $id   [political_party id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [political_party record is returned]
         */
        public function get_political_party_readme_by_id($id, $action)
        {
            if($action == 'intro')
            {
                $this->db->select('political_party.introduction');                
            }
            else
            {
                $this->db->select('political_party.election_history');                
            }            

            $this->db->from('political_party');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
        
            return $result[0]; 
        }

        /**
         * [get_attachments: Attachs additional attachments to the political_party record e.g designation_id
         *  is passed to get the designation name against the designation_id of political_party]
         * @param  [array] $attachments [political_party record]
         * @return [array]              [political_party record with attachments e.g. designation_id designation name 
         * against designation_id of political_party]
         */
        public function get_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++)
            { 
                $attachments[$i]['designation'] = $this->designation_model->get_designation_by_id($attachments[$i]['designation_id']); 
            }

            return $attachments;
        }

        public function record_count() 
        {
            return $this->db->count_all("political_party");
        }

        public function fetch_political_parties($limit, $start) 
        {
            $this->db->select('political_party.id, political_party.name, political_party.profile_image');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('political_party');

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                // $result = $this->get_attachments($result);

                return $result;
            }

            return false;
        }	

        /**
         * [delete_political_party: Delete a political_party record from the database]
         * @param  [int] $id    [political_party id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_political_party($table, $user_id = '', $political_party_id = '', $action = '')
        {
            if(!empty($political_party_id) && !empty($user_id))
            {
                if ($this->db->delete($table, array('entity_id' => $political_party_id, 'user_id' => $user_id, 'action' => $action))) 
                {
                    return TRUE;
                }
            }
            else
            {
                if ($this->db->delete('political_party', array('id' => $political_party_id))) 
                {
                    return TRUE;
                }
            }
        }    

        public function get_likes_dislikes()
        {

            $q = $this->db->get_where('like-dislike', array('user_id' => $this->input->post('user_id'), 'action' => 'like', 
                                        'entity_id' =>  $this->input->post('entity_id'), 'entity' => 'political_party')); 

            $q2 = $this->db->get_where('like-dislike', array('user_id' => $this->input->post('user_id'), 'action' => 'dislike', 
                                        'entity_id' =>  $this->input->post('entity_id'), 'entity' => 'political_party')); 

            if($q->num_rows() > 0)
            {
                echo json_encode(array('like' => 'liked'));
            }
            else if($q2->num_rows() > 0)
            {
                echo json_encode(array('dislike' => 'disliked'));
            }
        }

        public function get_top_ten_liked_political_parties()
        {
            $this->db->select('political_party.id, political_party.name, political_party.likes');
         
            $this->db->limit(10);          

            $this->db->order_by('`political_party`.`likes`', 'desc');

            $this->db->where('`political_party`.`likes` >', '0');

            $query = $this->db->get('political_party');
            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                echo json_encode(array('result' => $result));
            }
            else
            {
                echo json_encode(array('no_result' => 'No Results Found!'));
            }
        }

        public function get_top_ten_disliked_political_parties()
        {
            $this->db->select('political_party.id, political_party.name, political_party.dislikes');
         
            $this->db->limit(10);          

            $this->db->order_by('`political_party`.`dislikes`', 'desc');

            $this->db->where('`political_party`.`dislikes` >', '0');

            $query = $this->db->get('political_party');
            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                echo json_encode(array('result' => $result));
            }
            else
            {
                echo json_encode(array('no_result' => 'No Results Found!'));
            }
        }

        public function insert_political_party_bulk_by_csv($csv)
        {
            if ($this->db->insert_batch('political_party', $csv))
            {
                return TRUE;
            }
        }
        
        public function post_a_comment($story_id, $user_id)
        {
            $user_id = trim($this->db->escape($user_id), "' ");
            $story_id = trim($this->db->escape($story_id), "' ");
            $comment = strtolower(trim($this->db->escape($this->input->post('comment')), "' "));

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
                return array('time' => $time, 'date' => $date, 'full_name' => ucwords($result['full_name']), 'comment' => $comment, 'comment_id' => $comment_id);
            }
        }
}