<?php  

    /**
    * Halqa_type_model class is model of User controller it performs 
    * basic Read operations
    * Methods: get_halqa_type_by_id
    *          get_halqa_types
    *          get_id_by_key
    */

	class Halqa_type_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();        		
        }

        public function get_halqa_types()
        {
            $query = $this->db->get('halqa_type');
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
	}