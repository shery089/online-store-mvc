<?php  
    /**
    * Political_party_model class is model of political_party controller it performs 
    * basic CRUD operations
    * Methods: insert_political_party
    *          update_political_party
    *          delete_political_party
    *          get_political_party_by_id
    *          get_attachments
    *          record_count
    *          fetch_political_partys
    */

	class Political_party_model extends CI_Model {

        private $name,
                $leader,
                $flag,
                $address;

        public function __construct()
        {
            parent::__construct();        		
            $this->load->model('admin/designation_model');
        }

        /**
         * [insert_political_party: Inserts a political_party record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_political_party($flag = '', $thumb_image = '', $profile_image = '')
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->leader = strtolower(trim($this->db->escape($this->input->post('leader')), "' "));

            // if flag is empty then set default flag i.e. no_image_600.png
            $this->flag = empty($flag) ? 'no_image_600.png' : $flag;
            
            // if($this->flag !== 'no_image_600.png')
            // {
            //     $this->thumbnail = PARTY_IMAGE_PATH . 'thumb_' . $this->flag;
            //     $this->profile_image = PARTY_IMAGE_PATH . 'thumb_' . $this->flag;
            // }

            $this->thumbnail = $this->flag == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

            $this->profile_image = $this->flag == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $this->address = strtolower(trim($this->db->escape($this->input->post('address')), "' "));

            $this->designation = strtolower(trim($this->db->escape($this->input->post('designation')), "' "));

            $this->founded_date = strtolower(trim($this->db->escape($this->input->post('founded_date')), "' "));

            $this->introduction = strtolower(trim($this->db->escape($this->input->post('introduction')), "' "));

            $this->election_history = strtolower(trim($this->db->escape($this->input->post('election_history')), "' "));

            $data = array(

                'name' => $this->name,

                'leader' => $this->leader,

                'founded_date' => $this->founded_date,

                'designation_id' => $this->designation,

                'flag' => $this->flag,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image,

                'founded_date' => $this->founded_date,

                'address' => $this->address,
                
                'introduction' => $this->introduction,

                'election_history' => $this->election_history
            );

            if ($this->db->insert('political_party', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_political_party: Updates a political_party record into the database]
         * @param  [int] $id    [political_party id whom record is updating]
         * @param  [string] $flag [new flag name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_political_party($id, $flag, $image_thumb, $profile_image)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->leader = strtolower(trim($this->db->escape($this->input->post('leader')), "' "));

            // if flag is empty then set default flag i.e. no_image_600.png
            if(!empty($flag))
            {                
                $this->flag = trim($this->db->escape($flag), "' ");
                $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
                $this->profile_image = trim($this->db->escape($profile_image), "' ");
            }

            $this->designation = strtolower(trim($this->db->escape($this->input->post('designation')), "' "));

            $this->founded_date = strtolower(trim($this->db->escape($this->input->post('founded_date')), "' "));

            $this->address = strtolower(trim($this->db->escape($this->input->post('address')), "' "));

            $this->introduction = strtolower(trim($this->db->escape($this->input->post('introduction')), "' "));

            $this->election_history = strtolower(trim($this->db->escape($this->input->post('election_history')), "' "));

            $data = array(

                'name' => $this->name,

                'leader' => $this->leader,

                'designation_id' => $this->designation,

                'flag' => $this->flag,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image,

                'founded_date' => $this->founded_date,

                'introduction' => $this->introduction,

                'election_history' => $this->election_history,

                'address' => $this->address
            ); 

            $this->db->where('id', $id);
                
            if ($this->db->update('political_party', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [get_political_parties Returns all details of political_parties 
         * e.g id = 1, name = Pakistan Muslim League (N) ................]
         * @return [array] [return all details of political_parties]
         */
        public function get_political_parties()
        {
            $query = $this->db->get('political_party');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_political_parties_dropdown Returns all political_parties name and if for dropdown 
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all political_parties]
         */
        public function get_political_parties_dropdown()
        {
            $this->db->select('`political_party`.`id`, `political_party`.`name`');
            $this->db->order_by('`political_party`.`name`');
            $query = $this->db->get('`political_party`');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [delete_political_party: Delete a political_party record from the database]
         * @param  [int] $id    [political_party id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_political_party($id)
        {
            if ($this->db->delete('political_party', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_political_party_by_id Fetchs a political_party record from the database by political_party id]
         * @param  [type]  $id   [political_party id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [political_party record is returned]
         */
        public function get_political_party_by_id($id, $edit = FALSE, $post = FALSE )
        {
            if($edit)
            {
                $this->db->select('`political_party`.`id`, `political_party`.`name`, `political_party`.`designation_id`, 
                                    `political_party`.`flag`, `political_party`.`address`, `political_party`.`founded_date`,
                                    `political_party`.`leader`, `political_party`.`introduction`, `political_party`.`election_history`,
                                    `political_party`.`thumbnail`, `political_party`.`profile_image`');                
            }
            else if($post)
            {
                $this->db->select('`political_party`.`id`, `political_party`.`name`');
            }
            else
            {
                $this->db->select('*');                
            }

            $this->db->from('political_party');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
            
            if(!$post)
            {
                $result = $this->get_attachments($result);
            }
        
            return $result[0]; 

        }

        /**
         * [get_by_id_by_key Returns a political_party id from the database by key e.g.  Pakistan Muslim League (N)]
         * @param  [type]  $key   [political_party key whose id is to returned]
         * @return [array] [political_party record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('political_party.id');
            $this->db->where('political_party.name', $key);
            $this->db->from('political_party');
            $q = $this->db->get();
            if ($q->num_rows() > 0) 
            {
                return $q->result_array()[0];        
            }
            else
            {
                echo $key;
                die;
            }

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

        public function fetch_political_parties($limit, $start, $attach_specialization = TRUE) 
        {
            $this->db->select('*');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('political_party');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                $result = $this->get_attachments($result);

                return $result;
            }

            return false;
        }

/*        public function insert_political_party_bulk_by_csv($csv)
        {
            if ($this->db->insert_batch('political_party', $csv))
            {
                return TRUE;
            }
        }*/	
	}