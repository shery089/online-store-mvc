<?php  
    /**
    * product_attribute_model class is model of product_attribute controller it performs 
    * basic CRUD operations
    * Methods: insert_product_attribute
    *          update_product_attribute
    *          delete_product_attribute
    *          get_product_attribute_by_id
    *          get_attachments
    *          record_count
    *          fetch_product_attributes
    */

    class Product_attribute_model extends CI_Model {

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
        }

        /**
         * [insert_product_attribute: Inserts a product_attribute record into the database]
         * @param  [string] $flag [New random flag name]
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_product_attribute()
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

            $data = array(

                'name' => $this->name

            );

            if ($this->db->insert('product_attribute', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_product_attribute: Updates a product_attribute record into the database]
         * @param  [int] $id    [product_attribute id whom record is updating]
         * @param  [string] $flag [new flag name if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_product_attribute($id)
        {
            $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

            $data = array(

                'name' => $this->name
            );

            $this->db->where('id', $id);
                
            if ($this->db->update('product_attribute', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [get_all_product_attributes Returns all details of product_attribute 
         * @return [array] [return all details of product_attributes]
         */
        public function get_all_product_attributes()
        {
            $query = $this->db->get('product_attribute');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_all_product_attributes Returns all details of product_attribute
         * @return [array] [return all details of product_attributes]
         */
        public function get_product_attributes_only_key($key)
        {
            $this->db->select($key);
            $this->db->from('product_attribute');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }

        /**
         * [delete_product_attribute: Delete a product_attribute record from the database]
         * @param  [int] $id    [product_attribute id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_product_attribute($id)
        {
            if ($this->db->delete('product_attribute', array('id' => $id))) 
            {
                return TRUE;
            }
        }

        /**
         * [get_product_attributes_dropdown Returns all product_attributes name and if for dropdown 
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all product_attributes]
         */
        public function get_product_attributes_dropdown()
        {
            $this->db->select('`product_attribute`.`id`, `product_attribute`.`name`');
            $this->db->order_by('`product_attribute`.`name`');
            $query = $this->db->get('`product_attribute`');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_product_attr_name_by_id Returns all product_attributes name and if for dropdown
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all product_attributes]
         */
        public function get_product_attr_name_by_id($id)
        {
            $this->db->select('product_attribute`.`name`');
            $query = $this->db->where('`product_attribute`.`id`', $id);
            $query = $this->db->get('`product_attribute`');
            $result = $query->result_array();
            return $result[0]['name'];
        }

        /**
         * [get_product_attribute_by_id Fetchs a product_attribute record from the database by product_attribute id]
         * @param  [type]  $id   [product_attribute id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [product_attribute record is returned]
         */
        public function get_product_attribute_by_id($id)
        {
            if(!empty($specific_cols))
            {
                $this->db->select('`product_attribute`.`name`');
            }

            $this->db->from('product_attribute');
        
            $this->db->where(array('id' => $id)); 
            
            $q = $this->db->get(); 

            $result = $q->result_array();

            return $result[0]; 
        }

        /**
         * [get_attachments: Attachs additional attachments to the product_attribute record e.g newspaper_id
         *  is passed to get the designation name against the newspaper_id of product_attribute]
         * @param  [array] $attachments [product_attribute record]
         * @return [array]              [product_attribute record with attachments e.g. newspaper_id designation name 
         * against newspaper_id of product_attribute]
         */
        public function get_attachments($attachments)
        {
            for ($i = 0, $count = count($attachments); $i < $count; $i++) 
            { 
                if($attachments[$i]['name'] != 'parent')
                {
                    $attachments[$i]['parent'] = $this->get_product_attribute_by_id($attachments[$i]['parent'], 'parent'); 
                }
            }

            return $attachments;
        }

        public function record_count() 
        {
            return $this->db->count_all("product_attribute");
        }

        public function fetch_product_attributes($limit, $start) 
        {
            $this->db->select('`product_attribute`.`id`, `product_attribute`.`name`');
         
            $this->db->limit($limit, $start);
          
            $this->db->order_by('`name`');

            $query = $this->db->get('product_attribute');

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();
                return $result;
            }
            return FALSE;
        }
    }