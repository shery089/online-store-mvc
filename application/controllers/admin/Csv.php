<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv extends PD_Parsecsv {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/halqa_model');
		$this->load->model('admin/designation_model');
		$this->load->model('admin/political_party_model');
		$this->load->model('admin/politician_model');
		$this->load->model('admin/autocomplete_model');
	}

	function index()
	{
				
	}

	public function parse_politicians_by_csv()
	{
		die;
		ini_set('max_execution_time', 100000);
		if(file_exists(ADMIN_EXCEL_PATH . '/punjab.csv'))
		{
			// Parse 'politicians.csv' CSV strings to arrays
			$csv = $this->parse_file(ADMIN_EXCEL_PATH . '/punjab.csv');

			for ($i = 0, $count = count($csv); $i < $count; $i++)
			{ 
				$political_party = strtolower($csv[$i]['political_party']);
				$name = strtolower($csv[$i]['name']);
				$halqa_id = strtolower($csv[$i]['halqa_id']);
				$csv[$i]['name'] = $name;
				$halqa_id = $this->halqa_model->get_id_by_key($halqa_id);
				$csv[$i]['halqa_id'] = $halqa_id['id'];
				$political_party = $this->political_party_model->get_id_by_key($political_party);
				$csv[$i]['political_party'] = $political_party['id'];
			}

/*			$csv = array_unique($csv, SORT_REGULAR);
*/

			for ($i = 0, $count = count($csv); $i < $count; $i++)
			{
				$political_party = $csv[$i]['political_party'];
				$name = $csv[$i]['name'];
				$halqa_id = $csv[$i]['halqa_id'];

				for ($j = $i + 1, $count = count($csv); $j < $count; $j++)
				{ 
					// $result = array_intersect ($csv[$i], $csv[$j]);
					if($csv[$j]['name'] == $name && $csv[$j]['political_party'] == $political_party)
					{
						$csv[$i]['halqa_ids'][$j] = $csv[$j]['halqa_id'];
						unset($csv[$j]);
						$csv = array_values($csv);
						$count = count($csv);
					}
				}
			}

			if($this->politician_model->insert_politician_bulk_by_csv($csv)) // insert into db
			{
				$count = count($csv);
				
				$this->session->set_flashdata('success_message', 'Bulk of ' . $count . ' Politicians has been successfully added!');

		    	echo json_encode(array('success' => 'Politician bulk inserted'));
	    	}
		}
	}

	public function parse_political_party_by_csv()
	{
		die;
		if(file_exists(ADMIN_EXCEL_PATH . '/political_parties.csv'))
		{
			// Parse 'political_parties.csv' CSV strings to arrays
			$csv = $this->parse_file(ADMIN_EXCEL_PATH . '/political_parties.csv');

			for ($i = 0, $count = count($csv); $i < $count; $i++)
			{ 
				$id = $this->designation_model->get_id_by_key(strtolower($csv[$i]['designation_id']));
				$id = $id['id'];
				$csv[$i]['designation_id'] = $id;
			}

			if($this->political_party_model->insert_political_party_bulk_by_csv($csv)) // insert into db
			{
				$count = count($csv);
				
				$this->session->set_flashdata('success_message', 'Bulk of ' . $count . ' Political Party has been successfully added!');

		    	echo json_encode(array('success' => 'Political Party bulk inserted'));
	    	}
		}
	}

	public function parse_city_by_csv()
	{
		if(file_exists(ADMIN_EXCEL_PATH . '/cities.csv'))
		{
			// Parse 'political_parties.csv' CSV strings to arrays
			$csv = $this->parse_file(ADMIN_EXCEL_PATH . '/cities.csv');

			for ($i = 0, $count = count($csv); $i < $count; $i++)
			{ 
				$name = strtolower($csv[$i]['name']);
				$id = $this->autocomplete_model->get_province_id_by_key(strtolower($csv[$i]['province_id']));
				$id = $id['id'];
				$csv[$i]['province_id'] = $id;
				$csv[$i]['name'] = $name;
			}

			if($this->autocomplete_model->insert_cities_bulk_by_csv($csv)) // insert into db
			{
				$count = count($csv);
				
				$this->session->set_flashdata('success_message', 'Bulk of ' . $count . ' Cities has been successfully added!');

		    	echo json_encode(array('success' => 'Cities bulk inserted'));
	    	}
		}
	}
}