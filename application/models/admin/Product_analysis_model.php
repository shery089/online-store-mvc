<?php  
class Product_analysis_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();        	
    }

    public function top_five_best_selling_products() {


        $date = $this->input->post('date');

        if(strpos($date, 'weeks')) {
            $filter = "%Y-%m-%d";
            $where = array(
                'DATE(`sales_order_details`.`created_date`) > ' => date("Y-m-d", strtotime("-4 weeks")),
                'DATE(`sales_order_details`.`created_date`) <= ' => date("Y-m-d")
            );

            $order_by = '`sales_order_details`.`created_date`)';

        }
        else if(strpos($date, 'week')) {
            $filter = "%a";
            $where = array(
                'DATE(`sales_order_details`.`created_date`) >= ' => date("Y-m-d", strtotime("-1 week +1 day")),
                'DATE(`sales_order_details`.`created_date`) <= ' => date("Y-m-d")
            );

            $order_by = 'DAY(`sales_order_details`.`created_date`)';

        }
        else if(strpos($date, 'months')) {
            $filter = "%M";

            if($date == '3 months') {
                $date = date("Y-m-d", strtotime("-3 month"));
            }
            else {
                $date = date("Y-m-d", strtotime("-6 month"));
            }

            $where = array(
                'DATE(`sales_order_details`.`created_date`) >= ' => $date,
                'DATE(`sales_order_details`.`created_date`) <= ' => date("Y-m-d")
            );

            $order_by = 'MONTH(`sales_order_details`.`created_date`)';

        }
        else if(is_numeric($date)) {
            $filter = "%M";
            $where = array(
                'DATE(`sales_order_details`.`created_date`) >= ' => $date.'-01-01',
                'DATE(`sales_order_details`.`created_date`) <= ' => $date.'-12-31',
            );

            $order_by = 'MONTH(`sales_order_details`.`created_date`)';

        }

        $this->db->select('SUM(`sales_order_details`.`sales_price`) as `total_selling`, 
        DATE_FORMAT(`sales_order_details`.`created_date`, "' . $filter .'") AS `sales_year`, `product`.`name` AS `product_name`');

        $this->db->from('`product`');
        $this->db->join('`product_detail`', '`product`.`id` = `product_detail`.`product_id`', 'inner');
        $this->db->join('`sales_order_details`', '`product_detail`.`id` = `sales_order_details`.`product_details_id`', 'inner');
        $this->db->where($where);
        $this->db->group_by(array('`product_name`'));
        $this->db->order_by($order_by);
        $this->db->limit(10);
        $q = $this->db->get();
//        echo $this->db->last_query();die;
        if ($q->num_rows() > 0) {
            $result = $q->result_array();
            return $result;
        }
        return array();
    }

    public function top_five_least_selling_products() {
        $this->db->select('COUNT(`sales_order_details`.`id`) as `total_least_selling`, `product`.`name` AS `product_name`');
        $this->db->from('`product`');
        $this->db->join('`product_detail`', '`product`.`id` = `product_detail`.`product_id`', 'inner');
        $this->db->join('`sales_order_details`', '`product_detail`.`id` = `sales_order_details`.`product_details_id`', 'inner');
        $this->db->where(array(
                            'DATE(`created_date`) >= ' => $this->input->post('start_date'),
                            'DATE(`created_date`) <= ' => $this->input->post('end_date')
                        ));
        $this->db->group_by(array('`product_name`'));
        $this->db->order_by('`total_least_selling`');
        $this->db->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->result_array();
            return $result;
        }
        return array();
    }

    public function total_sales() {
        $this->db->select('SUM(`sales_order`.`total`) as `total_selling`');
        $this->db->from('`sales_order`');
        $this->db->where(array(
                            'DATE(`sales_order`.`created_date`) >= ' => $this->input->post('start_date'),
                            'DATE(`sales_order`.`created_date`) <= ' => $this->input->post('end_date')
                        ));
        $this->db->limit(10);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->result_array();
            return $result;
        }
        return array();
    }
}