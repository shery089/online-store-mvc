<?php  
	class Login_model extends CI_Model {

        private $salt;
        private $password;
        private $user_id;
    
        public function __construct()
        {
            parent::__construct(); 
            $this->load->model('admin/role_model');
        }

        public function login()
        {
            $email = html_escape(strtolower(trim($this->db->escape($this->input->post('email')), "' ")));
            $password = html_escape(trim($this->db->escape($this->input->post('password')), "' "));

            if($user = $this->get_user($email))            
            {

                if(gettype($user) == 'string')
                {
                    return TRUE;
                }
                else
                {
                    foreach ($user as $key) 
                    {
                        $this->password =  $key['password'];
                        $this->salt =  $key['salt'];
                        $this->user_id =  $key['id'];
                    }

                    $password = hash('sha512', $password . $this->salt);
                    
                    if($password === $this->password )
                    {
                        return $user;
                    }
                    return 'incorrect password';
                }
            }
            return FALSE;    
        }


        /**
         * [get_role_by_type Fetchs a role record from the database by role type id]
         * @param  [string]  $type   [role type id whom record is to be fetched]
         * @return [array] [role records of specfic type are returned]
         */
        public function get_role_by_name($role)
        {
            $type = $this->role_model->get_role_id_by_name($role);
            $type = $type['id'];
            $q = $this->db->get_where('role', array('role' => $role)); 
            
            return $q->result_array();
        }

        public function get_user($email)
        {
            $roles = array('admin', 'editor', 'uploader');
            for ($i = 0; $i < 3; $i++) 
            { 
                $role_ids[$i] = $this->role_model->get_role_id_by_name($roles[$i]);
            }

            $roles = ucwords(entity_decode(implode(',', array_column($role_ids, 'id'))));
            $field = is_numeric($email) ? 'mobile_number' : 'email';
            $sql = "SELECT * FROM user WHERE $field = ? AND role_id IN (?)";
            $q = $this->db->query($sql, array($email, $roles));
            if($q->num_rows() > 0)
            {
                return $q->result_array();
            }
                return 'email not found';
                // echo json_encode(array("email" => "<div class='error_prefix text-right'>Your provided " . str_replace("_", " ", $field) . 
                                    // " doesn't exist<div>"));
        }
	}	
?>