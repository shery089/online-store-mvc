<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* post class is a controller class it has all methods for basic operation
* of post i.e. CRUD lookups, get bootstrap modals, pagination etc for post  
* Methods: index
* 		   add_post_lookup
* 		   edit_post_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_post_by_id_lookup
* 		   delete_post_by_id_lookup
*/

class Post extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/post_model');
		$this->load->model('admin/politician_model');
		$this->load->model('admin/political_party_model');
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
		$this->layouts->set_title('Posts'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->post_model->record_count();
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

        $data["posts"] = $this->post_model->fetch_posts($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/posts', $data);
	}

	/**
	 * [add_post_lookup: This method can work with both ajax call and 
	 * normal PHP method call. Validates post data. If data is valid 
	 * then, it allows access to its insert post model function. Otherwise, 
	 * It gives appropriate error messages. After data is successfully 
	 * inserted then, it gives a success flash message]
	 */
	public function add_post_lookup()
	{	
		$this->layouts->set_title('Add Post'); 

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			
			$data['post'] = $this->input->post('post');
			
			$data['post'] = strlen($data['post']) > 0 ? 'OKaa' : '';
			if(empty($this->input->post('political_party')) && empty($this->input->post('politician')))
			{
				$data['type'] = '';
			}
			else
			{
				$data['type'] = 'OK';
			}
    		// $data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
		
			$this->form_validation->set_data($data);
		}

		// Post

	    $this->form_validation->set_rules(

    		'post', 'Post',
    		'trim|required|max_length[63206]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Type

	    $this->form_validation->set_rules(

    		'type', 'Type',
    		'required'
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

				if(empty($this->input->post('political_party')) && empty($this->input->post('politician')))
				{
					$errors['type'] = '<div class="error_prefix text-right">Please select one or more options from post subjects<div>';
				}
				else
				{
					$errors['type'] = '';
				}

		    	$errors['post'] = (!empty(form_error('post')) ? form_error('post') : '');

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['political_parties'] = $this->political_party_model->get_political_parties_dropdown();
				$data['politicians'] = $this->politician_model->get_politicians_dropdown();
				$this->layouts->view('templates/admin/add_post', $data);
			}
	    }	    
	    else // Validation Passed
	    {	

			if($this->post_model->insert_post()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Post has been successfully added!');
				unset($_POST);
		    	echo json_encode(array('success' => 'post inserted'));
	    	}
	    }		
	}	

	/**
	 * [edit_post_lookup: Edits a post by id, Validates post data. If data is valid then, 
	 * it allows access to its edit post model function. Otherwise, It gives appropriate 
	 * error messages. After data is successfully edited then, it gives a success flash 
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_post_lookup($id)
	{		
		$this->layouts->set_title('Edit Post'); 
		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		
		if($this->input->is_ajax_request())
		{
			$data = array();
			
			$data['post'] = $this->input->post('post');
			
			if(empty($this->input->post('political_party')) && empty($this->input->post('politician')))
			{
				$data['type'] = '';
			}
			else
			{
				$data['type'] = 'OK';
			}
    		// $data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
		
			$this->form_validation->set_data($data);
		}

		// Post

	    $this->form_validation->set_rules(

    		'post', 'Post',
    		'trim|required|min_length[4]|max_length[63206]',
        	array(
            	'required'      => '%s is required',
	        	'min_length'    => '%s should be at least %s chars',
	        	'max_length'    => '%s should be at most %s chars'
    		)
	    );

		// Type

	    $this->form_validation->set_rules(

    		'type', 'Type',
    		'required'
	    );
		$data['record'] = $this->get_post_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc
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

				if(empty($this->input->post('political_party')) && empty($this->input->post('politician')))
				{
					$errors['type'] = '<div class="error_prefix text-right">Please select one or more options from post subjects<div>';
				}
				else
				{
					$errors['type'] = '';
				}

		    	$errors['post'] = (!empty(form_error('post')) ? form_error('post') : '');

		    	echo json_encode($errors);
			}
			else // if not an ajax call
			{
				$data['political_parties'] = $this->political_party_model->get_political_parties();
				$data['politicians'] = $this->politician_model->get_politicians();
				$this->layouts->view('templates/admin/edit_post', $data);
			}
	    }	    
	    else // Validation Passed
	    {	
			if($this->post_model->update_post($id))
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
		$count = $this->post_model->insert_like_dislike();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function get_likes_dislikes_lookup()
	{	
		$this->post_model->get_likes_dislikes();			
	}
	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [post id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{	
		$action = $this->input->post('action');
		$action_parts = explode('_', $action);
		$entity = (!empty($action_parts[2]) ? $action_parts[1] . '_' . $action_parts[2] : $action_parts[1]);  
		$data['post'] = $this->get_post_by_id_lookup($id, $entity);
		$this->load->view('templates/admin/post_modal', $data);		
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [post id whom record is to be loaded in bootstrap modal]
	 */
	public function set_post_feature_lookup()
	{	
		$details = $this->input->post('details');
		$details_parts = explode('_', $details);
		$id = $details_parts[0];
		$entity = $details_parts[2] == 'party' ? $details_parts[1] . '_' . $details_parts[2] : $details_parts[1];
		$featured = $details_parts[3] == 0 ? $details_parts[3] : $details_parts[2];
		$entity_id = !empty($details_parts[4]) ? $details_parts[4] : $details_parts[3];
		if($this->post_model->set_post_feature($id, $entity, $featured, $entity_id))
		{
			$this->session->set_flashdata('success_message', 'Post has been successfully featured!');
			echo json_encode(array('success' => 'Successfully Featured'));
		}
	}

	/**
	 * [edit_unique It's a callback function that is called in edit_post_lookup
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
	 * [_alpha_numeric_hypen: It's a callback function that is called in add_post_lookup
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
		$posts = $this->post_model->get_posts();
		return $posts;
	}	

	/**
	 * [get_post_by_id_lookup This function will retrieve a specific post from database
	 * by its $id]
	 * @param  [type]  $id   [post id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific post record who's $id is passed]
	 */
	public function get_post_by_id_lookup($id, $entity)
	{
		$post = $this->post_model->get_post_by_id($id, $entity);
		return $post;
	}	

	public function get_bla()
	{
		$date = $this->post_model->bla();
		print_r($date);
	}

	/**
	 * [delete_post_by_id_lookup This function will delete a specific post from database
	 * by its $id and post picture from assets/admin/images/posts folder and then, redirects
	 * post to the posts page]
	 * @param  [type] $id [post id whom record is to be deleted from database and picture 
	 * from assets/admin/images/posts folder]
	 */
	public function delete_post_by_id_lookup($id)
	{
		if ($this->post_model->delete_post('story_details', $id)) 
		{
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
		    redirect('/admin/post/');
		}
	}	
}