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
        $this->unset_product_search_filter();
		if($this->input->is_ajax_request()) {
			$this->search_product_lookup();
		}

		$this->layouts->set_title('Product');

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = PRODUCTS_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$config["total_rows"] = $this->product_model->record_count();

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

		$data["products"] = $this->fetch_products($config["per_page"], $current_page);

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
    		'trim|required|is_unique[product.name]|min_length[5]|max_length[255]|callback__product_name',
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
				$this->session->set_flashdata('success_message', 'Product <strong>' . ucwords($this->input->post('name')) . '</strong> has been successfully added!');
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

        $data['product'] = $record = $this->product_model->get_product_desc_by_prod_id($id);

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
				$this->session->set_flashdata('success_message', '<strong>' . ucwords($record['name']) . "'s</strong>" . ' Description has been updated successfully updated!');
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
				'trim|required|callback_edit_unique[product.name.' . $id . ']|min_length[5]|max_length[255]|callback__product_name',
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

        $data['product'] = $product = $this->get_product_by_id_lookup($id, FALSE, FALSE, FALSE, TRUE);
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
				$this->layouts->view('templates/admin/edit_product', $data);
			}
	    }
	    else // Validation Passed
	    {
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
				$this->delete_picture(PRODUCT_IMAGE_PATH, $product);

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
                $image_name = $product['image'];
                $profile_image_name = $product['profile_image'];
                $image_thumb_name = $product['thumbnail'];
			}

			if($this->product_model->update_product($id, $image_name, $image_thumb_name, $profile_image_name))
			{
				$this->session->set_flashdata('success_message', 'Product <strong>' . ucfirst($this->input->post('name')) .
											' </strong> has been successfully updated!');
		    	echo json_encode(array('success' => 'Product Updated'));
	    	}
	    }
	}

	public function get_modal($id)
	{
		$action = $this->input->post('action');
		if($action == 'details')
		{
            $product_attributes = $this->product_attribute_model->get_product_attributes_dropdown();

            $data['product_attributes'] = array_unique(array_column($product_attributes, 'name'));

			$data['product'] = $this->get_product_by_id_lookup($id, FALSE, FALSE, TRUE);

			$this->load->view('templates/admin/detail_modal', $data);
		}
		else
		{
			$data['product'] = $this->get_product_by_id_lookup($id, FALSE, TRUE);
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
     * [edit_unique It's a callback function that is called in edit_user_lookup
     * validation it checks if same attribute data exists other than the current
     * current record than returns FALSE. If does not exists it returns TRUE]
     * @param  [string] $value  [User entered value e.g. in case of email validation
     * sheryarahmed007@gmail.com]
     * @param  [string] $params [table.attribute.id e.g. user.email.3]
     * @return [type]  [if same data exists other than current record then, returns
     * FALSE. If it doesn't exists other than current record then, returns TRUE.]
     */
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

	public function product_lookup()
	{
		$political_parties = $this->product_model->get_political_parties();
		return $political_parties;
	}

	public function get_product_by_id_lookup($id, $return_data_with_joins = FALSE, $only_category_join=FALSE, $only_product_attributes=FALSE, $return_data_without_joins=FALSE)
	{
//        $this->unset_user_search_filter();
        $product = $this->product_model->get_product_by_id($id, $return_data_with_joins, $only_category_join, $only_product_attributes, $return_data_without_joins);
        return $product;
	}

	public function delete_product_by_id_lookup($id)
	{
		$record = $this->get_product_by_id_lookup($id); // TRUE to get all items for edit purpose not full_name etc

		$this->delete_picture(PRODUCT_IMAGE_PATH, $record);

		if ($this->product_model->delete_product($id))
		{
            $this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/product/');
		}
	}

	public function product_name_autocomplete()
	{
		$product_name = trim(strtolower($this->input->post('product_name')));

        $product_names = $this->product_model->product_full_name_autocomplete($product_name);

        $full_names = array();

        if(!empty($product_names)) {
            foreach($product_names as $product_name) {
                $full_names[] = ucwords($product_name['name']);
            }
        } else {
            $full_names[] = 'No Results Found';
        }

        echo json_encode($full_names);
	}

	public function search_product_lookup() {

        $this->get_product_search_filter();

        $this->set_product_search_filter();

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = PRODUCTS_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$products = $this->fetch_products($config["per_page"], $current_page);

		$config["total_rows"] = $this->products_count();

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

		$data["products"] = $products;

		$this->load->view('templates/admin/search_products', $data);
	}

	public function fetch_products($per_page, $current_page)
	{
		$product_category = isset($_POST['product_category']) && is_numeric($_POST['product_category']);
		$product_name = isset($_POST['product_name']) && !empty($_POST['product_name']);
		$product_category_ac_id = $this->input->post('product_category');
		$product_name_ac = strtolower($this->input->post('product_name'));

		$product_name_ac = str_replace('-', ' - ', $product_name_ac);

		if($product_category) {
            $products = $this->product_model->fetch_products(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'category' => $product_category_ac_id,
                    'has_category_join' => TRUE
                )
            );
		}

		if($product_name) {
            $products = $this->product_model->fetch_products(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'name' => $product_name_ac,
                    'has_category_join' => TRUE
                )
            );
		}

		if($product_category && $product_name) {

            $products = $this->product_model->fetch_products(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'category' => $product_category_ac_id,
                    'name' => $product_name_ac,
                    'has_category_join' => TRUE
                )
            );

		}

        if(!$this->input->is_ajax_request()) {
            $products = $this->product_model->fetch_products(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'has_category_join' => TRUE
                )
            );
        }

        if(isset($products)) return $products;
	}

    /**
     * @return mixed Users count
     *
     */
    public function products_count()
    {
        $category_cond = isset($_POST['product_category']) && is_numeric($_POST['product_category']);
        $category = $this->input->post('product_category');
        $product_name = strtolower($this->input->post('product_name'));
        if($category_cond) {
            $products = $this->product_model->record_count(array(
                    'category' => $category
                )
            );
        }

        if(!empty($_POST['product_name'])) {
            $products = $this->product_model->record_count(array(
                    'name' => $product_name
                )
            );
        }

        if($category_cond && !empty($_POST['product_name'])) {
            $products = $this->product_model->record_count(array(
                    'category' => $category,
                    'name' => $product_name
                )
            );
        }

        if(!$this->input->is_ajax_request()) {
            $products = $this->product_model->record_count();
        }

        if(isset($products)) {
            return $products;
        }
    }

    public function set_product_search_filter() {
        $product_category = trim($this->input->post('product_category'));
        $product_name = trim($this->input->post('product_name'));
        if(!empty($product_category) || !empty($product_name)) {
            $this->session->set_userdata(array(
                    'product_category' 	=> $product_category,
                    'product_name' => $product_name
                )
            );
        }
    }

    public function get_product_search_filter() {

        $session_product_name = $this->session->userdata('product_name');
        $product_name = $this->input->post('product_name');
        $session_product_category = $this->session->userdata('product_category');
        $product_category = $this->input->post('product_category');

        if(empty($product_name)) {
            $_POST['product_name'] = $session_product_name;
        }

        if(empty($product_category)) {
            $_POST['product_category'] = $session_product_category;
        }
    }

    public function unset_product_search_filter() {
        $this->session->unset_userdata(array(
                'product_name', 'product_category'
            )
        );
    }
}