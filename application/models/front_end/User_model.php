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
                $ins_on_halqa,
                $ins_prov_halqa,
                $no_halqa_id,
                $off_halqa,
                $joined_date,
                $updated_date;

        public function __construct()
        {
            parent::__construct();              
            $this->load->model('admin/role_model');
            $this->load->model('front_end/user_model');
            $this->load->model('front_end/politician_model');
            $this->load->model('admin/halqa_model');
            $this->load->model('admin/halqa_type_model');
        }

        /**
         * [insert_user: Inserts a user record into the database]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_user()
        {            
            $this->first_name = strtolower(trim($this->db->escape($this->input->post('first_name')), "' "));

            $this->last_name = strtolower(trim($this->db->escape($this->input->post('last_name')), "' "));

            $this->password = trim($this->db->escape($this->input->post('password')), "' ");

            $this->salt = openssl_random_pseudo_bytes(32, $cstrong);
            $this->salt = uniqid('', TRUE);
        
            $this->password = hash('sha512', $this->password . $this->salt);

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->mobile_number = trim($this->db->escape($this->input->post('mobile_number')), "' ");

            $role = $this->role_model->get_role_id_by_name('user');

            $this->role = $role['id'];
        
            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");
            
            $this->joined_date = $date;

            $this->updated_date = $date;
                
            $data = array(

                'first_name' => $this->first_name,

                'last_name' => $this->last_name,

                'password' => $this->password,

                'salt' => $this->salt,

                'email' => $this->email,

                'mobile_number' => $this->mobile_number,
                
                'role_id' => $this->role,

                'joined_date' => $this->joined_date,

                'updated_date' => $this->updated_date,
            );

            if ($this->db->insert('user', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_user: Updates a user record into the database]
         * @param  [int] $id    [user id whom record is updating]
         * @param  [string] $image [new image name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_user($id, $image, $image_thumb_name)
        {
            $this->user_name = strtolower(trim($this->db->escape($this->input->post('user_name')), "' "));
            
            $this->first_name = strtolower(trim($this->db->escape($this->input->post('first_name')), "' "));

            $this->middle_name = strtolower(trim($this->db->escape($this->input->post('middle_name')), "' "));

            $this->last_name = strtolower(trim($this->db->escape($this->input->post('last_name')), "' "));

            $this->email = strtolower(trim($this->db->escape($this->input->post('email')), "' "));

            $this->mobile_number = trim($this->db->escape($this->input->post('mobile_number')), "' ");
        
            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");
            
            $this->updated_date = $date;

            if(!empty($image) && !empty($image_thumb_name))
            {
                $this->image = trim($this->db->escape($image), "' ");
                $this->thumbnail = trim($this->db->escape($image_thumb_name), "' ");
            }            

            $data = array(

                'user_name' => $this->user_name,

                'first_name' => $this->first_name,

                'middle_name' => $this->middle_name,

                'last_name' => $this->last_name,

                'email' => $this->email,

                'mobile_number' => $this->mobile_number,

                'picture' => $this->image,
                
                'thumbnail' => $this->thumbnail,
                
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
         * [get_user_by_id Fetchs a user record from the database by user id]
         * @param  [type]  $id   [user id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [User record is returned]
         */
        public function get_user_by_id($id, $edit = FALSE)
        {
            if($edit)
            {
                $this->db->select('*');                
            }
            else
            {
                $this->db->select('`user`.`id`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`,
                `user`.`user_name`, `user`.`mobile_number`, `user`.`email`, `user`.`role_id`, `user`.`joined_date`, `user`.`updated_date`');
            }

            $this->db->from('user');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
        
            $result = $this->get_attachments($result);
        
            return $result; 

        }

        public function get_user_halqa($id)
        {
            $this->db->select('`user`.`na_halqa`, `user`.`prov_halqa`, `user`.`prov_halqa_type`');                

            $this->db->from('user');

            $this->db->where(array('id' => $id)); 

            $q = $this->db->get(); 

            $result = $q->result_array();
            
            $result = $result[0]; 

            $keys = array_values($result);

            $result = (empty($keys[0]) && empty($keys[1])) ? 'FALSE' : $result;

            return $result;
        }

        public function has_already_voted_this_entity($id, $user_id)
        {
            $this->db->select('`politician_vote`.`id`');               

            $this->db->from('politician_vote');

            $this->db->where(array('politician_id' => $id, 'user_id' => $user_id)); 

            $q = $this->db->get();

            $result = $q->result_array();
            $result = (empty($result)) ? 'FALSE' : 'TRUE';

            return $result;
        }

        public function vote_this_entity($id, $user_id)
        {
            $main_entity_name       = strtolower(trim($this->db->escape($this->input->post('main_entity_name')), "' "));
            $main_entity_id         = strtolower(trim($this->db->escape($this->input->post('main_entity_id')), "' "));

            $politician_halqas = $this->politician_model->get_politician_halqas_by_id($main_entity_id);
            
            $this->no_halqa_id = $this->halqa_model->get_id_by_key('no halqa');
            $this->no_halqa_id = $this->no_halqa_id['id'];
            
            $provincial_assembly = $this->get_user_halqa($user_id);
            $on_halqa = $provincial_assembly['na_halqa'];
            $provincial_halqa = $provincial_assembly['prov_halqa'];

            $provincial_assembly = $provincial_assembly['prov_halqa_type'];
            
            for ($i = 0; $i < count($politician_halqas); $i++)
            { 
                if($this->no_halqa_id == $politician_halqas[$i]['halqa_id'])
                {
                    unset($politician_halqas[$i]);
                }
            }
         
            $politician_halqas = array_column($politician_halqas, 'halqa_type', 'halqa_id');
            
            /**
             * On Halqa NA and on Halqa Prov
             * Array ([17] => national assembly[1] => national assembly[277] => province of punjab)
             */
            foreach ($politician_halqas as $key => $value) 
            {
                if($key == $on_halqa && $value == 'national assembly')
                {
                    $this->ins_on_halqa = $key . ',' . $value;
                }
                else if($key == $provincial_halqa && $value == $provincial_assembly)
                {
                    $this->ins_prov_halqa = $key . ',' . $value;
                }
                else
                {
                    continue;
                }
            }

            $this->off_halqa = ($this->ins_on_halqa == '' || $this->ins_prov_halqa == '') ? 'off_halqa' : '';

            $entities = array('politician', 'political_party');
            if(in_array($main_entity_name, $entities))
            {    
                if($main_entity_name == 'politician')
                {
                    $this->ins_on_halqa = $this->ins_on_halqa == '' ? '' : explode(',', $this->ins_on_halqa);
                    $this->ins_prov_halqa = $this->ins_prov_halqa == '' ? '' : explode(',', $this->ins_prov_halqa);
                    if(is_array($this->ins_on_halqa))
                    {
                        $on_halqa_data = array(
                        
                            'halqa_id' => $this->ins_on_halqa[0],
                            'vote_type' => $this->ins_on_halqa[1]
                        );
                    }
                    if(is_array($this->ins_prov_halqa))
                    {
                        $prov_halqa_data = array(
                        
                            'halqa_id' => $this->ins_prov_halqa[0],
                            'vote_type' => $this->ins_prov_halqa[1]
                        );
                    }
                    if(strlen($this->off_halqa) > 0)
                    {
                        $off_halqa_data = array(
                        
                        'vote_type' => $this->off_halqa,
                        'halqa_id'  => $this->no_halqa_id
                        );
                    }
                }

                $data = array(

                    $main_entity_name . '_id' => $main_entity_id,
                    
                    'user_id' => $user_id
                );

                // Now cast vote

                for($i = 0; $i < 3; $i++)
                {
                    if(is_array($this->ins_on_halqa) && $i == 0)
                        $data = array_merge($data, $on_halqa_data);

                    else if(is_array($this->ins_prov_halqa) && $i == 1)
                        $data = array_merge($data, $prov_halqa_data);
                    
                    else if(strlen($this->off_halqa) > 0 && $i == 2)
                        $data = array_merge($data, $off_halqa_data);
                    else
                        continue;
                    $this->db->insert($main_entity_name . '_vote', $data);
                }
                    return TRUE;           
            }
                return FALSE;
        }

        public function has_already_voted($id)
        {
            $this->db->select('`user`.`na_halqa`, `user`.`prov_halqa`');                

            $this->db->from('user');

            $this->db->where(array('id' => $id)); 

            $q = $this->db->get();

            $result = $q->result_array();
            
            $result = $result[0]; 

            $keys = array_values($result);

            $result = (empty($keys[0]) && empty($keys[1])) ? 'FALSE' : 'TRUE';
            return $result;
        }

        public function insert_halqas_plus_vote_now()
        {
            // user id
            $id                     = strtolower(trim($this->db->escape($this->input->post('id')), "' "));
            $on_halqa               = strtolower(trim($this->db->escape($this->input->post('on_halqa')), "' "));
            $provincial_assembly    = strtolower(trim($this->db->escape($this->input->post('provincial_assembly')), "' "));
            $provincial_halqa       = strtolower(trim($this->db->escape($this->input->post('provincial_halqa')), "' "));
            $main_entity_name       = strtolower(trim($this->db->escape($this->input->post('main_entity_name')), "' "));
            $main_entity_id         = strtolower(trim($this->db->escape($this->input->post('main_entity_id')), "' "));

            $politician_halqas = $this->politician_model->get_politician_halqas_by_id($main_entity_id);
            $this->no_halqa_id = $this->halqa_model->get_id_by_key('no halqa');
            $this->no_halqa_id = $this->no_halqa_id['id'];
            
            $provincial_assembly = $this->halqa_type_model->get_halqa_type_by_id($provincial_assembly);
            $provincial_assembly = $provincial_assembly['name'];
         
            for ($i = 0; $i < count($politician_halqas); $i++)
            { 
                if($this->no_halqa_id == $politician_halqas[$i]['halqa_id'])
                {
                    unset($politician_halqas[$i]);
                }
            }
         
            $politician_halqas = array_column($politician_halqas, 'halqa_type', 'halqa_id');
            
            /**
             * On Halqa NA and on Halqa Prov
             * Array ([17] => national assembly[1] => national assembly[277] => province of punjab)
             */
            foreach ($politician_halqas as $key => $value) 
            {
                if($key == $on_halqa && $value == 'national assembly')
                {
                    $this->ins_on_halqa = $key . ',' . $value;
                }
                else if($key == $provincial_halqa && $value == $provincial_assembly)
                {
                    $this->ins_prov_halqa = $key . ',' . $value;
                }
                else
                {
                    continue;
                }
            }

            $this->off_halqa = ($this->ins_on_halqa == '' || $this->ins_prov_halqa == '') ? 'off_halqa' : '';

            $entities = array('politician', 'political_party');
            if(in_array($main_entity_name, $entities))
            {
                 $data = array(

                    'na_halqa' => $on_halqa,
                    
                    'prov_halqa' => $provincial_halqa,

                    'prov_halqa_type' => $provincial_assembly
                );

                $this->db->where('id', $id);
                    
                if ($this->db->update('user', $data)) 
                {
                    unset($data);

                    if($main_entity_name == 'politician')
                    {
                        $this->ins_on_halqa = $this->ins_on_halqa == '' ? '' : explode(',', $this->ins_on_halqa);
                        $this->ins_prov_halqa = $this->ins_prov_halqa == '' ? '' : explode(',', $this->ins_prov_halqa);
                        if(is_array($this->ins_on_halqa))
                        {
                            $on_halqa_data = array(
                            
                                'halqa_id' => $this->ins_on_halqa[0],
                                'vote_type' => $this->ins_on_halqa[1]
                            );
                        }
                        if(is_array($this->ins_prov_halqa))
                        {
                            $prov_halqa_data = array(
                            
                                'halqa_id' => $this->ins_prov_halqa[0],
                                'vote_type' => $this->ins_prov_halqa[1]
                            );
                        }
                        if(strlen($this->off_halqa) > 0)
                        {
                            $off_halqa_data = array(
                            
                            'vote_type' => $this->off_halqa,
                            'halqa_id'  => $this->no_halqa_id
                            );
                        }
                    }

                    $data = array(

                        $main_entity_name . '_id' => $main_entity_id,
                        
                        'user_id' => $id,
                    );

                    // foreac

                    // Now cast vote

                    for($i = 0; $i < 3; $i++)
                    {
                        if(is_array($this->ins_on_halqa) && $i == 0)
                            $data = array_merge($data, $on_halqa_data);

                        else if(is_array($this->ins_prov_halqa) && $i == 1)
                            $data = array_merge($data, $prov_halqa_data);
                        
                        else if(strlen($this->off_halqa) > 0 && $i == 2)
                            $data = array_merge($data, $off_halqa_data);
                        else
                            continue;

                        $this->db->insert($main_entity_name . '_vote', $data);
                    }
                        return TRUE;
                }
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
                $attachments[$i]['role'] = $this->role_model->get_role_by_id($attachments[$i]['role_id']); 
            }
            return $attachments;
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
    }