<?php  

    /**
    * Role_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_role
    *          update_role
    *          delete_role
    *          get_role_by_id
    *          get_attachments
    *          record_count
    *          fetch_roles
    */

	class Role_model extends CI_Model {

        private $name,
                $permissions;

        public function __construct()
        {
            parent::__construct();        		
        }

        /**
         * [insert_role: Inserts a role record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_role()
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->permissions  =  '{' . '"' . $this->name . '"' . ':1}';

            $data = array(

                'name' => $this->name,

                'permissions' => $this->permissions

            );

            if ($this->db->insert('user_role', $data))
            {
                return TRUE;
            }
        }

        /**
         * [update_role: Updates a role record into the database]
         * @return [boolean] [if updation is performed successfully 
         * then, returns TRUE.]
         */
        public function update_role($id)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->permissions  =  '{' . '"' . $this->name . '"' . ':1}';

            $data = array(

                'name' => $this->name,

                'permissions' => $this->permissions

            );

            $this->db->where('id', $id);

            if ($this->db->update('user_role', $data))
            {
                return TRUE;
            }
        }

        public function get_user_roles()
        {
            $query = $this->db->get('user_role');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_role_by_id Fetchs a role record from the database by role id]
         * @param  [type]  $id   [role id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [Role record is returned]
         */
        public function get_role_by_id($id)
        {
            $q = $this->db->get_where('user_role', array('id' => $id)); 
            
            return $q->result_array()[0];
        }

        /**
         * [get_role_id_by_name Fetchs a role record from the database by role name]
         * @param  [type]  $name   [role name whom record is to be fetched]
         * attibutes are retrieved for display/view purpose]
         * @return [array] [Role record is returned]
         */
        public function get_role_id_by_name($name)
        {
            $this->db->select('user_role.id');
            $this->db->where(array('name' => $name)); 
            $q = $this->db->get('user_role');
            
            return $q->result_array()[0];
        }

        /**
         * [get_role_id_by_name Fetchs a role record from the database by role name]
         * @param  [type]  $name   [role name whom record is to be fetched]
         * attibutes are retrieved for display/view purpose]
         * @return [array] [Role record is returned]
         */
        public function get_role_name_by_id($id)
        {
            $this->db->select('user_role.name AS role');
            $this->db->where(array('id' => $id));
            $q = $this->db->get('user_role');
            return $q->result_array()[0];
        }

        /**
         * [delete_role: Delete a role record from the database]
         * @param  [int] $id    [role id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_role($id)
        {
            if ($this->db->delete('user_role', array('id' => $id))) 
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
            return $this->db->count_all("user_role");
        }

        /**
         * [fetch_user_role Returns roles with a $limit defined in Role controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any roles then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_roles($limit, $start) 
        {
            $this->db->limit($limit, $start);
            $query = $this->db->get('user_role');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }
            return false;
        }	
	}	
?>