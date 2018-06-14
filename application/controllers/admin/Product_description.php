<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_description extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/product_description_model');
		$this->load->model('admin/product_model');
        $this->load->model('admin/configuration_model');
	}
	public function index()
	{
		$this->layouts->set_title('Product Description'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->product_description_model->record_count();
		$config['per_page'] = $this->configuration_model->get_items_per_page();
        $config["uri_segment"] = 4;
		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        
        // By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
        $config['cur_tag_open'] = "<li><span><b>";
        $config['cur_tag_close'] = "</b></span></li>";

		$this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $data["product_descriptions"] = $this->product_description_model->fetch_product_descriptions($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/product_descriptions', $data);
	}
	
	public function add_product_description_lookup()
	{
		$this->layouts->set_title('Add Product Description');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		// echo $this->input->post('submitted_designation');

		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value)
			{
				if($key == 'add_product_description_desc')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// Product Short Description

	    $this->form_validation->set_rules(

    		'short_desc', 'Product Short Description',
    		'trim|required|min_length[5]|max_length[255]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Product Long Description

	    $this->form_validation->set_rules(

    		'long_desc', 'Product Long Description',
    		'trim|required|min_length[5]|max_length[255]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
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
					if($key == 'add_product_description_desc')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$this->layouts->view('templates/admin/add_product_description');
			}
	    }
	    else // Validation Passed
	    {
			if($this->product_description_model->insert_product_description()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Product description has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'Product description inserted'));
	    	}
	    }
	}

	public function edit_product_description_lookup($id)
	{		
		$this->layouts->set_title('Edit Product Description'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_product')
				{
					continue;
				}
				if($key == 'submitted_designation')
				{
					$_POST['designation'] = $this->input->post('submitted_designation');
				}
				if($key == 'submitted_halqa')
				{
					$_POST['halqa'] = $this->input->post('submitted_halqa');
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		// Name of Political Party

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[5]|max_length[200]|callback__product_description_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_product_description_name', 'Only alphabets, spaces and "-()" are allowed');	    
		
		// Designation

	    $this->form_validation->set_rules(

    		'designation', 'Designation', 
    		'required',
        	array(
            	'required'      => '%s is required'
    		)
	    );	
		
		// Halqa

	    $this->form_validation->set_rules(

    		'halqa', 'Halqa', 
    		'required',
        	array(
            	'required'      => '%s is required'
    		)
	    );	

		// Political Party

	    $this->form_validation->set_rules(

    		'political_party', 'Political Party', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
    		)
	    );

		// Introduction

	    $this->form_validation->set_rules(

    		'introduction', 'Introduction', 
    		'trim|required|min_length[5]|max_length[5000]', // |callback__details
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Election History

	    $this->form_validation->set_rules(

    		'election_history', 'Election History', 
    		'trim|required|min_length[5]|max_length[5000]',//
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );
		
		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
	    );	


		// $data['designations'] = $this->designation_model->get_user_designations();
		$data['designations'] = $this->designation_model->get_user_designations();
		$data['political_parties'] = $this->political_party_model->get_political_parties_dropdown();
		$data['halqas'] = $this->halqa_model->get_halqas();
		$data['Product Description'] = $this->get_product_description_by_id_lookup($id);

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
					if($key == 'edit_product')
					{
						continue;
					}
					if($key == 'submitted_designation')
					{
						$_POST['designation'] = $this->input->post('submitted_designation');
					}
					if($key == 'submitted_halqa')
					{
						$_POST['halqa'] = $this->input->post('submitted_halqa');
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_product', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/product_description_model', 'get_product_description_by_id', PRODUCT_IMAGE_PATH, 'image');

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, PRODUCT_IMAGE_PATH, 'image');

	    		$image_thumb = PRODUCT_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = PRODUCT_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
	    		$image_dest = PRODUCT_IMAGE_PATH . '/IMAGE_' . $image;
	    		
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $profile_image, 100);
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $image_dest, 800);
	    		
	    		$image_thumb_name = 'THUMB_' . $image;
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

		    	unlink(PRODUCT_IMAGE_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image_name = $data['Product Description']['image'];
	    		$image_thumb_name = $data['Product Description']['thumbnail'];
	    		$profile_image_name = $data['Product Description']['profile_image'];
	    	}
	    	
			if($this->product_description_model->update_product($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Product Description ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Product Description Updated'));
	    	}
	    }		
	}
	public function get_modal($id)
	{	
		$data['product'] = $this->get_product_description_by_id_lookup($id);
		
		$this->load->view('templates/admin/product_description_modal', $data);
	}

	public function add_new_product_description_attribute_section()
	{
		$data['categories'] = $this->category_model->get_categories_dropdown();
		$data['product_description_attributes'] = $this->product_description_attribute_model->get_product_description_attributes_dropdown();
		$this->load->view('templates/admin/product_description_attribute_section', $data);
	}

	public function get_product_description_details_options()
	{
		$data['product_description_attribute_details'] = $this->product_description_attribute_detail_model->get_product_description_attr_detail_by_product_description_attr_id($this->input->post('product_description_attribute'));
//		$data['product_description_attribute_name'] = $this->product_description_attribute_model->get_product_description_attr_name_by_id($this->input->post('product_description_attribute'));
		$this->load->view('templates/admin/product_description_attribute_detail_dropdown', $data);
	}

	public function _product_description_name($product)
	{
		if (preg_match("/^[a-z0-9 \-()]+$/i", $product))
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * @param $money
	 * @return bool
	 * Checks If $money contains only numbers. First number of $money is greater than 0. It contains optional dot and
	 * dot must have 1 or 2 numbers after it then it returns TRUE
	 * Else return false
	 * Valid Values: E.g. 12.00, 99.99, 100
	 * Invalid Values 01, 0.11, 100.123
	 */
	public function _is_money($money)
	{
		if (preg_match("/^[1-9][0-9]*(\.{1}[0-9]{1,2})?$/", $money))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function _details($details)
	{
		if (preg_match("/^[a-z #\-\.0-9()\/:,]+$/i", $details)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}
	public function edit_unique($value, $params)
	{
	    $this->form_validation->set_message('edit_unique',
	        'The %s is already being used by another account.');
	    list($table, $field, $id) = explode(".", $params, 3);

	    $query = $this->db->select($field)->from($table)
	        ->where($field, $value)->where('id !=', $id)->limit(1)->get();

	    if ($query->row()) 
	    {
	        return FALSE;
	    } 
	    else 
	    {
	        return TRUE;
	    }
	}
	public function product_description_lookup()
	{
		$political_parties = $this->product_description_model->get_political_parties();
		return $political_parties;
	}
	public function get_product_description_by_id_lookup($id, $edit = FALSE)
	{
		$product = $this->product_description_model->get_product_description_by_id($id, $edit);
		return $product;
	}
	public function delete_product_description_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/product_description_model', 'get_product_description_by_id', PRODUCT_IMAGE_PATH, 'image');

		if ($this->product_description_model->delete_product($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/product/');
		}
	}
}