<?php  

    /**
    * Halqa_type_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_halqa_type
    *          update_halqa_type
    *          delete_halqa_type
    *          get_halqa_type_by_id
    *          get_attachments
    *          record_count
    *          fetch_halqa_types
    */

	class Halqa_type_model extends CI_Model {

        private $name,
                $permissions;

        public function __construct()
        {
            parent::__construct();        		
        }

        /**
         * [insert_halqa_type: Inserts a halqa_type record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_halqa_type()
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), " "));
            
            $this->abbreviation = strtolower(trim($this->db->escape($this->input->post('abbreviation')), " "));
            
            $data = array(

                'name' => $this->name,

                'abbreviation' => $this->abbreviation

            );

            if ($this->db->insert('halqa_type', $data))
            {
                return TRUE;
            }
        }   

        /**
         * [update_halqa_type: Updates a halqa_type record into the database]
         * @return [boolean] [if updation is performed successfully 
         * then, returns TRUE.]
         */
        public function update_halqa_type($id)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), " "));
            
            $this->abbreviation = strtolower(trim($this->db->escape($this->input->post('abbreviation')), " "));

            $data = array(

                'name' => $this->name,

                'abbreviation' => $this->abbreviation

            );

            $this->db->where('id', $id);

            if ($this->db->update('halqa_type', $data))
            {
                return TRUE;
            }
        }

        public function get_halqa_types($specific_cols = FALSE)
        {
            if($specific_cols)
            {
                $this->db->select('halqa_type.id, halqa_type.name');
                $this->db->from('halqa_type');
                $query = $this->db->get();
            }
            else
            {
                $query = $this->db->get('halqa_type');
            }

            $result = $query->result_array();
            
            return $result;
        }

        /**
         * [get_halqa_type_by_id Fetchs a halqa_type record from the database by halqa_type id]
         * @param  [type]  $id   [halqa_type id whom record is to be fetched]
         * @return [array] [halqa_type record is returned]
         */
        public function get_halqa_type_by_id($id)
        {
            $q = $this->db->get_where('halqa_type', array('id' => $id)); 
            
            return $q->result_array()[0];
        }

        /**
         * [get_by_id_by_key Returns a halqa_type id from the database by key e.g. abbrevation NA]
         * @param  [type]  $key   [halqa_type key whose id is to returned]
         * @return [array] [halqa_type record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('halqa_type.id');
            $this->db->where('abbreviation', $key);
            $q = $this->db->get('halqa_type');
            return $q->result_array()[0];
        }

        /**
         * [delete_halqa_type: Delete a halqa_type record from the database]
         * @param  [int] $id    [halqa_type id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_halqa_type($id)
        {
            if ($this->db->delete('halqa_type', array('id' => $id))) 
            {
                return TRUE;
            }
            return FALSE;
        }       

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count() 
        {
            return $this->db->count_all("halqa_type");
        }

        /**
         * [fetch_halqa_type Returns halqa_types with a $limit defined in halqa_type controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any halqa_types then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_halqa_types($limit, $start) 
        {
            $this->db->limit($limit, $start);
            $query = $this->db->get('halqa_type');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
            return false;
        }	
	}	
?>