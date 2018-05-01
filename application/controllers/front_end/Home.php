<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->load->model('front_end/home_model');
		$this->load->model('front_end/comment_model');
		$this->load->model('admin/post_model');
	}

	public function index()
	{
		$this->front_end_layouts->set_title('Pak Democrates');
		$data['politicians'] = $this->home_model->get_three_random_politicians();
		$data['political_parties'] = $this->home_model->get_three_random_political_parties();
		$data['columnists'] = $this->home_model->get_three_random_columnists();
		$data['featured_post'] = $this->home_model->get_random_post();
		if(!empty($data['featured_post'] ))
		{
			$post_id = $data['featured_post'][0]['id'];

			$data["post_comments_count"] = $this->comment_model->comments_count($post_id);

	        $rating = $this->post_model->get_rating($post_id);

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
		}
		$this->front_end_layouts->view('templates/front_end/index', $data);
	}

	public function get_post_by_id($id)
	{
		$this->front_end_layouts->set_title('Pak Democrates | Story');
		if(!is_numeric($id))
		{
			echo '<h3 style="color: red; text-align: center; border: 1px solid #ccc; padding: 20px">Page not Found!</h3>';
			die();
		} 

		$data['featured_post'] = $this->home_model->get_post_by_id($id);

		if(!empty($data['featured_post'] ))
		{
			$post_id = $data['featured_post'][0]['id'];

			$data["post_comments_count"] = $this->comment_model->comments_count($post_id);

			$data["comments"] = $this->comment_model->get_comments($post_id);

	        $rating = $this->post_model->get_rating($post_id);

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
		}
		else
		{
			echo '<h3 style="color: red; text-align: center; border: 1px solid #ccc; padding: 20px">Page not Found!</h3>';
			die();
		}

		$this->front_end_layouts->view('templates/front_end/post', $data);
	}
}