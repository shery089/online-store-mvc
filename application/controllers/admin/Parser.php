<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Parser class is a controller class it has all methods for basic operation
* of parser i.e. CRUD lookups, get bootstrap modals, pagination etc for parser  
* Methods: index
* 		   add_parser_lookup
* 		   edit_parser_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_parser_by_id_lookup
* 		   delete_parser_by_id_lookup
*/

class Parser extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/parser_model');
		$this->load->model('admin/designation_model');
	}


	/**
	 * [add_parser_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates parser data. If data is valid 
	 * then, it allows access to its insert parser model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function na_halqa_parser_lookup()
	{	
		if($this->parser_model->insert_na_halqa()) // insert into db
		{
			$this->session->set_flashdata('success_message', 'NA Halqa\'s ' . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'NA Halqa inserted'));
    	}
	}	

	/**
	 * [edit_parser_lookup: Edits a parser by id, Validates parser data. If data is valid then, 
	 * it allows access to its edit parser model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_parser_lookup($id)
	{		
		$this->layouts->set_title('Edit parser'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_parser')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		
		// Name of Political Party

	    $this->form_validation->set_rules(

    		'name', 'Name', 
    		'trim|required|min_length[5]|is_unique[user_designation.name]|max_length[500]|callback__party_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_designation', 'Only alphabets, spaces and "-/" are allowed');	 

		$this->form_validation->set_message('_party_name', 'Only alphabets, spaces and "-()" are allowed');

		// Name of Party Leader

	    $this->form_validation->set_rules(

    		'leader', 'Leader',
    		'trim|required|min_length[5]|max_length[500]|callback__leader_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );	

		$this->form_validation->set_message('_leader_name', 'Only alphabets, spaces and "-.()" are allowed');	 
			    
		// Address

	    $this->form_validation->set_rules(

    		'address', 'Address', 
    		'trim|required|min_length[5]|max_length[500]|callback__address',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_address', 'Only alphabets, spaces and "a-z .()0-9:,-/" are allowed');	    
		
		// Flag
	    
	    $this->form_validation->set_rules(

			'flag', 'Flag', 
			'trim'
	    );	

		$data['designations'] = $this->designation_model->get_user_designations();
		$data['record'] = $this->get_parser_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc

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
					if($key == 'edit_parser')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_parser', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['flag']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_parser_by_id_lookup($id);

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, PARTY_IMAGE_PATH, 'flag');
	    	}
	    	else
	    	{
	    		$image = $data['record'][0]['flag'];
	    	}

	    	
			if($this->parser_model->update_parser($id, $image))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Political Party ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'parser Updated'));
	    	}
	    	

	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [parser id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['record'] = $this->get_parser_by_id_lookup($id);	
		
		$this->load->view('templates/admin/parser_modal', $data);		
	}

	/**
	 * [__parser_name It's a callback function that is called in add_parser_lookup
	 * validation it checks if $parser_name has "a-z -()/" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$party_name entered value e.g. Pakistan Muslim League (N)
	 * @return [type]  [If $party_name has "a-z -()/" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _party_name($party_name)
	{
		if (preg_match("/^[a-z \-()\/]+$/i", $party_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__political_leader_name It's a callback function that is called in add_parser_lookup
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
	 * [__designation It's a callback function that is called in add_parser_lookup
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
		if (preg_match("/^[a-z \-\/]+$/i", $designation)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__address It's a callback function that is called in add_parser_lookup
	 * validation it checks if $address has "a-z .0-9:,-/#" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$address entered value e.g. Chairperson, Quaid-e-Tehreek/ Chairman,
	 * Central President
	 * 
	 * @return [type]  [If $address has "a-z .0-9:,-/#" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _address($address)
	{
		if (preg_match("/^[a-z #\-\.0-9()\/:,]+$/i", $address)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_parser_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [parser entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. parser.email.3]
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
	 * [parser_lookup This function will retrieve all political_parties from database without
	 * pagination]
	 * @return [array] [All parser records]
	 */
	public function parser_lookup()
	{
		$political_parties = $this->parser_model->get_political_parties();
		return $political_parties;
	}	

	/**
	 * [get_parser_by_id_lookup This function will retrieve a specific parser from database
	 * by its $id]
	 * @param  [type]  $id   [parser id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific parser record who's $id is passed]
	 */
	public function get_parser_by_id_lookup($id, $edit = FALSE)
	{
		$parser = $this->parser_model->get_parser_by_id($id, $edit);
		return $parser;
	}

	/**
	 * [delete_parser_by_id_lookup This function will delete a specific parser from database
	 * by its $id and parser picture from assets/admin/images/political_parties folder and then, redirects
	 * parser to the political_parties page]
	 * @param  [type] $id [parser id whom record is to be deleted from database and picture 
	 * from assets/admin/images/political_parties folder]
	 */
	public function delete_parser_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/parser_model', 'get_parser_by_id', PARTY_IMAGE_PATH, 'flag');

		if ($this->parser_model->delete_parser($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/parser/');
		}
	}	
}

