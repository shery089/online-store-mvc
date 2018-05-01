<?php  

    /**
    * Halqa_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_halqa
    *          update_halqa
    *          delete_halqa
    *          get_halqa_by_id
    *          get_attachments
    *          record_count
    *          fetch_halqas
    */

	class Halqa_model extends CI_Model {

        private $name,
                $permissions;

        public function __construct()
        {
            parent::__construct();       		
            $this->load->model('admin/halqa_type_model');
        }

        /**
         * [insert_halqa: Inserts a halqa record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_halqa()
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), " "));
            
            $this->type = strtolower(trim($this->db->escape($this->input->post('type')), " "));
            
            $data = array(

                'name' => $this->name,

                'type' => $this->type

            );

            if ($this->db->insert('halqa', $data))
            {
                return TRUE;
            }
        }   

        /**
         * [update_halqa: Updates a halqa record into the database]
         * @return [boolean] [if updation is performed successfully 
         * then, returns TRUE.]
         */
        public function update_halqa($id)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), " "));
            
            $this->type = strtolower(trim($this->db->escape($this->input->post('type')), " "));

            $data = array(

                'name' => $this->name,

                'type' => $this->type

            );

            $this->db->where('id', $id);

            if ($this->db->update('halqa', $data))
            {
                return TRUE;
            }
        }

        /**
         * [get_halqas Returns all halqa's e.g na-1, na-2]
         * @return [array] [return all halqa's]
         */
        public function get_halqas()
        {
            $query = $this->db->get('halqa');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_halqa_by_id Fetchs a halqa record from the database by halqa id]
         * @param  [type]  $id   [halqa id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [halqa record is returned]
         */
        public function get_halqa_by_id($id)
        {
            $q = $this->db->get_where('halqa', array('id' => $id)); 
            
            return $q->result_array()[0];
        }

        /**
         * [get_halqas_by_type Fetchs a halqa record from the database by halqa type id]
         * @param  [string]  $type   [halqa type id whom record is to be fetched]
         * @return [array] [halqa records of specfic type are returned]
         */
        public function get_halqas_by_type($type)
        {
            $type = $this->halqa_type_model->get_id_by_key($type);
            $type = $type['id'];
            $q = $this->db->get_where('halqa', array('type' => $type)); 
            
            return $q->result_array();
        }

        /**
         * [get_halqas_by_type Fetchs a halqa record from the database by halqa type id]
         * @param  [string]  $type   [halqa type id whom record is to be fetched]
         * @return [array] [halqa records of specfic type are returned]
         */
        public function get_halqas_by_type_id($type, $specfic = FALSE)
        {
            if($specfic)
            {
                $this->db->select('halqa.id');
                $this->db->from('halqa');
                $this->db->where(array('type' => $type));
                $q = $this->db->get();
            }
            else
            {
                $q = $this->db->get_where('halqa', array('type' => $type));
            }
            return $q->result_array();
        }

        /**
         * [delete_halqa: Delete a halqa record from the database]
         * @param  [int] $id    [halqa id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_halqa($id)
        {
            if ($this->db->delete('halqa', array('id' => $id))) 
            {
                return TRUE;
            }
            return FALSE;
        }   

        /**
         * [get_by_id_by_key Returns a halqa id from the database by key e.g. NA-1 id is 1]
         * @param  [type]  $key   [halqa key whose id is to returned]
         * @return [array] [halqa record is returned]
         */
        public function get_id_by_key($key)
        {
            $this->db->select('halqa.id');
            $this->db->where('name', $key);
            $q = $this->db->get('halqa');
            return $q->result_array()[0];
        }

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count() 
        {
            return $this->db->count_all("halqa");
        }

        /**
         * [fetch_halqa Returns halqas with a $limit defined in halqa controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any halqas then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_halqas($limit, $start) 
        {
            $this->db->limit($limit, $start);
            $query = $this->db->get('halqa');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
            return false;
        }

        public function	add_na_halqa_bulk($na_halqa)
        {
            if ($this->db->insert_batch('halqa', $na_halqa))
            {
                return TRUE;
            }
        }
	}	
?>