<?php  
class Product_description_model extends CI_Model {
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
//        $this->load->model('admin/political_party_model');
//        $this->load->model('admin/designation_model');
//        $this->load->model('admin/halqa_model');
//        $this->no_designation_id = $this->designation_model->get_id_by_key('no designation');
//        $this->no_designation_id = $this->no_designation_id['id'];
//        $this->no_halqa_id = $this->halqa_model->get_id_by_key('no halqa');
//        $this->no_halqa_id = $this->no_halqa_id['id'];
    }

    public function insert_product_description($image = '', $thumb_image = '', $profile_image = '')
    {
        die;
        $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));

        $this->category = (trim($this->db->escape($this->input->post('category')), "' "));

        $data = array(

            'name' => $this->name,

            'category' => $this->category

        );

        if ($this->db->insert('product_description', $data)) {

            return TRUE;
        }
        return FALSE;

    }

    public function update_product_description($id, $image, $image_thumb, $profile_image)
    {
        $this->name = strtolower(trim($this->db->escape($this->input->post('name')), "' "));
        
        $this->designation = strtolower(trim($this->db->escape($this->input->post('designation')), "' "));
        
        $this->political_party = strtolower(trim($this->db->escape($this->input->post('political_party')), "' "));

        $this->introduction = strtolower(trim($this->db->escape($this->input->post('introduction')), "' "));

        $this->election_history = strtolower(trim($this->db->escape($this->input->post('election_history')), "' "));

        // if image is empty then set default image i.e. no_image_600.png
        if(!empty($image))
        {                
            $this->image = trim($this->db->escape($image), "' ");
            $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
            $this->profile_image = trim($this->db->escape($profile_image), "' ");
        }

        $this->halqa = strtolower(trim($this->db->escape($this->input->post('halqa')), "' "));

        $data = array(

            'name' => $this->name,

            'political_party_id' => $this->political_party,

            'introduction' => $this->introduction,

            'election_history' => $this->election_history,

            'image' => $this->image,

            'thumbnail' => $this->thumbnail,
            
            'profile_image' => $this->profile_image
        ); 

        $this->db->where('id', $id);
            
        if ($this->db->update('product_description', $data)) 
        {  
            $q = $this->db->get_where('product_description_detail', array('product_description_id' => $id));

            /*=============================================
            =            Designation's block              =
            =============================================*/

            $existing_designations_and_halqas = $q->result_array();


            $existing_designations = array_column($existing_designations_and_halqas, 'designation_id');
            $existing_designations_insert = implode(',', $existing_designations);

            // to delete $this->no_designation_id
            $designation_str = $this->designation;
            $designation_str = $designation_str . ',' . $this->no_designation_id;
            
            // to delete $this->no_designation_id
            for ($i = 0, $count = count($existing_designations); $i < $count; $i++)
            {
                if($existing_designations[$i] == $this->no_designation_id)
                {
                    unset($existing_designations[$i]);
                }
            }
            
            $existing_designations_str = implode(',', $existing_designations);
            $this->designation = explode(',', $this->designation);

            // to delete $this->no_designation_id for array_diff
            for ($i = 0, $count = count($this->designation); $i < $count; $i++)
            {
                if($this->designation[$i] == $this->no_designation_id)
                {
                    unset($this->designation[$i]);
                }
            }

            // $existing_halqas = array_column($existing_designations_and_halqas, 'halqa_id');
            // $existing_halqas_str = implode(',', array_column($existing_halqas, 'halqa_id'));

            // $existing_designations

            if(strrchr($existing_designations_str, ',')) // multiple designations
            {
                $record_to_insert = array_diff($this->designation, $existing_designations);
                $q = $this->db->query("SELECT * FROM product_description_detail WHERE product_description_id = $id AND designation_id NOT IN ($designation_str)");
                // else
                // {
                //     $q = $this->db->query("SELECT * FROM product_description_detail WHERE product_description_id = $id AND designation_id NOT IN ($existing_designations_insert)");
                // }
                $to_delete_string = implode(',', array_column($q->result_array(), 'id'));

                if(strlen($to_delete_string) > 0)
                {
                    $q = $this->db->query("DELETE FROM product_description_detail WHERE id IN ($to_delete_string)");
                }

                if(!empty($record_to_insert))
                {
                    foreach ($record_to_insert as $to_insert) 
                    {
                        $this->db->query("INSERT INTO product_description_detail (product_description_id, designation_id, halqa_id) 
                                            VALUES($id, $to_insert, $this->no_halqa_id)");                
                    }
                }
            } // multiple designations end
            else // single designations
            {
                if(count($this->designation) == 1)
                {
                    $this->designation = $this->designation;
                }
                $q = $this->db->query("SELECT * FROM product_description_detail WHERE product_description_id = $id AND designation_id NOT IN ($designation_str)");
                $to_delete = array_column($q->result_array(), 'id');
                if(count($to_delete) >= 1)
                {
                    $to_delete_string = implode(',', $to_delete);
                    if(!empty($to_delete))
                    {
                        $this->db->query("DELETE FROM product_description_detail WHERE id IN ($to_delete_string)");
                    }
                }

                /*if(empty($to_delete))
                {
                    // $q = $this->db->query("DELETE FROM product_description_detail WHERE id IN ($to_delete[0])");
                }*/
                
                $record_to_insert = array_diff($this->designation, $existing_designations);
                if(!empty($record_to_insert))
                {
                    if(count($record_to_insert) > 1)
                    {
                        foreach ($record_to_insert as $to_insert) 
                        {
                            $this->db->query("INSERT INTO product_description_detail (product_description_id, designation_id, halqa_id) 
                                                VALUES($id, $to_insert, $this->no_halqa_id)");                
                        }
                    }
                    else
                    {
                        $key = array_keys($record_to_insert);
                        $key = $key[0];

                        $this->db->query("INSERT INTO product_description_detail (product_description_id, designation_id, halqa_id) 
                            VALUES($id, $record_to_insert[$key], $this->no_halqa_id)");                         
                    }                
                }
            } // single designations end

            /*=====  End of Designations's block  ===============*/


            /*=============================================
            =            Halqa's block                    =
            =============================================*/
                            
            $existing_halqas = array_column($existing_designations_and_halqas, 'halqa_id');
            $existing_halqas_insert = implode(',', $existing_halqas);

            // to delete $this->no_halqa_id
            $halqa_str = $this->halqa . ',' . $this->no_halqa_id;
            $halqa_str_single = $this->halqa;
            // to delete $this->no_halqa_id
            for ($i = 0, $count = count($existing_halqas); $i < $count; $i++)
            {
                if($existing_halqas[$i] == $this->no_halqa_id)
                {
                    unset($existing_halqas[$i]);
                }
            }
            
            $existing_halqas_str = implode(',', $existing_halqas);
            $this->halqa = explode(',', $this->halqa);

            // to delete $this->no_halqa_id for array_diff
            for ($i = 0, $count = count($this->halqa); $i < $count; $i++)
            {
                if($this->halqa[$i] == $this->no_halqa_id)
                {
                    unset($this->halqa[$i]);
                }
            }
            // $existing_halqas = array_column($existing_designations_and_halqas, 'halqa_id');
            // $existing_halqas_str = implode(',', array_column($existing_halqas, 'halqa_id'));
            if(strrchr($existing_halqas_str, ',')) // multiple halqas
            {
                $record_to_insert = array_diff($this->halqa, $existing_halqas);
                $q = $this->db->query("SELECT * FROM product_description_detail WHERE product_description_id = $id AND halqa_id NOT IN ($halqa_str)");
                $to_delete_string = implode(',', array_column($q->result_array(), 'id'));
                if(strlen($to_delete_string) > 0)
                {
                    $q = $this->db->query("DELETE FROM product_description_detail WHERE id IN ($to_delete_string)");
                }

                if(!empty($record_to_insert))
                {
                    foreach ($record_to_insert as $to_insert) 
                    {
                        $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                                            VALUES($id, $to_insert, $this->no_designation_id)");                
                    }
                }
            } // multiple halqas end
            else // single halqas
            {
                if(count($this->halqa) == 1)
                {
                    $this->halqa = $this->halqa;
                }
                $q = $this->db->query("SELECT * FROM product_description_detail WHERE product_description_id = $id AND halqa_id NOT IN ($halqa_str)");
                $to_delete = array_column($q->result_array(), 'id');

                if(count($to_delete) >= 1)
                {
                    $to_delete_string = implode(',', $to_delete);

                    if(!empty($to_delete))
                    {
                        $q = $this->db->query("DELETE FROM product_description_detail WHERE id IN ($to_delete_string)");
                    }
                }
                
                $record_to_insert = array_diff($this->halqa, $existing_halqas);

                if(!empty($record_to_insert))
                {
                    if(count($record_to_insert) > 1)
                    {
                        foreach ($record_to_insert as $to_insert) 
                        {
                            $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                                                VALUES($id, $to_insert, $this->no_designation_id)");                
                        }
                    }
                    else
                    {
                        $key = array_keys($record_to_insert);
                        $key = $key[0];

                        $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                            VALUES($id, $record_to_insert[$key], $this->no_designation_id)");                         
                    }
                }


            } // single halqas end

            /*=====  End of Halqa's block  ===============*/

        } // update
        return TRUE;
    }
    public function get_product_descriptions()
    {
        $query = $this->db->get('product_description');
        $result = $query->result_array();
        return $result;
    }
    public function delete_product_description($id)
    {
        if ($this->db->delete('product_description', array('id' => $id))) 
        {
            return TRUE;
        }
    }
    public function get_product_description_detail($product_description_id)
    {
        $this->db->select('product_description_detail.id, product_description_detail.product_description_id, product_description_detail.designation_id, 
                        product_description_detail.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
        $this->db->from('product_description_detail');
        $this->db->where('product_description_detail.product_description_id', $product_description_id);
        $this->db->join('halqa', 'halqa.id = product_description_detail.halqa_id', 'inner');
        $this->db->join('user_designation', 'user_designation.id = product_description_detail.designation_id', 'inner');
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
    public function get_product_descriptions_dropdown()
    {
        $this->db->select('`product_description`.`id`, `product_description`.`name`');
        $this->db->order_by('`product_description`.`name`');
        $query = $this->db->get('`product_description`');
        $result = $query->result_array();
        return $result;
    }
    public function get_product_description_by_id($id, $edit, $specific_cols = array(), $post = FALSE)
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
            $this->db->select('`product_description`.`name`');
        }
        else
        {
            $this->db->select('*');
        }

        $this->db->from('product_description');
    
        $this->db->where(array('id' => $id)); 
        
        $q = $this->db->get(); 

        $result = $q->result_array();
    
        if(!$post)
        {
            $result = $this->get_attachments($result);
        }


        return $result[0]; 
    }
    public function get_attachments($attachments, $attach_product_description_detail = TRUE)
    {
        for ($i = 0, $count = count($attachments); $i < $count; $i++) 
        { 
            // TRUE is passed 
            $attachments[$i]['political_party_id'] = $this->political_party_model->get_political_party_by_id($attachments[$i]['political_party_id'], FALSE, TRUE); 
        }

        if($attach_product_description_detail)
        {
            //$result['specialization'] = $this->get_doctor_specialization($id);
            for ($i=0, $count = count($attachments); $i < $count; $i++) 
            { 
                if($attachments[$i])
                {
                    $attachments[$i]['product_description_detail'] = $this->get_product_description_detail($attachments[$i]['id']);
                }
            }
        }
        return $attachments;
    }
    public function record_count() 
    {
        return $this->db->count_all("product_description");
    }
    public function fetch_product_descriptions($limit, $start, $attach_specialization = TRUE) 
    {
        $this->db->select('`product_description`.`id`, `product_description`.`name`, `product_description`.`political_party_id`, `product_description`.`likes`, `product_description`.`dislikes`');
     
        $this->db->limit($limit, $start);
      
        $this->db->order_by('`name`');

        $query = $this->db->get('product_description');

        if ($query->num_rows() > 0) 
        {
            $result = $query->result_array();
            $result = $this->get_attachments($result, TRUE); 
            return $result;
        }

        return FALSE;
    }
    public function insert_product_description_bulk_by_csv($csv)
    {

        foreach ($csv as $product_description) 
        {
            $data = array(

                'name' => $product_description['name'],

                'political_party_id' => $product_description['political_party']
            );
        
            if ($this->db->insert('product_description', $data)) 
            {
                $product_description_id = $this->db->insert_id();

                
                /** Both halqa_id and user_designation_id insertion is done separetly 
                 * for a case: If product_description is both senator and national assembly
                 * member and we insert halqa_id against the user_designation_id. In
                 * future election if product_description is not national assembly member or 
                 * some other designation is loosed then we will loose the halqa_id 
                 * on updation. So, both halqa_id and user_designation_id insertion
                 * is done separetly  
                 

                /**
                 * halqa_id insertion in product_description_detail table
                 * if: If multiple halqa's are inserted
                 * else: If single halqa is inserted
                 */
                 
                if(isset($product_description['halqa_ids']))
                {
                    foreach ($product_description['halqa_ids'] as $halqa_id) 
                    {
                        $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                                            VALUES($product_description_id, $halqa_id, $this->no_designation_id)");                
                    }
                    $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                                        VALUES($product_description_id, " . $product_description['halqa_id'] . ", $this->no_designation_id)");
                }
                else
                {
                    $this->db->query("INSERT INTO product_description_detail (product_description_id, halqa_id, designation_id) 
                                        VALUES($product_description_id, " . $product_description['halqa_id'] . ", $this->no_designation_id)");
                }
            }
        }
   }
}