<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Star extends CI_Controller {

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
		// $this->load->model('front_end/user_model');
		// $this->load->model('front_end/politician_model');
		// $this->load->model('front_end/halqa_type_model');
		// $this->load->model('front_end/comment_model');
		// $this->load->model('admin/halqa_model');
		$this->load->model('admin/post_model');
}

  
  /**
   * Set a rate
   */
  public function add_rating_lookup(){
    	
    $rating = $this->post_model->add_rating();
	echo $this->get_rating_lookup($rating);
  }
  
 public function get_rating_lookup($rating = '')
 {
 	// echo $rating;
 	$html_class = "size-4";
 	$type = "html";
 	
 	if(empty($rating))
 	{
		$rating = $this->post_model->get_rating();
 	}

	if($rating == 0)
	{
		
		$rate_times = 0;
		
		$rate_value = 0;
		
		$rate_bg = 0;
	}
	else
	{
		$post_id = $this->input->post('post_id');
		$rate_times = $this->post_model->get_total_ratings_count($post_id);  	
		$rate_value = $rating/$rate_times;
		$rate_bg = (($rate_value)/5)*100;
	}

	if($type == "html")
	{
		$html = '<div class="Fr-star '. $html_class .'" data-title="'. round($rate_value, 2) .' / 5 by '. $rate_times .' ratings" data-rating="'. $rate_value .'">';
		
		$html .= '<div class="Fr-star-value" style="width: '. $rate_bg .'%"></div>';
		
		$html .= '<div class="Fr-star-bg"></div>';
		
		$html .= '</div>';
		
		return $html;
	}
	// else if($type == "rate_value"){
	// return $rate_value;
	// }else if($type == "rate_percentage"){
	// return $rate_bg;
	// }else if($type == "rate_times"){
	// return $rate_times;
	//}
}
  
  public function userRating($user_id){
    $sql = $this->dbh->prepare("SELECT `rate` FROM `{$this->config['db']['table']}` WHERE `user_id` = ?");
    $sql->execute(array($user_id));
    return $sql->fetchColumn();
  }
}