<?php  
class Gallery_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();        	
    }

    public function insert_gallery_images($images)
    {
        foreach ($images as $image)
        {
            $image = json_decode($image, TRUE);

            $this->product_id = $image['product_id'];

            $this->title = 'a random caption';

            $this->image = $image['image_name'];

            $this->thumbnail = $image['thumbnail'];;

            $this->profile_image = $image['profile_image'];;
            
            $this->posted_by = array_column($this->session->userdata['admin_record'], 'id')[0];

            date_default_timezone_set("Asia/Karachi");
            
            $date = date("Y-m-d H:i:s");
            
            $this->uploaded_date = $date;

            $this->updated_date = $date;
                
            $data = array(

                'product_id' => $this->product_id,

                'title' => $this->title,

                'image' => $this->image,
              
                'thumbnail' => $this->thumbnail,
              
                'profile_image' => $this->profile_image,

                'posted_by' => $this->posted_by,

                'updated_by' => $this->posted_by,

                'uploaded_date' => $this->uploaded_date,

                'updated_date' => $this->updated_date,
            );

            $this->db->insert('gallery', $data);
        }

        return TRUE;
    }
        
    public function update_picture($id, $image, $image_thumb, $profile_image)
    {
        $this->title = strtolower(trim($this->db->escape($this->input->post('title')), "' "));
                
        $this->updated_by = array_column($this->session->userdata['admin_record'], 'id')[0];

        date_default_timezone_set("Asia/Karachi");
        
        $date = date("Y-m-d H:i:s");
        
        $this->updated_date = $date;

        // if image is empty then set default image i.e. no_image_600.png
        if(!empty($image))
        {                
            $this->image = trim($this->db->escape($image), "' ");
            $this->thumbnail = trim($this->db->escape($image_thumb), "' ");
            $this->profile_image = trim($this->db->escape($profile_image), "' ");
        }

        $data = array(

            'title' => $this->title,

            'image' => $this->image,
          
            'thumbnail' => $this->thumbnail,
          
            'profile_image' => $this->profile_image,

            'updated_by' => $this->updated_by,

            'updated_date' => $this->updated_date,
        );

        $this->db->where('id', $id);
            
        if ($this->db->update('gallery', $data)) 
        {
            return TRUE;
        }
    }

    public function insert_gallery_description()
    {        
        $this->description = strtolower(trim($this->db->escape($this->input->post('desc')), "' "));
        
        /**
         * [Description triming]
         */

        $this->description = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(
            array('\r\n', '\n\r', '\r', '\n'), '<br>', $this->description)))));

        $this->description = preg_replace('/\[removed\]/', '', $this->description);
        $this->description = preg_replace('/(<br>)+/', '<br>', $this->description);
        $this->description = preg_replace('/[\t]+/', '    ', $this->description);
        $this->description = preg_replace('/[\s]+/', ' ', $this->description);
  
        $this->description = escape($this->description);

        $this->posted_by = array_column($this->session->userdata['admin_record'], 'id')[0];

        date_default_timezone_set("Asia/Karachi");
        
        $date = date("Y-m-d H:i:s");
        
        $this->uploaded_date = $date;

        $this->updated_date = $date;
            
        $data = array(

            'description' => $this->description,

            'posted_by' => $this->posted_by,

            'updated_by' => $this->posted_by,

            'uploaded_date' => $this->uploaded_date,

            'updated_date' => $this->updated_date,
        );

        if($this->db->insert('gallery_description', $data))
        {
            return TRUE;
        } 
    }
    public function update_gallery_description($id)
    {        
        $this->description = strtolower(trim($this->db->escape($this->input->post('desc')), "' "));
        
        /**
         * [Description triming]
         */

        $this->description = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(
            array('\r\n', '\n\r', '\r', '\n'), '<br>', $this->description)))));

        $this->description = preg_replace('/\[removed\]/', '', $this->description);
        $this->description = preg_replace('/(<br>)+/', '<br>', $this->description);
        $this->description = preg_replace('/[\t]+/', '    ', $this->description);
        $this->description = preg_replace('/[\s]+/', ' ', $this->description);
  
        $this->description = escape($this->description);

        $this->updated_by = array_column($this->session->userdata['admin_record'], 'id')[0];

        date_default_timezone_set("Asia/Karachi");
        
        $date = date("Y-m-d H:i:s");
        
        $this->updated_date = $date;
            
        $data = array(

            'description' => $this->description,

            'updated_by' => $this->updated_by,

            'updated_date' => $this->updated_date,
        );

        $this->db->where('id', $id);

        if($this->db->update('gallery_description', $data))
        {
            return TRUE;
        } 
    }

    /**
     * [set_item_feature Fetchs a post record from the database by post id]
     * @param  [type]  $id   [post id whom record is to be fetched]
     * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
     * all attibutes areretrieved for editing purpose. If it is FALSE than 
     * attibutes are retrieved for display/view purpose]
     * @return [array] [post record is returned]
     */
    public function set_item_feature($item_id, $status)
    {
        /**
         * To update status value of a item 
        */
        
        $this->status = $status == 0 ? 1 : 0;

        $data = array(

            'status' => $this->status

        );

        $this->db->where('id', $item_id);

        if ($this->db->update('gallery', $data))
        {
            return TRUE;
        }
    }

    /**
     * [set_item_feature Fetchs a post record from the database by post id]
     * @param  [type]  $id   [post id whom record is to be fetched]
     * @param  [boolean] $edit [Default: FALSE. If it is set to TRUE then 
     * all attibutes areretrieved for editing purpose. If it is FALSE than 
     * attibutes are retrieved for display/view purpose]
     * @return [array] [post record is returned]
     */
    public function set_description_feature($item_id, $status)
    {
        /**
         * To update featured to 0 of all the descriptions
         */

        $data = array(

            'status' => 0
        );

        $this->db->update('gallery_description', $data);

        /**
         * To update status value of a item 
        */
        
        $this->status = $status == 0 ? 1 : 0;

        $data = array(

            'status' => $this->status

        );

        $this->db->where('id', $item_id);

        if ($this->db->update('gallery_description', $data))
        {
            return TRUE;
        }
    }

    public function get_gallerys()
    {
        $query = $this->db->get('gallery');
        $result = $query->result_array();
        return $result;
    }

    public function delete_picture_by_id($id)
    {
        if ($this->db->delete('gallery', array('id' => $id))) 
        {
            return TRUE;
        }
    }

    public function delete_description_by_id($id)
    {
        if ($this->db->delete('gallery_description', array('id' => $id))) 
        {
            return TRUE;
        }
    }

    public function get_gallery_details($gallery_id)
    {
        $this->db->select('gallery_details.id, gallery_details.gallery_id, gallery_details.designation_id, 
                        gallery_details.halqa_id, user_designation.name AS designation, halqa.name AS halqa');
        $this->db->from('gallery_details');
        $this->db->where('gallery_details.gallery_id', $gallery_id);
        $this->db->join('halqa', 'halqa.id = gallery_details.halqa_id', 'inner');
        $this->db->join('user_designation', 'user_designation.id = gallery_details.designation_id', 'inner');
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
    
    public function get_gallerys_dropdown()
    {
        $this->db->select('`gallery`.`id`, `gallery`.`name`');
        $this->db->order_by('`gallery`.`name`');
        $query = $this->db->get('`gallery`');
        $result = $query->result_array();
        return $result;
    }

    public function get_picture_by_id($id, $edit = FALSE)
    {
        if($edit)
        {
            $this->db->select('`gallery`.`id`, `gallery`.`title`, `gallery`.`image, `gallery`.`thumbnail, `gallery`.`profile_image`');
        }
        else
        {
            $this->db->select('`gallery`.`id`, `gallery`.`title`, `gallery`.`thumbnail`, `gallery`.`uploaded_date`,
                                `gallery`.`updated_date`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, 
                                " ", `user`.`last_name`) AS `full_name`');
        
            $this->db->join('user', 'user.id = gallery.posted_by', 'left');
        }

        $this->db->from('gallery');
    
        $this->db->where(array('gallery.id' => $id)); 
        
        $q = $this->db->get(); 

        $result = $q->result_array();
    
        return $result[0];
    }

    public function get_gallery_description_by_id($id, $edit = FALSE)
    {
        if($edit)
        {
            $this->db->select('`gallery_description`.`id`, `gallery_description`.`description`');
        }
        else
        {
            $this->db->select('`gallery_description`.`id`, `gallery_description`.`description`, `gallery_description`.`posted_by`, 
                `gallery_description`.`uploaded_date`, `gallery_description`.`updated_date`, CONCAT(`user`.`first_name`, " ", 
                    `user`.`middle_name`, " ", `user`.`last_name`) AS `full_name`');
        
            $this->db->join('user', 'user.id = gallery_description.posted_by', 'left');
        }

        $this->db->from('gallery_description');
    
        $this->db->where(array('gallery_description.id' => $id)); 
        
        $q = $this->db->get(); 

        $result = $q->result_array();

        return $result[0]; 
    }

    public function get_attachments($attachments, $attach_gallery_details = TRUE)
    {
        for ($i = 0, $count = count($attachments); $i < $count; $i++) 
        { 
            // TRUE is passed 
            $attachments[$i]['political_party_id'] = $this->political_party_model->get_political_party_by_id($attachments[$i]['political_party_id'], FALSE, TRUE); 
        }

        if($attach_gallery_details)
        {
            //$result['specialization'] = $this->get_doctor_specialization($id);
            for ($i=0, $count = count($attachments); $i < $count; $i++) 
            { 
                if($attachments[$i])
                {
                    $attachments[$i]['gallery_details'] = $this->get_gallery_details($attachments[$i]['id']);
                }
            }
        }
        return $attachments;
    }

    public function record_count() 
    {
        return $this->db->count_all("gallery");
    }

    public function descriptions_count() 
    {
        return $this->db->count_all("gallery_description");
    }

    public function fetch_pictures($product_id, $limit, $start)
    {
        $this->db->select('`gallery`.`id`, `gallery`.`title`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) 
                            AS `full_name`, `gallery`.`thumbnail`, DATE(`gallery`.`uploaded_date`) AS uploaded_date, `gallery`.`status`');
        
        $this->db->join('user', 'user.id = gallery.posted_by', 'left');
        
        $this->db->limit($limit, $start);

        $this->db->from('gallery');

        $this->db->where('`gallery`.`product_id`', $product_id);

        $this->db->order_by('`uploaded_date`');

        $query = $this->db->get();

        echo $this->db->last_query();die;

        if ($query->num_rows() > 0) 
        {
            $result = $query->result_array();
            return $result;
        }

        return FALSE;
    }  

    public function fetch_descriptions($limit, $start) 
    {
        $this->db->select('`gallery_description`.`id`, `gallery_description`.`description`, CONCAT(`user`.`first_name`, " ", `user`.`middle_name`, " ", `user`.`last_name`) 
                            AS `full_name`,`gallery_description`.`uploaded_date`, `gallery_description`.`status`');
        
        $this->db->join('user', 'user.id = gallery_description.posted_by', 'left');
        
        $this->db->limit($limit, $start);
      
        $this->db->order_by('`uploaded_date`');

        $query = $this->db->get('gallery_description');

        if ($query->num_rows() > 0) 
        {
            $result = $query->result_array();
            return $result;
        }

        return FALSE;
    }    
}