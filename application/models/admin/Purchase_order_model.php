<?php
    class Purchase_order_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();              
        }

        /**
         * Inserts a product_detail record into the database
         * @param string $image
         * @param string $thumb_image
         * @param string $profile_image
         * @return bool
         */
        public function insert_purchase_order()
        {
            $count = count($_POST) / 6;
            $array_indices = get_partial_array_indices($_POST, 'sale_price_', 0, 11);
            for ($i = 0; $i < $count; $i++)  {
                $current_index = $array_indices[$i];
                $this->sale_price = trim($this->db->escape($this->input->post('sale_price_'.$current_index)), "' ");
                $this->purchase_price = trim($this->db->escape($this->input->post('purchase_price_' . $current_index)), "' ");
                $this->quantity = trim($this->db->escape($this->input->post('quantity_' . $current_index)), "' ");

                $last_updated_by = $this->session->userdata['admin_record'][0]['id'];
                $last_updated_on = date("Y-m-d H:i:s");

                $data = array(

                    'sale_price' => $this->sale_price,

                    'purchase_price' => $this->purchase_price,

                    'last_updated_by' => $last_updated_by,

                    'last_updated_on' => $last_updated_on,

                );

                $this->db->set('quantity', '`quantity` + ' . $this->quantity, FALSE);


                $this->db->where(array(
                    'product_attribute_detail_id' => $this->input->post('product_attribute_' . $current_index),
                    'product_attribute_detail_value' => $this->input->post('product_attr_details_' . $current_index),
                    'product_id' => $this->input->post('product_' . $current_index)
                ));

                if ($this->db->update('product_detail', $data))
                {
                    return TRUE;
                }
            }
        }

        /**
         * Updates a product_detail record into the database
         * @param $id
         * @param $image
         * @param $image_thumb
         * @param $profile_image
         * @param $joined_date
         * @return bool
         */
        public function update_purchase_order($id)
        {
            $this->quantity = trim($this->db->escape($this->input->post('quantity')), "' ");
            $this->purchase_price = trim($this->db->escape($this->input->post('purchase_price')), "' ");
            $this->sale_price = trim($this->db->escape($this->input->post('sale_price')), "' ");

            $last_updated_by = $this->session->userdata['admin_record'][0]['id'];
            $last_updated_on = date("Y-m-d H:i:s");

            $data = array(

                'quantity' => $this->quantity,

                'purchase_price' => $this->purchase_price,

                'sale_price' => $this->sale_price,

                'last_updated_by' => $last_updated_by,

                'last_updated_on' => $last_updated_on
            );

            $this->db->where('id', $id);
                
            if ($this->db->update('product_detail', $data))
            {
                return TRUE;
            }
        }

        /**
         * [delete_product_detail: Delete a product_detail record from the database]
         * @param  [int] $id    [product_detail id whom record is deleting]
         * @return [boolean] [if deletion is is performed successfully then, returns TRUE.]
         */
        public function delete_product_detail($id)
        {
            if ($this->db->delete('product_detail', array('id' => $id))) 
            {
                return TRUE;
            }
        }

        /**
         * Returns a product_detail record from the database by product_detail id
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_purchase_order_by_id_lookup($id, $edit=FALSE)
        {
            if($edit) {
                $this->db->select('`product_detail`.`id`, `product_detail`.`quantity`, `product_detail`.`purchase_price`, 
                `product_detail`.`sale_price`, `product`.`name` AS `product_name`, `product_attribute`.`name` AS `product_attribute_name`, 
                `product_attribute_detail`.`name` AS product_attribute_value');
                $this->db->from('`product`');
                $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
                $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
                $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');
            }
            else {
                $this->db->select('`product_detail`.`id`, `product_detail`.`quantity`, `product_detail`.`purchase_price`, 
                `product_detail`.`sale_price`, `company`.`name` AS `company_name`, `product`.`name` AS `product_name`, 
                `product_attribute`.`name` AS `product_attribute_name`, `product_attribute_detail`.`name` AS product_attribute_value,
                DATE_FORMAT(`product_detail`.`last_updated_on`, "%d %M %Y %h:%i %p") AS `last_updated_on`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `last_updated_by`');
                $this->db->from('`product`');
                $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
                $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
                $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
                $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');
                $this->db->join('`user`', '`product_detail`.`last_updated_by` = `user`.`id`', 'left');
            }

            $this->db->where('`product_detail`.`id`', $id);
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
            $this->db->select('COUNT(`product_detail`.`id`) as total');
            $this->db->from('`product_detail`');

            if(isset($params['product_id'])) {
                if(!empty($params['product_id'])) {
                    $this->db->where('`product_id`', $params['product_id']);
                }
            }

            if(isset($params['product_company'])) {
                if(!empty($params['product_company'])) {
                    $this->db->join('`product`', '`product_detail`.`product_id` = `product`.`id`', 'left');
                    $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
                    $this->db->where('`product`.`company`', $params['product_company']);
                }
            }

            if(isset($params['product_quantity'])) {
                if(!empty($params['product_quantity'])) {
                    $this->db->where(array(
                        '`product_detail`.`quantity` >= ' => $params['product_quantity'],
                        '`product_detail`.`purchase_price` != ' => 0,
                        '`product_detail`.`sale_price` != ' => 0
                    ));
                }
            }
            else {
                $this->db->where(array(
                    '`product_detail`.`quantity` != ' => 0,
                    '`product_detail`.`purchase_price` != ' => 0,
                    '`product_detail`.`sale_price` != ' => 0
                ));
            }

            $query = $this->db->get();
            $result = $query->result_array();
            return array_pop($result)['total'];
        }

        /**
         * [fetch_product_details Returns product_details with a $limit defined in Purchase_order controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any product_details then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_purchase_orders($params = array())
        {
            $this->db->select('`product_detail`.`id`, `product_detail`.`quantity`, `product_detail`.`purchase_price`, 
            `product_detail`.`sale_price`, `company`.`name` AS `company_name`, `product`.`name` AS `product_name`, 
            `product_attribute`.`name` AS `product_attribute_name`, `product_attribute_detail`.`name` AS product_attribute_value');
            $this->db->from('`product`');
            $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
            $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
            $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
            $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');

            $this->db->limit($params['per_page'], $params['current_page']);

            if(isset($params['product_id'])) {
                if(!empty($params['product_id'])) {
                    $this->db->where('`product_id`', $params['product_id']);
                }
            }

            if(isset($params['product_company'])) {
                if(!empty($params['product_company'])) {
                    $this->db->where('`product`.`company`', $params['product_company']);
                }
            }

            if(isset($params['product_quantity'])) {
                if(!empty($params['product_quantity'])) {
                    $this->db->where('`quantity` <= ', $params['product_quantity']);
                }
            }

            $this->db->where(array(
                '`quantity` != ' => 0,
                '`purchase_price` != ' => 0,
                '`sale_price` != ' => 0,
            ));

            $this->db->order_by('`product_detail`.`last_updated_on`', 'desc');

            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                return $result;
            }

            return false;
        }

        /**
         * Returns product_details by partially and full matches
         * @param $product_name
         * @return mixed Returns product_details name array or an empty array
         */
        public function product_detail_name_autocomplete($product_name)
        {
            $this->db->select('`product_detail`.`name`');
            $this->db->from('`product_detail`');

            $this->db->limit(AUTOCOMPLETE_RECORD_LIMIT, 0);

            $product_name = preg_replace('!\s+!', ' ', $product_name);

            if(strpos($product_name, ' ') !== FALSE) {
                $product_name = explode(' ', $product_name);
                array_walk($product_name, function(&$value,$key) {
                    $value="$value*";
                });
                $product_name = implode(' ', $product_name);
            }
            else {
                $product_name .= '*';
            }

            if(isset($product_name)) {
                $this->db->where("MATCH (`name`) AGAINST ('$product_name' IN BOOLEAN MODE)");
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

        public function get_product_details_dropdown() {
            $this->db->select('`product_detail`.`id`, `product_detail`.`name`');
            $this->db->from('`product_detail`');
            $q = $this->db->get();
            if($q->num_rows() > 0) {
                $result = $q->result_array();
                return $result;
            }
            return array();
        }
    }