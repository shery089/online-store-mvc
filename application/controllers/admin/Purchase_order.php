<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('layouts');
        $this->load->model('admin/configuration_model');
		$this->load->model('admin/purchase_order_model');
		$this->load->model('admin/product_model');
        $this->load->model('admin/product_attribute_model');
        $this->load->model('admin/product_attribute_detail_model');
	}

	public function index()
	{
		$this->unset_purchase_order_search_filter();
		if($this->input->is_ajax_request()) {
			$this->search_purchase_order_lookup();
		}

        $title = $this->uri->segment(2) == 'inventory' ? 'Inventory' : 'Purchase Orders';

		$this->layouts->set_title($title);

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();
        if($this->uri->segment(2) == 'inventory') {
            $data['entity'] = 'inventory';
            $config["base_url"] = str_replace('purchase_order', 'inventory', $config["base_url"]);
        }
        else {
            $data['entity'] = 'purchase_order';
        }

        if($this->uri->segment(3)) {
            $_POST['is_low_quantity_products'] = $this->configuration_model->get_minimum_products_notification();
            $config["base_url"] = str_replace('index', 'low_quantity_products', $config["base_url"]);
        }

        $config['per_page'] = $this->configuration_model->get_items_per_page();
		$config["uri_segment"] = URI_SEGMENT;

        $data["purchase_orders"] = $this->fetch_purchase_orders_lookup($config["per_page"], $current_page, TRUE);
		$config["total_rows"] = $this->purchase_order_model->record_count();

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config['cur_tag_open'] = "<li><span><b>";
		$config['cur_tag_close'] = "</b></span></li>";

		$this->pagination->initialize($config);

		$data["links"] = $this->pagination->create_links();

		if(!$this->input->is_ajax_request()) {
            $this->load->model('admin/company_model');
            $data['companies'] = $this->company_model->get_companies_dropdown();
			$this->layouts->view('templates/admin/purchase_orders', $data);
		}
	}

	/**
	 * [add_purchase_order_lookup: This method can work with both ajax call and
	 * normal PHP method call. Validates Purchase_order data. If data is valid
	 * then, it allows access to its insert Purchase_order model function. Otherwise,
	 * It gives appropriate error messages. After data is successfully
	 * inserted then, it gives a success flash message]
	 */
	public function add_purchase_order_lookup()
	{
		$this->unset_purchase_order_search_filter();

		$this->layouts->set_title('Add Purchase Order');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if ($this->input->is_ajax_request()) {
		    $data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'add_purchase_order') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}
			$this->form_validation->set_data($data);
        }

        // Attributes

        if(isset($data)) {
            foreach ($data as $key => $value) {
                if (strpos($key, 'product_attr_details') !== false) {
                    $name = 'Attribute Details';
                }
                else if (strpos($key, 'product_attribute') !== false) {
                    $name = 'Product Attribute';
                }
                else if (strpos($key, 'product') !== false) {
                    $name = 'Product';
                }
                else if (strpos($key, 'quantity') !== false) {
                    $name = 'Quantity';
                }
                else if (strpos($key, 'sale_price') !== false) {
                    $name = 'Sale Price';
                }
                else if (strpos($key, 'purchase_price') !== false) {
                    $name = 'Purchase Price';
                }

                $this->form_validation->set_rules(

                    "$key", "$name",
                    'trim|required',
                    array(
                        'required' => '%s is required'
                    )
                );
            }
        }

		if ($this->form_validation->run() === FALSE) // Validation fails
		{
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */

			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'add_purchase_order') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}
				echo json_encode($errors);
			} else // if not an ajax call
			{
                $data['products'] = $this->product_model->get_products_dropdown();
                $data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
				$this->layouts->view('templates/admin/add_purchase_order', $data);
			}
		} else // Validation Passed
		{
			if ($this->purchase_order_model->insert_purchase_order()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Purchase Order has been successfully added!');
				unset($_POST);
				unset($_FILES);
				echo json_encode(array('success' => 'Purchase Order inserted'));
			}
		}
	}

	/**
	 * [edit_purchase_order_lookup: Edits a Purchase Order by id, Validates Purchase Order data. If data is valid then,
	 * it allows access to its edit Purchase Order model function. Otherwise, It gives appropriate
	 * error messages. After data is successfully edited then, it gives a success flash
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_purchase_order_lookup($id)
	{
		$this->unset_purchase_order_search_filter();

        $title = $this->uri->segment(2) == 'inventory' ? 'Edit Inventory' : 'Edit Purchase Order';

        $data['entity'] = $this->uri->segment(2) == 'inventory' ? 'inventory' : 'purchase_order';

		$this->layouts->set_title($title);

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */

		if ($this->input->is_ajax_request()) {
			$data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'edit_purchase_order') {
					continue;
				}

				$data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
			}

			$this->form_validation->set_data($data);
		}

        // Quantity
        $this->form_validation->set_rules(

            'quantity', 'Quantity',
            'trim|required|min_length[1]|max_length[20]|is_natural_no_zero',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Sale Price
        $this->form_validation->set_rules(

            'sale_price', 'Sale Price',
            'trim|required|min_length[1]|max_length[20]|numeric',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Purchase Price
        $this->form_validation->set_rules(

            'purchase_price', 'Purchase Price',
            'trim|required|min_length[1]|max_length[20]|numeric',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

		$record = $this->get_purchase_order_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc
        $data['purchase_order'] = $record;
		if ($this->form_validation->run() === FALSE) // Validation fails
		{
			/**
			 * if its an ajax call then, check if there are
			 * any validation errors if there are errors then,
			 * echo them as JSON else leave empty.
			 */
			if ($this->input->is_ajax_request()) {
				$errors = array();
				foreach ($_POST as $key => $value) {
					if ($key == 'edit_purchase_order') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else {
				$this->layouts->view('templates/admin/edit_purchase_order', $data);
			}
		} else // Validation Passed
		{
			if($this->purchase_order_model->update_purchase_order($id))
			{
				$this->session->set_flashdata('success_message', 'Product <strong>' . ucwords($record['product_name']) . ' ' .
                    ' </strong> has been successfully updated!');
				echo json_encode(array('success' => 'Purchase Order Updated'));
			}
		}
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [Purchase_order id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{
		$this->unset_purchase_order_search_filter();

		$data['purchase_order'] = $this->get_purchase_order_by_id_lookup($id);
		$this->load->view('templates/admin/purchase_order_modal', $data);
	}

    /**
     * [edit_unique It's a callback function that is called in edit_purchase_order_lookup
     * validation it checks if same attribute data exists other than the current
     * current record than returns FALSE. If does not exists it returns TRUE]
     * @param  [string] $value  [Purchase Order entered value e.g. in case of email validation
     * sheryarahmed007@gmail.com]
     * @param  [string] $params [table.attribute.id e.g. Purchase Order.email.3]
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
	 * [get_purchase_order_by_id_lookup This function will retrieve a specific Purchase_order from database
	 * by its $id]
	 * @param  [type]  $id   [Purchase_order id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name
	 * instead of full_name]
	 * @return [array] [One specific Purchase_order record who's $id is passed]
	 */

	public function get_purchase_order_by_id_lookup($id, $edit=FALSE)
	{
		$this->unset_purchase_order_search_filter();
        $purchase_order = $this->purchase_order_model->get_purchase_order_by_id_lookup($id, $edit);
		return $purchase_order;
	}

	/**
	 * [delete_purchase_order_by_id_lookup This function will delete a specific Purchase_order from database
	 * by its $id and Purchase_order picture from assets/admin/images/purchase_orders folder and then, redirects
	 * Purchase_order to the Purchase Orders page]
	 * @param  [type] $id [Purchase_order id whom record is to be deleted from database and picture
	 * from assets/admin/images/purchase_orders folder]
	 */

	public function delete_purchase_order_by_id_lookup($id)
	{
		$this->unset_purchase_order_search_filter();

		$record = $this->get_purchase_order_by_id_lookup($id);

		$this->delete_picture(COMPANY_IMAGE_PATH, $record);

		if ($this->purchase_order_model->delete_purchase_order($id)) {
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
			redirect('/admin/purchase_order/');
		}
	}

	public function search_purchase_order_lookup() {

		$this->get_purchase_order_search_filter();

		$this->set_purchase_order_search_filter();

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

        $config['per_page'] = $this->configuration_model->get_items_per_page();
		$config["uri_segment"] = URI_SEGMENT;

		$data["purchase_orders"] = $this->fetch_purchase_orders_lookup($config["per_page"], $current_page);
		$config["total_rows"] = $this->purchase_orders_count();

		$config["num_links"] = 1;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
		$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';

		// By clicking on performing NEXT pagination.
		$config['next_link'] = 'Next';

		// By clicking on performing PREVIOUS pagination.
		$config['prev_link'] = 'Previous';
		$config["cur_tag_open"] = "<li class='active'><a href='#'>";
		$config["cur_tag_close"] = "</a></li>";

		$this->pagination->initialize($config);

		$data["links"] = $this->pagination->create_links();

		$this->load->view('templates/admin/search_purchase_orders', $data);
	}

	/**
	 * @param int $per_page
	 * @param int $current_page
	 * @return mixed Purchase Orders JSON Object
	 *
	 */
	public function fetch_purchase_orders_lookup($per_page, $current_page, $is_low_quantity_products=TRUE)
	{
	    if(isset($_REQUEST['product_id'])) {
            $_POST['product_id'] = $_REQUEST['product_id'];
        }

        $product_ac_id = $this->input->post('product_id');
        $product_ac_company = strtolower($this->input->post('product_company'));
        $product_ac_quantity = $this->input->post('product_quantity');

        if(!empty($_POST['product_id']) && !empty($_POST['product_company']) && !empty($_POST['product_quantity'])) {
            $purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity,
                )
            );
        }

        else if(!empty($_POST['product_id']) && !empty($_POST['product_company'])) {
            $purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_id' => $product_ac_id
                )
            );
        }

        else if(!empty($_POST['product_id']) && !empty($_POST['product_quantity'])) {
            $purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

        else if(!empty($_POST['product_company']) && !empty($_POST['product_quantity'])) {
            $purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

		else if(!empty($_POST['product_company'])) {
			$purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_company' => $product_ac_company
				)
			);
		}

		else if(!empty($_POST['product_id'])) {
			$purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_id' => $product_ac_id
				)
			);
		}

		else if(!empty($_POST['product_quantity'])) {
			$purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_quantity' => $product_ac_quantity
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$purchase_orders = $this->purchase_order_model->fetch_purchase_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page
				)
			);
		}

		if(isset($purchase_orders)) return $purchase_orders;
	}

	/**
	 * @return mixed Purchase Orders count
	 *
	 */
	public function purchase_orders_count()
	{
        if(isset($_REQUEST['product_id'])) {
            $_POST['product_id'] = $_REQUEST['product_id'];
        }

		$product_id = isset($_POST['product_id']) && !empty($_POST['product_id']);
		$product_company = isset($_POST['product_company']) && !empty($_POST['product_company']);
		$product_quantity = isset($_POST['product_quantity']) && !empty($_POST['product_quantity']);
		$product_ac_id = $this->input->post('product_id');
		$product_ac_company = strtolower($this->input->post('product_company'));
		$product_ac_quantity = $this->input->post('product_quantity');

		if($product_id && $product_quantity && $product_company) {
			$purchase_orders = $this->purchase_order_model->record_count(array(
			    'product_id' => $product_ac_id,
                'product_quantity' => $product_ac_quantity,
				'product_company' => $product_ac_company
				)
			);
		}

        else if($product_id && $product_company) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_id' => $product_ac_id,
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_id && $product_quantity) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

        else if($product_quantity && $product_company) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_quantity' => $product_ac_quantity,
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_id) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_id' => $product_ac_id
                )
            );
        }

        else if($product_company) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_quantity) {
            $purchase_orders = $this->purchase_order_model->record_count(array(
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

		if(!$this->input->is_ajax_request()) {
			$purchase_orders = $this->purchase_order_model->record_count();
		}

		if(isset($purchase_orders)) {
			return $purchase_orders;
		}
	}

	public function set_purchase_order_search_filter() {
		$product_company = trim($this->input->post('product_company'));
		$product_id = trim($this->input->post('product_id'));
		$product_quantity = trim($this->input->post('product_quantity'));
		if(!empty($product_company) || !empty($product_id) || $product_quantity) {
			$this->session->set_userdata(array(
					'session_purcahse_order_product_company' 	=> $product_company,
					'session_purcahse_order_product_id'         => $product_id,
					'session_purcahse_order_product_quantity'   => $product_quantity
				)
			);
		}
	}

	public function get_purchase_order_search_filter() {

		$session_product_company = $this->session->userdata('session_purcahse_order_product_company');
		$product_company = $this->input->post('product_company');
		$session_product_id = $this->session->userdata('session_purcahse_order_product_id');
        $product_id = $this->input->post('product_quantity');
        $session_product_quantity = $this->session->userdata('session_purcahse_order_product_quantity');
        $product_quantity = $this->input->post('product_quantity');

		if(empty($product_company)) {
			$_POST['product_company'] = $session_product_company;
		}

		if(empty($product_id)) {
			$_POST['product_id'] = $session_product_id;
		}

		if(empty($product_quantity)) {
			$_POST['product_quantity'] = $session_product_quantity;
		}
	}

	public function unset_purchase_order_search_filter() {
		$this->session->unset_userdata(array(
				'session_purcahse_order_product_company', 'session_purcahse_order_product_id',
                'session_purcahse_order_product_quantity'
			)
		);
	}

	public function add_new_purchase_order_section() {
        $data['products'] = $this->product_model->get_products_dropdown();
        $data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
        $this->load->view('templates/admin/purchase_product_section', $data);
	}
}