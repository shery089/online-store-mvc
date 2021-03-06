<?php  
class Product_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();        	
        $this->load->model('admin/product_attribute_model');
        $this->load->model('admin/product_attribute_detail_model');
    }

    public function insert_product($image = '', $thumb_image = '', $profile_image = '')
    {
        $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

        $this->category = (trim($this->db->escape($this->input->post('category')), "' "));

        $this->company = (trim($this->db->escape($this->input->post('company')), "' "));

        // if image is empty then set default flag i.e. no_image_600.png
        $this->image = empty($image) ? 'no_image_600.png' : $image;

        $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

        $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

        $this->short_desc = 'lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $this->long_desc = 'lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. luis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. uxcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

        $data = array(

            'name' => $this->name,

            'category' => $this->category,

            'company' => $this->company,

            'image' => $this->image,

            'thumbnail' => $this->thumbnail,

            'profile_image' => $this->profile_image,

            'short_description' => $this->short_desc,

            'long_description' => $this->long_desc

        );

        if ($this->db->insert('product', $data)) {
            $product_id = $this->db->insert_id();

            /**
             * Insertion of product_attribute_detail_id like id of color,
             * and product_attribute_detail_value id of color value
             */

            $attr_count = $this->get_attr_details_count();

            $count = 1;

            for($i = 0; $i < $attr_count; $i++) {

                $product_attr_details = $this->input->post("submitted_product_attr_details_$count");
                $product_attr = $this->input->post("submitted_product_attr_$count");

                if(strrchr($product_attr_details, ','))
                {
                    $product_attr_details = explode(',', $product_attr_details);

                    foreach ($product_attr_details as $product_attr_detail)
                    {
                        $this->db->query("INSERT INTO product_detail (product_id, product_attribute_detail_id, product_attribute_detail_value)
                        VALUES($product_id, $product_attr, $product_attr_detail)");
                    }
                }
                else
                {
                    $this->db->query("INSERT INTO product_detail (product_id, product_attribute_detail_id, product_attribute_detail_value)
                    VALUES($product_id, $product_attr, $product_attr_details)");
                }

                $count++;
            }
        }

        return TRUE;
    }

    public function update_product($product_id, $image, $image_thumb, $profile_image)
    {
        $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

        $this->category = (trim($this->db->escape($this->input->post('category')), "' "));

        $this->company = (trim($this->db->escape($this->input->post('company')), "' "));

        if(!empty($image))
        {
            $this->image = trim($this->db->escape($image), "' ");
            $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
            $this->profile_image = trim($this->db->escape($profile_image), "' ");
        }

        $data = array(

            'name' => $this->name,

            'category' => $this->category,

            'company' => $this->company,

            'image' => $this->image,

            'thumbnail' => $this->thumbnail,

            'profile_image' => $this->profile_image,

        );
        $this->db->where('id', $product_id);

        if ($this->db->update('product', $data))
        {
            /**
             * Updation of product_attribute_detail_id like id of color,
             * and product_attribute_detail_value id of color value
             */

            $product_attrs_to_delete = array();

            $attr_count = $this->get_attr_details_count();
            $count = 1;
            for($i = 0; $i < $attr_count; $i++)
            {
                $product_attrs_to_delete[] = $product_attr = $this->input->post("submitted_product_attr_$count");
                $product_attr_details = $this->input->post("submitted_product_attr_details_$count");
                $product_attr_details = explode(',', $product_attr_details);

                $prev_records = $this->product_details_records_by_prod_id($product_id, $product_attr);
                $prev_records = (array) $prev_records;
                $prev_records = array_column($prev_records, 'product_attribute_detail_value');

                $prev_records_str = implode(',', $prev_records);

                $to_insert = array_diff($product_attr_details, $prev_records);
                $to_delete = array_diff($prev_records, $product_attr_details);
                $to_delete_str = implode(', ', $to_delete);

                if(!empty($to_delete))
                {
                    $this->db->query("DELETE FROM `product_detail` WHERE `product_attribute_detail_id` = $product_attr
                    AND `product_attribute_detail_value` IN ($to_delete_str)");
                }

                if(!empty($to_insert))
                {
                    foreach($to_insert as $to_insert_rec)
                    {
                        $product_details_data = array(
                            'product_id' => $product_id,
                            'product_attribute_detail_id' => $product_attr,
                            'product_attribute_detail_value' => $to_insert_rec
                        );

                        $this->db->insert('product_detail', $product_details_data);
                    }
                }
                $count++;
            }

            if(!empty($product_attrs_to_delete))
            {
                $product_attrs_to_delete = implode(', ', $product_attrs_to_delete);

                $this->db->query("DELETE FROM `product_detail` WHERE `product_id` = $product_id
                AND `product_attribute_detail_id` NOT IN ($product_attrs_to_delete)");
            }
        }
        return TRUE;
    }

    public function update_product_description($product_id)
    {
        $this->short_description = strtolower(trim($this->db->escape($this->input->post('short_description')), "' "));

        $this->long_description = (trim($this->db->escape($this->input->post('long_description')), "' "));

        $data = array(

            'short_description' => $this->short_description,

            'long_description' => $this->long_description

        );

        $this->db->where('id', $product_id);

        if ($this->db->update('product', $data))
        {
            return TRUE;
        }

    }

    public function get_products()
    {
        $query = $this->db->get('product');
        $result = $query->result_array();
        return $result;
    }

    public function delete_product($id)
    {
        if ($this->db->delete('product', array('id' => $id))) 
        {
            return TRUE;
        }
    }

    public function get_products_dropdown()
    {
        $this->db->select('`product`.`id`, `product`.`name`');
        $this->db->order_by('`product`.`name`');
        $this->db->from('`product`');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_products_by_company_id($product_company)
    {
        $this->db->select('`product`.`id`, `product`.`name`');
        $this->db->order_by('`product`.`name`');
        $this->db->from('`product`');
        $this->db->where('`product`.`company`', $product_company);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_attachments($attachments, $return_data_with_joins, $only_product_attributes)
    {
        for ($i = 0, $count = count($attachments); $i < $count; $i++) {

            $this->db->select('`product_detail`.`product_attribute_detail_id`, `product_detail`.`product_attribute_detail_value`');

            $this->db->from('`product_detail`');

            $this->db->where('`product_detail`.`product_id`', $attachments[$i]['id']);

            $query = $this->db->get();

            $attachments[$i]['product_details']  = $query->result_array();
            if($return_data_with_joins || $only_product_attributes) {
                $attachments[$i] = $this->clean_attachments($attachments[$i], $only_product_attributes);
            }
        }
        return $attachments;
    }

    /**
     * clean_attachments
     * @param $attachments = one complete record of a product
     * Description: Clean the $attachments[$i]['product_details'] and makes it easy to search
     * Example: [product_attribute_detail_id] => Array([0] => 1[1] => 2
     *   )
     *
     *   [product_attribute_detail_value] => Array
     *   ([0] => 3[1] => 4[2] => 5[3] => 9[4] => 1[5] => 6[6] => ))
     *
     *   [color] => Array
     *   ([0] => #ffffff[1] => #000000[2] => #bfbfbf[3] => #1f1fff)
     *
     *   [size] => Array
     *   ([0] => l[1] => m[2] => s)
     * @return array
     *
     */
    public function clean_attachments($attachments, $only_product_attributes = FALSE)
    {
        if(!$only_product_attributes) {
            $category_id = $attachments['category'];
            $category = $this->category_model->get_category_name_by_id($category_id);

            $attachments['category'] =  $category['category'];

            $attachments['category_id'] =  $category_id;
        }

        $product_attribute_detail_ids = array_values(array_unique(array_column($attachments['product_details'], 'product_attribute_detail_id')));
        $product_attribute_detail_values = array_values(array_unique(array_column($attachments['product_details'], 'product_attribute_detail_value')));

        // I have used this extra for loop to avoid redundant multiple call to database to get $product_attr e.g. color, size

        for ($k = 0, $kcount = count($product_attribute_detail_ids); $k < $kcount; $k++) {

            $product_attr = $this->product_attribute_model->get_product_attr_name_by_id($product_attribute_detail_ids[$k]);
            $product_details = $attachments['product_details'];

            /**
             * This loop will return values specific to a product_attribute_detail_id e.g. $product_attribute_detail_ids[$i] = 1
             *[color] => Array ([0] => #ffffff[1] => #000000[2] => #bfbfbf[3] => #1f1fff)
             **/
            for ($j = 0, $jcount = count($product_details); $j < $jcount; $j++) {
                if($product_attribute_detail_ids[$k] == $product_details[$j]['product_attribute_detail_id']) {
                    $product_attr_val = $product_details[$j]['product_attribute_detail_value'];
                    $attachments[$product_attr][] = $a = $this->product_attribute_detail_model->get_product_attribute_detail_name_by_id($product_attr_val);
                }
            }
        }

        if($only_product_attributes) {
            unset($attachments['product_details']);
        }

        return $attachments;
    }

    public function record_count($params=array())
    {
        if(!isset($params['name']) && !isset($params['category']) && !isset($params['company'])) {
            return $this->db->count_all("product");
        }

        if(isset($params['name']) || isset($params['category']) || isset($params['company']) ) {
            $this->db->select('COUNT(id) as total');
        }

        if(isset($params['name'])) {
            $full_name = strtolower($params['name']);
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

            $this->db->where("MATCH (`name`) AGAINST ('$full_name' IN BOOLEAN MODE)");
        }

        if(isset($params['category'])) {
            if(!empty($params['category'])) {
                $this->db->where('`category`',$params['category']);
            }
        }

        if(isset($params['company'])) {
            if(!empty($params['company'])) {
                $this->db->where('`company`',$params['company']);
            }
        }

        $this->db->from('`product`');

        $query = $this->db->get();
        $result = $query->result_array();
        return array_pop($result)['total'];
    }

    public function fetch_products($params = array())
    {
        if(isset($params['has_category_join'])) {
            if ($params['has_category_join']) {
                $this->db->select('`product`.`id`, `product`.`name`, `product`.`thumbnail`, `category`.`name` AS `category`');
            }
            else {
                $this->db->select('`product`.`id`, `product`.`name`, `product`.`thumbnail`, `product`.`category`');
            }
        }

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

            $name = str_replace('* -* ', '-', $name);

            $this->db->where("MATCH (`product`.`name`) AGAINST ('$name' IN BOOLEAN MODE)");
        }

        if(isset($params['category'])) {
            if(!empty($params['category'])) {
                $this->db->where('category', $params['category']);
            }
        }

        if(isset($params['company'])) {
            if(!empty($params['company'])) {
                $this->db->where('company', $params['company']);
            }
        }

        $this->db->order_by('`product`.`id`', 'desc');

        $this->db->from('product');

        if(isset($params['has_category_join'])) {
            if($params['has_category_join']) {
                $this->db->join('`category`', '`category`.`id` = `product`.`category`', 'left');
            }
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            if(!isset($params['has_category_join'])) {
                $result = $this->get_attachments($result);
            }
            return $result;
        }

        return false;
    }

    public function product_details_records_by_prod_id($product_id, $product_attr_id)
    {
        $this->db->select('`product_detail`.`id`, `product_detail`.`product_id`, `product_detail`.`product_attribute_detail_id`,
        `product_detail`.`product_attribute_detail_value`');

        $this->db->where('`product_id` = ' . $product_id . ' AND `product_attribute_detail_id` = ' . $product_attr_id);

        $this->db->from('`product_detail`');

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->result_array();
            return $result;
        }

        return FALSE;
    }

    public function get_attr_details_count() {
        foreach ($this->input->post() as $key => $value) {
            if("submitted_product_attr_details_" == substr($key,0,31)){
                $attr_count = substr($key,strrpos($key,'_') + 1);
            }
        }
        return $attr_count;
    }

    public function get_descs_by_id($id) {
        $q = $this->db->select('`short_description`, `long_description`')
        ->from('`product`')
        ->where('`id`', $id)
        ->get();
        return $q->result_array()[0];
    }

    public function get_product_by_id($id, $return_data_with_joins=FALSE, $only_category_join=FALSE, $only_product_attributes=FALSE, $return_data_without_joins=FALSE) {
        if($only_category_join) {
            $this->db->select('`product`.`id`, `product`.`name`, `category`.`name` AS `category`, `product`.`profile_image`,
            `product`.`short_description`, `product`.`long_description`');
        }
        else if($only_product_attributes) {
            $this->db->select('`product`.`id`, `product`.`name`');
        }
        else {
            $this->db->select('`product`.`id`, `product`.`name`, `product`.`category`, `product`.`company`, `product`.`image`, `product`.`profile_image`, `product`.`thumbnail`');
        }
        $this->db->from('product');
        if($only_category_join) {
            $this->db->join('`category`', '`product`.`category` = `category`.`id`', 'left');
        }
        $this->db->where('`product`.`id`', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            if($return_data_with_joins || $only_product_attributes || $return_data_without_joins) {
                $result = $this->get_attachments($result, $return_data_with_joins, $only_product_attributes);
            }
            return $result[0];
        }
        return FALSE;
    }

    public function get_product_desc_by_prod_id($id) {
        $this->db->select('`product`.`id`, `product`.`name`, `product`.`short_description`, `long_description`');
        $this->db->from('product');
        $this->db->where('`product`.`id`', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result[0];
        }
        return FALSE;
    }

    /**
     * Returns products by partially and full matches
     * @param $full_name
     * @return mixed Returns users full_name array or an empty array
     */
    public function product_full_name_autocomplete($full_name)
    {
        $this->db->select('`product`.`name`');
        $this->db->from('`product`');

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
            $this->db->where("MATCH (`name`) AGAINST ('$full_name' IN BOOLEAN MODE)");
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

    public function get_product_specific_attributes($product_id) {
        $this->db->select('DISTINCT(`product_detail`.`product_attribute_detail_id`) AS product_attributes');
        $this->db->from('`product_detail`');
        $this->db->where('`product_detail`.`product_id`', $product_id);
        $q = $this->db->get();
        if($q->num_rows() > 0) {
            $result = $q->result_array();
            $result = array_column($result, 'product_attributes');
            $result = $this->get_product_attr_name_by_id($result);
            return $result;
        }
        return array();
    }

    public function get_product_sales_price_and_quantity($product_id, $product_attr_id, $product_attr_val, $return_specific_key=FALSE,
                    $specific_key='') {

        if($return_specific_key) {
            $this->db->select('`product_detail`.`' . $specific_key . '`');
        }
        else {
            $this->db->select('`product_detail`.`quantity`, `product_detail`.`sale_price`');
        }

        $this->db->from('`product_detail`');
        $this->db->where(array(
                            '`product_detail`.`product_id`' => $product_id,
                            '`product_detail`.`product_attribute_detail_id`' => $product_attr_id,
                            '`product_detail`.`product_attribute_detail_value`' => $product_attr_val
                        ));
        $q = $this->db->get();
        if($q->num_rows() > 0) {
            $result = $q->result_array();
            return $result[0];
        }
        return array();
    }

    public function get_product_attr_name_by_id($product_attrs) {
        $formatted_product_attrs = array();
        foreach ($product_attrs as $product_attr) {
            $formatted_product_attrs[$product_attr] = $this->product_attribute_model->get_product_attr_name_by_id($product_attr);
        }
        return $formatted_product_attrs;
    }

    public function get_product_attr_detail_name_by_id($product_attr_details) {
        $formatted_product_attr_details = array();
        foreach ($product_attr_details as $product_attr) {
            $formatted_product_attr_details[$product_attr] = $this->product_attribute_detail_model->get_product_attribute_detail_name_by_id($product_attr);
        }
        return $formatted_product_attr_details;
    }

    public function get_product_attr_detail_by_prod_id($product_id, $product_attr_id) {
        $this->db->select('DISTINCT(`product_detail`.`product_attribute_detail_value`) AS product_attribute_details');
        $this->db->from('`product_detail`');
        $this->db->where(array('`product_detail`.`product_id`' => $product_id,
                                '`product_detail`.`product_attribute_detail_id`' => $product_attr_id));
        $q = $this->db->get();
        if($q->num_rows() > 0) {
            $result = $q->result_array();
            $result = array_column($result, 'product_attribute_details');
            $result = $this->get_product_attr_detail_name_by_id($result);
            return $result;
        }
        return array();
    }

    public function fetch_low_quantity_product_details($params=array()) {
        $this->db->select('`product_detail`.`id`, `product_detail`.`quantity`, `product_detail`.`purchase_price`, 
            `product_detail`.`sale_price`, `company`.`name` AS `company_name`, `product`.`name` AS `product_name`, 
            `product_attribute`.`name` AS `product_attribute_name`, `product_attribute_detail`.`name` AS product_attribute_value');
        $this->db->from('`product`');
        $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
        $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
        $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
        $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');

        $this->db->limit($params['per_page'], 0);
//        $this->db->limit($params['per_page'], $params['current_page']);

        if(isset($params['minimum_products_notification'])) {
            if(!empty($params['minimum_products_notification'])) {
                $this->db->where('`quantity` <= ', $params['minimum_products_notification']);
            }
        }

        $this->db->order_by('`product_detail`.`quantity`', 'desc');

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->result_array();
            return $result;
        }

        return false;
    }

    public function count_low_quantity_products($params=array()) {
        $this->db->select('COUNT(`product_detail`.`id`) AS `count`');
        $this->db->from('`product`');
        $this->db->join('`company`', '`company`.`id` = `product`.`company`', 'left');
        $this->db->join('`product_detail`', '`product_detail`.`product_id` = `product`.`id`', 'left');
        $this->db->join('`product_attribute`', '`product_detail`.`product_attribute_detail_id` = `product_attribute`.`id`', 'left');
        $this->db->join('`product_attribute_detail`', '`product_detail`.`product_attribute_detail_value` = `product_attribute_detail`.`id`', 'left');
        if(isset($params['minimum_products_notification'])) {
            if(!empty($params['minimum_products_notification'])) {
                $this->db->where('`quantity` <= ', $params['minimum_products_notification']);
            }
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->result_array();
            return array_pop($result)['count'];
        }

        return false;
    }

    public function get_product_quantity($product_id, $product_attribute_detail_id, $product_attribute_detail_value)
    {
        if (!empty($product_id) &&
            !empty($product_attribute_detail_id) &&
            !empty($product_attribute_detail_value)) {
            $this->db->select('`product_detail`.`id` AS `product_detail_id`, `product_detail`.`quantity`');
            $this->db->from('`product_detail`');
            $this->db->where(array(
                '`product_detail`.`product_id`' => $product_id,
                '`product_detail`.`product_attribute_detail_id`' => $product_attribute_detail_id,
                '`product_detail`.`product_attribute_detail_value`' => $product_attribute_detail_value
            ));
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                return $result;
            }

            return false;
        }
    }
    public function get_product_details_by_pd_id($pd_id) {

        if(!empty($pd_id)) {
            $this->db->select('`product_detail`.`id` AS `product_detail_id`, `product`.`name` AS `product_name`, 
            `product_attribute`.`name` AS `product_attribute_name`, `product_attribute_detail`.`name` AS `product_attribute_detail_name`');
            $this->db->from('`product_detail`');
            $this->db->join('`product`', '`product`.`id` = `product_detail`.`product_id`', 'left');
            $this->db->join('`product_attribute`', '`product_attribute`.`id` = `product_detail`.`product_attribute_detail_id`', 'left');
            $this->db->join('`product_attribute_detail`', '`product_attribute_detail`.`id` = `product_detail`.`product_attribute_detail_value`', 'left');
            $this->db->where('`product_detail`.`id`', $pd_id);

            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                return $result;
            }

            return false;

        }
    }
}