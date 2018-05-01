<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://localhost/dailyshop/
	 *	- or -
	 *		http://localhost/dailyshop/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://localhost/dailyshop/
	 *-
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 */	

	public function __construct()
	{
		parent::__construct();
	
		$this->load->model('front_end/login_model', 'login_model');
	}
/*
	public function index()
	{
		if(!isset($this->session->userdata['logged_in']))
		{
			$this->load->view('templates/admin/login');
		}
		else
		{
			redirect('/admin/dashboard/');
		}
	}*/

	public function login_lookup()
	{
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
	    		$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
		
			$this->form_validation->set_data($data);
		}

		// Email

	    $this->form_validation->set_rules(

    		'login_email', 'Email', 
    		'trim|required|valid_email',
        	array(
            	'required'      => '%s is required',
            	'valid_email'      => 'Please enter a valid email'
    		)
	    );

		// Password

	    $this->form_validation->set_rules(

    		'login_password', 'Password', 
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
		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
	    }
	    else
	    {
			$user = $this->login_model->login();
			if ($user) 
			{          
				$user['logged_in'] = TRUE;
				$user['success'] = 'Login successful';
				$this->session->set_userdata($user);
		    	echo json_encode($user);
			}
			else
			{
		    	echo json_encode(array('failure' => 'Please enter your valid credentials!'));				
			}
	    }
	}

	public function logout_lookup()
	{		
		$domain = 'localhost';
		$this->session->sess_destroy();
		delete_cookie('isLoggedIn', $domain, '/'); 
		delete_cookie('user', $domain, '/'); 
		redirect('front_end/home');
	}
}
