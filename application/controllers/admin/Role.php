<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Role class is a controller class it has all methods for basic operation
* of Role i.e. CRUD lookups, get bootstrap modals, pagination etc for Role  
* Methods: index
* 		   add_role_lookup
* 		   edit_role_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_role_by_id_lookup
* 		   delete_role_by_id_lookup
*/

class Role extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/role_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] roles from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/ims/admin/role/index
	 *	- or -
	 *		http://localhost/ims/index.php/admin/role
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/role/<method_name>
	 *	- or -
	 * /admin/role/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Roles'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->role_model->record_count();
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

        $data["roles"] = $this->role_model->fetch_roles($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/roles', $data);
	}

	/**
	 * [add_role_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates role data. If data is valid 
	 * then, it allows access to its insert role model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_role_lookup()
	{	
		$this->layouts->set_title('Add role'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_role')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		
		// Name

	    $this->form_validation->set_rules(

	    		'name', 'Name', 
	    		'trim|required|min_length[3]|is_unique[user_role.name]|max_length[50]|callback__alpha_space',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
	            	'alpha'     	=> 'Only alphabets are allowed',
	            	'is_unique'		=> '%s already exists'
	    		)
	    );

		$this->form_validation->set_message('_alpha_space', 'Only alphabets and spaces are allowed'); 

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
					if($key == 'add_role')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$this->layouts->view('templates/admin/add_role');
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->role_model->insert_role()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Role ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'role inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_role_lookup: Edits a role by id, Validates role data. If data is valid then, 
	 * it allows access to its edit role model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_role_lookup($id)
	{		
		$this->layouts->set_title('Edit role'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_role')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		
		// Name

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[3]|callback__alpha_space|max_length[50]|callback_edit_unique[user_role.name.'. $id .']',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'     	=> 'Only alphabets are allowed'

    		)
	    );

		$this->form_validation->set_message('_alpha_space', 'Only alphabets and spaces are allowed'); 

		$data['role'] = $this->get_role_by_id_lookup($id);

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
					if($key == 'edit_role')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_role', $data);
			}
	    }	    
	    else // Validation Passed
	    {		    	
			if($this->role_model->update_role($id))
			{
				$this->session->set_flashdata('success_message', 'Role ' . ucfirst($this->input->post('name')) .
				' has been successfully updated!');
		    	echo json_encode(array('success' => 'role Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [role id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['role'] = $this->get_role_by_id_lookup($id);	
		
		$this->load->view('templates/admin/role_modal', $data);		
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_role_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [role entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. role.email.3]
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
	 * [__politician_name It's a callback function that is called in add_political_party_lookup
	 * validation it checks if $politician_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$party_name entered value e.g. Pakistan Muslim League (N)
	 * @return [type]  [If $party_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _alpha_space($role_name)
	{
		if (preg_match("/^[a-z ]+$/i", $role_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}


	/**
	 * [role_lookup This function will retrieve all roles from database without
	 * pagination]
	 * @return [array] [All role records]
	 */
	public function role_lookup()
	{
		$roles = $this->role_model->get_roles();
		return $roles;
	}	

	/**
	 * [get_role_by_id_lookup This function will retrieve a specific role from database
	 * by its $id]
	 * @param  [type]  $id   [role id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific role record who's $id is passed]
	 */
	public function get_role_by_id_lookup($id)
	{
		$role = $this->role_model->get_role_by_id($id);
		return $role;
	}

	/**
	 * [delete_role_by_id_lookup This function will delete a specific role from database
	 * by its $id and role picture from assets/admin/images/roles folder and then, redirects
	 * role to the roles page]
	 * @param  [type] $id [role id whom record is to be deleted from database and picture 
	 * from assets/admin/images/roles folder]
	 */
	public function delete_role_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/role_model', 'get_role_by_id', role_IMAGE_UPLOAD_PATH);

		if ($this->role_model->delete_role($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/role/');
		}
	}	
}

