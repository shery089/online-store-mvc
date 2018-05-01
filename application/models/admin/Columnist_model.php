<?php  
    /**
    * columnist_model class is model of columnist controller it performs 
    * basic CRUD operations
    * Methods: insert_columnist
    *          update_columnist
    *          delete_columnist
    *          get_columnist_by_id
    *          get_attachments
    *          record_count
    *          fetch_columnists
    */

	class Columnist_model extends CI_Model {

        private $name,
                $introduction,
                $dob,
                $newspaper,
                $image,
                $thumbnail,
                $city,
                $profile_image;

        public function __construct()
        {
            parent::__construct();        	
            $this->load->model('admin/newspaper_model');
            $this->load->model('admin/autocomplete_model');
        }

        /**
         * [insert_columnist: Inserts a columnist record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_columnist($image = '', $thumb_image = '', $profile_image = '')
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
                        
            $this->dob = strtolower(trim($this->db->escape($this->input->post('dob')), "' "));

            $this->introduction = strtolower(trim($this->db->escape($this->input->post('introduction')), "' "));

            $this->city = strtolower(trim($this->db->escape($this->input->post('city')), "' "));
            
            $this->newspaper = strtolower(trim($this->db->escape($this->input->post('submitted_newspaper')), "' "));

            // if image is empty then set default flag i.e. no_image_600.png
            $this->image = empty($image) ? 'no_image_600.png' : $image;

            $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

            $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $data = array(

                'name' => $this->name,

                'dob' => $this->dob,

                'city' => $this->city,

                'introduction' => $this->introduction,

                'image' => $this->image,
              
                'thumbnail' => $this->thumbnail,
              
                'profile_image' => $this->profile_image

            );

            if ($this->db->insert('columnist', $data)) 
            {
                $columnist_id = $this->db->insert_id();

                /**
                 * Both halqa_id and user_newspaper_id insertion is done separetly 
                 * for a case: If columnist is both senator and national assembly
                 * member and we insert halqa_id against the user_newspaper_id. In
                 * future election if columnist is not national assembly member or 
                 * some other designation is loosed then we will loose the halqa_id 
                 * on updation. So, both halqa_id and user_newspaper_id insertion
                 * is done separetly  
                 */

                /**
                 * newspaper_id insertion in columnist_details table
                 * if: If multiple halqa's are inserted
                 * else: If single halqa is inserted
                 */
                if(strrchr($this->newspaper, ','))
                {
                    $this->newspaper = explode(',', $this->newspaper);

                    foreach ($this->newspaper as $newspaper_id) 
                    {
                        $this->db->query("INSERT INTO columnist_details (columnist_id, newspaper_id) 
                                            VALUES($columnist_id, $newspaper_id)");                
                    }
                }
                else
                {
                    $this->db->query("INSERT INTO columnist_details (columnist_id, newspaper_id) 
                                        VALUES($columnist_id, $this->newspaper)");
                }
            }
                return TRUE;
        }

        /**
         * [update_columnist: Updates a columnist record into the database]
         * @param  [int] $id    [columnist id whom record is updating]
         * @param  [string] $flag [new flag name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_columnist($id, $image, $image_thumb, $profile_image)
        {


            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
                        
            $this->dob = strtolower(trim($this->db->escape($this->input->post('dob')), "' "));

            $this->introduction = strtolower(trim($this->db->escape($this->input->post('introduction')), "' "));

            $this->city = strtolower(trim($this->db->escape($this->input->post('city')), "' "));
            
            $this->newspaper = strtolower(trim($this->db->escape($this->input->post('submitted_newspaper')), "' "));

            // if image is empty then set default image i.e. no_image_600.png
            if(!empty($image))
            {                
                $this->image = trim($this->db->escape($image), "' ");
                $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
                $this->profile_image = trim($this->db->escape($profile_image), "' ");
            }

            $data = array(

                'name' => $this->name,

                'dob' => $this->dob,

                'introduction' => $this->introduction,

                'city' => $this->city,

                'image' => $this->image,
              
                'thumbnail' => $this->thumbnail,
              
                'profile_image' => $this->profile_image

            );

            $this->db->where('id', $id);
                
            if ($this->db->update('columnist', $data)) 
            {  
                $q = $this->db->get_where('columnist_details', array('columnist_id' => $id));

                /*=============================================
                =            Designation's block              =
                =============================================*/

                $existing_newspapers = $q->result_array();

                $existing_newspapers = array_column($existing_newspapers, 'newspaper_id');
                $existing_newspapers_insert = implode(',', $existing_newspapers);

                $this->newspaper = explode(',', $this->newspaper);
                $new_newspapers_str = implode(',', $this->newspaper);
                // $existing_newspapers

                if(strrchr($new_newspapers_str, ',')) // multiple designations
                {
                    $record_to_insert = array_diff($this->newspaper, $existing_newspapers);
                    $q = $this->db->query("SELECT * FROM columnist_details WHERE columnist_id = $id AND 
                            newspaper_id NOT IN ($new_newspapers_str)");
                    $to_delete_string = implode(',', array_column($q->result_array(), 'id'));
                    if(strlen($to_delete_string) > 0)
                    {
                        $q = $this->db->query("DELETE FROM columnist_details WHERE id IN ($to_delete_string)");
                    }

                    if(!empty($record_to_insert))
                    {
                        foreach ($record_to_insert as $to_insert) 
                        {
                            $this->db->query("INSERT INTO columnist_details (columnist_id, newspaper_id) 
                                                VALUES($id, $to_insert)");
                        }
                    }
                } // multiple newspapers end
                else // single newspaper
                {
                    if(count($this->newspaper) == 1)
                    {
                        $this->newspaper = $this->newspaper;
                    }

                    $q = $this->db->query("SELECT * FROM columnist_details WHERE columnist_id = $id AND newspaper_id NOT IN ($new_newspapers_str)");
                    $to_delete = array_column($q->result_array(), 'id');
                    if(count($to_delete) >= 1)
                    {
                        $to_delete_string = implode(',', $to_delete);
                        if(!empty($to_delete))
                        {
                            $this->db->query("DELETE FROM columnist_details WHERE id IN ($to_delete_string)");
                        }
                    }
                    
                    $record_to_insert = array_diff($this->newspaper, $existing_newspapers);
                    if(!empty($record_to_insert))
                    {
                        if(count($record_to_insert) > 1)
                        {
                            foreach ($record_to_insert as $to_insert) 
                            {
                                $this->db->query("INSERT INTO columnist_details (columnist_id, newspaper_id) 
                                                    VALUES($id, $to_insert)");
                            }
                        }
                        else
                        {
                            $key = array_keys($record_to_insert);
                            $key = $key[0];

                            $this->db->query("INSERT INTO columnist_details (columnist_id, newspaper_id) 
                                VALUES($id, $record_to_insert[$key])");                         
                        }                
                    }
                } // single designations end

                /*=====  End of Designations's block  ===============*/

            } // update
            return TRUE;
        }

        /**
         * [get_columnists Returns all details of columnist 
         * e.g id = 1, name = Nawaz Sharif) ................]
         * @return [array] [return all details of columnists]
         */
        public function get_columnists()
        {
            $query = $this->db->get('columnist');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [delete_columnist: Delete a columnist record from the database]
         * @param  [int] $id    [columnist id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_columnist($id)
        {
            if ($this->db->delete('columnist', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_doctor_specialization Fetchs a columnist details record from the database by columnist id]
         * @param  [type]  $id   [columnist id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [columnist record is returned]
         */
        public function get_columnist_details($columnist_id)
        {
            $this->db->select('columnist_details.id, columnist_details.columnist_id, columnist_details.newspaper_id, 
                            columnist_details.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
            $this->db->from('columnist_details');
            $this->db->where('columnist_details.columnist_id', $columnist_id);
            $this->db->join('halqa', 'halqa.id = columnist_details.halqa_id', 'inner');
            $this->db->join('user_designation', 'user_designation.id = columnist_details.newspaper_id', 'inner');
            $q = $this->db->get();
            $result = $q->result_array();

            // to delete $this->no_newspaper_id
            for ($i = 0, $count = count($result); $i < $count; $i++)
            {
                if($result[$i]['newspaper_id'] == $this->no_newspaper_id)
                {
                    unset($result[$i]['newspaper_id']);
                    unset($result[$i]['designation']);
                }
                if($result[$i]['halqa_id'] == $this->no_halqa_id)
                {
                    unset($result[$i]['halqa_id']);
                    unset($result[$i]['halqa']);
                }
            }

            return $result;
        }

        /**
         * [get_columnists_dropdown Returns all columnists name and if for dropdown 
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all columnists]
         */
        public function get_columnists_dropdown()
        {
            $this->db->select('`columnist`.`id`, `columnist`.`name`');
            $this->db->order_by('`columnist`.`name`');
            $query = $this->db->get('`columnist`');
            $result = $query->result_array();
            return $result;
        }         

        /**
         * [get_columnist_by_id Fetchs a columnist record from the database by columnist id]
         * @param  [type]  $id   [columnist id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [columnist record is returned]
         */
        public function get_columnist_by_id($id, $edit, $specific_cols = array(), $post = FALSE)
        {
            if(!empty($specific_cols))
            {
                $specific_cols = implode(", ", $specific_cols);
                $specific_cols = preg_replace('/full_name/', 'CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`'
                                    , $specific_cols);

                $this->db->select($specific_cols);
            }
            else if($post)
            {
                $this->db->select('`columnist`.`name`');
            }
            else
            {
                $this->db->select('*');
            }

            $this->db->from('columnist');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();
        
            if(!$post)
            {
                $result = $this->get_attachments($result);
            }


            return $result[0]; 
        }

        /**
         * [get_attachments: Attachs additional attachments to the columnist record e.g newspaper_id
         *  is passed to get the designation name against the newspaper_id of columnist]
         * @param  [array] $attachments [columnist record]
         * @return [array]              [columnist record with attachments e.g. newspaper_id designation name 
         * against newspaper_id of columnist]
         */
        public function get_attachments($attachments, $attach_columnist_details = TRUE)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                // TRUE is passed 
                $attachments[$i]['city'] = $this->autocomplete_model->get_city_by_id($attachments[$i]['city'], FALSE, TRUE); 
                $attachments[$i]['newspaper_id'] = $this->newspaper_model->get_newspapers_by_columist($attachments[$i]['id'], FALSE, TRUE); 
            }

            return $attachments;
        }

        public function record_count() 
        {
            return $this->db->count_all("columnist");
        }

        public function fetch_columnists($limit, $start, $attach_specialization = TRUE) 
        {
            $this->db->select('`columnist`.`id`, `columnist`.`name`, `columnist`.`profile_image`, `columnist`.`likes`, `columnist`.`dislikes`,
                                `columnist`.`city`, `columnist`.`dob`');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('columnist');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $this->get_attachments($result, TRUE); 
                return $result;
            }
            return FALSE;
        }	

        public function insert_columnist_bulk_by_csv($csv)
        {

            foreach ($csv as $columnist) 
            {
                $data = array(

                    'name' => $columnist['name'],

                    'political_party_id' => $columnist['political_party']
                );
            
                if ($this->db->insert('columnist', $data)) 
                {
                    $columnist_id = $this->db->insert_id();

                    
                    /** Both halqa_id and user_newspaper_id insertion is done separetly 
                     * for a case: If columnist is both senator and national assembly
                     * member and we insert halqa_id against the user_newspaper_id. In
                     * future election if columnist is not national assembly member or 
                     * some other designation is loosed then we will loose the halqa_id 
                     * on updation. So, both halqa_id and user_newspaper_id insertion
                     * is done separetly  
                     

                    /**
                     * halqa_id insertion in columnist_details table
                     * if: If multiple halqa's are inserted
                     * else: If single halqa is inserted
                     */
                     
                    if(isset($columnist['halqa_ids']))
                    {
                        foreach ($columnist['halqa_ids'] as $halqa_id) 
                        {
                            $this->db->query("INSERT INTO columnist_details (columnist_id, halqa_id, newspaper_id) 
                                                VALUES($columnist_id, $halqa_id, $this->no_newspaper_id)");                
                        }
                        $this->db->query("INSERT INTO columnist_details (columnist_id, halqa_id, newspaper_id) 
                                            VALUES($columnist_id, " . $columnist['halqa_id'] . ", $this->no_newspaper_id)");
                    }
                    else
                    {
                        $this->db->query("INSERT INTO columnist_details (columnist_id, halqa_id, newspaper_id) 
                                            VALUES($columnist_id, " . $columnist['halqa_id'] . ", $this->no_newspaper_id)");
                    }
                }
            }
	   }
    }