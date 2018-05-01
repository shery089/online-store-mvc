<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller {

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
		$this->load->model('front_end/user_model');
		$this->load->model('front_end/comment_model');
		$this->load->model('front_end/halqa_type_model');
		$this->load->model('admin/halqa_model');
		$this->load->model('admin/post_model');
		$this->load->model('admin/column_model');
	}

	public function edit_a_comment_lookup()
	{		
		$result = $this->comment_model->edit_a_comment();
		if(is_array($result))
		{
			$cookie = array(
			
				'name' => 'last_row',
			
				'value' => $result['last_row'],
			
				'expire' => 0,
				
				);
			
			$this->input->set_cookie($cookie);
			$data['result'] = $result;
			$this->load->view('templates/front_end/edit_comment_block', $data);
		}
	}

	public function edit_a_reply_lookup()
	{		
		$result = $this->comment_model->edit_a_reply();
		if(is_array($result))
		{
			$cookie = array(
			
				'name' => 'last_row',
			
				'value' => $result['last_row'],
			
				'expire' => 0,
				
				);
			
			$this->input->set_cookie($cookie);
			$data['result'] = $result;
			$this->load->view('templates/front_end/edit_reply_block', $data);
		}
	}

	public function edit_a_column_comment_lookup()
	{		
		$result = $this->comment_model->edit_a_column_comment();
		if(is_array($result))
		{
			$cookie = array(
			
				'name' => 'last_row',
			
				'value' => $result['last_row'],
			
				'expire' => 0,
				
				);
			
			$this->input->set_cookie($cookie);
			$data['result'] = $result;

			$this->load->view('templates/front_end/edit_column_comment_block', $data);
		}
	}

	public function get_comment_box()
	{		
		$this->input->post('user_id');

		$this->load->view('templates/front_end/comment_box');
	}

	public function get_reply_box()
	{		
		$this->input->post('user_id');

		$this->load->view('templates/front_end/post_reply_box');
	}

	public function no_change_comment_lookup()
	{		
		$result = $this->comment_model->no_change_comment_lookup();

		if(is_array($result))
		{	
			$data['result'] = $result;
			
			$this->load->view('templates/front_end/edit_comment_block_no_change', $data);
		}
	}

	public function no_change_reply_lookup()
	{		
		$result = $this->comment_model->no_change_reply_lookup();

		if(is_array($result))
		{	
			$data['result'] = $result;
			
			$this->load->view('templates/front_end/edit_reply_block_no_change', $data);
		}
	}

	public function no_change_column_comment_lookup()
	{		
		$result = $this->comment_model->no_change_column_comment_lookup();

		if(is_array($result))
		{	
			$data['result'] = $result;
			
			$this->load->view('templates/front_end/edit_comment_block_no_change', $data);
		}
	}

	public function post_a_comment_lookup($story_id, $user_id)
	{		
		if(is_numeric($story_id) && is_numeric($user_id))
		{
			$result = $this->comment_model->post_a_comment($story_id, $user_id);
			if(is_array($result))
			{
				$data['result'] = $result;
				$this->load->view('templates/front_end/post_comment_block', $data);
			}
		}
	}

	public function post_a_column_comment_lookup($column_id, $user_id)
	{		
		if(is_numeric($column_id) && is_numeric($user_id))
		{
			$result = $this->comment_model->post_a_column_comment($column_id, $user_id);
			if(is_array($result))
			{
				$data['result'] = $result;
				$this->load->view('templates/front_end/post_comment_block', $data);
			}
		}
	}

	public function post_a_column_reply_lookup($comment_id, $user_id, $column_id)
	{		
		if(is_numeric($comment_id) && is_numeric($user_id))
		{
			$result = $this->comment_model->post_a_column_reply($comment_id, $user_id, $column_id);
			if(is_array($result))
			{
				$data['result'] = $result;
				$this->load->view('templates/front_end/post_reply_block', $data);
			}
		}
	}

	public function post_a_reply_lookup($comment_id, $user_id, $post_id)
	{		
		if(is_numeric($comment_id) && is_numeric($user_id))
		{
			$result = $this->comment_model->post_a_reply($comment_id, $user_id, $post_id);
			if(is_array($result))
			{
				$data['result'] = $result;
				$this->load->view('templates/front_end/post_reply_block', $data);
			}
		}
	}

	public function get_politicians_lookup()
	{		
		$data['halqas'] = $this->politician_model->get_politicians();
		$data['halqa_types'] = $this->halqa_type_model->get_halqa_types();
		$this->load->view('templates/front_end/vote_now_modal', $data);
	}
	public function get_politician_by_id($id, $only_info = FALSE)
	{		
		$data['politician'] = $this->politician_model->get_politician_by_id($id);
		$data['casted_votes'] = $this->politician_model->get_politician_votes_id($id);
		$data['featured_post'] = $this->post_model->get_featured_post($id, 'politician');
		
		$politician_halqas = $this->politician_model->get_politician_halqas_by_id($id, TRUE);

        $this->no_halqa_id = $this->halqa_model->get_id_by_key('no halqa');
        $this->no_halqa_id = $this->no_halqa_id['id'];
     
        for ($i = 0; $i < count($politician_halqas); $i++)
        { 
            if($this->no_halqa_id == $politician_halqas[$i]['halqa_id'])
            {
                unset($politician_halqas[$i]);
            }
        }

        /**
         * Sorts with correct indexes
         * @var array
         */
        $politician_halqas_sorted_index = array();
        $i = 0;
        foreach ($politician_halqas as $politician_halqa => $value)
        { 	
			$politician_halqas_sorted_index[$i]=$value;
			unset($politician_halqas[$politician_halqa]);
			$i++;
        }

        for ($i=0, $count = count($politician_halqas_sorted_index); $i < $count; $i++) 
        { 
	        $politician_halqas_sorted_index[$i]['halqa_details'] = $this->halqa_model->get_halqa_by_id($politician_halqas_sorted_index[$i]['halqa_id']);
        }

        unset($politician_halqas);

		$data['politician_halqas'] = $politician_halqas_sorted_index;

		if($only_info)
		{
			return array('politician_halqas' => $politician_halqas_sorted_index, 'casted_votes' => $data['casted_votes']);
		}

		$this->front_end_layouts->view('templates/front_end/politician', $data);
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function delete_comment_reply_lookup()
	{	
		$this->comment_model->delete_comment_reply();
	}

	/**
	 * [delete_column_comment_reply_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 */
	public function delete_column_comment_reply_lookup()
	{	
		$this->comment_model->delete_column_comment_reply();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function insert_comment_reply_like_lookup()
	{	
		$count = $this->comment_model->insert_comment_reply_like();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function insert_column_comment_reply_like_lookup()
	{	
		$count = $this->comment_model->insert_column_comment_reply_like();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function insert_reply_box_lookup()
	{	
		$this->load->view('templates/front_end/post_reply_box');
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function get_likes_dislikes_lookup()
	{	
		$this->politician_model->get_likes_dislikes();			
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function get_comment_likes_lookup()
	{	
		$this->comment_model->get_comment_likes();			
	}

	/**
	 * [get_column_comment_likes_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [politician id whom record is to be loaded in bootstrap modal]
	 */
	public function get_column_comment_likes_lookup()
	{	
		$this->comment_model->get_column_comment_likes();			
	}
	
	public function get_latest_politician_votes_by_id($id)
	{
		$politician_details =  $this->get_politician_by_id($id, TRUE);
		$politician_halqas = $politician_details['politician_halqas'];					
		$casted_votes = $politician_details['casted_votes'];

		// politician actual halqas
		$halqa_details = array_column($politician_halqas, 'halqa_details');
		$halqa_details = array_column($halqa_details, 'name');

		$halqa_array_keys = array_keys($casted_votes['halqa_keys']);
		$halqa_array_values = array_values($casted_votes['halqa_keys']);

		$halqas_not_voted_yet = array_diff($halqa_details, $halqa_array_keys);
		$halqas_not_voted_yet_keys = array_combine($halqas_not_voted_yet, $halqas_not_voted_yet);
		foreach ($halqas_not_voted_yet_keys as $key => $value) 
		{
			$halqas_not_voted_yet_keys[$key] = 0;
		}

		$halqa_details_plus = array_combine($halqa_array_keys, $halqa_array_values);
		$halqa_details_plus = array_merge($halqa_details_plus, $halqas_not_voted_yet_keys);
		$halqa_details_plus = array_change_key_case($halqa_details_plus, CASE_UPPER);

		$halqa_details_plus = http_build_query($halqa_details_plus, '', ', ');
		$halqa_details_plus = str_replace('=', ': ', $halqa_details_plus);
		$politician_prov_halqas_plus = preg_replace("/(na)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);
		$politician_na_halqas_plus = preg_replace("/(pp|ps|pk|pb|la|gbla)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);

		// to remove extra white space and extra commas
		$politician_na_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_na_halqas_plus);
		$politician_na_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_na_halqas_plus);
		$politician_na_halqas_plus = trim($politician_na_halqas_plus, ', ');

		$politician_prov_halqas_plus = preg_replace("/(, ,)+/", ",", $politician_prov_halqas_plus);
		$politician_prov_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $politician_prov_halqas_plus);
		$politician_prov_halqas_plus = trim($politician_prov_halqas_plus, ', ');

		// get voted halqas

		$politician_halqas = array_column($politician_halqas, 'halqa_type');

		foreach ($politician_halqas as $politician_halqa)
		{
			if(in_array($politician_halqa, array('national assembly')))
			{
				$na_halqa_plus = TRUE;
			}
			if(!in_array($politician_halqa, array('national assembly')))
			{
				$prov_halqa_plus = TRUE;
			}
		}

		$votes_array_keys = array_keys($casted_votes['count_vote_types']);

		foreach ($votes_array_keys as $vote_type)
		{
			if(!in_array($vote_type, array('national assembly', 'off_halqa')))
			{
				$prov_assembly = $vote_type;
				$prov_assembly_votes = array_column($casted_votes, $prov_assembly);
				$prov_assembly_votes = $prov_assembly_votes[0];
			}
			else
			{
				$prov_assembly_votes = 0;
			}
		}

		if(in_array('national assembly', $votes_array_keys))
		{
			$national_assembly_votes = array_column($casted_votes, 'national assembly');
			$national_assembly_votes = $national_assembly_votes[0];
		}
		else
		{
			$national_assembly_votes = 0;
		}

		if(in_array('off_halqa', $votes_array_keys))
		{
			$off_halqa_votes = array_column($casted_votes, 'off_halqa');
			$off_halqa_votes = $off_halqa_votes[0];
		}
		else
		{
			$off_halqa_votes = 0;
		}

		echo json_encode (array('na_halqa_plus' => $na_halqa_plus, 'prov_halqa_plus' => $prov_halqa_plus,
					'prov_assembly_votes' => $prov_assembly_votes, 'national_assembly_votes' => $national_assembly_votes, 
					'off_halqa_votes' => $off_halqa_votes, 'politician_na_halqas_plus' => $politician_na_halqas_plus, 
					'politician_prov_halqas_plus' => $politician_prov_halqas_plus, 'success' => 'TRUE'));
	}
}