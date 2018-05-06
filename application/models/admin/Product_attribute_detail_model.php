<?php  

	class Product_attribute_detail_model extends CI_Model {

        private $product_attribute,
                $name;

        public function __construct()
        {
            parent::__construct();        	
        }

        /**
         * [insert_product_attribute_detail: Inserts a product_attribute_detail record into the database]
         * @param  No Parameter
         * @return [boolean] [if insertion is performed successfully 
         * then, returns TRUE.]
         */
        public function insert_product_attribute_detail()
        {
            $this->product_attribute = strtolower(trim($this->db->escape($this->input->post('product_attribute')), "' "));

            $this->name = !empty($_POST['product_attribute_detail_color']) ? '#' . $this->input->post('product_attribute_detail_color') : $this->input->post('product_attribute_detail');

            $this->name = strtolower(trim($this->db->escape($this->name), "' "));

            $data = array(

                'product_attribute_id' => $this->product_attribute,
              
                'name' => $this->name

            );

            if ($this->db->insert('product_attribute_detail', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [update_product_attribute_detail: Updates a product_attribute_detail record into the database]
         * @param  No Parameters
         * @param  [string] $flag [new flag product_attribute if previous one is change else it is
         * the old one]
         * @return [boolean] [if insertion is performed successfully then, returns TRUE.]
         */
        public function update_product_attribute_detail($id)
        {
            $this->product_attribute = strtolower(trim($this->db->escape($this->input->post('product_attribute')), "' "));

            $this->name = !empty($_POST['product_attribute_detail_color']) ? '#' . $this->input->post('product_attribute_detail_color') : $this->input->post('product_attribute_detail');

            $this->name = strtolower(trim($this->db->escape($this->name), "' "));
            
            $data = array(

                'product_attribute_id' => $this->product_attribute,

                'name' => $this->name
            ); 

            $this->db->where('id', $id);
                
            if ($this->db->update('product_attribute_detail', $data)) 
            {
                return TRUE;
            }
        }

        /**
         * [get_product_attribute_detail Returns all details of product_attribute_detail 
         * e.g id = 1, product_attribute = Nawaz Sharif) ................]
         * @return [array] [return all details of product_attribute_detail]
         */
        public function get_product_attribute_detail()
        {
            $query = $this->db->get('product_attribute_detail');
            $result = $query->result_array();
            return $result;
        }

        /**
         * [get_product_attribute_detail Returns all details of product_attribute_detail 
         * e.g id = 1, product_attribute = Nawaz Sharif) ................]
         * @return [array] [return all details of product_attribute_detail]
         */
        public function get_product_attribute_detail_dropdown()
        {
            $this->db->select('product_attribute_detail.id, product_attribute_detail.product_attribute_id');
            
            $this->db->from('product_attribute_detail');
                                                    
            $q = $this->db->get(); 

            $result = $q->result_array();

            return $result;
        }

        /**
         * [get_product_attr_name_by_id Returns all product_attributes name and if for dropdown
         * e.g id = 1, name = Pakistan Muslim League (N)]
         * @return [array] [return all product_attributes]
         */
        public function get_product_attribute_detail_name_by_id($id)
        {
            $this->db->select('product_attribute_detail`.`name`');
            $query = $this->db->where('`product_attribute_detail`.`id`', $id);
            $query = $this->db->get('`product_attribute_detail`');
            $result = $query->result_array();
            return $result[0]['name'];
        }


        /**
         * [get_product_attr_detail_by_product_attr_id Returns all details of product_attribute_detail
         * e.g id = 1, product_attribute = Nawaz Sharif) ................]
         * @return [array] [return all details of product_attribute_detail]
         */
        public function get_product_attr_detail_by_product_attr_id($id)
        {
            $this->db->select('`product_attribute_detail`.`id`, `product_attribute_detail`.`name`');

            $this->db->from('product_attribute_detail');

            $this->db->where('`product_attribute_detail`.`product_attribute_id`', $id);

            $q = $this->db->get();

            $result = $q->result_array();

            return $result;
        }

        /**
         * [delete_product_attribute_detail: Delete a product_attribute_detail record from the database]
         * @param  [int] $id    [product_attribute_detail id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_product_attribute_detail($id)
        {
            if ($this->db->delete('product_attribute_detail', array('id' => $id))) 
            {
                return TRUE;
            }
        }       

        /**
         * [get_product_attribute_detail_by_id Fetchs a product_attribute_detail record from the database by product_attribute_detail id]
         * @param  [type]  $id   [product_attribute_detail id whom record is to be fetched]
         * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
         * all attibutes areretrieved for editing purpose. If it is FALSE than 
         * attibutes are retrieved for display/view purpose]
         * @return [array] [product_attribute_detail record is returned]
         */
        public function get_product_attribute_detail_by_id($id, $edit = FALSE)
        {
            if(!$edit) {
                $this->db->select('`product_attribute_detail`.`id`, `product_attribute`.`name` AS `product_attribute_name`,
             `product_attribute_detail`.`name` AS `product_attribute_value`');

                $this->db->join('product_attribute', 'product_attribute.id = product_attribute_detail.product_attribute_id');

            } else {

                $this->db->select('`product_attribute_detail`.`id`, `product_attribute_detail`.`product_attribute_id`, `product_attribute_detail`.`name`');
            }

            $this->db->from('`product_attribute_detail`');

            $this->db->where(array('`product_attribute_detail`.`id`' => $id));
            
            $q = $this->db->get(); 

            $result = $q->result_array();

            return $result[0]; 
        }

        public function record_count() 
        {
            return $this->db->count_all("product_attribute_detail");
        }

        public function fetch_product_attribute_detail($limit, $start) 
        {
            $this->db->select('`product_attribute_detail`.`id`, `product_attribute`.`name` AS `product_attribute_name`,
             `product_attribute_detail`.`name` AS `product_attribute_value`');

            $this->db->from('product_attribute_detail');

            $this->db->join('product_attribute', 'product_attribute.id = product_attribute_detail.product_attribute_id');

            $this->db->order_by('product_attribute_name', 'asc');

            $this->db->limit($limit, $start);

            $query = $this->db->get();

            if ($query->num_rows() > 0) 
            {
                $result = $query->result_array();

                return $result;
            }

            return FALSE;
        }
    }