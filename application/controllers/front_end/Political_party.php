<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Political_party extends CI_Controller {

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
		$this->load->model('front_end/political_party_model', 'party_model');

		$this->load->model('front_end/user_model', 'user_model');
		$this->load->model('front_end/halqa_type_model', 'halqa_type_model');
		$this->load->model('front_end/comment_model', 'comment_model');
		$this->load->model('admin/halqa_model');
		$this->load->model('admin/post_model');
	}

	public function index()
	{
		$this->front_end_layouts->set_title('Political Party'); 
		$config = array();
        $config["base_url"] = base_url('front_end/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->party_model->record_count();
		$config['per_page'] = 14;
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

        $data["political_parties"] = $this->party_model->fetch_political_parties($config["per_page"], $page); 

        $data["links"] = $this->pagination->create_links();

		$this->front_end_layouts->view('templates/front_end/political_parties', $data);
	}

	// public function get_modal($id)
	// {		
	// 	if(!empty($id))
	// 	{
	// 		$result = $this->user_model->get_user_halqa($id);
	// 		if(is_array($result))
	// 		{

	// 		}
	// 		else
	// 		{
	// 			$data['halqas'] = $this->halqa_model->get_halqas_by_type('na');
	// 			$data['halqa_types'] = $this->halqa_type_model->get_halqa_types();
	// 			$this->load->view('templates/front_end/vote_now_modal', $data);
	// 		}
	// 	}
	// }

	public function post_a_comment_lookup($story_id, $user_id)
	{		
		if(is_numeric($story_id) && is_numeric($user_id))
		{
			$result = $this->party_model->post_a_comment($story_id, $user_id);
			if(is_array($result))
			{
				$data['result'] = $result;
				$this->load->view('templates/front_end/post_comment_block', $data);
			}
		}
	}

	public function get_political_parties_lookup()
	{		
		$data['halqas'] = $this->party_model->get_political_parties();
		$data['halqa_types'] = $this->halqa_type_model->get_halqa_types();
		$this->load->view('templates/front_end/vote_now_modal', $data);
	}
	
	public function get_political_party_by_id($id = '', $only_info = FALSE)
	{		
		$this->front_end_layouts->set_title('Political Party');
		
		$data['political_party'] = $this->party_model->get_political_party_by_id($id);
		
		if(empty($data['political_party']))
		{
			echo '<h3 style="color: red; text-align: center; border: 1px solid #ccc; padding: 20px">Page not Found!</h3>';
			die();
		}

		// $data['casted_votes'] = $this->party_model->get_political_party_votes_id($id);
		$post_id = $this->post_model->get_featured_post($id, 'political_party');		
		$data['featured_post'] = $post_id;
		if(!empty($data['featured_post']))
		{
			$post_id = $post_id[0]['id'];
			$rating = (!empty($post_id)) ? $this->post_model->get_rating($post_id) : 0;
			
			if($rating == 0)
			{
				
				$rate_times = 0;
				
				$rate_value = 0;
				
				$rate_bg = 0;
			}
			else
			{
				$rate_times = $this->post_model->get_total_ratings_count($post_id);  	
				$rate_value = $rating/$rate_times;
				$rate_bg = (($rate_value)/5)*100;
			}

			$html = '<div class="Fr-star size-4 userChoose" data-title="'. round($rate_value, 2) .' / 5 by '. $rate_times .' ratings" data-rating="'. $rate_value .'">';
			
			$html .= '<div class="Fr-star-value" style="width: '. $rate_bg .'%"></div>';
			
			$html .= '<div class="Fr-star-bg"></div>';
			
			$html .= '</div>';
			
			$data["rating"] = $html;

			$data["comments"] = (!empty($post_id)) ? $this->comment_model->get_comments($post_id) : '';
	        
	        $data["post_comments_count"] = (!empty($post_id)) ? $this->comment_model->comments_count($post_id) : 0;

	    }

		$this->front_end_layouts->view('templates/front_end/political_party', $data);
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [political_party id whom record is to be loaded in bootstrap modal]
	 */
	public function get_readme_modal($id, $action)
	{	
		$data['entity'] = $this->party_model->get_political_party_readme_by_id($id, $action);
		$data['action'] = $action;
		$this->load->view('templates/front_end/readme_modal', $data);		
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [political_party id whom record is to be loaded in bootstrap modal]
	 */
	public function like_dislike_lookup()
	{	
		$count = $this->party_model->insert_like_dislike();
	}

	/**
	 * [like_dislike_lookup loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [political_party id whom record is to be loaded in bootstrap modal]
	 */
	public function get_likes_dislikes_lookup()
	{	
		$this->party_model->get_likes_dislikes();			
	}
	
	public function get_latest_political_party_votes_by_id($id)
	{
		$political_party_details =  $this->get_political_party_by_id($id, TRUE);


		$political_party_halqas = $political_party_details['political_party_halqas'];					
		$casted_votes = $political_party_details['casted_votes'];

		// political_party actual halqas
		$halqa_details = array_column($political_party_halqas, 'halqa_details');
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
		$political_party_prov_halqas_plus = preg_replace("/(na)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);
		$political_party_na_halqas_plus = preg_replace("/(pp|ps|pk|pb|la|gbla)\-[0-9]+: [0-9]+/i", "", $halqa_details_plus);

		// to remove extra white space and extra commas
		$political_party_na_halqas_plus = preg_replace("/(, ,)+/", ",", $political_party_na_halqas_plus);
		$political_party_na_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $political_party_na_halqas_plus);
		$political_party_na_halqas_plus = trim($political_party_na_halqas_plus, ', ');

		$political_party_prov_halqas_plus = preg_replace("/(, ,)+/", ",", $political_party_prov_halqas_plus);
		$political_party_prov_halqas_plus = preg_replace("/(no\+halqa: )[0-9]+/i", ",", $political_party_prov_halqas_plus);
		$political_party_prov_halqas_plus = trim($political_party_prov_halqas_plus, ', ');

		// get voted halqas

		$political_party_halqas = array_column($political_party_halqas, 'halqa_type');

		foreach ($political_party_halqas as $political_party_halqa)
		{
			if(in_array($political_party_halqa, array('national assembly')))
			{
				$na_halqa_plus = TRUE;
			}
			if(!in_array($political_party_halqa, array('national assembly')))
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
					'off_halqa_votes' => $off_halqa_votes, 'political_party_na_halqas_plus' => $political_party_na_halqas_plus, 
					'political_party_prov_halqas_plus' => $political_party_prov_halqas_plus, 'success' => 'TRUE'));
	}
}