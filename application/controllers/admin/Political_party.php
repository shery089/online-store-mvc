<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Political_party class is a controller class it has all methods for basic operation
* of Political_party i.e. CRUD lookups, get bootstrap modals, pagination etc for Political_party  
* Methods: index
* 		   add_political_party_lookup
* 		   edit_political_party_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_political_party_by_id_lookup
* 		   delete_political_party_by_id_lookup
*/

class Political_party extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/political_party_model');
		$this->load->model('admin/designation_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] political_parties from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/political_party/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/political_party
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/political_party/<method_name>
	 *	- or -
	 * /admin/political_party/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Political Party'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->political_party_model->record_count();
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

        $data["political_parties"] = $this->political_party_model->fetch_political_parties($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/political_parties', $data);
	}

	/**
	 * [add_political_party_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates political_party data. If data is valid 
	 * then, it allows access to its insert political_party model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_political_party_lookup()
	{	
		$this->layouts->set_title('Add Political Party'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_political_party')
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
    		'trim|required|min_length[5]|max_length[500]|callback__party_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_party_name', 'Only alphabets, spaces and "-()/" are allowed');	    
		
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
		
		// Designation

	    $this->form_validation->set_rules(

    		'designation', 'Designation', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
    		)
	    );

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

		$this->form_validation->set_message('_address', 'Only alphabets, spaces and "a-z .()0-9:,-/#" are allowed');	    
		
		// Flag
	    
	    $this->form_validation->set_rules(

			'flag', 'Flag', 
			'trim'
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
		// $this->form_validation->set_message('_details', 'Only alphabets, spaces and "a-z .()0-9:,-/#" are allowed');	    

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
					if($key == 'add_political_party')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['designations'] = $this->designation_model->get_user_designations();
				$this->layouts->view('templates/admin/add_political_party', $data);
			}
	    }
	    else // Validation Passed
	    {	
	    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    	$image = $this->save_photo($_FILES['flag']['name'], PARTY_IMAGE_PATH, 'flag');
	    	if(!empty($image))
	    	{
		    	$image_thumb = PARTY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = PARTY_IMAGE_PATH . '/PROFILE_IMAGE' . $image;
		    	$image_dest = PARTY_IMAGE_PATH . '/IMAGE_' . $image;
		    	
		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $profile_image, 100);
		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $image_dest, 800);    

	    		$image_thumb_name = 'THUMB_' . $image;	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;
		    	unlink(PARTY_IMAGE_PATH . DS . $image);
		    }
	    	else
	    	{
	    		$image_name = '';
	    		$image_thumb_name = '';
	    		$profile_image_name = '';
	    	}

			if($this->political_party_model->insert_political_party($image_name, $image_thumb_name, $profile_image_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Political Party ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'political_party inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_political_party_lookup: Edits a political_party by id, Validates political_party data. If data is valid then, 
	 * it allows access to its edit political_party model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_political_party_lookup($id)
	{		
		$this->layouts->set_title('Edit Political Party'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_political_party')
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


		$data['designations'] = $this->designation_model->get_user_designations();
		$data['political_party'] = $this->get_political_party_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc

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
					if($key == 'edit_political_party')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_political_party', $data);
			}
	    }	    
	    else // Validation Passed
	    {
	    	$image = $_FILES['flag']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/political_party_model', 'get_political_party_by_id', PARTY_IMAGE_PATH, 'flag');

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, PARTY_IMAGE_PATH, 'flag');

	    		$image_thumb = PARTY_IMAGE_PATH . '/THUMB_' . $image;
		    	$profile_image = PARTY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
	    		$image_dest = PARTY_IMAGE_PATH . '/IMAGE_' . $image;

	    		$image_thumb_name = 'THUMB_' . $image;
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;
	    		$image_name = 'IMAGE_' . $image;

		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $profile_image, 100);
		    	$this->make_thumb(PARTY_IMAGE_PATH . '/' . $image, $image_dest, 800);

		    	unlink(PARTY_IMAGE_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image_name = $data['political_party']['flag'];
	    		$image_thumb_name = $data['political_party']['thumbnail'];
	    		$profile_image_name = $data['political_party']['profile_image'];
	    	}
	    	
			if($this->political_party_model->update_political_party($id, $image_name, $image_thumb_name, $profile_image_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Political Party ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'political_party Updated'));
	    	}
	    }		
	}
	
	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [political_party id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['political_party'] = $this->get_political_party_by_id_lookup($id);	
		
		$this->load->view('templates/admin/political_party_modal', $data);		
	}

	/**
	 * [__political_party_name It's a callback function that is called in add_political_party_lookup
	 * validation it checks if $political_party_name has "a-z -()/" case-insensitive then, returns TRUE.
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
	 * [__political_leader_name It's a callback function that is called in add_political_party_lookup
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
	 * [__address It's a callback function that is called in add_political_party_lookup
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
	 * [edit_unique It's a callback function that is called in edit_political_party_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [political_party entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. political_party.email.3]
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
	 * [political_party_lookup This function will retrieve all political_parties from database without
	 * pagination]
	 * @return [array] [All political_party records]
	 */
	public function political_party_lookup()
	{
		$political_parties = $this->political_party_model->get_political_parties();
		return $political_parties;
	}	

	/**
	 * [get_political_party_by_id_lookup This function will retrieve a specific political_party from database
	 * by its $id]
	 * @param  [type]  $id   [political_party id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific political_party record who's $id is passed]
	 */
	public function get_political_party_by_id_lookup($id, $edit = FALSE)
	{
		$political_party = $this->political_party_model->get_political_party_by_id($id, $edit);
		return $political_party;
	}

	/**
	 * [delete_political_party_by_id_lookup This function will delete a specific political_party from database
	 * by its $id and political_party picture from assets/admin/images/political_parties folder and then, redirects
	 * political_party to the political_parties page]
	 * @param  [type] $id [political_party id whom record is to be deleted from database and picture 
	 * from assets/admin/images/political_parties folder]
	 */
	public function delete_political_party_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/political_party_model', 'get_political_party_by_id', PARTY_IMAGE_PATH, 'flag');

		if ($this->political_party_model->delete_political_party($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/political_party/');
		}
	}	
}

