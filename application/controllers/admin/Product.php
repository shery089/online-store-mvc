<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/product_model');
		$this->load->model('admin/category_model');
		$this->load->model('admin/product_attribute_model');
		$this->load->model('admin/product_attribute_detail_model');
	}
	public function index()
	{
		if($this->input->is_ajax_request()) {
			$this->search_product_lookup();
		}

		$this->layouts->set_title('Product');

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = 6;
		$config["uri_segment"] = 4;

		$products = $this->fetch_products_by_elasticsearch($config["per_page"], $current_page);

		$config["total_rows"] = !empty($products['total']) ? $products['total'] : 0;

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config['cur_tag_open'] = "<li><span><b>";
		$config['cur_tag_close'] = "</b></span></li>";

		$this->pagination->initialize($config);

		$data["products"] = !empty($products['hits']) ? $products['hits'] : array();

		$data['categories'] = $this->category_model->get_all_categories();

		$data["links"] = $this->pagination->create_links();

		if(!$this->input->is_ajax_request()) {
			$this->layouts->view('templates/admin/products', $data);
		}

	}
	public function add_product_lookup()
	{
		$this->layouts->set_title('Add Product'); 
	
		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_product')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}

		// Product Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|callback_is_unique_es[name]|min_length[5]|max_length[255]|callback__product_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_product_name', 'Only alphabets, spaces. numbers and "-()" are allowed');

		// Product Parent Category

		$this->form_validation->set_rules(

    		'category', 'Category',
    		'required',
        	array(
            	'required'      => '%s is required'
    		)
	    );

		// Product Price

/*		$this->form_validation->set_rules(

    		'price', 'Price',
    		'required|call_back__is_money',
        	array(
            	'required'      => '%s is required'
    		)
	    );

*/
		// Attributes

		if(isset($data)) {

			foreach ($data as $key => $value) {

				if (strpos($key, 'submitted_') !== false || strpos($key, 'product_attribute') !== false) {

					$name = (strpos($key, 'details_') !== false) ? 'Product Detail' : 'Product Attribute';
					$this->form_validation->set_rules(

						"$key", "$name",
						'trim|required',
						array(
								'required' => '%s is required'
						)
					);
				}
			}
		}

		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
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
					if($key == 'add_product')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['categories'] = $this->category_model->get_all_categories();
				$data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
				$this->layouts->view('templates/admin/add_product', $data);
			}
	    }
	    else // Validation Passed
	    {	
	    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    	$image = $this->save_photo($_FILES['image']['name'], PRODUCT_IMAGE_PATH, 'image');

	    	if(!empty($image))
	    	{
		    	$image_thumb = PRODUCT_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = PRODUCT_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
	    		$image_dest = PRODUCT_IMAGE_PATH . '/IMAGE_' . $image;
		    	
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $profile_image, 320);
		    	$this->make_thumb(PRODUCT_IMAGE_PATH . '/' . $image, $image_dest, 1024);

	    		$image_thumb_name = 'THUMB_' . $image;	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;


		    	unlink(PRODUCT_IMAGE_PATH . DS . $image);

		    }
	    	else
	    	{
	    		$image_name = '';
	    		$image_thumb_name = '';
	    		$profile_image_name = '';
	    	}

			if($this->product_model->insert_product($image_name, $image_thumb_name, $profile_image_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Product ' . ucwords($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'Product inserted'));
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
				if($key == 'edit_product_desc')
				{
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// Product Short Description

		$this->form_validation->set_rules(

			'short_description', 'Short Description',
			'trim|required|min_length[10]|max_length[255]',
			array(
				'required'      => '%s is required',
				'min_length'    => '%s should be at least %s chars',
				'max_length'    => '%s should be at most %s chars'
			)
		);

		// Product Long Description

		$this->form_validation->set_rules(

			'long_description', 'Long Description',
			'trim|required|min_length[10]|max_length[3000]',
			array(
				'required'      => '%s is required',
				'min_length'    => '%s should be at least %s chars',
				'max_length'    => '%s should be at most %s chars'
			)
		);

		$source = '"_source": {

				"includes": [ "id", "name", "short_description", "long_description"]
				},';

		$record = $this->get_product_by_id_lookup($id, 'edit_product', $source);

		$data['record'] = $record;

		$data['tabs'] = $this->load->view('templates/admin/product_nav_tabs', $data, TRUE);

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

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_product_desc', $data);
			}
	    }	    
	    else // Validation Passed
	    {
			if($this->product_model->update_product_description($id))
			{
				$this->session->set_flashdata('success_message', custom_echo($record[0], 'name') . "'s" . ' Product Description has been updated successfully updated!');
		    	echo json_encode(array('success' => 'Product Updated'));
	    	}
	    }		
	}

	public function edit_product_lookup($id)
	{
		$this->layouts->set_title('Edit Product');

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

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// Product Name

		$this->form_validation->set_rules(

				'name', 'Name',
				'trim|required|callback_edit_unique_es[name.' . $id . ']|min_length[5]|max_length[255]|callback__product_name',
				array(
						'required'      => '%s is required',
						'min_length'    => '%s should be at least %s chars',
						'max_length'    => '%s should be at most %s chars'
				)
		);

		$this->form_validation->set_message('_product_name', 'Only alphabets, spaces. numbers and "-()" are allowed');

		// Product Parent Category

		$this->form_validation->set_rules(

				'category', 'Category',
				'required',
				array(
						'required'      => '%s is required'
				)
		);

		// Attributes

		if(isset($data)) {

			foreach ($data as $key => $value) {

				if (strpos($key, 'submitted_') !== false || strpos($key, 'product_attribute') !== false) {

					$name = (strpos($key, 'details_') !== false) ? 'Product Detail' : 'Product Attribute';
					$this->form_validation->set_rules(

							"$key", "$name",
							'trim|required',
							array(
									'required' => '%s is required'
							)
					);
				}
			}
		}

		// Image

		$this->form_validation->set_rules(

				'image', 'Image',
				'trim'
		);

		$data['categories'] = $this->category_model->get_all_categories();
		$product_attributes = $this->product_attribute_model->get_product_attributes_dropdown();
		$data['product_attributes'] = $product_attributes;

		$product_attributes = array_unique(array_column($product_attributes, 'name'));

		$product_attributes_str = '"' . implode('", "', $product_attributes) . '"';

		$source = '"_source": {

				"includes": [ "id", ' . $product_attributes_str . ', "product_attribute_detail_id", "product_attribute_detail_value",
				"name", "category_id", "image", "thumbnail", "profile_image"]
				},';

		$record = $this->get_product_by_id_lookup($id, 'edit_product', $source);

		$data['record'] = $record;

		$data['tabs'] = $this->load->view('templates/admin/product_nav_tabs', $data, TRUE);


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
				$this->delete_picture($id, 'product', USER_IMAGE_UPLOAD_PATH, $record);

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
				$record = current($record);
				$image_name = custom_echo($record, 'image', 'no_case_change');
				$profile_image_name = custom_echo($record, 'profile_image', 'no_case_change');
				$image_thumb_name = custom_echo($record, 'thumbnail', 'no_case_change');
			}

			if($this->product_model->update_product($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));

				$this->session->set_flashdata('success_message', 'Product ' . ' ' . ucfirst($this->input->post('name')) .
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Product Updated'));
	    	}
	    }
	}

	public function get_modal($id)
	{
		$action = $this->input->post('action');
		$action_parts = explode('_', $action);

		$product_attributes = $this->product_attribute_model->get_product_attributes_dropdown();

		$product_attributes = array_unique(array_column($product_attributes, 'name'));

		$product_attributes_str = '"' . implode('", "', $product_attributes) . '"';

		if($action_parts[0] == 'details')
		{
			$source = '"_source": {
			"includes": [ ' .  $product_attributes_str .  ' ]
			},';

			$data['record'] = $this->get_product_by_id_lookup($id, 'get_products', $source);

			$data['product_attributes'] = $product_attributes;

			$this->load->view('templates/admin/detail_modal', $data);
		}

		else
		{
			$source = '"_source": {
			"includes": [ "id", "name", "category", "short_description", "long_description"]
			},';

			$data['record'] = $this->get_product_by_id_lookup($id, 'get_products', $source);

			$this->load->view('templates/admin/product_modal', $data);
		}
	}

	public function add_new_product_attribute_section()
	{
		$data['categories'] = $this->category_model->get_categories_dropdown();
		$data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
		$this->load->view('templates/admin/product_attribute_section', $data);
	}

	public function get_product_details_options()
	{
		$data['product_attribute_details'] = $this->product_attribute_detail_model->get_product_attr_detail_by_product_attr_id($this->input->post('product_attribute'));

//		print_r($data['product_attribute_details']);die;

		$this->load->view('templates/admin/product_attribute_detail_dropdown', $data);
	}

	public function _product_name($product)
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

	/**
	 * [is_unique_es It's a callback function that is called in edit_product_lookup
	 * validation it checks if same attribute data exists other than the current
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [User entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. product.email.3]
	 * @return [type]  [if same data exists other than current record then, returns
	 * FALSE. If it doesn't exists other than current record then, returns TRUE.]
	 */
	public function is_unique_es($value, $field)
	{
		$this->form_validation->set_message('is_unique_es',
				'The %s is not available');

		$query = '{
		  "query": {
			"bool" : {
			  "must" : {
				"term" : { "' . $field . '" : "' . strtolower($value) . '" }
			  }
			}
		  }
		}';

		$product = $this->elasticsearch->advancedquery('products', 'product' , $query);

		return !empty($product['hits']['hits']) ? FALSE : TRUE;
	}

	/**
	 * [edit_unique_es It's a callback function that is called in edit_product_lookup
	 * validation it checks if same attribute data exists other than the current
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [User entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. product.email.3]
	 * @return [type]  [if same data exists other than current record then, returns
	 * FALSE. If it doesn't exists other than current record then, returns TRUE.]
	 */
	public function edit_unique_es($value, $params)
	{
		list($field, $id) = explode(".", $params, 2);

		$this->form_validation->set_message('edit_unique_es',
				'The %s is not available');

		$query = '{
		  "query": {
			"bool" : {
			  "must" : {
				"term" : { "' . $field . '" : "' . strtolower($value) . '" }
			  },
			  "must_not" : {
				"term" : { "id" : ' . $id . '}
			  }
			}
		  }
		}';

		$product = $this->elasticsearch->advancedquery('products', 'product', $query);

		return !empty($product['hits']['hits']) ? FALSE : TRUE;
	}

	public function product_lookup()
	{
		$political_parties = $this->product_model->get_political_parties();
		return $political_parties;
	}

	public function get_product_by_id_lookup($id, $method = 'get_product', $source = NULL)
	{
/*		if($method == 'edit_product' && is_null($source)) {
			$source = '"_source": {
				"includes": [ "id", "name", "category_id", "image", "", "email", "mobile_number", "image", "profile_image", "thumbnail", "role_id"]
				},';
		}
		if($method != 'edit_product' && is_null($source)){
			$source = '"_source": {
				"includes": [ "id", "full_name", "product_name", "email", "mobile_number", "profile_image", "role", "joined_date", "updated_date"]
				},';
		}*/

		$query = '
		{ ' .
				$source

				. '"query": {
				"query_string":{
					"query": '. $id .',
					"fields": ["id"]
				}
			}
		}';

		$product = $this->elasticsearch->advancedquery('products', 'product', $query);

		$product = $product['hits']['hits'];

		return $product;
	}

	public function delete_product_by_id_lookup($id)
	{
		$source = '"_source": {
			"includes": ["profile_image", "thumbnail", "image"]
			},';

		$record = $this->get_product_by_id_lookup($id, 'edit_product', $source); // TRUE to get all items for edit purpose not full_name etc

		$this->delete_picture(PRODUCT_IMAGE_PATH, $record);

		if ($this->product_model->delete_product($id)) 
		{
			$this->elasticsearch->delete('products', 'product', $id);
			$this->elasticsearch->refresh_index_type("product", $id);
			$this->elasticsearch->refresh_index_type('products', 'product', $id);
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/product/');
		}
	}

	public function product_name_autocomplete()
	{
		$product_name = trim(strtolower($this->input->post('product_name')));
		$product_name = str_replace('-', ' - ', $product_name);

		$source = '"_source": {
				"includes": [ "name"]
				},';

		$query = '
		{ ' .

				$source

				. '
			"from" : 0, "size" : 6,
				"query": {
					"query_string":{
						"query": '. '"*'.$product_name.'*"' .',
						"fields": ["name"],
						"default_operator": "AND"
					}
				}
			}';

		$products = $this->elasticsearch->advancedquery('products', 'product', $query);

		$products = $products['hits']['hits'];

		$product_names = [];

		if(!empty($products)) {
			foreach($products as $product) {
				$product_names[] = custom_echo($product, 'name');
			}
		} else {
			$product_names[] = 'No Results Found';
		}

		echo json_encode($product_names);
	}

	public function indice_product_elastic_search_lookup() {
		$time_taken_sec = $this->insert_product_bulk_elasticsearch_lookup(FALSE);
		$this->elasticsearch->refresh_index_type('products');
		$this->session->set_flashdata('success_message', 'Product Indexing is successfully completed');
		sleep($time_taken_sec/1000); // millseconds
			redirect('admin/product');
	}

	/**
	 * [insert_product_bulk_elasticsearch_lookup This function will insert bulk of product data
	 * from database to elasticsearch
	 */
	public function insert_product_bulk_elasticsearch_lookup($return_response = TRUE)
	{
		$product_attributes = $this->product_attribute_model->get_product_attributes_only_key('name');

		$product_attributes = array_column($product_attributes, 'name');

		$result = $this->product_model->insert_product_bulk_elasticsearch();

		$result_keys = array_keys($result[0][1]);

		$to_prepend_mapping_keys = array();

		foreach($result_keys as $result_key) {
			if(in_array($result_key, $product_attributes)) {
				$to_prepend_mapping_keys[] = $result_key;
			}
		}

		$result = json_encode($result);

		$result = str_replace(",{", "\n\r{", $result);

		$result = str_replace(array('[[', ']]'), '', $result);

		$result = str_replace(',[{', "\n\r{", $result);

		$result = str_replace(array(',}]', '}]'), "}", $result);

		file_put_contents(JSON_FILE_PATH . '/results.json', $result);

		$json_data = file_get_contents(JSON_FILE_PATH . "/results.json");
		$json_data = str_replace(array('[[', ']]'), '', $json_data);
		$json_data .= "\n\r";

		unlink(JSON_FILE_PATH . '/results.json');

		$this->elasticsearch->delete_index("products");

		$this->create_product_mapping($to_prepend_mapping_keys);

		$this->elasticsearch->create("products");

		$response = $this->elasticsearch->insert_bulk("product", 'POST', $json_data);

		if($return_response) {
			echo json_encode($response);
		}
		else {
			return $response['took'];
		}
	}

	public function create_product_mapping($to_prepend_mapping_keys) {

		$this->load->library('product_mapping');
		$mapping = $this->product_mapping->create_product_mapping($to_prepend_mapping_keys);
		$this->elasticsearch->create('products', $mapping);
	}

	public function search_product_lookup() {

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = 4;
		$config["uri_segment"] = 4;

		$products = $this->fetch_products_by_elasticsearch($config["per_page"], $current_page);

		$config["total_rows"] = !empty($products['total']) ? $products['total'] : 0;

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config["cur_tag_open"] = "<li class='active'><a href='#'>";
		$config["cur_tag_close"] = "</a></li>";

		$this->pagination->initialize($config);

		$data["links"] = $this->pagination->create_links();

		$data["products"] = !empty($products['hits']) ? $products['hits'] : array();

		$this->load->view('templates/admin/search_products', $data);
	}

	public function fetch_products_by_elasticsearch($per_page = 8, $current_page = 0)
	{
		$product_category = isset($_POST['product_category']) && is_numeric($_POST['product_category']);
		$product_name = isset($_POST['product_name']) && !empty($_POST['product_name']);
		$product_category_ac_id = $this->input->post('product_category');
		$product_name_ac = strtolower($this->input->post('product_name'));

		$product_name_ac = str_replace('-', ' - ', $product_name_ac);

		if($product_category) {
			$query_string = '"query": '. $product_category_ac_id .',
						"fields": ["category_id"]';
		}

		if($product_name) {
			$query_string = '"query": "*'. $product_name_ac . '*",
						"fields": ["name"],
						"default_operator": "AND"';
		}

		if($product_category && $product_name) {
			$query_string =	'"bool": {
				"must" : [
			  	{
				  "term": {
				  "category_id": ' . $product_category_ac_id . '
			   	}
			 },
				 {
					"query_string":{
						"query": "*'.$product_name_ac.'*"' .',
							"fields": ["name"],
							"default_operator": "AND"

					}
				 }
			 ]
		 	}';
		}


		$query = '
				{
					"from" : ' . $current_page . ', "size" : ' . $per_page;

		if($product_category && $product_name) {
			$query .= ',"query": {
				' . $query_string .  '
				}';
		}else {

			if($product_category || $product_name) {
				$query .= ',"query": {
				"query_string":{
					' . $query_string . '
					}
				}';
			}
		}


		$query .= '}';

//		echo $query;die;

		$products = $this->elasticsearch->advancedquery('products', 'product', $query);

		$products = !empty($products['hits']) ? $products['hits'] : array();

		return $products;

	}
}