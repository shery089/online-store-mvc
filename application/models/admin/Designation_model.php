<?php  

    /**
    * Designation_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_designation
    *          update_designation
    *          delete_designation
    *          get_designation_by_id
    *          get_attachments
    *          record_count
    *          fetch_designations
    */

	class Designation_model extends CI_Model {

        private $name;

        public function __construct()
        {
            parent::__construct();        		
        }

        /**
         * [insert_designation: Inserts a designation record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_designation()
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $data = array(

                'name' => $this->name
            );

            if ($this->db->insert('user_designation', $data))
            {
                return TRUE;
            }
        }

        /**
         * [update_designation: Updates a designation record into the database]
         * @return [boolean] [if updation is performed successfully 
         * then, returns TRUE.]
         */
        public function update_designation($id)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->permissions  =  '{' . '"' . $this->name . '"' . ':1}';

            $data = array(

                'name' => $this->name
            );

            $this->db->where('id', $id);

            if ($this->db->update('user_designation', $data))
            {
                return TRUE;
            }
        }

        /**
         * [get_user_designations This function will returns all designations in ascending order]
         * @return [array] [returns all designations]
         */
        public function get_user_designations()
        {
            $this->db->select('*');
            $this->db->from('user_designation');
            $this->db->order_by('user_designation.name');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_designation_by_id Fetchs a designation record from the database by designation id]
         * @param  [type]  $id   [designation id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [designation record is returned]
         */
        public function get_designation_by_id($id)
        {
            $q = $this->db->get_where('user_designation', array('id' => $id)); 
            return $q->result_array()[0];
        }

        /**
         * [get_by_id_by_key Returns a user_designation id from the database by key e.g. name president]
         * @param  [type]  $key   [user_designation key whose id is to returned]
         * @return [array] [user_designation record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('user_designation.id');
            $this->db->where('user_designation.name', $key);
            $this->db->from('user_designation');
            $q = $this->db->get();
            return $q->result_array()[0];
        }

        /**
         * [delete_designation: Delete a designation record from the database]
         * @param  [int] $id    [designation id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_designation($id)
        {
            if ($this->db->delete('user_designation', array('id' => $id))) 
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
            return $this->db->count_all("user_designation");
        }

        /**
         * [fetch_user_designation Returns designations with a $limit defined in designation controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any designations then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_designations($limit, $start) 
        {
            $this->db->limit($limit, $start);
            $query = $this->db->get('user_designation');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
            return false;
        }	
	}	
?>