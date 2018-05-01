<?php  
    /**
    * Politician_model class is model of newspaper controller it performs 
    * basic CRUD operations
    * Methods: insert_newspaper
    *          update_newspaper
    *          delete_newspaper
    *          get_newspaper_by_id
    *          get_attachments
    *          record_count
    *          fetch_newspapers
    */

	class Newspaper_model extends CI_Model {

        private $name,
                $thumbnail;

        public function __construct()
        {
            parent::__construct();        	
        }

        /**
         * [insert_newspaper: Inserts a newspaper record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_newspaper($thumb_image = '')
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

            // if image is empty then set default flag i.e. no_image_600.png
            $this->thumbnail = empty($thumb_image) ? 'no_image_600_thumb.png' : $thumb_image;

            $data = array(

                'name' => $this->name,
              
                'thumbnail' => $this->thumbnail

            );

            if ($this->db->insert('newspaper', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_newspaper: Updates a newspaper record into the database]
         * @param  [int] $id    [newspaper id whom record is updating]
         * @param  [string] $flag [new flag name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_newspaper($id, $image_thumb)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            // if image is empty then set default image i.e. no_image_600.png
            if(!empty($image_thumb))
            {                
                $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
            }

            $data = array(

                'name' => $this->name,

                'thumbnail' => $this->thumbnail
            ); 

            $this->db->where('id', $id);
                
            if ($this->db->update('newspaper', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [get_newspapers Returns all details of newspaper 
         * e.g id = 1, name = Nawaz Sharif) ................]
         * @return [array] [return all details of newspapers]
         */
        public function get_newspapers()
        {
            $query = $this->db->get('newspaper');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [delete_newspaper: Delete a newspaper record from the database]
         * @param  [int] $id    [newspaper id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_newspaper($id)
        {
            if ($this->db->delete('newspaper', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_newspaper_by_id Fetchs a newspaper record from the database by newspaper id]
         * @param  [type]  $id   [newspaper id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [newspaper record is returned]
         */
        public function get_newspaper_by_id($id)
        {
            $this->db->select('newspaper.id, newspaper.name, newspaper.thumbnail');
            
            $this->db->from('newspaper');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();

            return $result[0]; 
        }

        public function get_newspapers_by_columist($id)
        {
            
            $this->db->select('newspaper.id, newspaper.name, newspaper.thumbnail');
            
            $this->db->from('newspaper');

            $this->db->join('columnist_details', 'columnist_details.newspaper_id = newspaper.id', 'left');
        
            $this->db->where(array('columnist_details.columnist_id' => $id)); 
            
            $q = $this->db->get(); 
            $result = $q->result_array();
            return $result; 
        }

        public function record_count() 
        {
            return $this->db->count_all("newspaper");
        }

        public function fetch_newspapers($limit, $start, $attach_specialization = TRUE) 
        {
            $this->db->select('*');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('newspaper');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                return $result;
            }

            return FALSE;
        }
    }