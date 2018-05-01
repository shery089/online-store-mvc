<?php  
class Product_model extends CI_Model {
    private $name,
            $political_party_id,
            $halqa_id,
            $user_designation_id,
            $introduction,
            $no_designation_id,
            $no_halqa_id,
            $election_history;
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

        // if image is empty then set default flag i.e. no_image_600.png
        $this->image = empty($image) ? 'no_image_600.png' : $image;

        $this->thumbnail = $this->image == 'no_image_600.png' ? 'no_image_600_thumb.png' : $thumb_image;

        $this->profile_image = $this->image == 'no_image_600.png' ? 'no_image_600_profile_image.png' : $profile_image;

        $this->short_desc = 'lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

        $this->long_desc = 'lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. luis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. uxcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

        $data = array(

            'name' => $this->name,

            'category' => $this->category,

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

        $this->insert_product_elasticsearch($product_id, $data, $attr_count);

        $this->elasticsearch->refresh_index_type("products", "product", $product_id);

        return TRUE;
    }

    public function update_product($product_id, $image, $image_thumb, $profile_image)
    {
        $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

        $this->category = (trim($this->db->escape($this->input->post('category')), "' "));

        if(!empty($image))
        {
            $this->image = trim($this->db->escape($image), "' ");
            $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
            $this->profile_image = trim($this->db->escape($profile_image), "' ");
        }

        $data = array(

            'name' => $this->name,

            'category' => $this->category,

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

            $attr_count = $this->get_attr_details_count();

            $count = 1;

            for($i = 0; $i < $attr_count; $i++)
            {
                $product_attr = $this->input->post("submitted_product_attr_$count");
                $product_attr_details = $this->input->post("submitted_product_attr_details_$count");
                $product_attr_details = explode(',', $product_attr_details);

                $prev_records = $this->product_details_records_by_prod_id($product_id, $product_attr);
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
        }

        $descs = $this->get_descs_by_id($product_id);

        $data['short_description'] = $descs['short_description'];
        $data['long_description'] = $descs['long_description'];

        $this->insert_product_elasticsearch($product_id, $data, $attr_count);

        $this->elasticsearch->refresh_index_type("products", "product", $product_id);

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

            $data = json_encode($data);

            $data = '{
                    "doc": ' . $data . '
                }';

            $this->elasticsearch->update_partial_index("products", "product", $product_id, $data);

            $this->elasticsearch->refresh_index_type("products", "product", $product_id);

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

    public function get_product_detail($product_id)
    {
        $this->db->select('product_detail.id, product_detail.product_id, product_detail.designation_id, 
                        product_detail.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
        $this->db->from('product_detail');
        $this->db->where('product_detail.product_id', $product_id);
        $this->db->join('halqa', 'halqa.id = product_detail.halqa_id', 'inner');
        $this->db->join('user_designation', 'user_designation.id = product_detail.designation_id', 'inner');
        $q = $this->db->get();
        $result = $q->result_array();

        // to delete $this->no_designation_id
        for ($i = 0, $count = count($result); $i < $count; $i++)
        {
            if($result[$i]['designation_id'] == $this->no_designation_id)
            {
                unset($result[$i]['designation_id']);
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
    public function get_products_dropdown()
    {
        $this->db->select('`product`.`id`, `product`.`name`');
        $this->db->order_by('`product`.`name`');
        $query = $this->db->get('`product`');
        $result = $query->result_array();
        return $result;
    }
    public function get_product_by_id($id, $edit, $specific_cols = array(), $post = FALSE)
    {
        if(!empty($specific_cols))
        {
            $specific_cols = implode(", ", $specific_cols);
            $specific_cols = preg_replace('/full_name/', 'CONCAT(`product`.`first_name`, " ", `product`.`middle_name`, " ", `product`.`last_name`) AS `full_name`'
                                , $specific_cols);

            $this->db->select($specific_cols);
        }
        else if($post)
        {
            $this->db->select('`product`.`name`');
        }
        else
        {
            $this->db->select('*');
        }

        $this->db->from('product');
    
        $this->db->where(array('id' => $id)); 
        
        $q = $this->db->get(); 

        $result = $q->result_array();
    
        if(!$post)
        {
            $result = $this->get_attachments($result);
        }


        return $result[0]; 
    }

    public function get_attachments($attachments)
    {
        for ($i = 0, $count = count($attachments); $i < $count; $i++)
        {
            $this->db->select('`product_detail`.`product_attribute_detail_id`, `product_detail`.`product_attribute_detail_value`');

            $this->db->from('`product_detail`');

            $this->db->where('`product_detail`.`product_id`', $attachments[$i]['id']);

            $query = $this->db->get();

            $attachments[$i]['product_details']  = $query->result_array();

            $clean_attachments[$i] = $this->clean_attachments($attachments[$i], $i);

            unset($attachments[$i]);
        }

        return $clean_attachments;
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
    public function clean_attachments($attachments)
    {
        $category_id = $attachments['category'];

        $category = $this->category_model->get_category_name_by_id($category_id);

        $attachments['category'] =  $category['category'];

        $attachments['category_id'] =  $category_id;

        $index_arr = array();
        $index_arr[0] = array('index' => array("_index" => "products", "_type" => "product", "_id" => $attachments['id']));
        $index_arr[1] = $attachments;

        $product_attribute_detail_ids = array_values(array_unique(array_column($attachments['product_details'], 'product_attribute_detail_id')));
        $index_arr[1]['product_attribute_detail_id'] = $product_attribute_detail_ids;
        $product_attribute_detail_values = array_values(array_unique(array_column($attachments['product_details'], 'product_attribute_detail_value')));
        $index_arr[1]['product_attribute_detail_value'] = $product_attribute_detail_values;

        // I have used this extra for loop to avoid redundant multiple call to database to get $product_attr e.g. color, size

        for ($k = 0, $kcount = count($product_attribute_detail_ids); $k < $kcount; $k++) {

            $product_attr = $this->product_attribute_model->get_product_attr_name_by_id($product_attribute_detail_ids[$k]);

            $product_details = $index_arr[1]['product_details'];

            /**
             * This loop will return values specific to a product_attribute_detail_id e.g. $product_attribute_detail_ids[$i] = 1
             *[color] => Array ([0] => #ffffff[1] => #000000[2] => #bfbfbf[3] => #1f1fff)
             **/
            for ($j = 0, $jcount = count($product_details); $j < $jcount; $j++) {

                if($product_attribute_detail_ids[$k] == $product_details[$j]['product_attribute_detail_id']) {

                    $product_attr_val = $product_details[$j]['product_attribute_detail_value'];
                    $index_arr[1][$product_attr][] = $this->product_attribute_detail_model->get_product_attribute_detail_name_by_id($product_attr_val);
                }
            }
        }

        unset($index_arr[1]['product_details']);

        return $index_arr;
    }

    public function record_count()
    {
        return $this->db->count_all("product");
    }

    public function fetch_products($limit, $start, $attach_specialization = TRUE) 
    {
        $this->db->select('`product`.`id`, `product`.`name`, `product`.`political_party_id`, `product`.`likes`, `product`.`dislikes`');
     
        $this->db->limit($limit, $start);
      
        $this->db->order_by('`name`');

        $query = $this->db->get('product');

        if ($query->num_rows() > 0) 
        {
            $result = $query->result_array();
            $result = $this->get_attachments($result, TRUE);

            return $result;
        }

        return FALSE;
    }

    public function insert_product_bulk_elasticsearch()
    {
        $this->db->select('`product`.`id`, `product`.`name`, `product`.`category`, `product`.`image`, `product`.`thumbnail`,
        `product`.`profile_image`, `product`.`short_description`, `product`.`long_description`');

        $this->db->from('`product`');

        $this->db->order_by('`product`.`name`');

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->result_array();

            $result = $this->get_attachments($result, TRUE);
            return $result;
        }
        return FALSE;
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

    /*
    public function insert_product_bulk_by_csv($csv)
    {

        foreach ($csv as $product) 
        {
            $data = array(

                'name' => $product['name'],

                'political_party_id' => $product['political_party']
            );
        
            if ($this->db->insert('product', $data)) 
            {
                $product_id = $this->db->insert_id();*/

                
                /** Both halqa_id and user_designation_id insertion is done separetly 
                 * for a case: If product is both senator and national assembly
                 * member and we insert halqa_id against the user_designation_id. In
                 * future election if product is not national assembly member or 
                 * some other designation is loosed then we will loose the halqa_id 
                 * on updation. So, both halqa_id and user_designation_id insertion
                 * is done separetly  
                 

                /**
                 * halqa_id insertion in product_detail table
                 * if: If multiple halqa's are inserted
                 * else: If single halqa is inserted
                 */

     /*           if(isset($product['halqa_ids']))
                {
                    foreach ($product['halqa_ids'] as $halqa_id) 
                    {
                        $this->db->query("INSERT INTO product_detail (product_id, halqa_id, designation_id) 
                                            VALUES($product_id, $halqa_id, $this->no_designation_id)");                
                    }
                    $this->db->query("INSERT INTO product_detail (product_id, halqa_id, designation_id) 
                                        VALUES($product_id, " . $product['halqa_id'] . ", $this->no_designation_id)");
                }
                else
                {
                    $this->db->query("INSERT INTO product_detail (product_id, halqa_id, designation_id) 
                                        VALUES($product_id, " . $product['halqa_id'] . ", $this->no_designation_id)");
                }
            }
        }
    }*/

    public function insert_product_elasticsearch($id, $data, $attr_count) {

        $category_id = $data['category'];

        $category = $this->category_model->get_category_name_by_id($category_id);

        $count = 1;

        for($i = 0; $i < $attr_count; $i++) {

            $product_attr_details = $this->input->post("submitted_product_attr_details_$count");
            $product_attr_id = $this->input->post("submitted_product_attr_$count");

            $data['product_attribute_detail_id'][] = $product_attr_id;
            if(strrchr($product_attr_details, ','))
            {
                $product_attr_details = explode(',', $product_attr_details);

                $product_attr = $this->product_attribute_model->get_product_attr_name_by_id($product_attr_id);

                foreach ($product_attr_details as $product_attr_detail)
                {
                    $data['product_attribute_detail_value'][] = $product_attr_detail;
                    $data[$product_attr][] = $this->product_attribute_detail_model->get_product_attribute_detail_name_by_id($product_attr_detail);
                }
            }
            else
            {
                $data['product_attribute_detail_value'][] = $product_attr_details;
                $product_attr = $this->product_attribute_model->get_product_attr_name_by_id($product_attr_id);
                $data[$product_attr][] = $this->product_attribute_detail_model->get_product_attribute_detail_name_by_id($product_attr_details);
            }

            $count++;
        }

        $data['id'] = $id;
        $data['category'] =  $category['category'];
        $data['category_id'] =  $category_id;

        $json_data = json_encode($data);

        $this->elasticsearch->add("products", "product", $id, $json_data);

        $this->elasticsearch->refresh_index_type('products', 'product', $id);

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

    public function get_product_by_id_db($id) {
        $q = $this->db->select('`id`, `name`, `category` AS `category_id`, `image`, `profile_image`, `thumbnail`,
        ')
        ->from('`product`')
        ->where('`id`', $id)
        ->get();
        return $q->result_array()[0];
    }
}