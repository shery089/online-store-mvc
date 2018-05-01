<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Category class is a controller class it has all methods for basic operation
* of Category i.e. CRUD lookups, get bootstrap modals, pagination etc for category  
* Methods: index
* 		   add_category_lookup
* 		   edit_category_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_category_by_id_lookup
* 		   delete_category_by_id_lookup
*/

class Category extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/category_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] categories from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/categories/admin/category/index
	 *	- or -
	 *		http://localhost/categories/index.php/admin/category
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/category/<method_name>
	 *	- or -
	 * /admin/category/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Category'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->category_model->record_count();
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

        $data["categories"] = $this->category_model->fetch_categories($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
		$this->layouts->view('templates/admin/categories', $data);
	}

	/**
	 * [add_category_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates category data. If data is valid 
	 * then, it allows access to its insert category model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_category_lookup()
	{	
		$this->layouts->set_title('Add Category'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_category')
				{
					continue;
				}
				if($key == 'parent' && $value == 0)
				{
					$_POST['parent'] = "0";
				}
		
	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}
		
		// Category Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|callback_is_unique[category.name]|min_length[2]|max_length[200]|callback__category_name',
        	array(
            	'required'      => 'Please provide a Category %s e.g. Camera',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

	    $this->form_validation->set_message('_category_name', 'Only alphabets, spaces and "-()" are allowed');	    


		// Parent Category e.g. Digital or Electronics is parent category of Camera  

	    $this->form_validation->set_rules(

    		'parent', 'Parent', 
    		'trim|required',
        	array(
            	'required'      => 'Please provide a Category %s e.g. Electronics is parent category of Camera',
    		)
	    );

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
					if($key == 'add_category')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
//				$data['categories'] = $this->category_model->get_all_categories();
				$data['categories'] = $this->category_model->get_all_categories();
				$this->layouts->view('templates/admin/add_category', $data);
			}
	    }
	    else // Validation Passed
	    {	
	    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    	$image = $this->save_photo($_FILES['image']['name'], CATEGORY_IMAGE_PATH, 'image');

	    	if(!empty($image))
	    	{
		    	$image_thumb = CATEGORY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = CATEGORY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
	    		$image_dest = CATEGORY_IMAGE_PATH . '/IMAGE_' . $image;
		    	
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $profile_image, 100);
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $image_dest, 800);

	    		$image_thumb_name = 'THUMB_' . $image;	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

		    	unlink(CATEGORY_IMAGE_PATH . DS . $image);

		    }
	    	else
	    	{
	    		$image_name = '';
	    		$image_thumb_name = '';
				$profile_image_name = '';
	    	}

			if($this->category_model->insert_category($image_name, $image_thumb_name, $profile_image_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Category ' . ucwords($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'category inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_category_lookup: Edits a category by id, Validates category data. If data is valid then, 
	 * it allows access to its edit category model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_category_lookup($id)
	{		
		$this->layouts->set_title('Edit Category'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_category')
				{
					continue;
				}

				if($key == 'parent' && $value == 0)
				{
					$_POST['parent'] = "0";
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}

		// Category Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[2]|max_length[200]|callback__category_name|callback_edit_unique[category.name.'. $id .']',
        	array(
            	'required'      => 'Please provide a Category %s e.g. Camera',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

	    $this->form_validation->set_message('_category_name', 'Only alphabets, spaces and "-()" are allowed');	    


		// Parent Category e.g. Digital or Electronics is parent category of Camera  

	    $this->form_validation->set_rules(

    		'parent', 'Parent', 
    		'trim|required',
        	array(
            	'required'      => 'Please provide a Category %s e.g. Electronics is parent category of Camera',
    		)
	    );

		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
	    );	

		$data['categories'] = $this->category_model->get_categories_dropdown($id, 'specific');

		$data['record'] = $this->get_category_by_id_lookup($id, 'specific');

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
					if($key == 'edit_category')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_category', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/category_model', 'get_category_by_id', CATEGORY_IMAGE_PATH, 'image');

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, CATEGORY_IMAGE_PATH, 'image');

	    		$image_thumb = CATEGORY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = CATEGORY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
	    		$image_dest = CATEGORY_IMAGE_PATH . '/IMAGE_' . $image;
	    		
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $profile_image, 100);
		    	$this->make_thumb(CATEGORY_IMAGE_PATH . '/' . $image, $image_dest, 800);
	    		
	    		$image_thumb_name = 'THUMB_' . $image;
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

		    	unlink(CATEGORY_IMAGE_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image_name = $data['record']['image'];
	    		$image_thumb_name = $data['record']['thumbnail'];
	    		$profile_image_name = $data['record']['profile_image'];
	    	}
	    	
			if($this->category_model->update_category($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Category ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Category Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [category id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['category'] = $this->get_category_by_id_lookup($id);	
		
		$this->load->view('templates/admin/category_modal', $data);		
	}

	/**
	 * [_category_name It's a callback function that is called in add_category_lookup
	 * validation it checks if $category_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$category entered value e.g. Mian Muhammad Nawaz Sharif
	 * @return [type]  [If $category has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _category_name($category)
	{
		if (preg_match("/^[a-z \-()]+$/i", $category)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__political_leader_name It's a callback function that is called in add_category_lookup
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
	 * [_details It's a callback function that is called in add_category_lookup
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
	 * [edit_unique It's a callback function that is called in edit_category_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [category entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. category.email.3]
	 * @return [type]  [if same data exists other than current record then, returns
	 * FALSE. If it doesn't exists other than current record then, returns TRUE.]
	 */
	public function is_unique($value, $params)
	{
	    $this->form_validation->set_message('is_unique',
	        'The %s is already being used by another account.');
	    list($table, $field) = explode(".", $params, 2);

		$parent = $this->input->post('parent');

		if(!empty($parent)) {
			$query = $this->db->select($field)->from($table)
					->where($field, $value)->where('parent =', $parent)->limit(1)->get();
		}
		else {
			$query = $this->db->select($field)->from($table)
					->where($field, $value)->limit(1)->get();
		}

		if ($query->row())
	    {
	        return FALSE;
	    } 
	    else 
	    {
	        return TRUE;
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

	/**
	 * [category_lookup This function will retrieve all categories from database without
	 * pagination]
	 * @return [array] [All category records]
	 */
	public function category_lookup()
	{
		$categories = $this->category_model->get_categories();
		return $categories;
	}	

	/**
	 * [get_category_by_id_lookup This function will retrieve a specific category from database
	 * by its $id]
	 * @param  [type]  $id   [category id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific category record who's $id is passed]
	 */
	public function get_category_by_id_lookup($id, $edit = '')
	{
		$category = $this->category_model->get_category_by_id($id, $edit);
		return $category;
	}

	/**
	 * [delete_category_by_id_lookup This function will delete a specific category from database
	 * by its $id and category picture from assets/admin/images/categories folder and then, redirects
	 * category to the categories page]
	 * @param  [type] $id [category id whom record is to be deleted from database and picture 
	 * from assets/admin/images/categories folder]
	 */
	public function delete_category_by_id_lookup($id)
	{
		$record = $this->get_category_by_id_lookup($id, TRUE);
		$this->delete_picture(CATEGORY_IMAGE_PATH, $record);

		if ($this->category_model->delete_category($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/category/');
		}
	}
}