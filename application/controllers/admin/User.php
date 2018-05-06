<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* User class is a controller class it has all methods for basic operation
* of user i.e. CRUD lookups, get bootstrap modals, pagination etc for user  
* Methods: index
* 		   add_user_lookup
* 		   edit_user_lookup
* 		   get_modal
* 		   is_unique
* 		   get_user_by_id_lookup
* 		   delete_user_by_id_lookup
*/

class User extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('layouts');
		$this->load->model('admin/user_model');
		$this->load->model('admin/role_model');
	}

	public function index()
	{
		$this->unset_user_search_filter();
		if($this->input->is_ajax_request()) {
			$this->search_user_lookup();
		}

		$this->layouts->set_title('Users');

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = USERS_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$data["users"] = $this->fetch_users_lookup($config["per_page"], $current_page);
		$config["total_rows"] = $this->user_model->record_count();

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
		$this->unset_user_search_filter();

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
				'trim|required|min_length[2]|max_length[50]|alpha_numeric|is_unique[user.user_name]',
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
				'trim|required|max_length[70]|valid_email|is_unique[user.email]',
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
				'trim|required|min_length[11]|max_length[11]|is_natural|is_unique[user.mobile_number]',
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

			if ($this->user_model->insert_user($image_name, $profile_image_name, $image_thumb_name)) // insert into db
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
		$this->unset_user_search_filter();

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
				'trim|required|min_length[2]|max_length[50]|alpha_numeric|callback_edit_unique[user.user_name.'. $id .']',
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
				'trim|required|min_length[15]|max_length[70]|valid_email|callback_edit_unique[user.email.'. $id .']',
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
				'trim|required|min_length[11]|max_length[11]|is_natural|callback_edit_unique[user.mobile_number.'. $id .']',
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
		$record = $this->get_user_by_id_lookup($id); // TRUE to get all items for edit purpose not full_name etc
		$data['user'] = $record;
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
				$image_name = $record['image'];
				$profile_image_name = $record['profile_image'];
				$image_thumb_name = $record['thumbnail'];
			}

			$user = $this->get_user_by_id_lookup($id);

			if($this->user_model->update_user($id, $image_name, $image_thumb_name, $profile_image_name))
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
		$this->unset_user_search_filter();

		$data['user'] = $this->get_user_by_id_lookup($id, TRUE);
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
		$this->unset_user_search_filter();

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
	 * [current_password_match It's a callback function that is called in current_password_match
	 * validation it checks if current password entered by the user is same as DB password then returns TRUE
	 * else returns FALSE]
	 * @param  [string] $value  [Current Passoword value e.g. 123456]
	 * @param  [string] $params [table.attribute.id e.g. user.password.3]
	 * @return [type]  [boolen]
	 */
	public function current_password_match($form_password, $params)
	{
		$this->unset_user_search_filter();

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

	public function get_user_by_id_lookup($id, $return_role_name=FALSE)
	{
		$this->unset_user_search_filter();
        $user = $this->user_model->get_user_by_id_lookup($id, $return_role_name);
		return $user;
	}

	public function user_full_name_autocomplete()
	{
		$full_name = trim(strtolower($this->input->post('full_name')));

		$users = $this->user_model->user_full_name_autocomplete($full_name);

		if(!empty($users)) {
			foreach($users as $user) {
				$full_names[] = ucwords($user['full_name']);
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
		$this->unset_user_search_filter();

		$record = $this->get_user_by_id_lookup($id);

		$this->delete_picture(USER_IMAGE_UPLOAD_PATH, $record);

		if ($this->user_model->delete_user($id)) {
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

	public function search_user_lookup() {

		$this->get_user_search_filter();

		$this->set_user_search_filter();

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = USERS_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$data["users"] = $this->fetch_users_lookup($config["per_page"], $current_page);

		$config["total_rows"] = $this->users_count();

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

		$this->pagination->initialize($config);

		$data["links"] = $this->pagination->create_links();

		$this->load->view('templates/admin/search_users', $data);
	}

	/**
	 * @param int $per_page
	 * @param int $current_page
	 * @return mixed Users JSON Object
	 *
	 */
	public function fetch_users_lookup($per_page, $current_page)
	{
		$role_id = isset($_POST['role_id']) && is_numeric($_POST['role_id']);
		$full_name = isset($_POST['full_name']) && !empty($_POST['full_name']);
		$role_ac_id = $this->input->post('role_id');
		$full_name_ac = strtolower($this->input->post('full_name'));

		if($role_id) {
			$users = $this->user_model->fetch_users(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'role_id' => $role_ac_id
				)
			);
		}

		if($full_name) {
			$users = $this->user_model->fetch_users(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'full_name' => $full_name_ac
				)
			);
		}

		if($role_id && $full_name) {
			$users = $this->user_model->fetch_users(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'role_id' => $role_ac_id,
				'full_name' => $full_name_ac
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$users = $this->user_model->fetch_users(array(
				'per_page' => $per_page,
				'current_page' => $current_page
				)
			);
		}

		if(isset($users)) return $users;
	}

	/**
	 * @return mixed Users count
	 *
	 */
	public function users_count()
	{
		$role_id = isset($_POST['role_id']) && is_numeric($_POST['role_id']);
		$full_name = isset($_POST['full_name']) && !empty($_POST['full_name']);
		$role_ac_id = $this->input->post('role_id');
		$full_name_ac = strtolower($this->input->post('full_name'));

		if($role_id) {
			$users = $this->user_model->record_count(array(
				'role_id' => $role_ac_id
				)
			);
		}

		if($full_name) {
			$users = $this->user_model->record_count(array(
				'full_name' => $full_name_ac
				)
			);
		}

		if($role_id && $full_name) {
			$users = $this->user_model->record_count(array(
				'role_id' => $role_ac_id,
				'full_name' => $full_name_ac
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$users = $this->user_model->record_count();
		}

		if(isset($users)) {
			return $users;
		}
	}

	public function set_user_search_filter() {
		$role_id = trim($this->input->post('role_id'));
		$full_name = trim($this->input->post('full_name'));
		if(!empty($role_id) || !empty($full_name)) {
			$this->session->set_userdata(array(
					'role_id' 	=> $this->input->post('role_id'),
					'full_name' => $this->input->post('full_name')
				)
			);
		}
	}

	public function get_user_search_filter() {

		$session_full_name = $this->session->userdata('full_name');
		$full_name = $this->input->post('full_name');
		$session_role_id = $this->session->userdata('role_id');
		$role_id = $this->input->post('role_id');

		if(empty($full_name)) {
			$_POST['full_name'] = $session_full_name;
		}

		if(empty($role_id)) {
			$_POST['role_id'] = $session_role_id;
		}
	}

	public function unset_user_search_filter() {
		$this->session->unset_userdata(array(
				'role_id', 'full_name'
			)
		);
	}
}