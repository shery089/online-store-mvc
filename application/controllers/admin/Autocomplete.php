<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autocomplete extends CI_Controller {

	private $date;
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
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 */	

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('admin/autocomplete_model');
		$this->load->model('admin/politician_model');
		$this->load->model('admin/political_party_model');
		$this->load->model('admin/halqa_type_model');
	}	
	
	public function get_political_party_by_name_lookup()
	{
		$political_party = $this->input->post('search_term');
		if(!empty($political_party))
		{
			$this->autocomplete_model->get_political_party_by_name($political_party);
		}
	}

	public function get_entity_by_name_lookup()
	{
		$entity = $this->input->post('search_term');
		if(!empty($entity))
		{
			$this->autocomplete_model->get_entity_by_name($entity);
		}		
	}	
	
	public function get_politician_by_name_lookup()
	{
		$politician = $this->input->post('search_term');
		if(!empty($politician))
		{
			$this->autocomplete_model->get_politician_by_name($politician);
		}		
	}	
	
	public function display_political_parties()
	{
		$data['political_parties'] = $this->autocomplete_model->get_political_party_by_key();
		$this->load->view('templates/front_end/searched_political_parties', $data);
	}	

	public function display_entities()
	{
		$entity = $this->input->post('search_term');
		if(!empty($entity))
		{
			$data['entities'] = $this->autocomplete_model->get_entities_by_key($entity);

			$this->load->view('templates/front_end/searched_entities', $data);
		}
	}	

	public function display_filters()
	{
		$data['political_parties'] = $this->political_party_model->get_political_parties_dropdown();
		$data['cities'] = $this->autocomplete_model->get_cities();
		$data['provinces'] = $this->autocomplete_model->get_provinces();
		$data['halqa_types'] = $this->halqa_type_model->get_halqa_types(TRUE);
		$this->load->view('templates/front_end/display_filters', $data);
	}

	public function display_politician_results_lookup()
	{

		$party = $this->input->post('party');
		$age = $this->input->post('age');
		$city = $this->input->post('city');
		$halqa_type = $this->input->post('halqa_type');
		$gender = $this->input->post('gender');
		$provincial_halqa = $this->input->post('provincial_halqa');

		if(!empty($party_id) && !empty($age) && !empty($halqa_type) && !empty($gender))
        {
			$this->session->unset_userdata('last_record');
		}

        else if(!empty($party_id) && !empty($age) && !empty($halqa_type))
        {
			$this->session->unset_userdata('last_record');
		}

        else if(!empty($party_id) && !empty($age) && !empty($provincial_halqa))
        {
			$this->session->unset_userdata('last_record');
		}

		else if(!empty($party_id) && !empty($gender) && !empty($halqa_type))
        {
        	$this->session->unset_userdata('last_record');
        }
	
		else if(!empty($party_id) && !empty($gender) && !empty($provincial_halqa))
        {
        	$this->session->unset_userdata('last_record');
        }
	
        else if(!empty($party_id) && !empty($age) && !empty($gender))
        {
			$this->session->unset_userdata('last_record');
		}

		else if(!empty($halqa_type) && !empty($age) && !empty($gender))
        {
			$this->session->unset_userdata('last_record');		
		}

		else if(!empty($provincial_halqa) && !empty($age) && !empty($gender))
        {
			$this->session->unset_userdata('last_record');		
		}

		else if(!empty($halqa_type) && !empty($age))
        {
			$this->session->unset_userdata('last_record');
        }

		else if(!empty($provincial_halqa) && !empty($age))
        {
			$this->session->unset_userdata('last_record');
        }
        
        else if(!empty($halqa_type) && !empty($gender)) 
        {
			$this->session->unset_userdata('last_record');        	
		}
        
        else if(!empty($provincial_halqa) && !empty($gender)) 
        {
			$this->session->unset_userdata('last_record');        	
		}

	    else if(!empty($halqa_type) && !empty($party_id))
	    {
			$this->session->unset_userdata('last_record');
	    }

	    else if(!empty($provincial_halqa) && !empty($party_id))
	    {
			$this->session->unset_userdata('last_record');
	    }

	    else if(!empty($gender) && !empty($party_id))
	    {
			$this->session->unset_userdata('last_record');
	    }
	    
	    else if(!empty($age) && !empty($party_id))
	    {
	    	$this->session->unset_userdata('last_record');
	    }
        
        else if(!empty($age) && !empty($gender))
        {
        	$_POST['cookie_gender_age'] = 'gender_age';
        	delete_cookie('gender');
        	delete_cookie('age');
        }
        
        else if(!empty($age))
        {
        	$_POST['cookie_age'] = 'age';
        	delete_cookie('gender');
        	delete_cookie('gender_age');
        }
        
        else if(!empty($gender))
        {
        	$_POST['cookie_gender'] = 'gender';
        	delete_cookie('gender');
       	}
       	
       	else if(!empty($party_id))
        {
        	$this->session->unset_userdata('last_record');
        }
       	
       	else if(!empty($provincial_halqa))
        {
        	$this->session->unset_userdata('last_record');
        }
        
        else
        {
	        if(!empty($halqa_type))
	        {
	        	$this->session->unset_userdata('last_record');
        	}
        }
		
		if($this->input->post('scroll') == 'scroll')
		{
			if(isset($this->session->userdata['last_record']))
			{
				$last_record = $this->session->userdata['last_record'];
			}
		}
		else
		{
			$last_record = 0;
		}
		
		$data['entities'] = $this->autocomplete_model->get_politicians_by_keys_filter($last_record);
		
		if(!empty($data['entities']))
		{
			$last_record = $last_record + 10;
		}

		if($this->input->post('scroll') == '')
		{	
			if(!isset($this->session->userdata['last_record']))
			{
				$newdata = array(
				  
				    'last_record'  => $last_record		
				);

				$this->session->set_userdata($newdata);
			}

			$this->load->view('templates/front_end/filtered_entities', $data);
		}
		else
		{
			$this->session->set_userdata('last_record', $last_record);
			$this->load->view('templates/front_end/filtered_entities_scroll', $data);
		}
	}

	public function display_politicians()
	{
		$data['politicians'] = $this->autocomplete_model->get_politician_by_key();
		$this->load->view('templates/front_end/searched_politicians', $data);
	}

	public function display_politicians_by_party()
	{
		$data['entities'] = $this->autocomplete_model->get_politician_by_party_id();
		$this->load->view('templates/front_end/filtered_politicians', $data);
	}

	public function display_politicians_by_key()
	{
		$data['entities'] = $this->autocomplete_model->get_politicians_by_keys_filter();
		$this->load->view('templates/front_end/filtered_politicians', $data);
	}
}