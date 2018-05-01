<?php  
	class Login_model extends CI_Model {

        private $salt,
                $email,
                $password,
                $db_password,
                $user_id;
    
        public function __construct()
        {
            parent::__construct();        		
        }

        public function login()
        {
            $this->email = html_escape(strtolower(trim($this->db->escape($this->input->post('login_email')), "''")));
            $this->password = html_escape(trim($this->db->escape($this->input->post('login_password')), "''"));

            if($user = $this->get_user($this->email))            
            {
                foreach ($user as $key) 
                {
                    $this->db_password =  $key['password'];
                    $this->salt =  $key['salt'];
                    $this->user_id =  $key['id'];
                    $this->first_name =  $key['first_name'];
                    $this->picture =  $key['picture'];
                }

                $this->password = hash('sha512', $this->password . $this->salt);
      
                if($this->password === $this->db_password)
                {
                    return array('id' => $this->user_id, 'first_name' => $this->first_name, 'picture' => $this->picture);
                }
                    return FALSE;    
            }
            return FALSE;
        }

        public function get_user($email)
        {
            $field = is_numeric($email) ? 'mobile_number' : 'email';
            $sql = "SELECT * FROM user WHERE $field = ?";
            $q = $this->db->query($sql, array($email));

            if($q->num_rows() > 0)
            {
                return $q->result_array();
            }
                return FALSE;
        }
	}