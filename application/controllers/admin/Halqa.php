<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Halqa class is a controller class it has all methods for basic operation
* of halqa i.e. CRUD lookups, get bootstrap modals, pagination etc for halqa  
* Methods: index
* 		   add_halqa_lookup
* 		   edit_halqa_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_halqa_by_id_lookup
* 		   delete_halqa_by_id_lookup
*/

class Halqa extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/halqa_model');
		$this->load->model('admin/halqa_type_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] halqas from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/halqa/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/halqa
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/halqa/<method_name>
	 *	- or -
	 * /admin/halqa/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Halqa\'s'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->halqa_model->record_count();
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

        $data["halqas"] = $this->halqa_model->fetch_halqas($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/halqas', $data);
	}

	/**
	 * [add_halqa_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_halqa_lookup()
	{	
		$this->layouts->set_title('Add Halqa'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'add_halqa')
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
    		'trim|required|min_length[4]|is_unique[halqa.name]|max_length[15]|callback__alpha_numeric_hypen',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
            	'alpha_numeric' => 'Only alpha_numeric characters are allowed',
            	'is_unique'		=> '%s already exists'
    		)
	    );
	
		// Halqa Type

	    $this->form_validation->set_rules(

    		'type', 'Type', 
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
					if($key == 'add_halqa')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['types'] = $this->halqa_type_model->get_halqa_types();
				$this->layouts->view('templates/admin/add_halqa', $data);
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->halqa_model->insert_halqa()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'halqa inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_halqa_lookup: Edits a halqa by id, Validates halqa data. If data is valid then, 
	 * it allows access to its edit halqa model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_halqa_lookup($id)
	{		
		$this->layouts->set_title('Edit halqa'); 
			    	    
		/**
		* if its an ajax call then, set post data so
		* post data will be available for validation.
		*/
	
		if($this->input->is_ajax_request())
		{
			$data = array();
			foreach ($_POST as $key => $value) 
			{
				if($key == 'edit_halqa')
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
    		'trim|required|min_length[3]|callback__alpha_space|max_length[50]|callback_edit_unique[user_halqa.name.'. $id .']',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars',
	        	'alpha'     	=> 'Only alphabets are allowed'
    		)
	    );

		$this->form_validation->set_message('_alpha_space', 'Only alphabets and spaces are allowed'); 

		$data['halqa'] = $this->get_halqa_by_id_lookup($id);

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
					if($key == 'edit_halqa')
					{
						continue;
					}

		    		$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

		    	echo json_encode($errors);
			}
			else
			{
				$this->layouts->view('templates/admin/edit_halqa', $data);
			}
	    }	    
	    else // Validation Passed
	    {		    	
			if($this->halqa_model->update_halqa($id))
			{
				$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) .
				' has been successfully updated!');
		    	echo json_encode(array('success' => 'halqa Updated'));
	    	}
	    }		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [halqa id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$data['halqa'] = $this->get_halqa_by_id_lookup($id);	
		
		$this->load->view('templates/admin/halqa_modal', $data);		
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_halqa_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [halqa entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. halqa.email.3]
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
	 * [_alpha_numeric_hypen: It's a callback function that is called in add_halqa_lookup
	 * validation it checks if $halqa_name has "a-z0-9-" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$halqa_name entered value e.g. NA-60
	 * @return [type]  [If $halqa_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _alpha_numeric_hypen($halqa_name)
	{
		if (preg_match("/^[a-z0-9\- ]+$/i", $halqa_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [halqa_lookup: This function will retrieve all halqas from database without
	 * pagination]
	 * @return [array] [All halqa records]
	 */
	public function halqa_lookup()
	{
		$halqas = $this->halqa_model->get_halqas();
		return $halqas;
	}	

	/**
	 * [get_halqa_by_id_lookup This function will retrieve a specific halqa from database
	 * by its $id]
	 * @param  [type]  $id   [halqa id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific halqa record who's $id is passed]
	 */
	public function get_halqa_by_id_lookup($id)
	{
		$halqa = $this->halqa_model->get_halqa_by_id($id);
		return $halqa;
	}

	/**
	 * [get_halqa_by_id_lookup This function will retrieve a specific halqa from database
	 * by its $id]
	 * @param  [type]  $id   [halqa id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific halqa record who's $id is passed]
	 */
	public function get_halqas_by_type_lookup($id)
	{
		$data['halqas'] = $this->halqa_model->get_halqas_by_type_id($id);
		$this->load->view('templates/front_end/prov_halqa_dropdown', $data);
	}

	/**
	 * [delete_halqa_by_id_lookup This function will delete a specific halqa from database
	 * by its $id and halqa picture from assets/admin/images/halqas folder and then, redirects
	 * halqa to the halqas page]
	 * @param  [type] $id [halqa id whom record is to be deleted from database and picture 
	 * from assets/admin/images/halqas folder]
	 */
	public function delete_halqa_by_id_lookup($id)
	{
		$this->delete_picture($id, 'admin/halqa_model', 'get_halqa_by_id', halqa_IMAGE_UPLOAD_PATH);

		if ($this->halqa_model->delete_halqa($id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/halqa/');
		}
	}	

	/**
	 * [add_na_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_na_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('NA');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 272; $i++) 
        { 
            $halqa = "na-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}

	/**
	 * [add_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_pp_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('PP');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 297; $i++) 
        { 
            $halqa = "pp-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}	

	/**
	 * [add_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_ps_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('PS');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 130; $i++) 
        { 
            $halqa = "ps-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}

	/**
	 * [add_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_pk_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('PK');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 99; $i++) 
        { 
            $halqa = "pk-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}
	/**
	 * [add_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_la_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('LA');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 41; $i++) 
        { 
            $halqa = "la-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}	
	/**
	 * [add_halqa_bulk_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates halqa data. If data is valid 
	 * then, it allows access to its insert halqa model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_gbla_halqa_bulk_lookup()
	{	
		$id = $this->halqa_type_model->get_id_by_key('GBLA');
            
        $id = $id['id'];

        $na_halqa = array();
        
        for ($i = 0; $i < 24; $i++) 
        { 
            $halqa = "gbla-" . ($i + 1);
            $na_halqa[$i]['name'] = $halqa;
            $na_halqa[$i]['type'] = $id;
        }
	
		if($this->halqa_model->add_na_halqa_bulk($na_halqa)) // insert into db
		{
			$this->session->set_flashdata('success_message', 'halqa ' . ucfirst($this->input->post('name')) . ' has been successfully added!');
			unset($_POST);
	    	echo json_encode(array('success' => 'halqa inserted'));
    	}
	}	
}

