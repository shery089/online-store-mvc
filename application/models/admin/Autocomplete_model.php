<?php  
	class Autocomplete_model extends CI_Model {

        public function __construct()
        {
            parent::__construct();
        }

        public function get_political_party_by_name($political_party)
        {
            $result = $this->get_political_party_by_name_details($political_party, 'after');

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = $this->get_political_party_by_name_details($political_party, 'both');

                if(!empty($result))
                {
                    echo json_encode($result);
                }
                else
                {
                    $result = array('name' => 'no results found', 'thumbnail' => 'no_result_thumb.png');
                    echo json_encode([$result]);
                }
            }
        }

        public function get_politician_by_party_id()
        {

            $party_id = trim($this->db->escape($this->input->post('id')), "' ");   
            $halqa_type = trim($this->db->escape($this->input->post('halqa_type')), "' ");   
            
            if($halqa_type != 'NULL')
            {
                $result = $this->display_filter_results($halqa_type, $party_id);
            }
            else
            {

                $this->db->select('politician.id, politician.name, politician.thumbnail, politician.profile_image, 
                                    political_party.id AS party_id, political_party.name AS party_name');
                
                $this->db->from('politician');      

                $this->db->where('politician.political_party_id', $party_id);

                $this->db->join('political_party', 'political_party.id = politician.political_party_id', 'left');

                $this->db->order_by("politician.name", "asc");
                        
                $q = $this->db->get();

                if ($q->num_rows() > 0) 
                {
                    $result = $q->result_array();
                }
                else
                {
                    $result = array();
                }
            }

            return $result;
        }

        public function get_politicians_by_keys_filter($last_record)
        {
            $party_id = trim($this->db->escape($this->input->post('party')), "' ");   
            $province = trim($this->db->escape($this->input->post('province')), "' ");   
            $age = trim($this->db->escape($this->input->post('age')), "' ");   
            $city = trim($this->db->escape($this->input->post('city')), "' ");   
            $halqa_type = trim($this->db->escape($this->input->post('halqa_type')), "' ");   
            $gender = trim($this->db->escape($this->input->post('gender')), "' ");
            $provincial_halqa = trim($this->db->escape($this->input->post('provincial_halqa')), "' ");
            $halqa_type = $halqa_type == 'NULL' ? '' : $halqa_type;
            $gender = $gender == 'NULL' ? '' : $gender;
            switch ($age) {
                case 'lt30':
                        $age = '< 30';
                    break;
                case 'gt30lt45':
                        $age = 'BETWEEN 30 AND 45';
                    break;
                case 'gt45':
                        $age = '> 45';
                    break;
                default:
                        $age = '';
                    break;
            }

            $last_record = (int) $last_record;

            if($halqa_type != '')
            {
                $id = $this->halqa_type_model->get_id_by_key($halqa_type);
            
                $id = $id['id'];
                
                $halqas = $this->halqa_model->get_halqas_by_type_id($id, TRUE);
            
                $halqas = array_column($halqas, 'id');
                            
                $halqas_string = implode(',', $halqas);
            }

            $this->db->query('SET group_concat_max_len = 2048');

            if(!empty($party_id) && !empty($age) && !empty($halqa_type) && !empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician`.`gender` = '$gender' 
                AND ( `politician`.`age` $age) AND `politician_details`.`halqa_id` IN ($halqas_string) GROUP BY `politician`.`name`
                ORDER BY `politician`.`name`";
            }

            else if(!empty($party_id) && !empty($age) && !empty($provincial_halqa))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND ( `politician`.`age` $age) AND 
                `politician_details`.`halqa_id` = '$provincial_halqa' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($party_id) && !empty($age) && !empty($halqa_type))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND ( `politician`.`age` $age) AND 
                `politician_details`.`halqa_id` IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($party_id) && !empty($gender) && !empty($halqa_type))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician`.`gender` = '$gender' AND 
                `politician_details`.`halqa_id` IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($party_id) && !empty($gender) && !empty($provincial_halqa))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician`.`gender` = '$gender' AND 
                `politician_details`.`halqa_id` = '$provincial_halqa'  GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($party_id) && !empty($age) && !empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND ( `politician`.`age` $age) AND 
                GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            
            else if(!empty($halqa_type) && !empty($age) && !empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`gender` = '$gender' AND ( `politician`.`age` $age) AND 
                `politician_details`.`halqa_id` IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            
            else if(!empty($halqa_type) && !empty($age) && !empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`gender` = '$gender' AND ( `politician`.`age` $age) AND 
                `politician_details`.`halqa_id` = '$provincial_halqa' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
             
            else if(!empty($halqa_type) && !empty($age))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE ( `politician`.`age` $age) AND `politician_details`.`halqa_id` 
                IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }    

            else if(!empty($provincial_halqa) && !empty($age))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE ( `politician`.`age` $age) AND `politician_details`.`halqa_id` 
                = '$provincial_halqa' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($halqa_type) && !empty($gender)) 
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`gender` = '$gender' AND `politician_details`.`halqa_id` 
                IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($provincial_halqa) && !empty($gender)) 
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`gender` = '$gender' AND `politician_details`.`halqa_id` 
                 = '$provincial_halqa' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            
            else if(!empty($halqa_type) && !empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` 
                ON `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician_details`.`halqa_id` 
                IN ($halqas_string) GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($provincial_halqa) && !empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` 
                ON `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician_details`.`halqa_id` 
                 = '$provincial_halqa' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            
            else if(!empty($gender) && !empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` 
                ON `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND `politician`.`gender` = '$gender' 
                GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            else if(!empty($age) && !empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` 
                ON `politician_details`.`halqa_id` = `halqa`.`id` WHERE `political_party`.`id` = '$party_id' AND ( `politician`.`age` $age) 
                GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }
            else if(!empty($age) && !empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` 
                ON `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`gender` = '$gender' AND ( `politician`.`age` $age) 
                GROUP BY `politician`.`name` ORDER BY `politician`.`name` LIMIT $last_record, 10";        
            }
            else if(!empty($age))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN `halqa` ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician`.`age` $age GROUP BY `politician`.`name` 
                ORDER BY `politician`.`name` LIMIT $last_record, 10";
            }
            else if(!empty($gender))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` AS party_id,
                        `political_party`.`name` AS party_name FROM `politician` LEFT JOIN `political_party` ON 
                        `political_party`.`id` = `politician`.`political_party_id`  WHERE `politician`.`gender` = '$gender' 
                        GROUP BY `politician`.`name` ORDER BY `politician`.`name` LIMIT $last_record, 10";
    
            }            
            else if(!empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN 
                `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` 
                LEFT JOIN `halqa` ON `politician_details`.`halqa_id` = `halqa`.`id` 
                LEFT JOIN `halqa_type` ON `halqa_type`.`id` = `halqa`.`type`
                WHERE `political_party`.`id` = '$party_id'
                GROUP BY `politician`.`name` ORDER BY `politician`.`name`";
            }

            else if(!empty($provincial_halqa))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                AS party_id,`political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                AS `halqa_name` FROM `politician`
                LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` 
                LEFT JOIN `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` 
                LEFT JOIN `halqa` ON `politician_details`.`halqa_id` = `halqa`.`id` 
                WHERE `halqa`.`id` = '$provincial_halqa' GROUP BY `politician`.`name`, `political_party`.`name`";
            }
            else 
            {
                if(!empty($halqa_type))
                {
                    $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, `political_party`.`id` 
                    AS party_id,`political_party`.`name` AS party_name, `politician_details`.`halqa_id`, GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ') 
                    AS `halqa_name` FROM `politician`
                    LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` 
                    LEFT JOIN `politician_details` ON `politician_details`.`politician_id` = `politician`.`id` 
                    LEFT JOIN `halqa` ON `politician_details`.`halqa_id` = `halqa`.`id` 
                    LEFT JOIN `halqa_type` ON `halqa_type`.`id` = `halqa`.`type` 
                    WHERE `halqa_type`.`abbreviation` = '$halqa_type' GROUP BY `politician`.`name`, `political_party`.`name`";
                }
                    /*
                    SELECT `pol`.`id`, `pol`.`name`, `pol`.`thumbnail`, `pol`.`profile_image`, `pp`.`id` 
                    AS party_id,`pp`.`name` AS party_name, `pd`.`halqa_id`, GROUP_CONCAT(`h`.`name` SEPARATOR ', ') 
                    AS `halqa_name` FROM `politician` AS pol, `political_party` AS pp, `politician_details` AS pd, `halqa` AS h, `halqa_type` AS ht
                    WHERE ht.`id` = '$halqa_type' AND pp.`id` = pol.`political_party_id` AND pd.`politician_id` = pol.`id` AND 
                    pd.`halqa_id` = h.`id` AND ht.`id` = h.`type` GROUP BY pol.`name`*/
            }
            unset($q);
            $q = $this->db->query($sql);
            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }

            return $result;
        }

        public function display_filter_results($halqa_type, $party_id = '')
        {
            $halqa_type = trim($this->db->escape($halqa_type), "' ");

            $id = $this->halqa_type_model->get_id_by_key($halqa_type );
            
            $id = $id['id'];
            
            $halqas = $this->halqa_model->get_halqas_by_type_id($id, TRUE);
            
            $halqas = array_column($halqas, 'id');
            
            $halqas_string = implode(',', $halqas);

            $this->db->query('SET group_concat_max_len = 2048');

            if(empty($party_id))
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`,
                `political_party`.`id` AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, 
                GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ' ) AS `halqa_name` FROM `politician` LEFT JOIN `political_party`
                ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN politician_details ON 
                `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN halqa ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician_details`.`halqa_id` IN ($halqas_string)
                GROUP BY `politician`.`name` ORDER BY `politician`.`name`";                
            }
            else
            {
                $sql = "SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`,
                `political_party`.`id` AS party_id, `political_party`.`name` AS party_name, `politician_details`.`halqa_id`, 
                GROUP_CONCAT(`halqa`.`name` SEPARATOR ', ' ) AS `halqa_name` FROM `politician` LEFT JOIN `political_party`
                ON `political_party`.`id` = `politician`.`political_party_id` LEFT JOIN politician_details ON 
                `politician_details`.`politician_id` = `politician`.`id` LEFT JOIN halqa ON 
                `politician_details`.`halqa_id` = `halqa`.`id` WHERE `politician_details`.`halqa_id` IN ($halqas_string) AND 
                `politician`.`political_party_id` = '$party_id' GROUP BY `politician`.`name` ORDER BY `politician`.`name`";   
            }

            $q = $this->db->query($sql);

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }
 
            return $result;
        }

        public function get_entity_by_name($entity)
        {
            $result = $this->get_entity_by_name_details($entity);
        }

        public function get_entity_by_name_details($entity, $return = FALSE, $records = '')
        {
            $entity = strtolower(trim($this->db->escape($entity), "' "));
            
            if(strpos($entity, ' ') !== FALSE)
            {
                
                $entity_plus = preg_replace("/\s+/", " +", $entity);
                $entity_plus = '+' . $entity_plus . '*';
                // +Pakistan +Muslim +league +(n*
                // '+"league (n"'
                if(strpos($entity, '(') !== FALSE)
                {
                    $needle = "+";
                    $lastPos = 0;
                    $positions = array();

                    while (($lastPos = strpos($entity_plus, $needle, $lastPos))!== false)
                    {
                        $positions[] = $lastPos;
                        $lastPos = $lastPos + strlen($needle);
                    }
                    
                    $second_last_item = count($positions) - 2;
                    $to_replace = substr($entity_plus, $positions[$second_last_item]);
                    $to_replace = str_replace(str_split('*+'), '', $to_replace);
                    $to_replace = str_replace('', '', $to_replace);
                    $to_replace = '+"' . $to_replace . '"';
                    $e_plus_len = strlen($entity_plus);
                    $entity_plus = substr_replace($entity_plus,$to_replace,$positions[$second_last_item], $e_plus_len);
                }

                if(empty($records))
                {
                    $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`,
                    `politician`.`political_party_id` FROM `politician` WHERE MATCH (`politician`.`name`) AGAINST 
                    ('$entity_plus' IN BOOLEAN MODE) ORDER BY `politician`.`name` LIMIT 8)
                    UNION ALL
                    (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, ''
                    FROM `political_party` WHERE MATCH (`political_party`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE)
                    ORDER BY `political_party`.`name` LIMIT 8)");
                }
                else
                {
                    $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`,
                    `politician`.`profile_image`, `politician`.`political_party_id`, `political_party`.`name` AS party_name 
                    FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` WHERE 
                    MATCH (`politician`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE) ORDER BY `politician`.`name`)
                    UNION ALL
                    (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, 
                    `political_party`.`profile_image`,  '', ''
                    FROM `political_party` WHERE MATCH (`political_party`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE)
                    ORDER BY `political_party`.`name`)");
                }
            }
            else
            {
                $entity_plus = $entity . '*';

                if(empty($records))
                {
                    $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`,
                    `politician`.`political_party_id` FROM `politician` WHERE MATCH (`politician`.`name`) AGAINST 
                    ('$entity_plus' IN BOOLEAN MODE) LIMIT 8)
                    UNION ALL
                    (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, ''
                    FROM `political_party` WHERE MATCH (`political_party`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE)
                    LIMIT 8)");
                }
                else
                {
                    $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`,
                    `politician`.`profile_image`, `politician`.`political_party_id`, `political_party`.`name` AS party_name 
                    FROM `politician` LEFT JOIN `political_party` ON `political_party`.`id` = `politician`.`political_party_id` 
                    WHERE MATCH (`politician`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE))
                    UNION ALL
                    (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, 
                    `political_party`.`profile_image`, '', '' FROM `political_party` WHERE MATCH (`political_party`.`name`)
                    AGAINST ('$entity_plus' IN BOOLEAN MODE))");
                }

            }
            if ($q->num_rows() > 0)
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }

            if(empty($result))
            {
                $result = array('name' => 'no results found', 'thumbnail' => 'no_result_thumb.png');
                if($return)
                {
                    return $result;
                }
                else
                {
                    echo json_encode([$result]);
                }
            }
            else
            {
                if($return)
                {
                    return $result;
                }
                else
                {
                    echo json_encode($result);
                }
            }
        }

        public function get_entity_by_name_detail($entity, $return = FALSE, $like_option_search_btn = '')
        {
            $entity = trim($this->db->escape($entity), "' ");

            if(!empty($like_option_search_btn))
            {
                $pol_entity = "%$entity%";
                $party_entity = "%$entity%";
            }
            else
            {
                // $pol_entity = "%$entity%";
                // $party_entity = "%$entity%";

             /*   $pol_after = $this->count_entity_by_key('politician', $entity, 'after');
                $pol_both =  $this->count_entity_by_key('politician', $entity, 'both');

                $pol_entity = $pol_after > 0 ? "$entity%" : "%$entity%";
                
                $party_after = $this->count_entity_by_key('political_party', $entity, 'after');
                $party_both =  $this->count_entity_by_key('political_party', $entity, 'both');

                $party_entity = $party_after > 0 ? "$entity%" : "%$entity%";
*/          }
/*
            $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`, `politician`.`profile_image`, 
            `politician`.`political_party_id`, `political_party`.`name` AS party_name FROM `politician` LEFT JOIN `political_party`
            ON `political_party`.`id` = `politician`.`political_party_id` WHERE `politician`.`name` LIKE '$entity%' LIMIT 8)
            UNION ALL
            (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, `political_party`.`profile_image`, '', ''
            FROM `political_party` WHERE `political_party`.`name` LIKE '$entity%' LIMIT 8)");

            if ($q->num_rows() > 0) 
            {
                $result = $q->result_array();
            }*/
            
            if(strpos($entity, ' ') !== FALSE)
            {
                $entity_plus = preg_replace("/\s+/", " +", $entity);
                $entity_plus = '+' . $entity_plus . '*';
            }
            else
            {
                $entity_plus = $entity . '*';
            }

            $q = $this->db->query("(SELECT `politician`.`id`, `politician`.`name`, `politician`.`thumbnail`,
            `politician`.`political_party_id` FROM `politician` WHERE MATCH (`politician`.`name`) AGAINST 
            ('$entity_plus' IN BOOLEAN MODE) LIMIT 8)
            UNION ALL
            (SELECT `political_party`.`id`, `political_party`.`name`, `political_party`.`thumbnail`, ''
            FROM `political_party` WHERE MATCH (`political_party`.`name`) AGAINST ('$entity_plus' IN BOOLEAN MODE) LIMIT 8)");
            if ($q->num_rows() > 0)
            {
                $result = $q->result_array();
            }
            else
            {
                $result = array();
            }

            if(empty($result))
            {
                $result = array('name' => 'no results found', 'thumbnail' => 'no_result_thumb.png');
                if($return)
                {
                    return $result;
                }
                else
                {
                    echo json_encode([$result]);
                }
            }
            else
            {
                if($return)
                {
                    return $result;
                }
                else
                {
                    echo json_encode($result);
                }
            }
        }

        public function count_entity_by_key($entity, $search_term, $like_option)
        {
            $q = $this->db->like("$entity.`name`", $search_term, $like_option)->get($entity);
            
            return $q->num_rows();
        }

        public function get_politician_by_name($politician)
        {
            $result = $this->get_politician_by_name_details($politician, 'after');

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = $this->get_politician_by_name_details($politician, 'both');

                if(!empty($result))
                {
                    echo json_encode($result);
                }
                else
                {
                    $result = array('name' => 'no results found', 'thumbnail' => 'no_result_thumb.png');
                    echo json_encode([$result]);
                }
            }
        }

        public function get_political_party_by_name_details($political_party, $like_option)
        {
            $political_party = trim($this->db->escape($political_party), "' ");   

            $this->db->select('political_party.id, political_party.name, political_party.thumbnail, political_party.profile_image');
            
            $this->db->from('political_party');      
            
            $this->db->like('political_party.name', $political_party, $like_option);

            $this->db->order_by("political_party.name", "asc");
                    
            $q = $this->db->get();

            $result = $q->result_array();

            return $result;
        }

        public function get_politician_by_name_details($politician, $like_option)
        {
            $politician = trim($this->db->escape($politician), "' ");   

            $this->db->select('politician.id, politician.name, politician.thumbnail, politician.profile_image, 
                                political_party.id AS party_id, political_party.name AS political_party');
            
            $this->db->from('politician');      
            
            $this->db->like('politician.name', $politician, $like_option);

            $this->db->join('political_party', 'political_party.id = politician.political_party_id', 'left');

            $this->db->order_by("politician.name", "asc");
                    
            $q = $this->db->get();

            $result = $q->result_array();

            return $result;
        }

        public function get_entity_by_key($entity)
        {
            $result = $this->get_entity_by_name_details($entity, TRUE);

            return $result;
        }

        public function get_entities_by_key($entity)
        {
            $result = $this->get_entity_by_name_details($entity, TRUE, 'ALL');

            return $result;
        }

        public function insert_cities_bulk_by_csv($csv)
        {
            if ($this->db->insert_batch('city', $csv))
            {
                return TRUE;
            }
        }   

        public function get_province_id_by_key($province)
        {
            $province = trim($this->db->escape($province), "' ");   

            $this->db->select('province.id');
            
            $this->db->from('province');      
            
            $this->db->where('province.name', $province);

            $q = $this->db->get();

            $result = $q->result_array();

            $result = $result[0];

            return $result;

        }

        public function get_cities()
        {
            $this->db->select('city.id, city.name');

            $this->db->from('city');      
            
            $this->db->order_by('city.name');      
            
            $q = $this->db->get();

            $result = $q->result_array();

            return $result;
        }

        public function get_city_by_id($id)
        {
            $this->db->select('city.id, city.name');

            $this->db->from('city');      
            
            $this->db->where('city.id', $id);      
                        
            $q = $this->db->get();

            $result = $q->result_array();

            $result = $result[0];

            return $result;
        }

        public function get_provinces()
        {
            $this->db->select('province.id, province.name');

            $this->db->from('province');      
            
            $this->db->order_by('province.name');      
            
            $q = $this->db->get();

            $result = $q->result_array();

            return $result;
        }

        public function get_political_party_by_key()
        {
            $search_term = html_escape(strtolower(trim($this->db->escape($this->input->post('search_term')), "' ")));

            $result = $this->get_political_party_by_name_details($search_term, 'after');

            if(!empty($result))
            {
                return $result;
            }
            else
            {
                $result = $this->get_political_party_by_name_details($search_term, 'both');

                return $result;
            }
        }

        public function get_politician_by_key()
        {
            $search_term = html_escape(strtolower(trim($this->db->escape($this->input->post('search_term')), "' ")));

            $result = $this->get_politician_by_name_details($search_term, 'after');

            if(!empty($result))
            {
                return $result;
            }
            else
            {
                $result = $this->get_politician_by_name_details($search_term, 'both');

                return $result;
            }
        }


/*        public function get_values()
        {
            $mr_number = html_escape(trim($this->db->escape($this->input->post('mr_number')), "'' "));
 
            $this->db->distinct();

            $this->db->select('patient.id, patient.mr_number');
            
            $this->db->from('patient');      
            
            $this->db->like('patient.mr_number', $mr_number);
            
            $this->db->join('appointment', 'patient.id = appointment.patient_id', 'left');

            $this->db->order_by("patient.mr_number", "asc");
        
            $q = $this->db->get();

            $result = $q->result_array();
 
            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('mr_number' => 'No Results Found');
                echo json_encode([$result]);
            }
        }

        public function get_mr_number_by_cnic()
        {
            $old_cnic = html_escape(trim($this->db->escape($this->input->post('old_cnic')), "'' "));
 
            $this->db->distinct();

            $this->db->select('patient.id, patient.mr_number');
            
            $this->db->from('patient');      
            
            $this->db->where('patient.cnic', $old_cnic);
                    
            $q = $this->db->get();

            $result = $q->result_array();
 
            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('mr_number' => 'No Results Found');
                echo json_encode([$result]);
            }
        }

        public function get_patient_father()
        {
            $father_name = html_escape(trim($this->db->escape($this->input->post('father_name')), "'' "));   

            $this->db->select('patient.id, patient.father_name');
            
            $this->db->from('patient');      
            
            $this->db->like('patient.father_name', $father_name);

            $this->db->order_by("patient.father_name", "asc");
        
            $this->db->group_by('patient.father_name');  
            
            $q = $this->db->get();

            $result = $q->result_array();

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('father_name' => 'No Results Found');
                echo json_encode([$result]);
            }
        }


        public function get_patient_mobile_number()
        {
            $mobile_number = html_escape(trim($this->db->escape($this->input->post('search_mobile_number')), "'' "));   

            $this->db->select('patient.id, patient.mobile_number');
            
            $this->db->from('patient');      
            
            $this->db->like('patient.mobile_number', $mobile_number);

            $this->db->order_by("patient.mobile_number", "asc");
        
            $this->db->group_by('patient.mobile_number');  
            
            $q = $this->db->get();

            $result = $q->result_array();

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('mobile_number' => 'No Results Found');
                echo json_encode([$result]);
            }
        }

        public function get_doctor_mobile_number()
        {
            $mobile_number = html_escape(trim($this->db->escape($this->input->post('search_doc_mobile_number')), "'' "));   

            $this->db->select('doctor.id, doctor.mobile_number');
            
            $this->db->from('doctor');      
            
            $this->db->like('doctor.mobile_number', $mobile_number);

            $this->db->order_by("doctor.mobile_number", "asc");
        
            $this->db->group_by('doctor.mobile_number');  
            
            $q = $this->db->get();

            $result = $q->result_array();

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('mobile_number' => 'No Results Found');
                echo json_encode([$result]);
            }
        }

        public function get_doctor_specialization()
        {
            $specialization = html_escape(trim($this->db->escape($this->input->post('search_doc_specialization')), "'' "));   

            $this->db->distinct();

            $this->db->select('doctor_specialization.id, doctor_specialization.name');
            
            $this->db->from('doctor_specialization');
            
            $this->db->like('doctor_specialization.name', $specialization);

            $this->db->join('doctor_details', 'doctor_specialization.id = doctor_details.specialization_id', 'inner');
                        
            $q = $this->db->get();
            
            $result = $q->result_array();

            if(!empty($result))
            {
                echo json_encode($result);
            }
            else
            {
                $result = array('name' => 'No Results Found');
                echo json_encode([$result]);
            }
        }

        public function get_appointments()
        {
            $mr_number = html_escape(trim($this->db->escape($this->input->post('mr_number')), "'' "));

            $date = html_escape(trim($this->db->escape($this->input->post('date')), "''"));

            $time = html_escape(trim($this->db->escape($this->input->post('time')), "''"));
            
            if(!empty($time))
            {
                $time = explode(":", $time);
                $time[0] = trim($time[0]);
                $time[1] = trim($time[1]);
                $time = implode(":", $time);
            }

            $this->db->distinct();

            $this->db->select('appointment.*');
            
            $this->db->from('patient');      
                       
            if(!empty($mr_number) && !empty($date) && !empty($time))
            {
                $where = "appointment.date = '$date' AND 'patient.mr_number', $mr_number AND ( appointment.morning_shift = '$time' OR appointment.evening_shift = '$time')";
                $this->db->where($where);
                // $this->db->where(array('appointment.date' => $date, 'appointment.time' => $time));
            }            
            elseif(!empty($mr_number) && !empty($date))
            {
                $this->db->where(array('appointment.date' => $date, 'patient.mr_number', $mr_number));
            }
            elseif(!empty($time) && !empty($date))
            {
                $where = "appointment.date = '$date' AND ( appointment.morning_shift = '$time' OR appointment.evening_shift = '$time')";
                $this->db->where($where);            }            
            elseif(!empty($time) && !empty($mr_number))
            {
                $where = "patient.mr_number = '$mr_number' AND (appointment.morning_shift = '$time' OR appointment.evening_shift = '$time')";
                $this->db->where($where);
            }
            elseif(!empty($date))
            {
                // $this->db->like(array('patient.mr_number' => $mr_number, 'appointment.date' => $date));
                $this->db->where('appointment.date', $date);
            }
            elseif(!empty($time))
            {
                $where = "appointment.morning_shift = '$time' OR appointment.evening_shift = '$time'";
                $this->db->where($where);
                // $this->db->like(array('patient.mr_number' => $mr_number, 'appointment.date' => $date));
            }            
            else
            {   
                $this->db->where('patient.mr_number', $mr_number);
            }
            
            $this->db->join('appointment', 'patient.id = appointment.patient_id', 'inner');

            $this->db->order_by('appointment.date', 'desc');
        
            $q = $this->db->get();

            $result = $q->result_array();

            $result = $this->appointment_model->get_attachments($result);

            if(($result))
            {
                return json_encode($result);
            }
            else
            {
                $result = array('error_message' => 'No Results Found');
                return json_encode([$result]);
            }
        }        

        public function get_appointments_by_doctor()
        {
            $doctor = html_escape(trim($this->db->escape($this->input->post('doctor')), "'' "));
            $date = $this->input->post('date');
            $time = html_escape(trim($this->db->escape($this->input->post('time')), "'' "));
            
            if(!empty($time))
            {
                $time = explode(":", $time);
                $time[0] = trim($time[0]);
                $time[1] = trim($time[1]);
                $time = implode(":", $time);
            }

            $this->db->distinct();

            $this->db->select('appointment.*');
            
            $this->db->from('doctor');      
                       
            if(!empty($doctor) && !empty($time))
            {
                $this->db->like('doctor.first_name', $doctor);
                // $this->db->where(array('doctor.first_name' => $doctor, 'appointment.time' => $time));
                $where = "doctor.first_name = '$doctor' AND ( appointment.morning_shift = '$time' OR appointment.evening_shift = '$time')";
                $this->db->where($where);
            }            
            elseif(!empty($doctor))
            {
                // $this->db->like(array('patient.mr_number' => $mr_number, 'appointment.date' => $date));
                $this->db->where('doctor.first_name', $doctor);
            }
            else
            {
                if(!empty($time))
                {
                    // $this->db->where(array('appointment.date' => $date, 'appointment.time' => $time));
                    $where = "appointment.date = '$date' AND ( appointment.morning_shift = '$time' OR appointment.evening_shift = '$time')";
                    $this->db->where($where);
                }            
            }
            
            $this->db->join('appointment', 'doctor.id = appointment.doctor_id', 'left');

            // $this->db->order_by('appointment.date', 'desc');
        
            $q = $this->db->get();

            $result = $q->result_array();

            $result = $this->appointment_model->get_attachments($result);

            if(($result))
            {
                return json_encode($result);
            }
            else
            {
                $result = array('error_message' => 'No Results Found');
                return json_encode([$result]);
            }
        }*/
	}