<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Product Attribute class is a controller class it has all methods for basic operation
* of Product Attribute i.e. CRUD lookups, get bootstrap modals, pagination etc for product_attribute  
* Methods: index
* 		   add_product_attribute_lookup
* 		   edit_product_attribute_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_product_attribute_by_id_lookup
* 		   delete_product_attribute_by_id_lookup
*/

class Product_attribute extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/product_attribute_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] product_attributes from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/product_attributes/admin/product_attribute/index
	 *	- or -
	 *		http://localhost/product_attributes/index.php/admin/product_attribute
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/product_attribute/<method_name>
	 *	- or -
	 * /admin/product_attribute/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Product Attribute'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->product_attribute_model->record_count();
		$config['per_page'] = 5;
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

        $data["product_attributes"] = $this->product_attribute_model->fetch_product_attributes($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/product_attributes', $data);
	}

	/**
	 * [add_product_attribute_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates product_attribute data. If data is valid 
	 * then, it allows access to its insert product_attribute model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_product_attribute_lookup()
	{	
		$this->layouts->set_title('Add Product Attribute'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_product_attribute')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}
		
		// Product Attribute Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|is_unique[product_attribute.name]|min_length[2]|max_length[100]|callback__product_attribute_name',
        	array(
            	'required'      => 'Please provide a Product Attribute %s e.g. Size, Color',
            	'is_unique'      => 'Please provide some other product_attribute %s already exists',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

	    $this->form_validation->set_message('_product_attribute_name', 'Only alphabets and spaces are allowed');

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
					if($key == 'add_product_attribute')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['product_attributes'] = $this->product_attribute_model->get_all_product_attributes();
				$this->layouts->view('templates/admin/add_product_attribute', $data);
			}
	    }
	    else // Validation Passed
	    {
			if($this->product_attribute_model->insert_product_attribute()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Product Attribute ' . ucwords($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'product_attribute inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_product_attribute_lookup: Edits a product_attribute by id, Validates product_attribute data. If data is valid then, 
	 * it allows access to its edit product_attribute model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_product_attribute_lookup($id)
	{		
		$this->layouts->set_title('Edit Product Attribute'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_product_attribute')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}

		// Product Attribute Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[2]|max_length[200]|callback__product_attribute_name|callback_edit_unique[product_attribute.name.'. $id .']',
        	array(
            	'required'      => 'Please provide a Product Attribute %s e.g. Camera',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_product_attribute_name', 'Only alphabets and spaces are allowed');

		$data['record'] = $this->get_product_attribute_by_id_lookup($id);

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
					if($key == 'edit_product_attribute')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_product_attribute', $data);
			}
	    }	    
	    else // Validation Passed
	    {
			if($this->product_attribute_model->update_product_attribute($id))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Product Attribute ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Product Attribute Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [product_attribute id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['product_attribute'] = $this->get_product_attribute_by_id_lookup($id);	
		
		$this->load->view('templates/admin/product_attribute_modal', $data);		
	}

	/**
	 * [_product_attribute_name It's a callback function that is called in add_product_attribute_lookup
	 * validation it checks if $product_attribute_name has "a-z " case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$product_attribute entered value e.g. Mian Muhammad Nawaz Sharif
	 * @return [type]  [If $product_attribute has "a-z " case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _product_attribute_name($product_attribute)
	{
		if (preg_match("/^[a-z ]+$/i", $product_attribute))
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__political_leader_name It's a callback function that is called in add_product_attribute_lookup
	 * validation it checks if $leader_name has "a-z -.()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$leader_name entered value e.g. Mian Muhammad Nawaz Sharif
	 * @return [type]  [If $leader_name has "a-z -.()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _leader_name($leader_name)
	{
		if (preg_match("/^[a-z \-\.()]+$/i", $leader_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [_details It's a callback function that is called in add_product_attribute_lookup
	 * validation it checks if $details has "a-z .0-9:,-/#" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$details entered value e.g. Nawaz Sharif became prime minister
	 * of Pakistan...........................................................................
	 * @return [type]  [If $details has "a-z .0-9:,-/#" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
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
	 * [edit_unique It's a callback function that is called in edit_product_attribute_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [product_attribute entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. product_attribute.email.3]
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

	/**
	 * [product_attribute_lookup This function will retrieve all product_attributes from database without
	 * pagination]
	 * @return [array] [All product_attribute records]
	 */
	public function product_attribute_lookup()
	{
		$product_attributes = $this->product_attribute_model->get_product_attributes();
		return $product_attributes;
	}	

	/**
	 * [get_product_attribute_by_id_lookup This function will retrieve a specific product_attribute from database
	 * by its $id]
	 * @param  [type]  $id   [product_attribute id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific product_attribute record who's $id is passed]
	 */
	public function get_product_attribute_by_id_lookup($id)
	{
		$product_attribute = $this->product_attribute_model->get_product_attribute_by_id($id);
		return $product_attribute;
	}

	/**
	 * [delete_product_attribute_by_id_lookup This function will delete a specific product_attribute from database
	 * by its $id and product_attribute picture from assets/admin/images/product_attributes folder and then, redirects
	 * product_attribute to the product_attributes page]
	 * @param  [type] $id [product_attribute id whom record is to be deleted from database and picture 
	 * from assets/admin/images/product_attributes folder]
	 */
	public function delete_product_attribute_by_id_lookup($id)
	{
		if ($this->product_attribute_model->delete_product_attribute($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/product_attribute/');
		}
	}
}