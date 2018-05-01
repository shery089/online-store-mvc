<?php  
    /**
    * User_model class is model of User controller it performs 
    * basic CRUD operations
    * Methods: insert_user
    *          update_user
    *          delete_user
    *          get_user_by_id
    *          get_attachments
    *          record_count
    *          fetch_users
    */

    class User_model extends CI_Model {

        private $user_name,
                $first_name,
                $middle_name,
                $last_name,
                $salt,
                $password,
                $email,
                $mobile_number,
                $role,
                $joined_date,
                $updated_date,
                $image;

        public function __construct()
        {
            parent::__construct();              
            $this->load->model('admin/role_model');
        }

        /**
         * [insert_user: Inserts a user record into the database]
         * @param  [string] $image [New random image name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_user($image = '', $thumb_image = '', $profile_image = '')
        {
            $this->user_name = strtolower(trim($this->db->escape($this->input->post('user_name')), "' "));
            
            $this->first_name = strtolower(trim($this->db->escape($this->input->post('first_name')), "' "));

            $this->middle_name = strtolower(trim($this->db->escape($this->input->post('middle_name')), "' "));

            $this->last_name = strtolower(trim($this->db->escape($this->input->post('last_name')), "' "));

            $this->password = trim($this->db->escape($this->input->post('password')), "' ");

            $this->salt = openssl_random_pseudo_bytes(32, $cstrong);
            $this->salt = uniqid('' , TRUE);
        
            $this->password = hash('sha512', $this->password . $this->salt);

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->mobile_number = trim($this->db->escape($this->input->post('mobile_number')), "' ");

            $this->role = strtolower(trim($this->db->escape($this->input->post('role')), "' "));
        
            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");
            
            $this->joined_date = $date;

            $this->updated_date = $date;
            
            // if image is empty then set default image i.e. no_image_600.png
            $this->image = empty($image) ? 'no_image_600.png' : $image;

            $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

            $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $data = array(

                'user_name' => $this->user_name,

                'first_name' => $this->first_name,

                'middle_name' => $this->middle_name,

                'last_name' => $this->last_name,

                'password' => $this->password,

                'salt' => $this->salt,

                'email' => $this->email,

                'mobile_number' => $this->mobile_number,

                'image' => $this->image,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image,

                'role_id' => $this->role,

                'joined_date' => $this->joined_date,

                'updated_date' => $this->updated_date

            );

            if ($this->db->insert('user', $data))
            {
                $id = $this->db->insert_id();
                $this->insert_user_elasticsearch($id, $data);
                return TRUE;
            }
        }

            public function insert_user_elasticsearch($id, $data) {
                $role = $this->role_model->get_role_name_by_id($data['role_id']);

                $data['middle_name'] = empty($data['middle_name']) ? $data['middle_name'] : ' ' . $data['middle_name'];

                $data['full_name'] = $data['first_name'] . $data['middle_name'] . ' ' . $data['last_name'];
                $data['id'] = $id;
                $data['role'] =  $role['role'];

    //            unset($data['first_name'], $data['middle_name'], $data['last_name']);

//                $index = array('index' => array("_index" => "users", "_type" => "user", "_id" => $id));
//
//                $index = json_encode($index);
//                $result = json_encode($data);
//
//                $result = $index . $result;
//
//                $result = str_replace("}{", "}\n\r{", $result);
//
//                file_put_contents(JSON_FILE_PATH . '/results.json', $result);
//                $json_data = file_get_contents(JSON_FILE_PATH."/results.json");
//                $json_data = str_replace(array('[', ']'), '', $json_data);
//                $json_data .= "\n\r";

                $json_data = json_encode($data);

                $this->elasticsearch->add("users", "user", $id, $json_data);
                $this->elasticsearch->refresh_index_type('users', 'user', $id);
            }

        /**
         * [update_user: Updates a user record into the database]
         * @param  [int] $id    [user id whom record is updating]
         * @param  [string] $image [new image name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_user($id, $image, $image_thumb, $profile_image, $joined_date)
        {
            $this->user_name = strtolower(trim($this->db->escape($this->input->post('user_name')), "' "));
            
            $this->first_name = strtolower(trim($this->db->escape($this->input->post('first_name')), "' "));

            $this->middle_name = strtolower(trim($this->db->escape($this->input->post('middle_name')), "' "));

            $this->last_name = strtolower(trim($this->db->escape($this->input->post('last_name')), "' "));

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->mobile_number = trim($this->db->escape($this->input->post('mobile_number')), "' ");

            $this->role = strtolower(trim($this->db->escape($this->input->post('role')), "' "));
        
            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");

            $this->joined_date = $joined_date;

            $this->updated_date = $date;

            if(!empty($image))
            {
                $this->image = trim($this->db->escape($image), "' ");
                $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
                $this->profile_image = trim($this->db->escape($profile_image), "' ");
            }

            $data = array(

                'user_name' => $this->user_name,

                'first_name' => $this->first_name,

                'middle_name' => $this->middle_name,

                'last_name' => $this->last_name,

                'email' => $this->email,

                'mobile_number' => $this->mobile_number,

                'image' => $this->image,

                'profile_image' => $this->profile_image,

                'thumbnail' => $this->thumbnail,

                'role_id' => $this->role,

                'updated_date' => $this->updated_date,

                'joined_date' => $this->joined_date

            );

            $this->db->where('id', $id);
                
            if ($this->db->update('user', $data))
            {
                $this->insert_user_elasticsearch($id, $data);
                $this->elasticsearch->refresh_index_type('users', 'user', $id);
                return TRUE;
            }
        }

        /**
         * [delete_user: Delete a user record from the database]
         * @param  [int] $id    [user id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_user($id)
        {
            if ($this->db->delete('user', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_user_by_id Fetchs a user record from the database by user id]
         * @param  [type]  $id   [user id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [User record is returned]
         */
        public function get_user_specific_record_by_id($id, $fields)
        {
            if(is_array($fields)) {
                $fields = implode(', ', $fields);
                $fields = rtrim(', ', $fields);
                $this->db->select($fields);
            }
            else {
                $this->db->select($fields);
            }

            $query = $this->db->get('user');

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();

                return $result;
            }

            return FALSE;
        }

        /**
         * [get_attachments: Attachs additional attachments to the user record e.g role_id
         *  is passed to get the role name against the role_id of user]
         * @param  [array] $attachments [User record]
         * @return [array]              [User record with attachments e.g. role_id role name 
         * against role_id of user]
         */
        public function get_attachments($attachments, $apply_indexing = FALSE)
        {
            $index_arr = array();
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            {
                if($apply_indexing) { // for elastic search
                    $index = !empty($index_arr) ? count($index_arr) : $i;
                    $index_arr[$index] = array('index' => array("_index" => "users", "_type" => "user", "_id" => $attachments[$i]['id']));
                    $index = $i == 0 ? $i+1 : $index+1;
                    $index_arr[$index] = $attachments[$i];
                    $role = $this->role_model->get_role_name_by_id($attachments[$i]['role_id']);

                    $index_arr[$index]['role'] =  $role['role'];
                }
                else {
                    $attachments[$i]['role'] = $this->role_model->get_role_by_id($attachments[$i]['role_id']);
                }
            }
            return $index_arr;
        }

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count() 
        {
            return $this->db->count_all("user");
        }


        /**
         * [fetch_users Returns users with a $limit defined in User controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any users then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_users($limit, $start) 
        {
            $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`,
            `user`.`user_name`, `user`.`mobile_number`, `user`.`email`, `user`.`role_id`');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`full_name`');

            $query = $this->db->get('user');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                $result = $this->get_attachments($result);

                return $result;
            }

            return false;
        }

        public function insert_user_bulk_elasticsearch()
        {
            $this->db->select('`user`.`id`,`user`.`first_name`,`user`.`middle_name`,`user`.`last_name`, CONCAT(`user`.`first_name`, `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`,
            `user`.`user_name`, `user`.`password`, `user`.`salt`, `user`.`image`, `user`.`thumbnail`, `user`.`profile_image`,
            `user`.`email` , `user`.`mobile_number`, `user`.`role_id`, `user`.`joined_date`, `user`.`updated_date`');

            $this->db->order_by('`full_name`');

            $query = $this->db->get('user');

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();

                $result = $this->get_attachments($result, TRUE);
                return $result;
            }
            return FALSE;
        }

        /**
         * [update_user: Updates a user record into the database]
         * @param  [int] $id    [user id whom record is updating]
         * @param  [string] $image [new image name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function change_password($id)
        {
            $this->password = trim($this->db->escape($this->input->post('confirm_new_password')), "' ");

            $this->salt = openssl_random_pseudo_bytes(32, $cstrong);
            $this->salt = uniqid('' , TRUE);

            $this->password = hash('sha512', $this->password . $this->salt);

            date_default_timezone_set("Asia/Karachi");

            $date = date("Y-m-d H:i:s");

            $this->updated_date = $date;

            $data = array(

                'salt' => $this->salt,

                'password' => $this->password,

                'updated_date' => $this->updated_date,

            );

            $this->db->where('id', $id);

            if ($this->db->update('user', $data))
            {
                return TRUE;
            }
        }


    }