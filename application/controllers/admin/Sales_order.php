<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('layouts');
        $this->load->model('admin/configuration_model');
		$this->load->model('admin/sales_order_model');
		$this->load->model('admin/product_model');
        $this->load->model('admin/product_attribute_model');
        $this->load->model('admin/product_attribute_detail_model');
	}

	public function index()
	{
		$this->unset_sales_order_search_filter();
		if($this->input->is_ajax_request()) {
			$this->search_sales_order_lookup();
		}

        $title = 'Sales Orders';

		$this->layouts->set_title($title);

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();
        if($this->uri->segment(2) == 'inventory') {
            $data['entity'] = 'inventory';
            $config["base_url"] = str_replace('sales_order', 'inventory', $config["base_url"]);
        }
        else {
            $data['entity'] = 'sales_order';
        }

        if($this->uri->segment(3)) {
            $_POST['is_low_quantity_products'] = $this->configuration_model->get_minimum_products_notification();
            $config["base_url"] = str_replace('index', 'low_quantity_products', $config["base_url"]);
        }

        $config['per_page'] = $this->configuration_model->get_items_per_page();
		$config["uri_segment"] = URI_SEGMENT;

        $data["sales_orders"] = $this->fetch_sales_orders_lookup($config["per_page"], $current_page, TRUE);
		$config["total_rows"] = $this->sales_order_model->record_count();

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
			$this->layouts->view('templates/admin/sales_orders', $data);
		}
	}

	/**
	 * [add_sales_order_lookup: This method can work with both ajax call and
	 * normal PHP method call. Validates Sales_order data. If data is valid
	 * then, it allows access to its insert Sales_order model function. Otherwise,
	 * It gives appropriate error messages. After data is successfully
	 * inserted then, it gives a success flash message]
	 */
	public function add_sales_order_lookup()
	{
		$this->unset_sales_order_search_filter();

		$this->layouts->set_title('Add Sales Order');

		/**
		 * if its an ajax call then, set post data so
		 * post data will be available for validation.
		 */
		if ($this->input->is_ajax_request()) {
		    $data = array();
			foreach ($_POST as $key => $value) {
				if ($key == 'add_sales_order') {
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
                    $this->form_validation->set_rules(
                        "$key", "$name",
                        'trim|required|is_natural_no_zero|callback_check_product_quantity',
                        array(
                            'required' => '%s is required',
                            'is_natural_no_zero' => 'Numeric Values allowed'
                        )
                    );
                }

                else if (strpos($key, 'price') !== false) {
                    $name = 'Price';
                    $this->form_validation->set_rules(
                        "$key", "$name",
                        'trim|required|is_natural_no_zero|callback_check_product_price',
                        array(
                            'required' => '%s is required',
                            'is_natural_no_zero' => 'Numeric Values allowed'
                        )
                    );

                }
                else if (strpos($key, 'discount') !== false) {
                    continue;
                }

                if(strpos($key, 'product_attr_details') !== false ||
                   strpos($key, 'product_attribute') !== false ||
                   strpos($key, 'product') !== false) {

                    $this->form_validation->set_rules(

                        "$key", "$name",
                        'trim|required',
                        array(
                            'required' => '%s is required'
                        )
                    );
                }
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
					if ($key == 'add_sales_order') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}
				echo json_encode($errors);
			} else // if not an ajax call
			{
                $data['products'] = $this->product_model->get_products_dropdown();
                $data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
				$this->layouts->view('templates/admin/add_sales_order', $data);
			}
		} else // Validation Passed
		{
			if ($this->sales_order_model->insert_sales_order()) // insert into db
			{
				$this->session->set_flashdata('success_message', 'Sales Order has been successfully added!');
				unset($_POST);
				echo json_encode(array('success' => 'Sales Order inserted'));
			}
		}
	}

	/**
	 * [edit_sales_order_lookup: Edits a Sales Order by id, Validates Sales Order data. If data is valid then,
	 * it allows access to its edit Sales Order model function. Otherwise, It gives appropriate
	 * error messages. After data is successfully edited then, it gives a success flash
	 * else wise error flash message]
	 * @param  [type] $id [id to edit]
	 */
	public function edit_sales_order_lookup($id)
	{
		$this->unset_sales_order_search_filter();

        $title = 'Edit Sales Order';

        $data['entity'] = 'sales_order';

		$this->layouts->set_title($title);


        /**
         * if its an ajax call then, set post data so
         * post data will be available for validation.
         */
        if ($this->input->is_ajax_request()) {
            $data = array();
            foreach ($_POST as $key => $value) {
                if ($key == 'edit_sales_order') {
                    continue;
                }

                $data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
            }
            $this->form_validation->set_data($data);
        }

        // Attributes

        if(isset($data)) {
            foreach ($data as $key => $value) {
                if (strpos($key, 'quantity') !== false) {
                    $name = 'Quantity';
                    $this->form_validation->set_rules(
                        "$key", "$name",
                        'trim|required|is_natural_no_zero|callback_check_product_quantity',
                        array(
                            'required' => '%s is required',
                            'is_natural_no_zero' => 'Numeric Values allowed'
                        )
                    );
                }

                else if (strpos($key, 'price') !== false) {
                    $name = 'Price';
                    $this->form_validation->set_rules(
                        "$key", "$name",
                        'trim|required|is_natural_no_zero|callback_check_product_price',
                        array(
                            'required' => '%s is required',
                            'is_natural_no_zero' => 'Numeric Values allowed'
                        )
                    );

                }
                else if (strpos($key, 'discount') !== false) {
                    continue;
                }

                if(strpos($key, 'product_attr_details') !== false ||
                    strpos($key, 'product_attribute') !== false ||
                    strpos($key, 'product') !== false) {

                    $this->form_validation->set_rules(

                        "$key", "$name",
                        'trim|required',
                        array(
                            'required' => '%s is required'
                        )
                    );
                }
            }
        }

		$record = $this->get_sales_order_by_id_lookup($id, TRUE); // TRUE to get all items for edit purpose not full_name etc
        $data['sales_orders'] = $record;
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
					if ($key == 'edit_sales_order') {
						continue;
					}

					$errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
				}

				echo json_encode($errors);
			} else {
				$this->layouts->view('templates/admin/edit_sales_order', $data);
			}
		} else // Validation Passed
		{
			if($this->sales_order_model->update_sales_order($id))
			{
				$this->session->set_flashdata('success_message', 'Product <strong>' . ucwords($record['product_name']) . ' ' .
                    ' </strong> has been successfully updated!');
				echo json_encode(array('success' => 'Sales Order Updated'));
			}
		}
	}

	/**
	 * [get_modal loads a bootstrap modal in memory buffer and then prints it
	 * modal is appended to the body and is called by jquery in ajax success callback]
	 * @param  [int] $id [Sales_order id whom record is to be loaded in bootstrap modal]
	 */
	public function get_modal($id)
	{
		$this->unset_sales_order_search_filter();

		$data['sales_order'] = $this->get_sales_order_by_id_lookup($id);
		$this->load->view('templates/admin/sales_order_modal', $data);
	}

    /**
     * [edit_unique It's a callback function that is called in edit_sales_order_lookup
     * validation it checks if same attribute data exists other than the current
     * current record than returns FALSE. If does not exists it returns TRUE]
     * @param  [string] $value  [Sales Order entered value e.g. in case of email validation
     * sheryarahmed007@gmail.com]
     * @param  [string] $params [table.attribute.id e.g. Sales Order.email.3]
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
	 * [get_sales_order_by_id_lookup This function will retrieve a specific Sales_order from database
	 * by its $id]
	 * @param  [type]  $id   [Sales_order id whom record is to be retrieved from database]
	 * @param  boolean $edit [ Default value is FALSE. If it is set to TRUE then, it
	 * will return all items for edit purpose e.g. first_name, middle_name, last_name
	 * instead of full_name]
	 * @return [array] [One specific Sales_order record who's $id is passed]
	 */

	public function get_sales_order_by_id_lookup($id, $edit=FALSE)
	{
		$this->unset_sales_order_search_filter();
        $sales_order = $this->sales_order_model->get_sales_order_by_id_lookup($id, $edit);
		return $sales_order;
	}

	/**
	 * [delete_sales_order_by_id_lookup This function will delete a specific Sales_order from database
	 * by its $id and Sales_order picture from assets/admin/images/sales_orders folder and then, redirects
	 * Sales_order to the Sales Orders page]
	 * @param  [type] $id [Sales_order id whom record is to be deleted from database and picture
	 * from assets/admin/images/sales_orders folder]
	 */

	public function delete_sales_order_by_id_lookup($id)
	{
		$this->unset_sales_order_search_filter();

		$record = $this->get_sales_order_by_id_lookup($id);

		$this->delete_picture(COMPANY_IMAGE_PATH, $record);

		if ($this->sales_order_model->delete_sales_order($id)) {
			$this->session->set_flashdata('delete_message', 'Record has been successfully deleted!');
			redirect('/admin/sales_order/');
		}
	}

	public function search_sales_order_lookup() {

		$this->get_sales_order_search_filter();

		$this->set_sales_order_search_filter();

		$current_page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$config = array();
		$config["base_url"] = base_url('admin/') . '/' . $this->router->fetch_class() . '/' . $this->router->fetch_method();

        $config['per_page'] = $this->configuration_model->get_items_per_page();
		$config["uri_segment"] = URI_SEGMENT;

		$data["sales_orders"] = $this->fetch_sales_orders_lookup($config["per_page"], $current_page);
		$config["total_rows"] = $this->sales_orders_count();

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

		$this->load->view('templates/admin/search_sales_orders', $data);
	}

	/**
	 * @param int $per_page
	 * @param int $current_page
	 * @return mixed Sales Orders JSON Object
	 *
	 */
	public function fetch_sales_orders_lookup($per_page, $current_page, $is_low_quantity_products=TRUE)
	{
	    if(isset($_REQUEST['product_id'])) {
            $_POST['product_id'] = $_REQUEST['product_id'];
        }

        $product_ac_id = $this->input->post('product_id');
        $product_ac_company = strtolower($this->input->post('product_company'));
        $product_ac_quantity = $this->input->post('product_quantity');

        if(!empty($_POST['product_id']) && !empty($_POST['product_company']) && !empty($_POST['product_quantity'])) {
            $sales_orders = $this->sales_order_model->fetch_sales_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity,
                )
            );
        }

        else if(!empty($_POST['product_id']) && !empty($_POST['product_company'])) {
            $sales_orders = $this->sales_order_model->fetch_sales_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_id' => $product_ac_id
                )
            );
        }

        else if(!empty($_POST['product_id']) && !empty($_POST['product_quantity'])) {
            $sales_orders = $this->sales_order_model->fetch_sales_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

        else if(!empty($_POST['product_company']) && !empty($_POST['product_quantity'])) {
            $sales_orders = $this->sales_order_model->fetch_sales_orders(array(
                    'per_page' => $per_page,
                    'current_page' => $current_page,
                    'product_company' => $product_ac_company,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

		else if(!empty($_POST['product_company'])) {
			$sales_orders = $this->sales_order_model->fetch_sales_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_company' => $product_ac_company
				)
			);
		}

		else if(!empty($_POST['product_id'])) {
			$sales_orders = $this->sales_order_model->fetch_sales_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_id' => $product_ac_id
				)
			);
		}

		else if(!empty($_POST['product_quantity'])) {
			$sales_orders = $this->sales_order_model->fetch_sales_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page,
				'product_quantity' => $product_ac_quantity
				)
			);
		}

		if(!$this->input->is_ajax_request()) {
			$sales_orders = $this->sales_order_model->fetch_sales_orders(array(
				'per_page' => $per_page,
				'current_page' => $current_page
				)
			);
		}

		if(isset($sales_orders)) return $sales_orders;
	}

	/**
	 * @return mixed Sales Orders count
	 *
	 */
	public function sales_orders_count()
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
			$sales_orders = $this->sales_order_model->record_count(array(
			    'product_id' => $product_ac_id,
                'product_quantity' => $product_ac_quantity,
				'product_company' => $product_ac_company
				)
			);
		}

        else if($product_id && $product_company) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_id' => $product_ac_id,
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_id && $product_quantity) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_id' => $product_ac_id,
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

        else if($product_quantity && $product_company) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_quantity' => $product_ac_quantity,
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_id) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_id' => $product_ac_id
                )
            );
        }

        else if($product_company) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_company' => $product_ac_company
                )
            );
        }

        else if($product_quantity) {
            $sales_orders = $this->sales_order_model->record_count(array(
                    'product_quantity' => $product_ac_quantity
                )
            );
        }

		if(!$this->input->is_ajax_request()) {
			$sales_orders = $this->sales_order_model->record_count();
		}

		if(isset($sales_orders)) {
			return $sales_orders;
		}
	}

	public function set_sales_order_search_filter() {
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

	public function get_sales_order_search_filter() {

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

	public function unset_sales_order_search_filter() {
		$this->session->unset_userdata(array(
				'session_purcahse_order_product_company', 'session_purcahse_order_product_id',
                'session_purcahse_order_product_quantity'
			)
		);
	}

	public function add_new_sales_order_section() {
        $data['products'] = $this->product_model->get_products_dropdown();
        $data['product_attributes'] = $this->product_attribute_model->get_product_attributes_dropdown();
        $this->load->view('templates/admin/sales_product_section', $data);
	}

    public function check_product_quantity($value, $params)
    {
        $this->form_validation->set_message('check_product_quantity',
            'Out of Stock!');

        $post_array = array_values($this->input->post());
        if(strlen(trim($post_array[0])) > 0 && strlen(trim($post_array[1])) > 0 && strlen(trim($post_array[2])) > 0) {
            $result = $this->product_model->get_product_sales_price_and_quantity(trim($post_array[0]), trim($post_array[1]), trim($post_array[2]),
                TRUE, 'quantity');
            if ($result['quantity'] > 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
            return TRUE;
    }

    public function check_product_price()
    {
        $this->form_validation->set_message('check_product_price',
            'Price is not defined in Inventory!');

        $post_array = array_values($this->input->post());
        if(strlen(trim($post_array[0])) > 0 && strlen(trim($post_array[1])) > 0 && strlen(trim($post_array[2])) > 0) {
            $result = $this->product_model->get_product_sales_price_and_quantity(trim($post_array[0]), trim($post_array[1]), trim($post_array[2]),
                TRUE, 'sale_price');
            if ($result['sale_price'] > 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function pdf() {
        $content = '
                <h1 align="center">Intelligent Software Solutions</h1>
                <h3 align="right">Date: 22 June 2018</h3>
                <h3 align="right">Invoice Number: 3</h3>
                <table  border="1" cellspacing="0" align="center" cellpadding="6">
                <thead>
                    <tr>    
                        <th><b>Product</b></th>
                        <th><b>Quantity</b></th>
                        <th><b>Total</b></th>
                        <th><b>Total Discount</b></th>
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td>Samsung S7</td>
                        <td>1</td>
                        <td>45000</td>
                        <td>5000</td>
                        
                    </tr>
                                    </tbody>
            </table>
            <h3 align="right">Total: RS. 45000</h3>';
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A8', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf');
        $pdf->SetHeaderMargin(0);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');

        // add a page
        $pdf->AddPage();

        $pdf->writeHTMLCell(0, 0, '', '', $content, 0, 1, 0, true, '', true);
        ob_end_clean();
        $pdf->Output('pdfexample.pdf', 'D');
    }
}