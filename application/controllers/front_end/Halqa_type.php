<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Halqa_type class is a controller class it has all methods for basic operation
* of halqa_type i.e. CRUD lookups, get bootstrap modals, pagination etc for halqa_type  
* Methods: index
* 		   add_halqa_type_lookup
* 		   edit_halqa_type_lookup
* 		   get_modal
* 		   edit_unique
* 		   get_halqa_type_by_id_lookup
* 		   delete_halqa_type_by_id_lookup
*/

class Halqa_type extends PD_Photo {
	
	public function __construct()
	{
		parent::__construct();
	
		$this->load->library('layouts');	
		$this->load->model('admin/halqa_type_model');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * It retrieves $config['per_page'] halqa_types from the database and
	 * generates pagination from other records
	 * 
	 * Maps to the following URL
	 * 		http://localhost/pak_democrates/admin/halqa_type/index
	 *	- or -
	 *		http://localhost/pak_democrates/index.php/admin/halqa_type
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/admin/halqa_type/<method_name>
	 *	- or -
	 * /admin/halqa_type/<method_name>
	 */

	public function index()
	{
/*		$this->layouts->set_title('Halqa Type\'s'); 

		$config = array();
        $config["base_url"] = base_url('admin/') . '/'  . $this->router->fetch_class() . '/' . $this->router->fetch_method();
	    $config["total_rows"] = $this->halqa_type_model->record_count();
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

        $data["halqa_types"] = $this->halqa_type_model->fetch_halqa_types($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

		$this->layouts->view('templates/admin/halqa_types', $data);*/
	}

	/**
	 * [halqa_type_lookup: This function will retrieve all halqa_types from database without
	 * pagination]
	 * @return [array] [All halqa_type records]
	 */
	public function halqa_type_lookup()
	{
		$halqa_types = $this->halqa_type_model->get_halqa_types();
		return $halqa_types;
	}	

	/**
	 * [get_halqa_type_by_id_lookup This function will retrieve a specific halqa_type from database
	 * by its $id]
	 * @param  [type]  $id   [halqa_type id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it 
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name 
	 * instead of full_name]
	 * @return [array] [One specific halqa_type record who's $id is passed]
	 */
	public function get_halqa_type_by_id_lookup($id)
	{
		$halqa_type = $this->halqa_type_model->get_halqa_type_by_id($id);
		return $halqa_type;
	}	
}