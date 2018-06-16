<?php
    class Configuration_model extends CI_Model
    {

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Updates a configuration record into the database
         * @param $id
         * @param $image
         * @param $image_thumb
         * @param $profile_image
         * @param $joined_date
         * @return bool
         */
        public function update_configuration()
        {
            $this->item_per_page = trim($this->db->escape($this->input->post('item_per_page')), "' ");
            $this->minimum_products_notification = trim($this->db->escape($this->input->post('minimum_products_notification')), "' ");
            $this->show_notification = trim($this->db->escape($this->input->post('show_notification')), "' ");

            if(strlen($this->show_notification) > 0) {
                $this->show_notification = 0;
            }
            else {
                $this->show_notification = 1;
            }

            $data = array(

                'item_per_page' => $this->item_per_page,

                'minimum_products_notification' => $this->minimum_products_notification,

                'show_notification' => $this->show_notification

            );

            foreach ($data as $key => $val) {
                $this->db->where('config', $key);
                $this->db->update('configuration', array('value' => $val));
            }
            return TRUE;
        }

        /**
         * Returns configurations from the database
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_configuration_details_lookup()
        {
            $query = $this->db->get('`configuration`');
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result;
            }
            return FALSE;
        }

        /**
         * Returns items_per_page from the database
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_items_per_page()
        {
            $this->db->select('`value` AS `items_per_page`');
            $this->db->from('`configuration`');
            $this->db->where('`config`', 'item_per_page');
            $query = $this->db->get('');

            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return array_pop($result)['items_per_page'];
            }
            return FALSE;
        }

        /**
         * Returns items_per_page from the database
         * @param $id
         * @param $fields
         * @return bool
         */
        public function get_minimum_products_notification()
        {
            $this->db->select('`value` AS `minimum_products_notification`');
            $this->db->from('`configuration`');
            $this->db->where('`config`', 'minimum_products_notification');
            $query = $this->db->get('');

            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return array_pop($result)['minimum_products_notification'];
            }
            return FALSE;
        }
    }    