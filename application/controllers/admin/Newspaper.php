<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* newspaper class is a controller class it has all methods for basic operation
* of newspaper i.e. CRUD lookups, get bootstrap modals, pagination etc for newspaper  
* Methods: index
* 		   add_newspaper_lookup
* 		   edit_newspaper_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_newspaper_by_id_lookup
* 		   delete_newspaper_by_id_lookup
*/

class Newspaper extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/newspaper_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] political_parties from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/newspaper/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/newspaper
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/newspaper/<method_name>
	 *	- or -
	 * /admin/newspaper/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Newspaper'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->newspaper_model->record_count();
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

        $data["newspapers"] = $this->newspaper_model->fetch_newspapers($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/newspapers', $data);
	}

	/**
	 * [add_newspaper_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates newspaper data. If data is valid 
	 * then, it allows access to its insert newspaper model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_newspaper_lookup()
	{	
		$this->layouts->set_title('Add Newspaper'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		// echo $this->input->post('submitted_designation');

		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_newspaper')
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
    		'trim|required|min_length[3]|max_length[100]|callback__newspaper_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_newspaper_name', 'Only alphabets, spaces and "-()" are allowed');	    
		
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
		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}	

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
/*				$data['designations'] = $this->designation_model->get_user_designations();
				$data['political_parties'] = $this->political_party_model->get_political_parties_dropdown();
				$data['halqas'] = $this->halqa_model->get_halqas();
				*/
				$this->layouts->view('templates/admin/add_newspaper');
			}
	    }
	    else // Validation Passed
	    {	
	    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    	$image = $this->save_photo($_FILES['image']['name'], NEWSPAPER_IMAGE_PATH, 'image');

	    	if(!empty($image))
	    	{
		    	$image_thumb = NEWSPAPER_IMAGE_PATH . '/THUMB_' . $image;
		    	
		    	$this->make_thumb(NEWSPAPER_IMAGE_PATH . '/' . $image, $image_thumb, 150);
		
	    		$image_thumb_name = 'THUMB_' . $image;

		    	unlink(NEWSPAPER_IMAGE_PATH . DS . $image);
		    }
	    	else
	    	{
	    		$image_thumb_name = '';
	    	}

			if($this->newspaper_model->insert_newspaper($image_thumb_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Newspaper ' . ucwords($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
		    	echo json_encode(array('success' => 'Newspaper inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_newspaper_lookup: Edits a newspaper by id, Validates newspaper data. If data is valid then, 
	 * it allows access to its edit newspaper model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_newspaper_lookup($id)
	{		
		$this->layouts->set_title('Edit newspaper'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_newspaper')
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
    		'trim|required|min_length[5]|max_length[200]|callback__newspaper_name',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		$this->form_validation->set_message('_newspaper_name', 'Only alphabets, spaces and "-()" are allowed');	    
		
		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
	    );	

		$data['newspaper'] = $this->get_newspaper_by_id_lookup($id);

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
					if($key == 'edit_newspaper')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_newspaper', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/newspaper_model', 'get_newspaper_by_id', NEWSPAPER_IMAGE_PATH, 'thumbnail');

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, NEWSPAPER_IMAGE_PATH, 'image');

	    		$image_thumb = NEWSPAPER_IMAGE_PATH . '/THUMB_' . $image;
		    	$this->make_thumb(NEWSPAPER_IMAGE_PATH . '/' . $image, $image_thumb, 150);
	    		
	    		$image_thumb_name = 'THUMB_' . $image;

		    	unlink(NEWSPAPER_IMAGE_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image_thumb_name = $data['newspaper']['thumbnail'];
	    	}
	    	
			if($this->newspaper_model->update_newspaper($id, $image_thumb_name))
			{
	   			$name = explode('(', rtrim(entity_decode($this->input->post('name')), ')'));
				
				$this->session->set_flashdata('success_message', 'Newspaper ' . ' ' . ucfirst($this->input->post('name')) . 
											' has been successfully updated!');
		    	echo json_encode(array('success' => 'newspaper Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [newspaper id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['newspaper'] = $this->get_newspaper_by_id_lookup($id);	
		
		$this->load->view('templates/admin/newspaper_modal', $data);		
	}

	/**
	 * [_newspaper_name It's a callback function that is called in add_newspaper_lookup
	 * validation it checks if $newspaper_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$newspaper entered value e.g. Mian Muhammad Nawaz Sharif
	 * @return [type]  [If $newspaper has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _newspaper_name($newspaper)
	{
		if (preg_match("/^[a-z \-()]+$/i", $newspaper)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [__political_leader_name It's a callback function that is called in add_newspaper_lookup
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
	 * [_details It's a callback function that is called in add_newspaper_lookup
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
	 * [edit_unique It's a callback function that is called in edit_newspaper_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [newspaper entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]	
	 * @param  [string] $params [table.attribute.id e.g. newspaper.email.3]
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
	 * [newspaper_lookup This function will retrieve all political_parties from database without
	 * pagination]
	 * @return [array] [All newspaper records]
	 */
	public function newspaper_lookup()
	{
		$political_parties = $this->newspaper_model->get_political_parties();
		return $political_parties;
	}	

	/**
	 * [get_newspaper_by_id_lookup This function will retrieve a specific newspaper from database
	 * by its $id]
	 * @param  [type]  $id   [newspaper id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific newspaper record who's $id is passed]
	 */
	public function get_newspaper_by_id_lookup($id, $edit = FALSE)
	{
		$newspaper = $this->newspaper_model->get_newspaper_by_id($id, $edit);
		return $newspaper;
	}

	/**
	 * [delete_newspaper_by_id_lookup This function will delete a specific newspaper from database
	 * by its $id and newspaper picture from assets/admin/images/political_parties folder and then, redirects
	 * newspaper to the political_parties page]
	 * @param  [type] $id [newspaper id whom record is to be deleted from database and picture 
	 * from assets/admin/images/political_parties folder]
	 */
	public function delete_newspaper_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/newspaper_model', 'get_newspaper_by_id', NEWSPAPER_IMAGE_PATH, 'thumbnail');

		if ($this->newspaper_model->delete_newspaper($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/newspaper/');
		}
	}
}