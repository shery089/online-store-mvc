<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* designation class is a controller class it has all methods for basic operation
* of designation i.e. CRUD lookups, get bootstrap modals, pagination etc for designation  
* Methods: index
* 		   add_designation_lookup
* 		   edit_designation_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_designation_by_id_lookup
* 		   delete_designation_by_id_lookup
*/

class Designation extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/designation_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] designations from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/designation/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/designation
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/designation/<method_name>
	 *	- or -
	 * /admin/designation/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Designations'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->designation_model->record_count();
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

        $data["designations"] = $this->designation_model->fetch_designations($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/designations', $data);
	}

	/**
	 * [add_designation_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates designation data. If data is valid 
	 * then, it allows access to its insert designation model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_designation_lookup()
	{	
		$this->layouts->set_title('Add Designation'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_designation')
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
	    		'trim|required|min_length[3]|is_unique[user_designation.name]|max_length[100]|callback__designation',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
		        	'is_unique'		=> '%s already exists'

	    		)
	    );

		$this->form_validation->set_message('_designation', 'Only alphabets, spaces and "-/&" are allowed');	 

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
					if($key == 'add_designation')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$this->layouts->view('templates/admin/add_designation');
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->designation_model->insert_designation()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Designation ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'designation inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_designation_lookup: Edits a designation by id, Validates designation data. If data is valid then, 
	 * it allows access to its edit designation model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_designation_lookup($id)
	{		
		$this->layouts->set_title('Edit Designation'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_designation')
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
    		'trim|required|min_length[3]|callback__designation|max_length[50]|callback_edit_unique[user_designation.name.'. $id .']',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'     	=> 'Only alphabets are allowed'

    		)
	    );

		$this->form_validation->set_message('_designation', 'Only alphabets, spaces and "-/" are allowed');	 

		$data['designation'] = $this->get_designation_by_id_lookup($id);

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
					if($key == 'edit_designation')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_designation', $data);
			}
	    }	    
	    else // Validation Passed
	    {		    	
			if($this->designation_model->update_designation($id))
			{
				$this->session->set_flashdata('success_message', 'Designation ' . ucfirst($this->input->post('name')) .
				' has been successfully updated!');
		    	echo json_encode(array('success' => 'designation Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [designation id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['designation'] = $this->get_designation_by_id_lookup($id);	
		
		$this->load->view('templates/admin/designation_modal', $data);		
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_designation_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [designation entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. designation.email.3]
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
	 * [__designation It's a callback function that is called in add_political_party_lookup
	 * validation it checks if $designation has "a-z -/" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$designation entered value e.g. Chairperson, Quaid-e-Tehreek/ Chairman,
	 * Central President
	 * 
	 * @return [type]  [If $designation has "a-z -/" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _designation($designation)
	{
		if (preg_match("/^[a-z \-\/&]+$/i", $designation)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [designation_lookup This function will retrieve all designations from database without
	 * pagination]
	 * @return [array] [All designation records]
	 */
	public function designation_lookup()
	{
		$designations = $this->designation_model->get_designations();
		return $designations;
	}	

	/**
	 * [get_designation_by_id_lookup This function will retrieve a specific designation from database
	 * by its $id]
	 * @param  [type]  $id   [designation id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific designation record who's $id is passed]
	 */
	public function get_designation_by_id_lookup($id)
	{
		$designation = $this->designation_model->get_designation_by_id($id);
		return $designation;
	}

	/**
	 * [delete_designation_by_id_lookup This function will delete a specific designation from database
	 * by its $id and designation picture from assets/admin/images/designations folder and then, redirects
	 * designation to the designations page]
	 * @param  [type] $id [designation id whom record is to be deleted from database and picture 
	 * from assets/admin/images/designations folder]
	 */
	public function delete_designation_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/designation_model', 'get_designation_by_id', designation_IMAGE_UPLOAD_PATH);

		if ($this->designation_model->delete_designation($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/designation/');
		}
	}	
}

