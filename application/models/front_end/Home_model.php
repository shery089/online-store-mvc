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

	class Home_model extends CI_Model 
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('admin/user_model');
        }

        public function get_three_random_politicians()
        {
            $this->db->select('politician.id, politician.name, politician.thumbnail, politician.political_party_id AS political_party_id, political_party.name AS political_party');

            $this->db->from('politician');

            $this->db->join('political_party', 'political_party.id = politician.political_party_id', 'left');

            $this->db->limit(3);          

            $this->db->order_by('RAND()');

            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }
            return $result;
        }
        
        public function get_three_random_political_parties()
        {
            $this->db->select('political_party.id, political_party.name, political_party.thumbnail, political_party.founded_date');

            $this->db->limit(3);          

            $this->db->order_by('RAND()');

            $q = $this->db->get('political_party');

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }
            return $result;
        }

        public function get_three_random_columnists()
        {
            $this->db->select('columnist.id, columnist.name, columnist.thumbnail, newspaper.name AS newspaper');

            $this->db->from('columnist');

            $this->db->join('columnist_details', 'columnist.id = columnist_details.columnist_id', 'left');

            $this->db->join('newspaper', 'newspaper.id = columnist_details.newspaper_id', 'left');
            
            $this->db->group_by('columnist.name');

            $this->db->limit(3);          

            $this->db->order_by('RAND()');

            $q = $this->db->get();

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }
            return $result;
        }

        public function get_random_post()
        {
            $this->db->select('`story`.`id`, `story`.`post`, `story`.`likes`, `story`.`dislikes`, 
                    `story`.`posted_by`, `story`.`posted_date`, DATE_FORMAT(`story`.`posted_time`, "%h:%i:%s %p") AS posted_time');

            $this->db->from('story');
            
            $this->db->limit(1);          

            $this->db->order_by('RAND()');

            $q = $this->db->get(); 

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
                $result = $this->get_attachments($result);
            }
            else
            {
                $result = array();
            }

            return $result;
        }

        public function get_post_by_id($id)
        {
            $this->db->select('`story`.`id`, `story`.`post`, `story`.`likes`, `story`.`dislikes`, 
                    `story`.`posted_by`, `story`.`posted_date`, DATE_FORMAT(`story`.`posted_time`, "%h:%i:%s %p") AS posted_time');

            $this->db->from('story');
            
            $this->db->where('`story`.`id`', $id);

            $q = $this->db->get(); 

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
                $result = $this->get_attachments($result);
            }
            else
            {
                $result = array();
            }

            return $result;
        }

        public function get_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                $attachments[$i]['user_details'] = $this->user_model->get_user_by_id($attachments[$i]['posted_by'], FALSE, array('full_name')); 
            }

            return $attachments;
        }
    }