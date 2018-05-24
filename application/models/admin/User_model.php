<?php
    class User_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();              
            $this->load->model('admin/role_model');
        }

        /**
         * Inserts a user record into the database
         * @param string $image
         * @param string $thumb_image
         * @param string $profile_image
         * @return bool
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
                return TRUE;
            }
        }

        /**
         * Updates a user record into the database
         * @param $id
         * @param $image
         * @param $image_thumb
         * @param $profile_image
         * @param $joined_date
         * @return bool
         */
        public function update_user($id, $image, $image_thumb, $profile_image)
        {
            $this->user_name = strtolower(trim($this->db->escape($this->input->post('user_name')), "' "));
            
            $this->first_name = strtolower(trim($this->db->escape($this->input->post('first_name')), "' "));

            $this->middle_name = strtolower(trim($this->db->escape($this->input->post('middle_name')), "' "));

            $this->last_name = strtolower(trim($this->db->escape($this->input->post('last_name')), "' "));

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->mobile_number = trim($this->db->escape($this->input->post('mobile_number')), "' ");

            $this->role = strtolower(trim($this->db->escape($this->input->post('role')), "' "));
        
            date_default_timezone_set("Asia/Karachi");
            
            $this->updated_date = date("Y-m-d H:i:s");

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
            );

            $this->db->where('id', $id);
                
            if ($this->db->update('user', $data))
            {
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
         * Returns a user record from the database by user id
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_user_by_id_lookup($id, $return_role_name=FALSE)
        {
            $this->db->select('`user`.`id`, `user`.`first_name`, `user`.`middle_name`, `user`.`last_name`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`,
                `user`.`user_name`, `user`.`mobile_number`, `user`.`email`, `user`.`image`, `user`.`profile_image`, `user`.`thumbnail`,
                `user`.`role_id`, DATE_FORMAT(`user`.`joined_date`, "%d %M %Y") AS `joined_date`, 
                DATE_FORMAT(`user`.`updated_date`, "%d %M %Y") AS `updated_date`');

            $this->db->from('user');
            $this->db->where('id', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                if($return_role_name) {
                    $result = $this->get_attachments($result);
                }
                return $result[0];
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
        public function get_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++)
            {
                $attachments[$i]['role'] = $this->role_model->get_role_by_id($attachments[$i]['role_id'], TRUE);
            }
            return $attachments;
        }

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count($params=array())
        {
            if(!isset($params['full_name']) && !isset($params['role_id'])) {
                return $this->db->count_all("user");
            }

            if(isset($params['full_name']) || isset($params['role_id'])) {
                $this->db->select('COUNT(id) as total');
            }

            if(isset($params['full_name'])) {
                $full_name = strtolower($params['full_name']);
                $full_name = preg_replace('!\s+!', ' ', $full_name);

                if(strpos($full_name, ' ') !== FALSE) {
                    $full_name = explode(' ', $full_name);
                    array_walk($full_name, function(&$value,$key) {
                        $value="$value*";
                    });
                    $full_name = implode(' ', $full_name);
                }
                else {
                    $full_name .= '*';
                }

                $this->db->where("MATCH (`first_name`, `last_name`) AGAINST ('$full_name' IN BOOLEAN MODE)");
            }

            if(isset($params['role_id'])) {
                if(!empty($params['role_id'])) {
                    $this->db->where('`role_id`',$params['role_id']);
                }
            }

            $this->db->from('`user`');

            $query = $this->db->get();
            $result = $query->result_array();
            return array_pop($result)['total'];
        }

        /**
         * [fetch_users Returns users with a $limit defined in User controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any users then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_users($params = array())
        {
            $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`,
            `user`.`user_name`, `user`.`mobile_number`, `user`.`email`, `user`.`role_id`');
         
            $this->db->limit($params['per_page'], $params['current_page']);

            if(isset($params['full_name'])) {
                $full_name = strtolower($params['full_name']);
                $full_name = preg_replace('!\s+!', ' ', $full_name);

                if(strpos($full_name, ' ') !== FALSE) {
                    $full_name = explode(' ', $full_name);
                    array_walk($full_name, function(&$value,$key) {
                        $value="$value*";
                    });
                    $full_name = implode(' ', $full_name);
                }
                else {
                    $full_name .= '*';
                }

                $this->db->where("MATCH (`first_name`, `last_name`) AGAINST ('$full_name' IN BOOLEAN MODE)");
            }

            if(isset($params['role_id'])) {
                if(!empty($params['role_id'])) {
                    $this->db->where('role_id',$params['role_id']);
                }
            }

            $this->db->order_by('`id`', 'desc');

            $this->db->from('user');

            $query = $this->db->get();

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
//                echo $this->db->last_query();die;
                $result = $this->get_attachments($result);
                return $result;
            }

            return false;
        }

        /**
         * Returns users by partially and full matches
         * @param $full_name
         * @return mixed Returns users full_name array or an empty array
         */
        public function user_full_name_autocomplete($full_name)
        {
            $this->db->select('CONCAT(`user`.`first_name`, " ", `user`.`last_name`) AS `full_name`');
            $this->db->from(`user`);

            $this->db->limit(AUTOCOMPLETE_RECORD_LIMIT, 0);

            $full_name = preg_replace('!\s+!', ' ', $full_name);

            if(strpos($full_name, ' ') !== FALSE) {
                $full_name = explode(' ', $full_name);
                array_walk($full_name, function(&$value,$key) {
                    $value="$value*";
                });
                $full_name = implode(' ', $full_name);
            }
            else {
                $full_name .= '*';
            }

            if(isset($full_name)) {
                $this->db->where("MATCH (`first_name`, `last_name`) AGAINST ('$full_name' IN BOOLEAN MODE)");
            }

            $this->db->order_by('`full_name`', 'desc');

            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                return $result;
            }

            return array();
        }

        /**
         * Updates a user password
         * @param $id
         * @return bool
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