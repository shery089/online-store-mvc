<?php
    class Sales_order_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();
            $this->load->model('admin/product_model');
        }

        /**
         * Inserts a product_detail record into the database
         * @param string $image
         * @param string $thumb_image
         * @param string $profile_image
         * @return bool
         */
        public function insert_sales_order()
        {
            // Beginning manually transaction
            $this->db->trans_begin();

            $this->created_by = $this->session->userdata['admin_record'][0]['id'];
            $this->created_date = date("Y-m-d H:i:s");

            $sales_order_data = array(

                'created_date' => $this->created_date,

                'updated_date' => $this->created_date,

                'created_by' => $this->created_by

            );

            $this->db->insert('sales_order', $sales_order_data);

            //  Get loop count
            $count = count($_POST) / 6;
            $array_indices = get_partial_array_indices($_POST, 'price_', 0, 6);
            $this->sales_order_id = $this->db->insert_id();

            $this->total = $this->total_discount = 0;

            for ($i = 0; $i < $count; $i++)  {

                // Getting values from POST array

                $current_index = $array_indices[$i];
                $this->product = trim($this->db->escape($this->input->post('product_' . $current_index)), "' ");
                $this->product_attribute = trim($this->db->escape($this->input->post('product_attribute_' . $current_index)), "' ");
                $this->product_attr_details = trim($this->db->escape($this->input->post('product_attr_details_' . $current_index)), "' ");
                $this->quantity = trim($this->db->escape($this->input->post('quantity_' . $current_index)), "' ");
                $this->sales_price = $this->price = trim($this->db->escape($this->input->post('price_' . $current_index)), "' ");
                $this->discount = trim($this->db->escape($this->input->post('discount_' . $current_index)), "' ");

                // Get product current quantity
                $result = $this->product_model->get_product_quantity($this->product, $this->product_attribute, $this->product_attr_details);

                if(!empty($result)) {
                    /**
                     * If user provided quantity is greater than stock's quantity.
                     * Then subtract user provided quantity from stock's quantity.
                     * After subtraction if quantity is zero then skip the entry.
                     */
                    if($this->quantity > $result['quantity']) {
                        // roll back transaction as user defined quantity is greater than stock's quantity
                        $this->db->trans_rollback();

                        echo json_encode(array('quantity_' . ($i+1) => '<div class="error_prefix text-right">Out of Stock!</div>',
                            'error_message' => 'Quantity of record number ' . ($i + 1) . ' is exceeding 
                            stock limit. Either decrease the quantity of the record or update the inventory'));
                        die();
//                        $this->quantity = $this->quantity - ($this->quantity - $result['quantity']);
                    }

                    if($this->quantity == 0) {
                        // roll back transaction as quantity of one product is zero
                        $this->db->trans_rollback();

                        echo json_encode(array('quantity_' . ($i+1) => '<div class="error_prefix text-right">Out of Stock!</div>',
                            'error_message' => 'Quantity of record number ' . ($i + 1) . ' is zero. 
                        Either remove the record or update the inventory'));
                        die();
                    }

                    /**
                     * If user has provided discount for a product
                     * Then, calculate the discounted value and update
                     * total discounted value
                     */

                    if(!empty($this->discount)) {
                        $discount = ($this->sales_price * ($this->discount/100));
                        $this->sales_price = $this->sales_price - $discount;
                        $this->total_discount += $discount * $this->quantity;
                    }

                    // Update total sales price
                    $this->sales_price = $this->sales_price * $this->quantity;
                    $this->total += $this->sales_price;
                    $sales_order_details_data = array(

                        'sales_order_id' => $this->sales_order_id,

                        'product_details_id' => $result['product_detail_id'],

                        'quantity' => $this->quantity,

                        'sales_price' => $this->sales_price,

                        'price' => $this->price,

                        'discount' => $this->discount,

                        'created_date' => $this->created_date

                    );

                    $this->db->insert('sales_order_details', $sales_order_details_data);

                    /**
                     * Subtract current product quantity from stock
                     */

                    $product_detail_data = array(

                        'last_updated_by' => $this->created_by,

                        'last_updated_on' => $this->created_date

                    );

                    $this->db->set('quantity', '`quantity` - ' . $this->quantity, FALSE);

                    $this->db->where(array(
                        'product_attribute_detail_id' => $this->product_attribute,
                        'product_attribute_detail_value' => $this->product_attr_details,
                        'product_id' => $this->product
                    ));

                    $this->db->update('product_detail', $product_detail_data);

                    /**
                     * Update total discounted value and total sales price
                     */

                    $sales_order_updated_data = array(

                        'total_discount' => $this->total_discount,

                        'total' => $this->total

                    );

                    $this->db->where('id', $this->sales_order_id);
                    $this->db->update('sales_order', $sales_order_updated_data);
                } // $result
            } // for loop

            // commit/complete manual transaction
            $this->db->trans_commit();

            return TRUE;
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
        public function update_sales_order($id)
        {
            $this->quantity = trim($this->db->escape($this->input->post('quantity')), "' ");
            $this->sales_price = trim($this->db->escape($this->input->post('sales_price')), "' ");
            $this->sales_price = trim($this->db->escape($this->input->post('sales_price')), "' ");

            $last_updated_by = $this->session->userdata['admin_record'][0]['id'];
            $last_updated_on = date("Y-m-d H:i:s");

            $data = array(

                'quantity' => $this->quantity,

                'sales_price' => $this->sales_price,

                'sales_price' => $this->sales_price,

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
        public function get_sales_order_by_id_lookup($id, $edit=FALSE)
        {
            if($edit) {
                $this->db->select('`sales_order_details`.*');
                $this->db->from('`sales_order_details`');
            }
            else {
                $this->db->select('`product_detail`.`id`, `product_detail`.`quantity`, `product_detail`.`sales_price`, 
                `product_detail`.`sales_price`, `company`.`name` AS `company_name`, `product`.`name` AS `product_name`, 
                `product_attribute`.`name` AS `product_attribute_name`, `product_attribute_detail`.`name` AS product_attribute_value,
                DATE_FORMAT(`product_detail`.`last_updated_on`, "%d %M %Y %h:%i %p") AS `last_updated_on`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) AS `last_updated_by`');
                $this->db->from('`product`');
                $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
                $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
                $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
                $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');
                $this->db->join('`user`', '`product_detail`.`last_updated_by` = `user`.`id`', 'left');
            }

            $this->db->where('`sales_order_details`.`sales_order_id`', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                $result = $this->get_attachments($result);
                return $result;
            }
            return FALSE;
        }

        public function get_attachments($attachments)
        {
            $this->load->model('admin/product_model');
            for ($i = 0, $count = count($attachments); $i < $count; $i++)
            {
                $attachments[$i]['product_details'] = $this->product_model->get_product_details_by_pd_id($attachments[$i]['product_details_id'], TRUE);
            }
            return $attachments;
        }

        /**
         * [record_count Returns total records in the database]
         * @return [int] [Returns total records in the database]
         */
        public function record_count($params=array())
        {
            $this->db->select('COUNT(`sales_order`.`id`) as total');

            $this->db->from('`sales_order`');

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
                        '`product_detail`.`quantity` <= ' => $params['product_quantity'],
//                        '`product_detail`.`sales_price` != ' => 0,
//                        '`product_detail`.`sales_price` != ' => 0
                    ));
                }
            }/*
            else {
                $this->db->where(array(
                    '`product_detail`.`quantity` != ' => 0,
                    '`product_detail`.`sales_price` != ' => 0,
                    '`product_detail`.`sales_price` != ' => 0
                ));
            }*/

            $query = $this->db->get();
            $result = $query->result_array();
            return array_pop($result)['total'];
        }

        /**
         * [fetch_product_details Returns product_details with a $limit defined in Sales_order controller i.e. $config['per_page']]
         * @param  [int]  $limit [No of record per page]
         * @param  [int]  $start [No of record from whom fetcching is to be start]
         * @return [mixed] [if there are any product_details then return them in array else return FALSE is
         * then, returns TRUE.]
         */
        public function fetch_sales_orders($params = array())
        {
            $this->db->select('`sales_order`.`id`, `sales_order`.`total`, `sales_order`.`total_discount`, 
            DATE_FORMAT(`sales_order`.`created_date`, "%d %M %Y %h:%i %p") AS `created_date`, 
            DATE_FORMAT(`sales_order`.`updated_date`, "%d %M %Y %h:%i %p") AS `updated_date`, 
            CONCAT(`u1`.`first_name`, " ", `u1`.`middle_name`, " ", `u1`.`last_name`) AS `created_by`,
            CONCAT(`u2`.`first_name`, " ", `u2`.`middle_name`, " ", `u2`.`last_name`) AS `updated_by`');
            $this->db->from('`sales_order`');
            $this->db->join('`user AS u1`', '`u1`.`id` = `sales_order`.`created_by`', 'left');
            $this->db->join('`user` AS u2', '`u2`.`id` = `sales_order`.`updated_by`', 'left');

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

            $this->db->order_by('`sales_order`.`id`', 'desc');

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