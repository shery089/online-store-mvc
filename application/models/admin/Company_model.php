<?php
    class Company_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();              
        }

        /**
         * Inserts a company record into the database
         * @param string $image
         * @param string $thumb_image
         * @param string $profile_image
         * @return bool
         */
        public function insert_company($image = '', $profile_image = '', $thumb_image = '')
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->phone_number = trim($this->db->escape($this->input->post('phone_number')), "' ");

            $this->website = trim($this->db->escape($this->input->post('website')), "' ");

            $this->description = trim($this->db->escape($this->input->post('description')), "' ");

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");
            
            $this->joined_date = $date;

            $this->updated_date = $date;
            
            // if image is empty then set default image i.e. no_image_600.png
            $this->image = empty($image) ? 'no_image_600.png' : $image;

            $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

            $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $data = array(

                'name' => $this->name,

                'email' => $this->email,

                'phone_number' => $this->phone_number,

                'website' => $this->website,

                'description' => $this->description,

                'image' => $this->image,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image,

                'joined_date' => $this->joined_date,

                'updated_date' => $this->updated_date

            );

            if ($this->db->insert('company', $data))
            {
                return TRUE;
            }
        }

        /**
         * Updates a company record into the database
         * @param $id
         * @param $image
         * @param $image_thumb
         * @param $profile_image
         * @param $joined_date
         * @return bool
         */
        public function update_company($id, $image, $profile_image, $image_thumb)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->phone_number = trim($this->db->escape($this->input->post('phone_number')), "' ");

            $this->website = trim($this->db->escape($this->input->post('website')), "' ");

            $this->description = trim($this->db->escape($this->input->post('description')), "' ");

            date_default_timezone_set("Asia/Karachi");

            $this->updated_date = date("Y-m-d H:i:s");

            if(!empty($image))
            {
                $this->image = trim($this->db->escape($image), "' ");
                $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
                $this->profile_image = trim($this->db->escape($profile_image), "' ");
            }

            $data = array(

                'name' => $this->name,

                'email' => $this->email,

                'phone_number' => $this->phone_number,

                'website' => $this->website,

                'description' => $this->description,

                'image' => $this->image,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image,

                'updated_date' => $this->updated_date

            );

            $this->db->where('id', $id);
                
            if ($this->db->update('company', $data))
            {
                return TRUE;
            }
        }

        /**
         * [delete_company: Delete a company record from the database]
         * @param  [int] $id    [company id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_company($id)
        {
            if ($this->db->delete('company', array('id' => $id))) 
            {
                return TRUE;
            }
        }

        /**
         * Returns a company record from the database by company id
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_company_by_id_lookup($id, $edit=FALSE)
        {
            if($edit) {
                $this->db->select('`company`.`id`, `company`.`name`, `company`.`phone_number`, `company`.`email`, 
                `company`.`image`, `company`.`profile_image`, `company`.`thumbnail`, `company`.`description`, `company`.`website`');
            }
            else {
                $this->db->select('`company`.`id`, `company`.`name`, `company`.`phone_number`, `company`.`email`, 
                `company`.`image`, `company`.`website`, `description`, DATE_FORMAT(`company`.`joined_date`, "%d %M %Y") AS `joined_date`, 
                DATE_FORMAT(`company`.`updated_date`, "%d %M %Y") AS `updated_date`');
            }

            $this->db->from('company');
            $this->db->where('id', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result[0];
            }
            return FALSE;
        }
        
        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count($params=array())
        {
            if(!isset($params['name']) && !isset($params['email'])) {
                return $this->db->count_all("company");
            }

            if(isset($params['name']) || isset($params['email'])) {
                $this->db->select('COUNT(id) as total');
            }

            if(isset($params['name'])) {
                $name = strtolower($params['name']);
                $name = preg_replace('!\s+!', ' ', $name);

                if(strpos($name, ' ') !== FALSE) {
                    $name = explode(' ', $name);
                    array_walk($name, function(&$value,$key) {
                        $value="$value*";
                    });
                    $name = implode(' ', $name);
                }
                else {
                    $name .= '*';
                }

                $this->db->where("MATCH (`name`) AGAINST ('$name' IN BOOLEAN MODE)");
            }

            if(isset($params['email'])) {
                $email = strtolower($params['email']);
                $email = preg_replace('!\s+!', ' ', $email);

                if(strpos($email, ' ') !== FALSE) {
                    $email = explode(' ', $email);
                    array_walk($email, function(&$value,$key) {
                        $value="$value*";
                    });
                    $email = implode(' ', $email);
                }
                else {
                    $email .= '*';
                }

                $this->db->where("MATCH (`email`) AGAINST ('$email' IN BOOLEAN MODE)");
            }

            $this->db->from('`company`');

            $query = $this->db->get();
            $result = $query->result_array();
            return array_pop($result)['total'];
        }

        /**
         * [fetch_companies Returns companies with a $limit defined in Company controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any companies then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_companies($params = array())
        {
            $this->db->select('`company`.`id`, `company`.`name`, `company`.`phone_number`, `company`.`email`, 
            `company`.`thumbnail`');
         
            $this->db->limit($params['per_page'], $params['current_page']);

            if(isset($params['name'])) {
                $name = strtolower($params['name']);
                $name = preg_replace('!\s+!', ' ', $name);

                if(strpos($name, ' ') !== FALSE) {
                    $name = explode(' ', $name);
                    array_walk($name, function(&$value,$key) {
                        $value="$value*";
                    });
                    $name = implode(' ', $name);
                }
                else {
                    $name .= '*';
                }

                $this->db->where("MATCH (`name`) AGAINST ('$name' IN BOOLEAN MODE)");
            }

            if(isset($params['email'])) {
                $email = strtolower($params['email']);
                $email = preg_replace('!\s+!', ' ', $email);

                if(strpos($email, ' ') !== FALSE) {
                    $email = explode(' ', $email);
                    array_walk($email, function(&$value,$key) {
                        $value="$value*";
                    });
                    $email = implode(' ', $email);
                }
                else {
                    $email .= '*';
                }

                $this->db->where("MATCH (`email`) AGAINST ('$email' IN BOOLEAN MODE)");
            }

            $this->db->order_by('`id`', 'desc');

            $this->db->from('company');

            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
//                echo $this->db->last_query();die;
                return $result;
            }

            return false;
        }

        /**
         * Returns companies by partially and full matches
         * @param $name
         * @return mixed Returns companies name array or an empty array
         */
        public function company_name_autocomplete($name)
        {
            $this->db->select('`company`.`name`');
            $this->db->from('`company`');

            $this->db->limit(AUTOCOMPLETE_RECORD_LIMIT, 0);

            $name = preg_replace('!\s+!', ' ', $name);

            if(strpos($name, ' ') !== FALSE) {
                $name = explode(' ', $name);
                array_walk($name, function(&$value,$key) {
                    $value="$value*";
                });
                $name = implode(' ', $name);
            }
            else {
                $name .= '*';
            }

            if(isset($name)) {
                $this->db->where("MATCH (`name`) AGAINST ('$name' IN BOOLEAN MODE)");
            }

            $this->db->order_by('`name`', 'desc');
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                return $result;
            }

            return array();
        }
    }