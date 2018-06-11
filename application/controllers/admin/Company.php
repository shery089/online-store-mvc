<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('layouts');
		$this->load->model('admin/company_model');
	}

	public function index()
	{
		$this->unset_company_search_filter();
		if($this->input->is_ajax_request()) {
			$this->search_company_lookup();
		}

		$this->layouts->set_title('Companies');

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = COMPANIES_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$data["companies"] = $this->fetch_companies_lookup($config["per_page"], $current_page);
		$config["total_rows"] = $this->company_model->record_count();

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

		if(!$this->input->is_ajax_request()) {
			$this->layouts->view('templates/admin/companies', $data);
		}
	}

	/**
	 * [add_company_lookup: This method can work with both ajax call and
	 * normal PHP method call. Validates Company data. If data is valid
	 * then, it allows access to its insert Company model function. Otherwise,
	 * It gives appropriate error messages. After data is successfully
	 * inserted then, it gives a success flash message]
	 */
	public function add_company_lookup()
	{
		$this->unset_company_search_filter();

		$this->layouts->set_title('Add Company');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if ($this->input->is_ajax_request()) {
		    $data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'add_company') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
			$this->form_validation->set_data($data);
		}

        // Company Name
		$this->form_validation->set_rules(

                'name', 'Name',
				'trim|required|min_length[2]|max_length[255]|is_unique[company.name]',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars'
				)
		);

		// Email
		$this->form_validation->set_rules(

				'email', 'Email',
				'trim|required|max_length[255]|valid_email|is_unique[company.email]',
				array(
						'required' => '%s is required',
						'max_length' => '%s should be at most %s chars',
						'valid_email' => 'Please enter a valid email',
						'is_unique' => 'This %s already exists'
				)
		);

		// Phone Number

		$this->form_validation->set_rules(

				'phone_number', 'Phone Number',
				'trim|required|min_length[11]|max_length[255]',
				array(
						'required' => '%s is required',
						'min_length' => '%s should be at least %s chars',
						'max_length' => '%s should be at most %s chars'
				)
		);

        // Description
        $this->form_validation->set_rules(

            'description', 'Description',
            'trim|min_length[10]|max_length[5000]',
            array(
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Website
        $this->form_validation->set_rules(

            'website', 'Website',
            'trim|min_length[10]|max_length[5000]|valid_url',
            array(
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars',
                'valid_url' => 'Please enter a valid URL'
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

			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'add_company') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}
				echo json_encode($errors);
			} else // if not an ajax call
			{
				$this->layouts->view('templates/admin/add_company');
			}
		} else // Validation Passed
		{
			// Passing 1: image name, 2: image upload path and 3: file name attribute value
			$image = $this->save_photo($_FILES['image']['name'], COMPANY_IMAGE_PATH, 'image');

			if(!empty($image))
			{
				$image_thumb = COMPANY_IMAGE_PATH . '/THUMB_' . $image;
				$profile_image = COMPANY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
				$image_dest = COMPANY_IMAGE_PATH . '/IMAGE_' . $image;
				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $profile_image, 320);
				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $image_dest, 1024);

				$image_thumb_name = 'THUMB_' . $image;
				$profile_image_name = 'PROFILE_IMAGE_' . $image;
				$image_name = 'IMAGE_' . $image;
				unlink(COMPANY_IMAGE_PATH . DS . $image);
			}
			else
			{
				$image_name = '';
				$image_thumb_name = '';
				$profile_image_name = '';
			}

			if ($this->company_model->insert_company($image_name, $profile_image_name, $image_thumb_name)) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Company <strong>' . ucfirst($this->input->post('first_name')) . ' ' . ucfirst($this->input->post('middle_name')) . ' ' . ucfirst($this->input->post('last_name')) . '</strong> has been successfully added!');
				unset($_POST);
				unset($_FILES);
				echo json_encode(array('success' => 'Company inserted'));
			}
		}
	}

	/**
	 * [edit_company_lookup: Edits a Company by id, Validates Company data. If data is valid then,
	 * it allows access to its edit Company model function. Otherwise, It gives appropriate
	 * error messages. After data is successfully edited then, it gives a success flash
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_company_lookup($id)
	{
		$this->unset_company_search_filter();

		$this->layouts->set_title('Edit Company');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if ($this->input->is_ajax_request()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'edit_company') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

        // Company Name
        $this->form_validation->set_rules(

            'name', 'Name',
            'trim|required|min_length[2]|max_length[255]|callback_edit_unique[company.name.'. $id .']',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Email
        $this->form_validation->set_rules(

            'email', 'Email',
            'trim|required|min_length[15]|max_length[70]|valid_email|callback_edit_unique[company.email.'. $id .']',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars',
                'valid_email' => 'Please enter a valid email'
            )
        );
        // Phone Number

        $this->form_validation->set_rules(

            'phone_number', 'Phone Number',
            'trim|required|min_length[11]|max_length[255]',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Description
        $this->form_validation->set_rules(

            'description', 'Description',
            'trim|min_length[10]|max_length[5000]',
            array(
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Website
        $this->form_validation->set_rules(

            'website', 'Website',
            'trim|min_length[10]|max_length[5000]|valid_url',
            array(
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars',
                'valid_url' => 'Please enter a valid URL'
            )
        );

        // Image

        $this->form_validation->set_rules(

            'image', 'Image',
            'trim'
        );

		$record = $this->get_company_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc
		$data['company'] = $record;
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
					if ($key == 'edit_company') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else {
				$this->layouts->view('templates/admin/edit_company', $data);
			}
		} else // Validation Passed
		{
			$image = $_FILES['image']['name'];
			if(!empty($image))
			{
				$this->delete_picture(COMPANY_IMAGE_PATH, $record);

				// Passing 1: image name, 2: image upload path and 3: file name attribute value
				$image = $this->save_photo($image, COMPANY_IMAGE_PATH, 'image');

				$image_thumb = COMPANY_IMAGE_PATH . '/THUMB_' . $image;
				$profile_image = COMPANY_IMAGE_PATH . '/PROFILE_IMAGE_' . $image;
				$image_dest = COMPANY_IMAGE_PATH . '/IMAGE_' . $image;

				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $image_thumb, 30);
				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $profile_image, 320);
				$this->make_thumb(COMPANY_IMAGE_PATH . '/' . $image, $image_dest, 1024);

				$image_thumb_name = 'THUMB_' . $image;
				$profile_image_name = 'PROFILE_IMAGE_' . $image;
				$image_name = 'IMAGE_' . $image;

				unlink(COMPANY_IMAGE_PATH . DS . $image);

			}
			else
			{
				$image_name = $record['image'];
				$profile_image_name = $record['profile_image'];
				$image_thumb_name = $record['thumbnail'];
			}

			if($this->company_model->update_company($id, $image_name, $profile_image_name, $image_thumb_name))
			{
				$this->session->set_flashdata('success_message', 'Company <strong>' . ucfirst($this->input->post('name')) . ' ' .
                    ' </strong> has been successfully updated!');
				echo json_encode(array('success' => 'Company Updated'));
			}
		}
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [Company id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{
		$this->unset_company_search_filter();

		$data['company'] = $this->get_company_by_id_lookup($id);
		$this->load->view('templates/admin/company_modal', $data);
	}

    /**
     * [edit_unique It's a callback function that is called in edit_company_lookup
     * validation it checks if same attribute data exists other than the current
     * current record than returns FALSE. If does not exists it returns TRUE]
     * @param  [string] $value  [Company entered value e.g. in case of email validation
     * sheryarahmed007@gmail.com]
     * @param  [string] $params [table.attribute.id e.g. Company.email.3]
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
	 * validation it checks if current password entered by the Company is same as DB password then returns TRUE
	 * else returns FALSE]
	 * @param  [string] $value  [Current Passoword value e.g. 123456]
	 * @param  [string] $params [table.attribute.id e.g. Company.password.3]
	 * @return [type]  [boolen]
	 */
	public function current_password_match($form_password, $params)
	{
		$this->unset_company_search_filter();

		$current_company_id = array_column($this->session->userdata['admin_record'], 'id')[0];

		$this->form_validation->set_message('current_password_match',
				'%s is is wrong');
		list($table, $field) = explode(".", $params);

		$query = $this->db->select($field . ', salt')->from($table)
				->where('id =', $current_company_id)->limit(1)->get();

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
	 * [Company_lookup This function will retrieve all Companies from database without
	 * pagination]
	 * @return [array] [All Company records]
	 */
	public function Company_lookup()
	{
		$companies = $this->company_model->get_companies();
		return $companies;
	}

	/**
	 * [get_company_by_id_lookup This function will retrieve a specific Company from database
	 * by its $id]
	 * @param  [type]  $id   [Company id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name
	 * instead of full_name]
	 * @return [array] [One specific Company record who's $id is passed]
	 */

	public function get_company_by_id_lookup($id, $edit=FALSE)
	{
		$this->unset_company_search_filter();
        $company = $this->company_model->get_company_by_id_lookup($id, $edit);
		return $company;
	}

	public function company_name_autocomplete()
	{
		$company_name = trim(strtolower($this->input->post('company_name')));

		$companies = $this->company_model->company_name_autocomplete($company_name);

        $company_names = array();

		if(!empty($companies)) {
			foreach($companies as $company) {
                $company_names[] = ucwords($company['name']);
			}
		} else {
            $company_names[] = 'No Results Found';
		}

		echo json_encode($company_names);
	}

	/**
	 * [delete_company_by_id_lookup This function will delete a specific Company from database
	 * by its $id and Company picture from assets/admin/images/companies folder and then, redirects
	 * Company to the Companies page]
	 * @param  [type] $id [Company id whom record is to be deleted from database and picture
	 * from assets/admin/images/companies folder]
	 */

	public function delete_company_by_id_lookup($id)
	{
		$this->unset_company_search_filter();

		$record = $this->get_company_by_id_lookup($id);

		$this->delete_picture(COMPANY_IMAGE_PATH, $record);

		if ($this->company_model->delete_company($id)) {
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
			redirect('/admin/company/');
		}
	}

	public function search_company_lookup() {

		$this->get_company_search_filter();

		$this->set_company_search_filter();

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

		$config['per_page'] = COMPANIES_PER_PAGE;
		$config["uri_segment"] = URI_SEGMENT;

		$data["companies"] = $this->fetch_companies_lookup($config["per_page"], $current_page);
		$config["total_rows"] = $this->companies_count();

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

		$this->load->view('templates/admin/search_companies', $data);
	}

	/**
	 * @param int $per_page
	 * @param int $current_page
	 * @return mixed Companies JSON Object
	 *
	 */
	public function fetch_companies_lookup($per_page, $current_page)
	{
		$company_name_ac = strtolower($this->input->post('company_name'));
		$company_email_ac = strtolower($this->input->post('company_email'));

        if(!empty($_POST['company_name']) && !empty($_POST['company_email'])) {
            $companies = $this->company_model->fetch_companies(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'name' => $company_name_ac,
                    'email' => $company_email_ac
                )
            );
        }

		else if(!empty($_POST['company_email'])) {
			$companies = $this->company_model->fetch_companies(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'email' => $company_email_ac
				)
			);
		}

		else if(!empty($_POST['company_name'])) {
			$companies = $this->company_model->fetch_companies(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'name' => $company_name_ac
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$companies = $this->company_model->fetch_companies(array(
				'per_page' => $per_page,
				'current_page' => $current_page
				)
			);
		}

		if(isset($companies)) return $companies;
	}

	/**
	 * @return mixed Companies count
	 *
	 */
	public function companies_count()
	{
		$company_email = isset($_POST['company_email']) && !empty($_POST['company_email']);
		$company_name = isset($_POST['company_name']) && !empty($_POST['company_name']);
		$company_email_ac_id = $this->input->post('company_email');
		$company_name_ac = strtolower($this->input->post('company_name'));

		if($company_email) {
			$companies = $this->company_model->record_count(array(
				'email' => $company_email_ac_id
				)
			);
		}

		if($company_name) {
			$companies = $this->company_model->record_count(array(
				'name' => $company_name_ac
				)
			);
		}

		if($company_email && $company_name) {
			$companies = $this->company_model->record_count(array(
				'email' => $company_email_ac_id,
				'name' => $company_name_ac
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$companies = $this->company_model->record_count();
		}

		if(isset($companies)) {
			return $companies;
		}
	}

	public function set_company_search_filter() {
		$company_name = trim($this->input->post('company_name'));
		$company_email = trim($this->input->post('company_email'));
		if(!empty($company_name) || !empty($company_email)) {
			$this->session->set_userdata(array(
					'company_name' 	=> $this->input->post('company_name'),
					'company_email' => $this->input->post('company_email')
				)
			);
		}
	}

	public function get_company_search_filter() {

		$session_company_name = $this->session->userdata('company_name');
		$company_name = $this->input->post('company_name');
		$session_company_email = $this->session->userdata('company_email');
		$company_email = $this->input->post('company_email');

		if(empty($company_name)) {
			$_POST['company_name'] = $session_company_name;
		}

		if(empty($company_email)) {
			$_POST['company_email'] = $session_company_email;
		}
	}

	public function unset_company_search_filter() {
		$this->session->unset_userdata(array(
				'company_name', 'company_email'
			)
		);
	}
}