<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends CI_Controller {

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
		$this->load->model('front_end/columnist_model');
		$this->load->model('front_end/politician_model');
		$this->load->model('front_end/political_party_model');
	}

	public function index()
	{		
		$this->front_end_layouts->set_title('Statistics');
		$this->front_end_layouts->view('templates/front_end/statistics');
	}

	public function get_top_ten_liked_politicians_lookup()
	{		
		$this->politician_model->get_top_ten_liked_politicians();
	}

	public function get_top_ten_disliked_politicians_lookup()
	{		
		$this->politician_model->get_top_ten_disliked_politicians();
	}

	public function get_top_ten_liked_political_parties_lookup()
	{		
		$this->political_party_model->get_top_ten_liked_political_parties();
	}

	public function get_top_ten_disliked_political_parties_lookup()
	{		
		$this->political_party_model->get_top_ten_disliked_political_parties();
	}

	public function get_column_by_id($id = '')
	{		
		if($id == '')
		{
			echo '<h3 style="color: red; text-align: center; border: 1px solid #ccc; padding: 20px">Page not Found!</h3>';
			die();
		}

		$this->front_end_layouts->set_title('Column'); 

		$data['column'] = $this->column_model->get_column_by_id($id);
		$id = $data['column']['columnist_id'];
		$data["comments"] = $this->comment_model->get_column_comments($id, 'columnist');
        $data["post_comments_count"] = $this->comment_model->get_column_comments_count($id, 'columnist');
		$data['columnist'] = $this->columnist_model->get_columnist_by_id($id);
		$data['columns'] = $this->column_model->get_column_by_columnist_id($id);
		$data['newspaper'] = $this->newspaper_model->get_newspapers_by_columist($id);

		if(empty($data['columnist']))
		{
			echo '<h3 style="color: red; text-align: center; border: 1px solid #ccc; padding: 20px">Page not Found!</h3>';
			die();
		}

		$this->front_end_layouts->view('templates/front_end/column', $data);
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [columnist id whom record is to be loaded in bootstrap modal]
	 */
	public function get_readme_modal($id, $action)
	{	
		$data['entity'] = $this->columnist_model->get_columnist_readme_by_id($id, $action);
		$data['action'] = $action;
		$this->load->view('templates/front_end/readme_modal', $data);
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [columnist id whom record is to be loaded in bootstrap modal]
	 */
	public function like_dislike_lookup()
	{	
		$count = $this->column_model->insert_like_dislike();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [columnist id whom record is to be loaded in bootstrap modal]
	 */
	public function get_likes_dislikes_lookup()
	{	
		$this->column_model->get_likes_dislikes();			
	}
}