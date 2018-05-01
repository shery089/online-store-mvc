<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* post class is a controller class it has all methods for basic operation
* of post i.e. CRUD lookups, get bootstrap modals, pagination etc for post  
* Methods: index
* 		   add_column_lookup
* 		   edit_column_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_column_by_id_lookup
* 		   delete_column_by_id_lookup
*/

class Column extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/column_model');
		$this->load->model('admin/columnist_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] posts from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/post/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/post
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/post/<method_name>
	 *	- or -
	 * /admin/post/<method_name>
	 */

	public function index()
	{
		$this->layouts->set_title('Columns'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->column_model->record_count();
		$config['per_page'] = 6;
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

        $data["columns"] = $this->column_model->fetch_columns($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/columns', $data);
	}

	/**
	 * [add_column_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates post data. If data is valid 
	 * then, it allows access to its insert post model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_column_lookup()
	{	
		$this->layouts->set_title('Add Column'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();

			$data['columnist'] = $this->input->post('columnist');

			$data['title'] = $this->input->post('title');

			$data['column'] = $this->input->post('column');
			
			$data['column'] = strlen($data['column']) > 0 ? 'OKaa' : '';
		
			$this->form_validation->set_data($data);
		}

		// Column

	    $this->form_validation->set_rules(

    		'column', 'Column',
    		'trim|required|max_length[63206]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Columnist

	    $this->form_validation->set_rules(

    		'columnist', 'Columnist',
    		'required'
	    );

		// title

	    $this->form_validation->set_rules(

    		'title', 'Title',
    		'required|trim|max_length[63206]'
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

		    	$errors['column'] = (!empty(form_error('column')) ? form_error('column') : '');
		    	$errors['columnist'] = (!empty(form_error('columnist')) ? form_error('columnist') : '');
		    	$errors['title'] = (!empty(form_error('title')) ? form_error('title') : '');

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['columnists'] = $this->columnist_model->get_columnists_dropdown();
				$this->layouts->view('templates/admin/add_column', $data);
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->column_model->insert_column()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Column has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'Column inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_column_lookup: Edits a post by id, Validates post data. If data is valid then, 
	 * it allows access to its edit post model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_column_lookup($id)
	{		
		$this->layouts->set_title('Edit Column'); 
		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if($this->input->is_ajax_request())
		{
			$data = array();
			
			$data['columnist'] = $this->input->post('columnist');

			$data['column'] = $this->input->post('column');
			
			$data['column'] = strlen($data['column']) > 0 ? 'OKaa' : '';
		
			$this->form_validation->set_data($data);
		}

		// Column

	    $this->form_validation->set_rules(

    		'column', 'Column',
    		'trim|required|max_length[63206]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Columnist

	    $this->form_validation->set_rules(

    		'columnist', 'Columnist',
    		'required'
	    );

		$data['record'] = $this->get_column_by_id($id, TRUE); // TRUE to get all items for edit purpose not full_name etc
	    
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

		    	$errors['column'] = (!empty(form_error('column')) ? form_error('column') : '');
		    	$errors['columnist'] = (!empty(form_error('columnist')) ? form_error('columnist') : '');

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['columnists'] = $this->columnist_model->get_columnists_dropdown();
				$this->layouts->view('templates/admin/edit_column', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
			if($this->column_model->update_post($id))
			{
				$this->session->set_flashdata('success_message', 'post ' . ucfirst($this->input->post('name')) .
				' has been successfully updated!');
		    	echo json_encode(array('success' => 'post Updated'));
	    	}
	    }		
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function like_dislike_lookup()
	{	
		$count = $this->column_model->insert_like_dislike();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function get_likes_dislikes_lookup()
	{	
		$this->column_model->get_likes_dislikes();			
	}
	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [post id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		// $action = $this->input->post('action');
		// $action_parts = explode('_', $action);
		// $entity = (!empty($action_parts[2]) ? $action_parts[1] . '_' . $action_parts[2] : $action_parts[1]);  
		$data['column'] = $this->get_column_by_id_lookup($id);	
		$this->load->view('templates/admin/column_modal', $data);		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [post id whom record is to be loaded in bootstrap modal]
	 */
	public function set_column_feature_lookup()
	{	
		$details = $this->input->post('details');
		$details_parts = explode('_', $details);
		$id = $details_parts[0];
		$entity = $details_parts[2] == 'party' ? $details_parts[1] . '_' . $details_parts[2] : $details_parts[1];
		$featured = $details_parts[3] == 0 ? $details_parts[3] : $details_parts[2];
		$entity_id = !empty($details_parts[4]) ? $details_parts[4] : $details_parts[3];
		if($this->column_model->set_column_feature($id, $entity, $featured, $entity_id))
		{
			$this->session->set_flashdata('success_message', 'column has been successfully featured!');
			echo json_encode(array('success' => 'Successfully Featured'));
		}
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_column_lookup
	 * validation it checks if same attribute data exists other than the current 
	 * current record than returns FALSE. If does not exists it returns TRUE]
	 * @param  [string] $value  [post entered value e.g. in case of email validation
	 * sheryarahmed007@gmail.com]
	 * @param  [string] $params [table.attribute.id e.g. post.email.3]
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
	 * [_alpha_numeric_hypen: It's a callback function that is called in add_column_lookup
	 * validation it checks if $post_name has "a-z0-9-" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE]
	 * @param  [string] $value  [$post_name entered value e.g. NA-60
	 * @return [type]  [If $post_name has "a-z -()" case-insensitive then, returns TRUE.
	 * If it contains other characters then, returns FALSE.]
	 */
	public function _alpha_numeric_hypen($post_name)
	{
		if (preg_match("/^[a-z0-9\- ]+$/i", $post_name)) 
		{	    	
			return TRUE;
		}
		else
		{ 
			return FALSE;
		}
	}

	/**
	 * [post_lookup: This function will retrieve all posts from database without
	 * pagination]
	 * @return [array] [All post records]
	 */
	public function post_lookup()
	{
		$posts = $this->column_model->get_posts();
		return $posts;
	}	

	/**
	 * [get_column_by_id_lookup This function will retrieve a specific post from database
	 * by its $id]
	 * @param  [type]  $id   [post id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific post record who's $id is passed]
	 */
	public function get_column_by_id_lookup($id)
	{
		$post = $this->column_model->get_column_by_id($id);
		return $post;
	}	

	public function get_bla()
	{
		$date = $this->column_model->bla();
		print_r($date);
	}

	/**
	 * [delete_column_by_id_lookup This function will delete a specific post from database
	 * by its $id and post picture from assets/admin/images/posts folder and then, redirects
	 * post to the posts page]
	 * @param  [type] $id [post id whom record is to be deleted from database and picture 
	 * from assets/admin/images/posts folder]
	 */
	public function delete_column_by_id_lookup($id)
	{
		if ($this->column_model->delete_post('column', $id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/post/');
		}
	}	
}