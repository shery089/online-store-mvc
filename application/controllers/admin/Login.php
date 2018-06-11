<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://localhost/ims/
	 *	- or -
	 *		http://localhost/ims/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://localhost/ims/
	 *-
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 */	

	public function __construct()
	{
		parent::__construct();
	
		$this->load->model('admin/login_model');
	}

	public function index()
	{
		if(!isset($this->session->userdata['logged_in']))
		{
			$this->load->view('templates/admin/login');
		}
		else
		{
			redirect('/admin/category/');
		}
	}

	public function login_lookup()
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
				if($key == 'login')
				{
					continue;
				}

	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}
		
		// Email

	    $this->form_validation->set_rules(

    		'email', 'Email', 
    		'trim|required|callback__email_validation[email]',
        	array(
            	'required'      => '%s is required'
    		)
	    );

        $field = is_numeric(trim($this->input->post('email'), " '\"")) ? 'Mobile Number' : 'Email';

	    $this->form_validation->set_message('_email_validation', $field . ' doesn\'t exists');


		// Password

	    $this->form_validation->set_rules(

    		'password', 'Password', 
    		'trim|required|callback__password_validation[password]',
        	array(
            	'required'      => '%s is required'
    		)
	    );

	    $this->form_validation->set_message('_password_validation', '%s is incorrect');

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
					if($key == 'login')
					{	
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/login', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
			$admin_record = $this->login_model->login();

			if (is_array($admin_record)) 
			{          
				$newdata = array(
			  
				    'admin_record'  => $admin_record,
				    'logged_in' => TRUE
				);

				$this->session->set_userdata($newdata);
				echo json_encode(array('success' => 'login successful'));
			}
			else
			{
				echo json_encode(array('failure' => 'Password is incorrect!'));
			}
		}
	}

	public function logout_lookup()
	{		
		if(!isset($this->session->userdata['logged_in']))
		{
			redirect('/admin/login/');
		}
		else
		{
			$this->session->sess_destroy();
			redirect('/admin/login/');
		}
	}

	function _email_validation($key)
	{
		$admin_record = $this->login_model->login();
		$type =	gettype($admin_record);
		// print_r($admin_record);
		// print_r($type);
		if($type == 'array')
		{
			return TRUE;
		}
		else
		{
			if (strpos($admin_record, 'password') !== false) 
			{
				return TRUE;
			}
		}
			return FALSE;
	}

	function _password_validation($key)
	{
		$admin_record = $this->login_model->login();
		$type =	gettype($admin_record);
		// print_r($admin_record);
		if($type == 'array')
		{
			return TRUE;
		}
		else
		{
			if (strpos($admin_record, 'password') !== false) 
			{
				return TRUE;
			}
		}
			return FALSE;
	}
}