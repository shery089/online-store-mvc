<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* User class is a controller class it has all methods for basic operation
* of user i.e. CRUD lookups, get bootstrap modals, pagination etc for user  
* Methods: index
* 		   add_user_lookup
* 		   edit_user_lookup
* 		   get_modal
* 		   is_unique_es
* 		   get_user_by_id_lookup
* 		   delete_user_by_id_lookup
*/

class User extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('elasticsearch');
		$this->load->library('layouts');
		$this->load->library('ajax_pagination');
		$this->load->model('admin/user_model');
		$this->load->model('admin/role_model');
	}

	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] users from the database and
	 * generates pagination from other records
	 *
	 * Maps to the following URL
	 *        http://localhost/pak_democrates/admin/user/index
	 *    - or -
	 *        http://localhost/pak_democrates/index.php/admin/user
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/user/<method_name>
	 *    - or -
	 * /admin/user/<method_name>
	 */

	public function index()
	{
		if($this->input->is_ajax_request()) {
			$this->search_user_lookup();
		}

		$this->layouts->set_title('Users');

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = 6;
//		$config['per_page'] = 1;
		$config["uri_segment"] = 4;

		$users = $this->fetch_users_by_elasticsearch($config["per_page"], $current_page);

		$config["total_rows"] = !empty($users['total']) ? $users['total'] : 0;

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config['cur_tag_open'] = "<li><span><b>";
		$config['cur_tag_close'] = "</b></span></li>";

		$this->pagination->initialize($config);

		$data["users"] = !empty($users['hits']) ? $users['hits'] : array();

		$data["links"] = $this->pagination->create_links();

		$data['roles'] = $this->role_model->get_user_roles();

		if(!$this->input->is_ajax_request()) {
			$this->layouts->view('templates/admin/users', $data);
		}
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
		$this->layouts->set_title('Add user');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if ($this->input->is_ajax_request()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'add_user') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// First Name

		$this->form_validation->set_rules(

				'first_name', 'First Name',
				'trim|required|min_length[2]|max_length[50]|alpha',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// Middle Name

		$this->form_validation->set_rules(

				'middle_name', 'Middle Name',
				'trim|min_length[1]|max_length[50]|alpha',
				array(
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// Last Name

		$this->form_validation->set_rules(

				'last_name', 'Last Name',
				'trim|required|min_length[2]|max_length[50]|alpha',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// User Name

		$this->form_validation->set_rules(

				'user_name', 'User Name',
				'trim|required|min_length[2]|max_length[50]|alpha_numeric|callback_is_unique_es[user_name]',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_numeric' => 'Only alpha_numeric is allowed'
				)
		);

		// Email

		$this->form_validation->set_rules(

				'email', 'Email',
				'trim|required|max_length[70]|valid_email|callback_is_unique_es[email]',
				array(
						'required' => '%s is required',
						'max_length' => '%s should be at most %s chars',
						'valid_email' => 'Please enter a valid email',
						'is_unique' => 'This %s already exists'
				)
		);

		// Mobile Number

		$this->form_validation->set_rules(

				'mobile_number', 'Mobile Number',
				'trim|required|min_length[11]|max_length[11]|is_natural|callback_is_unique_es[mobile_number]',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'is_natural' => 'Only numbers are allowed',
						'is_unique' => 'This %s already exists'
				)
		);

		// Role

		$this->form_validation->set_rules(

				'role', 'Role',
				'trim|required',
				array(
						'required' => '%s is required',
				)
		);

		// Image

		$this->form_validation->set_rules(

				'image', 'Image',
				'trim'
		);

		// Password

		$this->form_validation->set_rules(

				'password', 'Password',
				'trim|required|min_length[6]|max_length[50]|alpha_dash',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_dash' => 'Only alpha-numeric characters, underscores and hypens are allowed'
				)
		);

		// Confirm Password

		$this->form_validation->set_rules(

				'confirm_password', 'Confirm Password',
				'trim|required|min_length[6]|max_length[50]|alpha_dash|matches[password]',
				array(
						'required' => '%s is required',
						'is_unique' => 'This %s already exists',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_dash' => 'Only alpha-numeric characters, underscores and hypens are allowed'
				)
		);

		if ($this->form_validation->run() === FALSE) // Validation fails
		{
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */

			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'add_user') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else // if not an ajax call
			{
				$data['roles'] = $this->role_model->get_user_roles();
				$this->layouts->view('templates/admin/add_user', $data);
			}
		} else // Validation Passed
		{
			// Passing 1: image name, 2: image upload path and 3: file name attribute value
			$image = $this->save_photo($_FILES['image']['name'], USER_IMAGE_UPLOAD_PATH, 'image');

			if(!empty($image))
			{
				$image_thumb = USER_IMAGE_UPLOAD_PATH . '/THUMB_' . $image;
				$profile_image = USER_IMAGE_UPLOAD_PATH . '/PROFILE_IMAGE_' . $image;
				$image_dest = USER_IMAGE_UPLOAD_PATH . '/IMAGE_' . $image;

				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $image_thumb, 30);
				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $profile_image, 320);
				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $image_dest, 1024);

				$image_thumb_name = 'THUMB_' . $image;
				$profile_image_name = 'PROFILE_IMAGE_' . $image;
				$image_name = 'IMAGE_' . $image;
				unlink(USER_IMAGE_UPLOAD_PATH . DS . $image);
			}
			else
			{
				$image_name = '';
				$image_thumb_name = '';
				$profile_image_name = '';
			}

			if ($this->user_model->insert_user($image, $profile_image_name, $image_thumb_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'User ' . ucfirst($this->input->post('first_name')) . ' ' . ucfirst($this->input->post('middle_name')) . ' ' . ucfirst($this->input->post('last_name')) . ' has been successfully added!');
				unset($_POST);
				unset($_FILES);
				echo json_encode(array('success' => 'User inserted'));
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

		if ($this->input->is_ajax_request()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'edit_user') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// First Name

		$this->form_validation->set_rules(

				'first_name', 'First Name',
				'trim|required|min_length[2]|max_length[50]|alpha',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// Middle Name

		$this->form_validation->set_rules(

				'middle_name', 'Middle Name',
				'trim|min_length[1]|max_length[50]|alpha',
				array(
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// Last Name

		$this->form_validation->set_rules(

				'last_name', 'Last Name',
				'trim|required|min_length[2]|max_length[50]|alpha',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha' => 'Only alphabets are allowed'
				)
		);

		// User Name

		$this->form_validation->set_rules(

				'user_name', 'User Name',
				'trim|required|min_length[2]|max_length[50]|alpha_numeric|callback_edit_unique_es[user_name.' . $id . ']',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_numeric' => 'Only alpha_numeric is allowed'
				)
		);

		// Email

		$this->form_validation->set_rules(

				'email', 'Email',
				'trim|required|min_length[15]|max_length[70]|valid_email|callback_edit_unique_es[email.' . $id . ']',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'valid_email' => 'Please enter a valid email'
				)
		);

		// Mobile Number

		$this->form_validation->set_rules(

				'mobile_number', 'Mobile Number',
				'trim|required|min_length[11]|max_length[11]|is_natural|callback_edit_unique_es[mobile_number.' . $id . ']',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'is_natural' => 'Only numbers are allowed'
				)
		);

		// Role

		$this->form_validation->set_rules(

				'role', 'Role',
				'trim|required',
				array(
						'required' => '%s is required',
				)
		);

		// Image

		$this->form_validation->set_rules(

				'image', 'Image',
				'trim'
		);

		$data['roles'] = $this->role_model->get_user_roles();
		$record = $this->get_user_by_id_lookup($id, 'edit_user'); // TRUE to get all items for edit purpose not full_name etc
		$data['record'] = $record;

		if ($this->form_validation->run() === FALSE) // Validation fails
		{
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */
			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'edit_user') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else {
				$this->layouts->view('templates/admin/edit_user', $data);
			}
		} else // Validation Passed
		{
			$image = $_FILES['image']['name'];
			if(!empty($image))
			{
//				$this->delete_picture($id, 'user', USER_IMAGE_UPLOAD_PATH, $record);
				$this->delete_picture(USER_IMAGE_UPLOAD_PATH, $record);

				// Passing 1: image name, 2: image upload path and 3: file name attribute value
				$image = $this->save_photo($image, USER_IMAGE_UPLOAD_PATH, 'image');

				$image_thumb = USER_IMAGE_UPLOAD_PATH . '/THUMB_' . $image;
				$profile_image = USER_IMAGE_UPLOAD_PATH . '/PROFILE_IMAGE_' . $image;
				$image_dest = USER_IMAGE_UPLOAD_PATH . '/IMAGE_' . $image;

				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $image_thumb, 30);
				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $profile_image, 320);
				$this->make_thumb(USER_IMAGE_UPLOAD_PATH . '/' . $image, $image_dest, 1024);

				$image_thumb_name = 'THUMB_' . $image;
				$profile_image_name = 'PROFILE_IMAGE_' . $image;
				$image_name = 'IMAGE_' . $image;

				unlink(USER_IMAGE_UPLOAD_PATH . DS . $image);

			}
			else
			{
				$record = current($record);
				$image_name = custom_echo($record, 'image', 'no_case_change');
				$profile_image_name = custom_echo($record, 'profile_image', 'no_case_change');
				$image_thumb_name = custom_echo($record, 'thumbnail', 'no_case_change');
			}

			$source = '"_source": {
			"includes": [ "joined_date"]
			},';

			$user = $this->get_user_by_id_lookup($id, 'only_joined_date', $source);

			$joined_date = custom_echo($user[0], 'joined_date');

			if($this->user_model->update_user($id, $image_name, $image_thumb_name, $profile_image_name, $joined_date))
			{
				$this->session->set_flashdata('success_message', 'User ' . ucfirst($this->input->post('first_name')) . ' ' .
						(!empty($this->input->post('middle_name')) ? ' ' . ucfirst($this->input->post('middle_name')) : '')
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

	/**
	 * [change_password: This method can work with both ajax call and
	 * normal PHP method call. Validates user data. If data is valid
	 * then, it allows access to its insert user model function. Otherwise,
	 * It gives appropriate error messages. After data is successfully
	 * inserted then, it gives a success flash message]
	 */
	public function change_password_lookup()
	{
		$this->layouts->set_title('Change Password');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if ($this->input->is_ajax_request()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'change_password') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

		// Current Password

		$this->form_validation->set_rules(

				'current_password', 'Current Password',
				'trim|required|min_length[6]|max_length[50]|alpha_dash|callback_current_password_match[user.password]',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_dash' => 'Only alpha-numeric characters, underscores and hypens are allowed'
				)
		);

		// New Password

		$this->form_validation->set_rules(

				'new_password', 'New Password',
				'trim|required|min_length[6]|max_length[50]|alpha_dash',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_dash' => 'Only alpha-numeric characters, underscores and hypens are allowed'
				)
		);

		// Confirm New Password

		$this->form_validation->set_rules(

				'confirm_new_password', 'Confirm New Password',
				'trim|required|min_length[6]|max_length[50]|alpha_dash|matches[new_password]',
				array(
						'required' => '%s is required',
						'is_unique' => 'This %s already exists',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars',
						'alpha_dash' => 'Only alpha-numeric characters, underscores and hypens are allowed'
				)
		);

		if ($this->form_validation->run() === FALSE) // Validation fails
		{
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */

			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'change_password') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else // if not an ajax call
			{
				$this->layouts->view('templates/admin/change_password');
			}
		} else // Validation Passed
		{
			$current_user_id = array_column($this->session->userdata['admin_record'], 'id')[0];

			if ($this->user_model->change_password($current_user_id)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Password has been successfully changed!');
				unset($_POST);
				unset($_FILES);
				echo json_encode(array('success' => 'Password Changed'));
			}
		}
	}

	/**
	 * [is_unique_es It's a callback function that is called in edit_user_lookup
	 * validation it checks if same attribute data exists other than the current
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [User entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. user.email.3]
	 * @return [type]  [if same data exists other than current record then, returns
	 * FALSE. If it doesn't exists other than current record then, returns TRUE.]
	 */
	public function is_unique_es($value, $field)
	{
		$this->form_validation->set_message('is_unique_es',
				'The %s is not available');

		$query = '{
		  "query": {
			"bool" : {
			  "must" : {
				"term" : { "' . $field . '" : "' . strtolower($value) . '" }
			  }
			}
		  }
		}';

		$user = $this->elasticsearch->advancedquery('users', 'user', $query);

		return !empty($user['hits']['hits']) ? FALSE : TRUE;
	}

	/**
	 * [edit_unique_es It's a callback function that is called in edit_user_lookup
	 * validation it checks if same attribute data exists other than the current
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [User entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. user.email.3]
	 * @return [type]  [if same data exists other than current record then, returns
	 * FALSE. If it doesn't exists other than current record then, returns TRUE.]
	 */
	public function edit_unique_es($value, $params)
	{
		list($field, $id) = explode(".", $params, 2);

		$this->form_validation->set_message('edit_unique_es',
				'The %s is not available');

		$query = '{
		  "query": {
			"bool" : {
			  "must" : {
				"term" : { "' . $field . '" : "' . strtolower($value) . '" }
			  },
			  "must_not" : {
				"term" : { "id" : ' . $id . '}
			  }
			}
		  }
		}';

		$user = $this->elasticsearch->advancedquery('users', 'user', $query);

		return !empty($user['hits']['hits']) ? FALSE : TRUE;
	}

	/**
	 * [current_password_match It's a callback function that is called in current_password_match
	 * validation it checks if current password entered by the user is same as DB password then returns TRUE
	 * else returns FALSE]
	 * @param  [string] $value  [Current Passoword value e.g. 123456]
	 * @param  [string] $params [table.attribute.id e.g. user.password.3]
	 * @return [type]  [boolen]
	 */
	public function current_password_match($form_password, $params)
	{
		$current_user_id = array_column($this->session->userdata['admin_record'], 'id')[0];

		$this->form_validation->set_message('current_password_match',
				'%s is is wrong');
		list($table, $field) = explode(".", $params);

		$query = $this->db->select($field . ', salt')->from($table)
				->where('id =', $current_user_id)->limit(1)->get();

		$result = $query->result_array()[0];

		$db_password = $result['password'];
		$db_salt = $result['salt'];

		$form_password = hash('sha512', $form_password . $db_salt);

		if ($db_password === $form_password) {
			return TRUE;
		}
		return FALSE;
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

	public function get_user_by_id_lookup($id, $method = 'get_user', $source = NULL)
	{

		if($method == 'edit_user' && is_null($source)) {
			$source = '"_source": {
				"includes": [ "id", "first_name", "middle_name", "last_name", "user_name", "email", "mobile_number", "image", "profile_image", "thumbnail", "role_id"]
				},';
		}
		if($method != 'edit_user' && is_null($source)){
			$source = '"_source": {
				"includes": [ "id", "full_name", "user_name", "email", "mobile_number", "profile_image", "role", "joined_date", "updated_date"]
				},';
		}

		$query = '
		{ ' .

			$source

		. '"query": {
				"query_string":{
					"query": '. $id .',
					"fields": ["id"]
				}
			}
		}';

		$user = $this->elasticsearch->advancedquery('users', 'user', $query);

		$user = $user['hits']['hits'];

		return $user;
	}

	public function user_full_name_autocomplete()
	{
		$full_name = trim(strtolower($this->input->post('full_name')));
		$source = '"_source": {
				"includes": [ "full_name"]
				},';

		$query = '
		{ ' .

			$source

		. '
			"from" : 0, "size" : 6,
				"query": {
					"query_string":{
						"query": '. '"*'.$full_name.'*"' .',
						"fields": ["full_name"],
						"default_operator": "AND"
					}
				}
			}';

		$users = $this->elasticsearch->advancedquery('users', 'user', $query);

		$users = $users['hits']['hits'];

		$full_names = [];

		if(!empty($users)) {
			foreach($users as $user) {
				$full_names[] = custom_echo($user, 'full_name');
			}
		} else {
			$full_names[] = 'No Results Found';
		}

		echo json_encode($full_names);
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
		$source = '"_source": {
			"includes": ["profile_image", "thumbnail", "image"]
			},';

		$record = $this->get_user_by_id_lookup($id, 'edit_user', $source);

		$this->delete_picture(USER_IMAGE_UPLOAD_PATH, $record);

		if ($this->user_model->delete_user($id)) {
			$this->elasticsearch->delete('users', 'user', $id);
			$this->elasticsearch->refresh_index_type('users', 'user', $id);
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
			redirect('/admin/user/');
		}
	}

	public function indice_user_elastic_search_lookup() {
		$time_taken_sec = $this->insert_user_bulk_elasticsearch_lookup(FALSE);
		$this->elasticsearch->refresh_index_type('users');
		$this->session->set_flashdata('success_message', 'User Indexing is successfully completed');
		sleep($time_taken_sec/1000); // millseconds
		redirect('admin/user');
	}

	/**
	 * [insert_user_bulk_elasticsearch_lookup This function will insert bulk of user data
	 * from database to elasticsearch
	 */
	public function insert_user_bulk_elasticsearch_lookup($return_response = TRUE)
	{
		$result = $this->user_model->insert_user_bulk_elasticsearch();

		$result = json_encode($result);

		$result = str_replace(",{", "\n\r{", $result);

		file_put_contents(JSON_FILE_PATH . '/results.json', $result);
		$json_data = file_get_contents(JSON_FILE_PATH . "/results.json");
		$json_data = str_replace(array('[', ']'), '', $json_data);
		$json_data .= "\n\r";

		unlink(JSON_FILE_PATH . '/results.json');

		$this->elasticsearch->delete_index("users");

		$this->create_user_mapping();

		$response = $this->elasticsearch->insert_bulk("user", 'POST', $json_data);

		if(empty($response['errors'])) {
			echo json_encode($response);
		}
		else {
			return $response['took'];
		}
	}

	public function create_user_mapping() {

		$this->load->library('user_mapping');
		$mapping = $this->user_mapping->create_user_mapping();
		$this->elasticsearch->create('users', $mapping);
	}

	public function search_user_lookup() {

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = 4;
		$config["uri_segment"] = 4;

		$users = $this->fetch_users_by_elasticsearch($config["per_page"], $current_page);

		$config["total_rows"] = !empty($users['total']) ? $users['total'] : 0;

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config["cur_tag_open"] = "<li class='active'><a href='#'>";
		$config["cur_tag_close"] = "</a></li>";

//		$config['cur_tag_open'] = "<li><span><b>";
//		$config['cur_tag_close'] = "</b></span></li>";

		$this->pagination->initialize($config);

		$data["links"] = $this->pagination->create_links();

		$data["users"] = !empty($users['hits']) ? $users['hits'] : array();

		$this->load->view('templates/admin/search_users', $data);
	}

	public function fetch_users_by_elasticsearch($per_page = 8, $current_page = 0)
	{
		$role_id = isset($_POST['role_id']) && is_numeric($_POST['role_id']);
		$full_name = isset($_POST['full_name']) && !empty($_POST['full_name']);
		$role_ac_id = $this->input->post('role_id');
		$full_name_ac = strtolower($this->input->post('full_name'));

		if($role_id) {
			$query_string = '"query": '. $role_ac_id .',
						"fields": ["role_id"]';
		}

		if($full_name) {
			$query_string = '"query": "*'. $full_name_ac . '*",
						"fields": ["full_name"]';
		}

		if($role_id && $full_name) {
			$query_string =	'"bool": {
				"must" : [
			  	{
				  "term": {
				  "role_id": ' . $role_ac_id . '
			   	}
			 },
			 {
				"query_string":{
				"query": '. '"*'.$full_name_ac.'*"' .',
					"fields": ["full_name"]
				}
			}
		   ]
		 }';

		}


		$query = '
				{
					"from" : ' . $current_page . ', "size" : ' . $per_page;

		if($role_id && $full_name) {
			$query .= ',"query": {
				' . $query_string .  '
				}';
		}else {

			if($role_id || $full_name) {
				$query .= ',"query": {
				"query_string":{
					' . $query_string . '
					}
				}';
			}
		}


		$query .= '}';

		$users = $this->elasticsearch->advancedquery('users', 'user', $query);

		$users = !empty($users['hits']) ? $users['hits'] : array();

		return $users;

	}
}