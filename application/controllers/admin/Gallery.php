<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends PD_Photo {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://localhost/hlcc/
	 *	- or -
	 *		http://localhost/hlcc/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://localhost/hlcc/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 */	

	public function __construct()
	{
		parent::__construct();

		$this->load->model('admin/gallery_model');
	
		$this->load->library('layouts');	
	}

	public function product_pictures($product_id)
	{
		$config = array();
//        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
//	    $config["total_rows"] = $this->gallery_model->record_count();
//		$config['per_page'] = 5;
//        $config["uri_segment"] = 4;
//		$config["num_links"] = 1;
//		$config['full_tag_open'] = '<ul class="pagination">';
//		$config['full_tag_close'] = '</ul>';
//		$config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
//        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        
        // By clicking on performing NEXT pagination.
//		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
//		$config['prev_link'] = 'Previous';
//        $config['cur_tag_open'] = "<li><span><b>";
//        $config['cur_tag_close'] = "</b></span></li>";

//		$this->pagination->initialize($config);

//        $page = ($this->uri->segment(4)) ? $this->uri->segment(5) : 0;
		$this->layouts->set_title('Gallery');

//        $data["pictures"] = $this->gallery_model->fetch_pictures($product_id, $config["per_page"], $page);
        $data["pictures"] = $this->gallery_model->fetch_pictures($product_id);

//        $data["links"] = $this->pagination->create_links();
        $data['product']['id'] = $product_id;
        $data['tabs'] = $this->load->view('templates/admin/product_nav_tabs', $data, TRUE);
		$this->layouts->view('templates/admin/gallery', $data);
	}

	public function add_gallery_pics_lookup($product_id)
	{
		$this->layouts->set_title('Add Gallery Pictures'); 
		
		if($this->input->is_ajax_request())
		{
			// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    	$image = $this->save_photo($_FILES['file']['name'], GALLERY_IMAGE_PATH, 'file');

	    	if(!empty($image))
	    	{
		    	$image_thumb = GALLERY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = GALLERY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
		    	$image_dest = GALLERY_IMAGE_PATH . '/IMAGE_' . $image;
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $image_thumb, 270);
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $profile_image, 370);
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $image_dest, 1000); 

	    		$image_thumb_name = 'THUMB_' . $image;	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

				$image_arr = array(
				    'thumbnail'=> $image_thumb_name,
				    'profile_image'=> $profile_image_name,
				    'image_name'=> $image_name,
				    'product_id'=> $product_id
				);

				$json = json_encode($image_arr);

	    		$cookie = array(
			
					'name' => $image_name,
				
					'value' => $json,
				
					'expire' => 0,
				
				);
			
				$this->input->set_cookie($cookie);

		    	unlink(GALLERY_IMAGE_PATH . DS . $image);
		    }
		}
		else
		{
			$data['product']['id'] = $product_id;
			$data['tabs'] = $this->load->view('templates/admin/product_nav_tabs', $data, TRUE);

			$this->layouts->view('templates/admin/add_gallery_pics', $data);
		}
	    	
	}	

	public function edit_gallery_pics_lookup($product_id, $id)
	{
		$this->layouts->set_title('Edit Gallery Picture'); 
			
		$gallery = $this->gallery_model->get_picture_by_id($id, TRUE);

		if($this->input->is_ajax_request())
		{	
			$data = array();

			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_picture')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			if(empty($_FILES['image']['name']))
			{
				$data['image'] = $gallery['image'];
			}

			$data['image'] = empty($_FILES['image']['name']) ? $gallery['image'] : $_FILES['image']['name'];

			$this->form_validation->set_data($data);
		}
			    	    
		// Title

	    $this->form_validation->set_rules(

	    		'title', 'Title', 
	    		'trim|required|min_length[3]|max_length[100]|callback__title',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
	    		)
	    );

		$this->form_validation->set_message('_title', 'Only alphabets, spaces and "/,.-_()" are allowed');

		// Image

	    $this->form_validation->set_rules(

    		'image', 'Image', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
    		)
	    );	
	    
		if ($this->form_validation->run() === FALSE) // Validation fails
	    {
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */

			if($this->input->is_ajax_request())
			{
				$errors = array();
				foreach ($_POST as $key => $value) 
				{
					if($key == 'edit_picture')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				$errors['image'] = !empty(form_error('image')) ? form_error('image') : '';

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['gallery'] = $this->gallery_model->get_picture_by_id($id, TRUE);
				$this->layouts->view('templates/admin/edit_gallery', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];

	    	if(!empty($image))
	    	{
	    		$this->delete_picture(GALLERY_IMAGE_PATH, $gallery);

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, GALLERY_IMAGE_PATH, 'image');

		    	$image_thumb = GALLERY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = GALLERY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
		    	$image_dest = GALLERY_IMAGE_PATH . '/IMAGE_' . $image;
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $image_thumb, 270);
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $profile_image, 370);
		    	
		    	$this->make_thumb(GALLERY_IMAGE_PATH . '/' . $image, $image_dest, 1000); 

	    		$image_thumb_name = 'THUMB_' . $image;	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

		    	unlink(GALLERY_IMAGE_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image_name = $gallery['image'];
	    		$image_thumb_name = $gallery['thumbnail'];
	    		$profile_image_name = $gallery['profile_image'];
	    	}
	    	
			if($this->gallery_model->update_picture($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Picture ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Picture Updated'));
	    	}
	    }			
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [user id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['gallery'] = $this->gallery_model->get_picture_by_id($id);	
		
		$this->load->view('templates/admin/gallery_modal', $data);		
	}

	public function submit_images_names_lookup()
	{
		$images_arr = [];
		foreach ($_COOKIE as $cookie => $value)
		{
			if(preg_match('/^[IMAGE_Online_Store]/', $cookie))
			{
				$images_arr[$cookie] = $value;
			}
		}

		if($this->gallery_model->insert_gallery_images($images_arr))
		{
			foreach ($images_arr as $image_name => $value)
			{
				$ext = substr($image_name, -3);
				$lastpos = strripos($image_name, '_');
				$cookie_name = substr($image_name, 0, $lastpos);
				$cookie_name .= '.' . $ext;
				delete_cookie($cookie_name, 'local.ims.com', '/');
			}
		}
	}

	function _title($title) 
	{
		if (preg_match('/^[a-z \-\/0-9\(\)_.,]+$/i', $title)) 
		{
			return TRUE;
		} 
		else 
		{
			return FALSE;
		}
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [post id whom record is to be loaded in bootstrap modal]
	 */
	public function set_item_feature_lookup()
	{	
		$details = $this->input->post('details');
		$details_parts = explode('_', $details);
		$id = $details_parts[1];
		$status = $details_parts[2];

		if($this->gallery_model->set_item_feature($id, $status))
		{
			$success = ($status == 0) ? 'featured' : 'un-featured';
			$this->session->set_flashdata('success_message', 'Picture has been successfully ' . $success);
			echo json_encode(array('success' => 'Successfully Featured'));
		}
	}

	public function delete_picture_by_id_lookup($id)
	{
		$record = $this->gallery_model->get_picture_by_id($id, TRUE);
		$this->delete_picture(GALLERY_IMAGE_PATH, $record);

		$this->gallery_model->delete_picture_by_id($id);
		$this->session->set_flashdata('delete_message', 'Picture ' . ' ' . ucfirst($this->input->post('title')) .
							' has been successfully deleted!');
		redirect('admin/gallery/product_pictures/'.$record['product_id']);
	}	
}