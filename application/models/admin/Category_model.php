<?php  
    /**
    * category_model class is model of category controller it performs 
    * basic CRUD operations
    * Methods: insert_category
    *          update_category
    *          delete_category
    *          get_category_by_id
    *          get_attachments
    *          record_count
    *          fetch_categories
    */

    class Category_model extends CI_Model {

        /**
         * [insert_category: Inserts a category record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_category($image = '', $thumb_image = '', $profile_image = '')
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
            
            $this->parent = strtolower(trim($this->db->escape($this->input->post('parent')), "' "));
                        
            $this->image = empty($image) ? 'no_image_600.png' : $image;

            $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

            $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $data = array(

                'name' => $this->name,

                'parent' => $this->parent,

                'image' => $this->image,
              
                'thumbnail' => $this->thumbnail,
              
                'profile_image' => $this->profile_image

            );

            if ($this->db->insert('category', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_category: Updates a category record into the database]
         * @param  [int] $id    [category id whom record is updating]
         * @param  [string] $flag [new flag name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_category($id, $image, $image_thumb, $profile_image)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

            $this->parent = strtolower(trim($this->db->escape($this->input->post('parent')), "' "));

            $this->image = empty($image) ? 'no_image_600.png' : $image;

            $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $image_thumb;

            $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

            $data = array(

                'name' => $this->name,

                'parent' => $this->parent,

                'image' => $this->image,

                'thumbnail' => $this->thumbnail,

                'profile_image' => $this->profile_image

            );

            $this->db->where('id', $id);
                
            if ($this->db->update('category', $data)) 
            {
                return TRUE;
            } // update
        }

        /**
         * [get_all_categories Returns all details of category 
         * @return [array] [return all details of categories]
         */
        /*public function get_all_categories($specific_cols = '')
        {
            $query = $this->db->get('category');
            $result = $query->result_array();
            return $result;
        }*/

        /**
         * [get_all_categories Returns all details of category
         * @return [array] [return all details of categories]
         */
        public function get_all_categories($specific_cols = '')
        {
            $this->db->select('`category`.`id`, `category`.`name`, `category`.`parent`');

            $this->db->order_by('`name`');

            $query = $this->db->get('category');

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                $result = $this->get_dropdown_attachments($result);
                return $result;
            }
            return FALSE;
        }

        /**
         * [delete_category: Delete a category record from the database]
         * @param  [int] $id    [category id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_category($id)
        {
            if ($this->db->delete('category', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_doctor_specialization Fetchs a category details record from the database by category id]
         * @param  [type]  $id   [category id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [category record is returned]
         */
        public function get_category_details($category_id)
        {
            $this->db->select('category_details.id, category_details.category_id, category_details.newspaper_id, 
                            category_details.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
            $this->db->from('category_details');
            $this->db->where('category_details.category_id', $category_id);
            $this->db->join('halqa', 'halqa.id = category_details.halqa_id', 'inner');
            $this->db->join('user_designation', 'user_designation.id = category_details.newspaper_id', 'inner');
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
         * [get_categories_dropdown Returns all categories name and if for dropdown 
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all categories]
         */
        public function get_categories_dropdown()
        {
            $this->db->select('`category`.`id`, `category`.`name`');
            $this->db->order_by('`category`.`name`');
            $query = $this->db->get('`category`');
            $result = $query->result_array();
            return $result;
        }


        /**
         * [get_category_name_by_id Fetchs a role record from the database by role name]
         * @param  [type]  $name   [role name whom record is to be fetched]
         * attibutes are retrieved for display/view purpose]
         * @return [array] [Role record is returned]
         */
        public function get_category_name_by_id($id)
        {
            $this->db->select('category.name AS category');
            $this->db->where(array('id' => $id));
            $q = $this->db->get('category');
            return $q->result_array()[0];
        }


        /**
         * [get_category_by_id Fetchs a category record from the database by category id]
         * @param  [type]  $id   [category id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [category record is returned]
         */
        // public function get_category_by_id($id, $edit, $specific_cols = array(), $post = FALSE)
        public function get_category_by_id($id, $specific_cols = '', $dropdown = FALSE)
        {
            if(!empty($specific_cols))
            {
                $this->db->select('`category`.`id`, `category`.`name`, `category`.`parent`, `category`.`image`
                , `category`.`profile_image`, `category`.`thumbnail`');
            }
            else
            {
                if($dropdown) {
                    $this->db->select('`category`.`id`, `category`.`name`');
                }
                else {
                    $this->db->select('*');
                }
            }

            $this->db->from('category');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();

            if(empty($specific_cols) && !$dropdown)
            {
                $result = $this->get_attachments($result);
            }

            return $result[0]; 
        }

        /**
         * [get_attachments: Attachs additional attachments to the category record e.g newspaper_id
         *  is passed to get the designation name against the newspaper_id of category]
         * @param  [array] $attachments [category record]
         * @return [array]              [category record with attachments e.g. newspaper_id designation name 
         * against newspaper_id of category]
         */
        public function get_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                if($attachments[$i]['name'] != 'parent')
                {
                    $attachments[$i]['parent'] = $this->get_category_by_id($attachments[$i]['parent'], 'parent'); 
                }
            }

            return $attachments;
        }

        public function get_dropdown_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++)
            {
                if($attachments[$i]['name'] != 'parent')
                {
                    $attachments[$i]['parent'] = $this->get_category_by_id($attachments[$i]['parent'], '', TRUE);
                }
            }

//            $attachments = $this->clean_dropdown_attachments($attachments);


            return $attachments;
        }

/*        public function clean_dropdown_attachments($attachments) {

            $child_arr_count = 0;

            $clean_attachments = [];
            for ($i = 0, $count = count($attachments); $i < $count; $i++)
            {
                if(is_array($attachments[$i]['parent']))
                {
                    $parent_id = $attachments[$i]['parent']['id'];
                    $parent_name = $attachments[$i]['parent']['name'];

                    for ($j = 0, $jcount = count($attachments); $j < $jcount; $j++)
                    {
                        if(is_array($attachments[$j]['parent'])) {

                            if ($attachments[$j]['parent']['id'] == $parent_id && $attachments[$j]['name'] != 'parent'
                                && $attachments[$j]['parent']['name'] != 'parent'
                            )
                            {
                                $clean_attachments[$i]['id'] = $parent_id;
                                $clean_attachments[$i]['name'] = $parent_name;
                                $clean_attachments[$i]['children'][$child_arr_count]['id'] = $attachments[$j]['id'];
                                $clean_attachments[$i]['children'][$child_arr_count]['name'] = $attachments[$j]['name'];
                                $child_arr_count++;
                            }
                        }
                    }

                }
            }

            $clean_attachments = array_values($clean_attachments);

            print_r($clean_attachments);die;

            return $attachments;
        }*/

        public function record_count() 
        {
            return $this->db->count_all("category");
        }

        public function fetch_categories($limit, $start) 
        {
            $this->db->select('`category`.`id`, `category`.`name`, `category`.`profile_image`, `category`.`parent`');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('category');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                $result = $this->get_attachments($result); 
                return $result;
            }
            return FALSE;
        }
    }