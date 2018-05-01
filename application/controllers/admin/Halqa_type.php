<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Halqa_type class is a controller class it has all methods for basic operation
* of halqa_type i.e. CRUD lookups, get bootstrap modals, pagination etc for halqa_type  
* Methods: index
* 		   add_halqa_type_lookup
* 		   edit_halqa_type_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_halqa_type_by_id_lookup
* 		   delete_halqa_type_by_id_lookup
*/

class Halqa_type extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('front_end/halqa_type_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] halqa_types from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/halqa_type/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/halqa_type
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/halqa_type/<method_name>
	 *	- or -
	 * /admin/halqa_type/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Halqa Type\'s'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->halqa_type_model->record_count();
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

        $data["halqa_types"] = $this->halqa_type_model->fetch_halqa_types($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/halqa_types', $data);
	}

	/**
	 * [add_halqa_type_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa_type data. If data is valid 
	 * then, it allows access to its insert halqa_type model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_halqa_type_lookup()
	{	
		$this->layouts->set_title('Add Halqa Type'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_halqa_type')
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
    		'trim|required|min_length[10]|is_unique[halqa_type.name]|max_length[100]|callback__alpha_space_hypen',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
            	'is_unique'		=> '%s already exists'
    		)
	    );	

		$this->form_validation->set_message('_alpha_space_hypen', 'Only alphabets and spaces are allowed'); 

		// Abbrevation

	    $this->form_validation->set_rules(

    		'abbreviation', 'Abbreviation', 
    		'trim|required|min_length[2]|is_unique[halqa_type.abbreviation]|max_length[15]|alpha',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'    		=> 'Only alphabets are allowed',
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
					if($key == 'add_halqa_type')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$this->layouts->view('templates/admin/add_halqa_type');
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->halqa_type_model->insert_halqa_type()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Halqa Type ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'halqa_type inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_halqa_type_lookup: Edits a halqa_type by id, Validates halqa_type data. If data is valid then, 
	 * it allows access to its edit halqa_type model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_halqa_type_lookup($id)
	{		
		$this->layouts->set_title('Edit halqa_type'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_halqa_type')
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
    		'trim|required|min_length[3]|callback__alpha_space|max_length[50]|callback_edit_unique[user_halqa_type.name.'. $id .']',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'     	=> 'Only alphabets are allowed'
    		)
	    );

		$this->form_validation->set_message('_alpha_space', 'Only alphabets and spaces are allowed'); 

		$data['halqa_type'] = $this->get_halqa_type_by_id_lookup($id);

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
					if($key == 'edit_halqa_type')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_halqa_type', $data);
			}
	    }	    
	    else // Validation Passed
	    {		    	
			if($this->halqa_type_model->update_halqa_type($id))
			{
				$this->session->set_flashdata('success_message', 'halqa_type ' . ucfirst($this->input->post('name')) .
				' has been successfully updated!');
		    	echo json_encode(array('success' => 'halqa_type Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [halqa_type id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['halqa_type'] = $this->get_halqa_type_by_id_lookup($id);	
		
		$this->load->view('templates/admin/halqa_type_modal', $data);		
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_halqa_type_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [halqa_type entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. halqa_type.email.3]
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
	 * [_alpha_numeric_hypen: It's a callback function that is called in add_halqa_type_lookup
	 * validation it checks if $halqa_type_name has "a-z0-9-" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$halqa_type_name entered value e.g. NA-60
	 * @return [type]  [If $halqa_type_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _alpha_space_hypen($halqa_type_name)
	{
		if (preg_match("/^[a-z \-]+$/i", $halqa_type_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [halqa_type_lookup: This function will retrieve all halqa_types from database without
	 * pagination]
	 * @return [array] [All halqa_type records]
	 */
	public function halqa_type_lookup()
	{
		$halqa_types = $this->halqa_type_model->get_halqa_types();
		return $halqa_types;
	}	

	/**
	 * [get_halqa_type_by_id_lookup This function will retrieve a specific halqa_type from database
	 * by its $id]
	 * @param  [type]  $id   [halqa_type id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific halqa_type record who's $id is passed]
	 */
	public function get_halqa_type_by_id_lookup($id)
	{
		$halqa_type = $this->halqa_type_model->get_halqa_type_by_id($id);
		return $halqa_type;
	}

	/**
	 * [delete_halqa_type_by_id_lookup This function will delete a specific halqa_type from database
	 * by its $id and halqa_type picture from assets/admin/images/halqa_types folder and then, redirects
	 * halqa_type to the halqa_types page]
	 * @param  [type] $id [halqa_type id whom record is to be deleted from database and picture 
	 * from assets/admin/images/halqa_types folder]
	 */
	public function delete_halqa_type_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/halqa_type_model', 'get_halqa_type_by_id', halqa_type_IMAGE_UPLOAD_PATH);

		if ($this->halqa_type_model->delete_halqa_type($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/halqa_type/');
		}
	}	
}

