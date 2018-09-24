<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Dashboard class is a controller class it has all methods for basic operation
* of Dashboard i.e. CRUD lookups, get bootstrap modals, pagination etc for dashboard  
* Methods: index
* 		   add_dashboard_lookup
* 		   edit_dashboard_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_dashboard_by_id_lookup
* 		   delete_dashboard_by_id_lookup
*/

class Dashboard extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
//		$this->load->model('admin/dashboard_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] categories from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/categories/admin/dashboard/index
	 *	- or -
	 *		http://localhost/categories/index.php/admin/dashboard
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/dashboard/<method_name>
	 *	- or -
	 * /admin/dashboard/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Dashboard'); 
		$this->layouts->view('templates/admin/dashboard');
	}

	/**
	 * [add_dashboard_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates dashboard data. If data is valid 
	 * then, it allows access to its insert dashboard model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_dashboard_lookup()
	{	
		$this->layouts->set_title('Add Dashboard'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_dashboard')
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
		
		// Dashboard Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|callback_is_unique[dashboard.name]|min_length[2]|max_length[200]|callback__dashboard_name',
        	array(
            	'required'      => 'Please provide a Dashboard %s e.g. Camera',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

	    $this->form_validation->set_message('_dashboard_name', 'Only alphabets, spaces and "-()" are allowed');	    


		// Parent Dashboard e.g. Digital or Electronics is parent dashboard of Camera  

	    $this->form_validation->set_rules(

    		'parent', 'Parent', 
    		'trim|required',
        	array(
            	'required'      => 'Please provide a Dashboard %s e.g. Electronics is parent dashboard of Camera',
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
					if($key == 'add_dashboard')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
//				$data['categories'] = $this->dashboard_model->get_all_categories();
				$data['categories'] = $this->dashboard_model->get_all_categories();
				$this->layouts->view('templates/admin/add_dashboard', $data);
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

			if($this->dashboard_model->insert_dashboard($image_name, $image_thumb_name, $profile_image_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Dashboard ' . ucwords($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'dashboard inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_dashboard_lookup: Edits a dashboard by id, Validates dashboard data. If data is valid then, 
	 * it allows access to its edit dashboard model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_dashboard_lookup($id)
	{		
		$this->layouts->set_title('Edit Dashboard'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_dashboard')
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

		// Dashboard Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[2]|max_length[200]|callback__dashboard_name|callback_edit_unique[dashboard.name.'. $id .']',
        	array(
            	'required'      => 'Please provide a Dashboard %s e.g. Camera',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

	    $this->form_validation->set_message('_dashboard_name', 'Only alphabets, spaces and "-()" are allowed');	    


		// Parent Dashboard e.g. Digital or Electronics is parent dashboard of Camera  

	    $this->form_validation->set_rules(

    		'parent', 'Parent', 
    		'trim|required',
        	array(
            	'required'      => 'Please provide a Dashboard %s e.g. Electronics is parent dashboard of Camera',
    		)
	    );

		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
	    );	

		$data['categories'] = $this->dashboard_model->get_categories_dropdown($id, 'specific');

		$data['record'] = $this->get_dashboard_by_id_lookup($id, 'specific');

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
					if($key == 'edit_dashboard')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_dashboard', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/dashboard_model', 'get_dashboard_by_id', CATEGORY_IMAGE_PATH, 'image');

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
	    	
			if($this->dashboard_model->update_dashboard($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Dashboard ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'Dashboard Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [dashboard id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['dashboard'] = $this->get_dashboard_by_id_lookup($id);	
		
		$this->load->view('templates/admin/dashboard_modal', $data);		
	}

	/**
	 * [_dashboard_name It's a callback function that is called in add_dashboard_lookup
	 * validation it checks if $dashboard_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$dashboard entered value e.g. Mian Muhammad Nawaz Sharif
	 * @return [type]  [If $dashboard has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _dashboard_name($dashboard)
	{
		if (preg_match("/^[a-z \-()]+$/i", $dashboard)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__political_leader_name It's a callback function that is called in add_dashboard_lookup
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
	 * [_details It's a callback function that is called in add_dashboard_lookup
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
	 * [edit_unique It's a callback function that is called in edit_dashboard_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [dashboard entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. dashboard.email.3]
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
	 * [dashboard_lookup This function will retrieve all categories from database without
	 * pagination]
	 * @return [array] [All dashboard records]
	 */
	public function dashboard_lookup()
	{
		$categories = $this->dashboard_model->get_categories();
		return $categories;
	}	

	/**
	 * [get_dashboard_by_id_lookup This function will retrieve a specific dashboard from database
	 * by its $id]
	 * @param  [type]  $id   [dashboard id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific dashboard record who's $id is passed]
	 */
	public function get_dashboard_by_id_lookup($id, $edit = '')
	{
		$dashboard = $this->dashboard_model->get_dashboard_by_id($id, $edit);
		return $dashboard;
	}

	/**
	 * [delete_dashboard_by_id_lookup This function will delete a specific dashboard from database
	 * by its $id and dashboard picture from assets/admin/images/categories folder and then, redirects
	 * dashboard to the categories page]
	 * @param  [type] $id [dashboard id whom record is to be deleted from database and picture 
	 * from assets/admin/images/categories folder]
	 */
	public function delete_dashboard_by_id_lookup($id)
	{
		$record = $this->get_dashboard_by_id_lookup($id, TRUE);
		$this->delete_picture(CATEGORY_IMAGE_PATH, $record);

		if ($this->dashboard_model->delete_dashboard($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/dashboard/');
		}
	}
}