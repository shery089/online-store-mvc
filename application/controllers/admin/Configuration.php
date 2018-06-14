<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends PD_Photo
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('layouts');
		$this->load->model('admin/configuration_model');
	}

	public function index()
	{
        $this->layouts->set_title('Configurations');

        /**
         * if its an ajax call then, set post data so
         * post data will be available for validation.
         */

        if ($this->input->is_ajax_request()) {
            $data = array();
            foreach ($_POST as $key => $value) {
                if ($key == 'update_configurations_btn') {
                    continue;
                }

                $data[$key] = (!empty($this->input->post($key)) ? $this->input->post($key) : '');
            }

            $this->form_validation->set_data($data);
        }

        // Items Per Page
        $this->form_validation->set_rules(

            'item_per_page', 'Items Per Page',
            'trim|required|min_length[1]|max_length[20]|is_natural_no_zero',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

        // Minimum Products Notification
        $this->form_validation->set_rules(

            'minimum_products_notification', 'Minimum Products Notification',
            'trim|required|min_length[1]|max_length[20]|is_natural_no_zero',
            array(
                'required' => '%s is required',
                'min_length' => '%s should be at least %s chars',
                'max_length' => '%s should be at most %s chars'
            )
        );

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
                    if ($key == 'update_configurations_btn') {
                        continue;
                    }

                    $errors[$key] = (!empty(form_error($key)) ? form_error($key) : '');
                }

                echo json_encode($errors);
            } else {

                $data['configurations'] = $this->configuration_model->get_configuration_by_id_lookup();

                $data['configurations'] = array_column($data['configurations'], 'value', 'config');

                $this->layouts->view('templates/admin/configurations', $data);
            }
        } else // Validation Passed
        {
            if($this->configuration_model->update_configuration())
            {
                $this->session->set_flashdata('success_message', 'Configurations have been successfully updated!');
                echo json_encode(array('success' => 'Configurations Updated'));
            }
        }
	}
}