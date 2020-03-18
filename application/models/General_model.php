<?php
class General_model extends CI_Model {

    private $_table;
    private $_fields;
    public $fields;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    // Custom Pagination 
     public function fetch_data($table, $limit, $id, $start='', $where= '', $sort='') {
         $on = '';
        if(isset($sort) && $sort!=''){
           
    switch($sort){

        case "1A":

            $on="'product_id', 'Asc'";

            break;

        case "1D":

            $on="product_id Desc";

            break;

        case "2A":

            $on="SKU Asc";

            break;

        case "2D":

            $on="SKU Desc";

            break;

        case "3A":

            $on="name Asc";

            break;

        case "3D":

            $on="name Desc";

            break;

        case "4A":

            $on="CAST(`price` AS decimal) Asc";

            break;

        case "4D":

            $on="CAST(`price` AS decimal) Desc";

            break;

        case "5A":

            $on="CAST(`sale_price` AS decimal) Asc";

            break;

        case "5D":

            $on="CAST(`sale_price` AS decimal) Desc";

            break;

        case "6A":

            $on="ABS(`current_stock`) Asc";

            break;

        case "6D":

            $on="ABS(`current_stock`) Desc";

            break;

        default :

            $on="'product_id', 'Desc'";

            break;

    }

}
        $this->db->start_cache();
        $this->db->select('*');
        $this->db->from('products');
        if($where!=''){
            $this->db->where($where);
         }  
         $this->db->order_by($on); 
         $this->db->stop_cache();
        $totalRows = $this->db->count_all_results();

        $this->db->limit($limit, $start);
        $query = $this->db->get();
       // echo $this->db->last_query();die;
         
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data['result'][] = $row;
            }
            $this->db->flush_cache();
            $data['total_count'] = $totalRows;
            return $data;
        }
        $this->db->flush_cache();
        return false;
    }

    public function fetch_order_data($table, $limit, $id, $start='', $where= '', $sort='') {
        $on="po.order_id DESC";
        if(isset($sort) && $sort!=''){
           
            switch($sort){

                case "1A":

                    $on="po.order_date Asc";

                    break;

                case "1D":

                    $on="po.order_date Desc";

                    break;

                case "2A":

                    $on="po.user_name Asc";

                    break;

                case "2D":

                    $on="po.user_name Desc";

                    break;
                case "3A":

                    $on="po.email Asc";

                    break;

                case "3D":

                    $on="po.email Desc";

                    break;

                case "4A":

                    $on="po.received_date Asc";

                    break;

                case "4D":

                    $on="po.received_date Desc";

                    break; 

            default :

                $on="po.order_id Desc";

                break; 
            } 
        }

        $this->db->start_cache();
        $this->db->select('po.*');
        $this->db->from('purchase_order as po');
        //$this->db->join('purchase_order_product as pop', "pop.order_id = po.order_id", "inner"); 
        if($where!=''){
            $this->db->where($where);
        }  
        $this->db->order_by($on);
        //$this->db->group_by('po.order_id'); 
        $this->db->stop_cache();
        $totalRows = $this->db->count_all_results();
        
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query();die; 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data['result'][] = $row;
            }
            $this->db->flush_cache();
            $data['total_count'] = $totalRows;
            return $data;
        }
        $this->db->flush_cache();
        return false;
   }

   function get_categoryname($catid = NULL) {
        if ($catid != NULL ) {

            $this->db->select("GROUP_CONCAT(name SEPARATOR ', ') as name")->from("categories")->where('FIND_IN_SET(cat_id, "'.$catid.'" ) ')->order_by('name','asc');
            $query = $this->db->get();
            $record = $query->row();
            if (!empty($record)) {
                return $record->name;
            }
            return "";
        }
        return "";
    }

    /**
     * Set table name
     * @access public
     * @param  string - sets table_name
     * @return null
     * @author Dipesh Shah
     */
    function set_table($table_name) {
        $this->_table = $table_name;
        $this->_fields = $this->db->list_fields($this->_table);
        foreach ($this->_fields as $field) {
            $this->fields[$field] = "";
        }
    }

    /**
     * Set table name : Search from config file and set global instance.
     * @access public
     * @param  string - sets table_name
     * @return null
     * @author Dipesh Shah
     */
    function set_data_table($table_name) {
        $this->_table = $this->config->item($table_name);
        $this->_fields = $this->db->list_fields($this->_table);
        foreach ($this->_fields as $field) {
            $this->fields[$field] = "";
        }
    }

    /**
     * Get record from tables
     * @access public
     * @return array()
     * @author Dipesh Shah
     */
    function get_fields_array() {
        return $this->_fields;
    }

    /**
     * Get record from table
     * @access public
     * @param number - sets limit
     * @param number - sets offset
     * @param array  - sets order
     * @author Dipesh Shah
     * @return array()
     */
    function get($select = array(), $conditions = array(), $order = array(), $limit = NULL, $offset = NULL) {
        $this->db->select($select)->from($this->_table);
        if ($conditions)
            $this->db->where($conditions);

        if ($order)
            $this->db->order_by(key($order), $order[key($order)]);
        if ($limit && $offset)
            $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * GET [copy] Get record from table
     * @access public
     * @param number - sets limit
     * @param number - sets offset
     * @param array  - sets order
     * @author Dipesh Shah
     * @return stdClass()
     */
    function get_stdClass($select = array(), $conditions = array(), $order = array(), $limit = NULL, $offset = NULL) {
        $this->db->select($select)->from($this->_table)->where($conditions);
        if ($order)
            $this->db->order_by(key($order), $order[key($order)]);
        if ($limit && $offset)
            $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Advance Get Function
     * @access public
     * @param number - select values
     * @param number - sets limit
     * @param number - sets offset
     * @param array  - sets order
     * @param array  - sets groupby
     * @author Dipesh Shah
     * @return array()
     */
    function advance_get($select = array(), $conditions = array(), $order = array(), $groupby = '', $limit = NULL, $offset = NULL) {
        $this->db->select($select)->from($this->_table)->where($conditions);
        if ($order)
            $this->db->order_by(key($order), $order[key($order)]);

        if ($groupby != '') {
            $this->db->group_by($groupby);
        }

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get record by id from table
     * @access public
     * @param number - sets limit
     * @param number - sets offset
     * @param array  - sets order
     * @author Dipesh Shah
     * @return array()
     */
    function get_by_id($id, $order = array("id" => "ASC"), $limit = '1', $offset = NULL) {
        $this->db->where("id", $id);
        $this->db->from($this->_table)->order_by(key($order), $order[key($order)])->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Save record in table
     * @access public
     * @param array  -
     * @return insert id
     * @author Dipesh Shah
     */
    function save($data, $password = NULL, $created = NULL) {
        if (!empty($data)) {
            //if password field exist then
            if ($password != NULL) {
                $data[$password] = md5($data[$password]);
            }
            if ($created != NULL) {
                $data[$created] = date('Y-m-d H:i:s');
            }

            //$data = elements($this->_fields, $data);
            $this->db->insert($this->_table, $data);
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Save batch record in table
     * @access public
     * @param array  - all combine data
     * @return insert id
     * @author Dipesh Shah
     */
    public function saveBatch($collection) {
        $this->db->insert_batch($this->_table, $collection);
        return $this->db->insert_id();
    }

    /**
     * Update record in table
     * @access public
     * @param array  - task data
     * @param array  - field name & value
     * @return boolean
     *  @author Dipesh Shah
     */
    function update($data, $fieldValue = array()) {
        if (!empty($data) && !empty($fieldValue)) {
            try {
                $this->db->where($fieldValue);
                $this->db->update($this->_table, $data);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Delete record in table
     * @access public
     * @param array  - field name & value
     * @return boolean
     *  @author Dipesh Shah
     */
    function delete($fieldValue = array()) {
        if (!empty($fieldValue)) {
            $this->db->delete($this->_table, $fieldValue);
            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * Delete record in table
     * @access public
     * @param array  - field name & value
     * @return boolean
     *  @author Dipesh Shah
     */
    function delete_multiple($where_in = array(), $fieldName) {
        if (!empty($where_in)) {
            $this->db->where_in($fieldName, $where_in);
            $this->db->delete($this->_table);
            if ($this->db->affected_rows() > 0)
                return true;
            else
                return false;
        }
        return false;
    }

    /**
     * Delete record in table
     * @access public
     * @param array  - field name & value
     * @return boolean
     *  @author Dipesh Shah
     */
    function update_multiple($data, $where_in = array(), $fieldName) {
        if (!empty($where_in)) {
            try {
                $this->db->where_in($fieldName, $where_in);
                $this->db->update($this->_table, $data);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Get Field or Fields By Id
     * @access public
     * @param  string  - field name
     * @param  number  - field id
     * @return boolean
     *  @author Dipesh Shah
     */
    function get_fields($field_names = NULL, $id = NULL) {
        if ($field_names != NULL && $id != NULL) {
            $this->db->select($field_names)->from($this->_table)->where('id', $id);
            $query = $this->db->get();
            $record = $query->result_array();
            if (!empty($record)) {
                if (count(explode(",", $field_names)) > 1)
                    return $record[0];
                else
                    return $record[0][$field_names];
            }
            return "";
        }
        return "";
    }

    /**
     * Get Field or Fields By DynamicId
     * @access public
     * @param  string  - field name
     * @param  number  - field id
     * @return boolean
     *  @author Dipesh Shah
     */
    function get_fieldsvalue($field_names = NULL, $id = NULL,$field_id = NULL,$table = NULL) {
        if ($field_names != NULL && $id != NULL && $field_id != NULL && $table != NULL ) {
            $this->db->select($field_names)->from($table)->where($field_id , $id);
            $query = $this->db->get();
            $record = $query->result_array();
            if (!empty($record)) {
                if (count(explode(",", $field_names)) > 1)
                    return $record[0];
                else
                    return $record[0][$field_names];
            }
            return "";
        }
        return "";
    }

    function get_usersname($field_names = NULL, $id = NULL,$field_id = NULL,$table = NULL) {
        if ($field_names != NULL && $id != NULL && $field_id != NULL && $table != NULL ) {
            $this->db->select($field_names)->from($table)->where($field_id , $id);
            $query = $this->db->get();
            $record = $query->result_array();

            if (!empty($record)) {

                if ( $record[0]['username']!='')
                    return $record[0][$field_names];
                else
                    $this->db->select('meta_value')->from('usermeta')->where(array('user_id' =>$id,'meta_key'=>'first_name'));
                    $query = $this->db->get();
                    $record = $query->row();
                    if (!empty($record)) {
                        return $record->meta_value;                 
                    }
            }else{
                
                return "";
            }
            return "";
        }
        return "";
    }

    /**
     * Join Two Table
     * @access public
     * @param array  - result
     * @return stdClass
     *  @author Dipesh Shah
     */
    public function singleJoin($parentTable, $childTable, $select, $condition, $where = array(), $joinType = "") {
        $this->db->select($select);
        $this->db->from($parentTable);
        $this->db->where($where);
        $this->db->join($childTable, $condition, $joinType);
        return $this->db->get()->result_array();
    }

    /**
     * Join Two or More Table : mulitple joins with multiple where condition and multiple like condition
     * @access public
     * @param array  - result
     * @return stdClass   - result
     * @author Dipesh Shah
     */
    public function multijoins($fields, $from, $joins, $where, $ordersby = '', $groupby = '', $num = NULL, $offset = NULL, $action = 'array') {

        $this->db->select($fields, true);
        $this->db->where($where);

        if ($groupby != '') {
            $this->db->group_by($groupby);
        }
        foreach ($joins as $key => $value) {
            $this->db->join($key, $value[0], $value[1]);
        }

        if ($ordersby != '') {
            $this->db->order_by('' . $ordersby . '');
        }
        if ($action == 'count') {
            return $this->db->get($from)->num_rows();
        } elseif ($action == 'array') {
            return $this->db->get($from, $num, $offset)->result_array();
        } else {
            return $this->db->get($from, $num, $offset)->result();
        }
    }

    /**
     * Function check record is exist or not.
     * @access public
     * @param array  - result
     * @return boolean true if have dublicate record and false doen't dublicate record
     * @author Dipesh Shah
     */
    public function checkDuplicate($condition, $table = '') {
        if ($table == '')
            $table = $this->_table;

        $query = $this->db->get_where($table, $condition);
        if ($query->num_rows() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Count Number of record from table
     * @access public
     * @Optional = table name
     * @author Dipesh Shah
     * @return array()
     */
    function count_record($condition, $table = '') {
        if ($table == '')
            $table = $this->_table;

        $query = $this->db->get_where($table, $condition);
        return $query->num_rows();
    }

    /**
     * Count Number of record from table
     * @access public
     * @Optional = table name
     * @author Dipesh Shah
     * @return array()
     */
    function custom_get($select, $condition = '') {
        $sql = "SELECT " . $select . " FROM " . $this->_table;
        if ($condition != '')
            $sql .= " Where " . $condition;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function custom_query($sql = '') {
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return false;
        }
        
    } 
    function check_permission($moduleid,$action){

        $userroleid = $this->session->userdata('admin_logged_in')['role_id']; 
        $this->db->where(array('user_role_id'=>$userroleid,'module_id'=>$moduleid));
        $result  = $this->db->get('permission_meta');

        if($result->num_rows() > 0){
            $row = json_decode($result->row()->permission_value); 
            if($row->$action=='1'){
                return true;
            }else{
                return false;
            }


        }else{
            return false;
        }

    }

    /**
     * Trancate table
     * @access public
     * @author 
     */
    public function trancate_table($table_name = '') {
        if (!empty($table_name)) {
            return $this->db->truncate($table_name);
        }
        return false;
    }

     
     

}