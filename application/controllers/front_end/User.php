<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* User class is a controller class it has all methods for basic operation
* of user i.e. CRUD lookups, get bootstrap modals, pagination etc for user  
* Methods: index
* 		   add_user_lookup
* 		   edit_user_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_user_by_id_lookup
* 		   delete_user_by_id_lookup
*/

class User extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('front_end_layouts');	
		$this->load->model('front_end/user_model');
		// header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		// header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		// header('Cache-Control: post-check=0, pre-check=0', false);
		// header('Pragma: no-cache');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] users from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/user/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/user
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/user/<method_name>
	 *	- or -
	 * /admin/user/<method_name>
	 */
	
	// public function index()
	// {
	// 	if(empty($this->session->id))
	// 	{
	// 		redirect('front_end/politician');
	// 	}

	// 	$this->front_end_layouts->set_title('Setting'); 
	// 	$record = $this->user_model->get_user_by_id($this->session->id, TRUE);
	// 	$record = $record[0];
	// 	$data['user'] = $record;
	// 	$this->front_end_layouts->view('templates/front_end/setting', $data);
	// }	
	
	public function setting()
	{
/*		if(empty($this->session->id))
		{
			redirect('front_end/politician');
		}
*/

		$this->front_end_layouts->set_title('Setting'); 
		$record = $this->user_model->get_user_by_id($this->session->id, TRUE);
		if(empty($record))
		{
			redirect('front_end/home');
		}	

		$record = $record[0];
		$data['user'] = $record;
		$this->front_end_layouts->view('templates/front_end/setting', $data);
	}

	/**
	 * [add_user_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates user data. If data is valid 
	 * then, it allows access to its insert user model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_user_lookup()
	{	
		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'register_user')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
					
			$this->form_validation->set_data($data);
		}
		
		// First Name

	    $this->form_validation->set_rules(

    		'first_name', 'First Name', 
    		'trim|required|min_length[3]|max_length[50]|alpha',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
            	'alpha'     	=> 'Only alphabets are allowed'
    		)
	    );

	    // Last Name

	    $this->form_validation->set_rules(

    		'last_name', 'Last Name', 
    		'trim|required|min_length[2]|max_length[50]|alpha',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
            	'alpha'     	=> 'Only alphabets are allowed'
    		)
	    );

	    // Email

	    $this->form_validation->set_rules(

			'email', 'Email', 
			'trim|required|max_length[70]|valid_email|is_unique[user.email]',
	    	array(
	        	'required'      => '%s is required',
	        	'max_length'    => '%s should be at most %s chars',
	        	'valid_email'   => 'Please enter a valid email',
	        	'is_unique'     => 'This %s already exists'
			)
	    );
		
	    // Mobile Number

		$this->form_validation->set_rules(

			'mobile_number', 'Mobile Number',
			'trim|required|min_length[11]|max_length[11]|is_natural|is_unique[user.mobile_number]',
	    	array(
	        	'required'       => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'is_natural'  	 => 'Only numbers are allowed',
	        	'is_unique'      => 'This %s already exists'
			)
	    );	

	    // Password

		$this->form_validation->set_rules(

			'password', 'Password',
			'trim|required|min_length[6]|max_length[50]|alpha_dash',
	    	array(
	        	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha_dash'	=> 'Only alpha-numeric characters, underscores and hypens are allowed'
			)
	    );

		$this->form_validation->set_rules(

			'password_confirmation', 'Password Confirmation',
			'trim|required|min_length[6]|max_length[50]|alpha_dash|matches[password]',
	    	array(
	        	'required'      => '%s is required',
	        	'is_unique'     => 'This %s already exists',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	    	    'alpha_dash'	=> 'Only alpha-numeric characters, underscores and hypens are allowed'
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
					if($key == 'register_user')
					{
						continue;
					}
					
		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				// print_r($errors);

		    	echo json_encode($errors);
			}
	    }	    
	    else // Validation Passed
	    {	
			if($this->user_model->insert_user()) // insert into db
			{
		    	echo json_encode(array('success' => $_POST['email']));
				unset($_POST);
	    	}
	    }		
	}	

	/**
	 * [edit_user_lookup: Edits a user by id, Validates user data. If data is valid then, 
	 * it allows access to its edit user model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_user_lookup($id)
	{	
		$this->layouts->set_title('Edit user'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_user')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		
		// First Name

	    $this->form_validation->set_rules(

	    		'first_name', 'First Name', 
	    		'trim|required|min_length[3]|max_length[50]|alpha',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
	            	'alpha'     	=> 'Only alphabets are allowed'
	    		)
	    );

	    // Middle Name

	    $this->form_validation->set_rules(

			'middle_name', 'Middle Name', 
			'trim|min_length[1]|max_length[50]|alpha',
	       	array(
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'     	=> 'Only alphabets are allowed'
			)	    
	    );

	    // Last Name

	    $this->form_validation->set_rules(

	    		'last_name', 'Last Name', 
	    		'trim|required|min_length[2]|max_length[50]|alpha',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
	            	'alpha'     	=> 'Only alphabets are allowed'
	    		)
	    );

		// User Name

	    $this->form_validation->set_rules(

	    		'user_name', 'User Name', 
	    		'trim|required|min_length[2]|max_length[50]|alpha_numeric|callback_edit_unique[user.user_name.'. $id .']',
	        	array(
	            	'required'      => '%s is required',
		        	'min_length'    => '%s should be at least %s chars',
		        	'max_length'    => '%s should be at most %s chars',
	            	'alpha_numeric' => 'Only alpha_numeric is allowed'
	    		)
	    );

	    // Email

	    $this->form_validation->set_rules(

			'email', 'Email', 
			'trim|required|min_length[15]|max_length[70]|valid_email|callback_edit_unique[user.email.'. $id .']',
	    	array(
	        	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'valid_email'   => 'Please enter a valid email'
			)
	    );
		
	    // Mobile Number

		$this->form_validation->set_rules(

			'mobile_number', 'Mobile Number',
			'trim|required|min_length[11]|max_length[11]|is_natural|callback_edit_unique[user.mobile_number.'. $id .']',
	    	array(
	        	'required'       => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'is_natural'  	 => 'Only numbers are allowed'
			)
	    );	

		// Image
	    
	    $this->form_validation->set_rules(

			'image', 'Image', 
			'trim'
	    );	 

		$data['record'] = $this->get_user_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc

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
					if($key == 'edit_user')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_user', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
	    	$image = $_FILES['image']['name'];
	    	if(!empty($image))
	    	{
	    		$this->delete_picture($id, 'admin/user_model', 'get_user_by_id', USER_IMAGE_UPLOAD_PATH, 'picture');

	    		// $this->delete_user_by_id_lookup($id);

		    	// Passing 1: image name, 2: image upload path and 3: file name attribute value
	    		$image = $this->save_photo($image, USER_IMAGE_UPLOAD_PATH, 'image');

	    		$image_thumb = USER_IMAGE_UPLOAD_PATH . '/THUMB_' . $image;

		    	$profile_image = USER_IMAGE_UPLOAD_PATH . '/PROFILE_IMAGE_' . $image;
	    		
	    		$image_thumb_name = 'THUMB_' . $image;
	    		
	    		$profile_image_name = 'PROFILE_IMAGE_' . $image;

		    	$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $image_thumb, 30);
		    	
		    	$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $profile_image, 600);

		    	unlink(USER_IMAGE_UPLOAD_PATH . DS . $image);
	    	}
	    	else
	    	{
	    		$image = $data['record'][0]['picture'];
	    		$image_thumb_name = $data['record'][0]['thumbnail'];
	    	}
			if($this->user_model->update_user($id, $profile_image_name, $image_thumb_name))
			{
				$this->session->set_flashdata('success_message', 'User ' . ucfirst($this->input->post('first_name')) . ' ' . 
								(!empty($this->input->post('middle_name')) ? '-' . ucfirst($this->input->post('middle_name')) : '') 
									. ' ' . ucfirst($this->input->post('last_name')) . ' has been successfully updated!');
		    	echo json_encode(array('success' => 'User Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [user id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['record'] = $this->get_user_by_id_lookup($id);	
		
		$this->load->view('templates/admin/user_modal', $data);		
	}

	public function has_already_voted_lookup($id)
	{
		$result = $this->user_model->has_already_voted($id);
		echo json_encode(array('success' => $result));
	}

	public function insert_halqas_plus_vote_now_lookup()
	{
				/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'vote-btn')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}

		// On Halqa

	    $this->form_validation->set_rules(

    		'on_halqa', 'On Halqa', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
    		)
	    );
	
		// Provincial Assembly

	    $this->form_validation->set_rules(

    		'provincial_assembly', 'On Halqa', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
    		)
	    );
		
		// On Halqa

	    $this->form_validation->set_rules(

    		'provincial_halqa', 'Provincial Halqa', 
    		'trim|required',
        	array(
            	'required'      => '%s is required'
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
					if($key == 'vote-btn')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->user_model->insert_halqas_plus_vote_now()) // update into db
			{
				$main_entity_id = trim($this->input->post('main_entity_id'), "' ");
				echo $latest_politician_info = file_get_contents(site_url('front_end/politician/get_latest_politician_votes_by_id/' . $main_entity_id));
	    	}
	    	else
	    	{
		    	echo json_encode(array('failure' => 'halqas not inserted'));
	    	}
	    }
	}


	public function has_already_voted_this_entity_lookup($id, $user_id)
	{
		$result = $this->user_model->has_already_voted_this_entity($id, $user_id);
		echo json_encode(array('success' => $result));
	}

	public function vote_this_entity_lookup($id, $user_id)
	{
		if($this->user_model->vote_this_entity($id, $user_id))
		{
			echo $latest_politician_info = file_get_contents(site_url('front_end/politician/get_latest_politician_votes_by_id/' . $id));
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

	/**
	 * [user_lookup This function will retrieve all users from database without
	 * pagination]
	 * @return [array] [All user records]
	 */
	public function user_lookup()
	{
		$users = $this->user_model->get_users();
		return $users;
	}	

	/**
	 * [get_user_by_id_lookup This function will retrieve a specific user from database
	 * by its $id]
	 * @param  [type]  $id   [user id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific user record who's $id is passed]
	 */
	public function get_user_by_id_lookup($id, $edit = FALSE)
	{
		$user = $this->user_model->get_user_by_id($id, $edit);
		return $user;
	}

	/**
	 * [delete_user_by_id_lookup This function will delete a specific user from database
	 * by its $id and user picture from assets/admin/images/users folder and then, redirects
	 * user to the users page]
	 * @param  [type] $id [user id whom record is to be deleted from database and picture 
	 * from assets/admin/images/users folder]
	 */
	public function delete_user_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/user_model', 'get_user_by_id', USER_IMAGE_UPLOAD_PATH, 'picture');

		if ($this->user_model->delete_user($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/user/');
		}
	}
}

