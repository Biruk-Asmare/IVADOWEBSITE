<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
Class BirukDatabase{
    public $researcher_table_name;
    public $expenses_table_name;
    public $itemized_expenses_table_name;
    public $res_id_column;
    public $exp_id_column;
    public $eit_exp_id_column;
    public $eit_exp_fk_exp_id_column;
    public $options_table_name;
    public $option_id_column;
    private $charset_collate;


    
    
    function __construct(){
       
        
        global $wpdb;
        $this->researcher_table_name=$wpdb->prefix . 'researcher';
        $this->expenses_table_name=$wpdb->prefix . 'expense';
        $this->itemized_expenses_table_name=$wpdb->prefix . 'itemized_expense';
        $this->options_table_name=$wpdb->prefix .'biruk_custom_form_options';
        $this->res_id_column='res_id';
        $this->exp_id_column='exp_id';
        $this->eit_exp_id_column='eit_id';
        $this->eit_exp_fk_exp_id_column='eit_exp_id';
        $this->option_id_column='option_id';
        
        $this->charset_collate = $wpdb->get_charset_collate();

        
    }
    function setup_database(){
        
        $res_msg= $this->create_researcher_table();
        $exp_msg= $this->create_expense_table();
        $ite_exp_msg= $this->create_itemized_expense_table();
        $options_table= $this->create_options_table();
        $this->insert_default_options();
        $resp=array(
            "researcher"=> $res_msg,
            "expense"=> $exp_msg,
            "itemized_expense"=>$ite_exp_msg,
            "options"=>$options_table
        );

        return $resp;

    }
    
    function clean_db(){

        $del_iexp= $this->delete_table($this->itemized_expenses_table_name);
        $del_exp= $this->delete_table($this->expenses_table_name);
        $del_res= $this->delete_table($this->researcher_table_name);
        $del_options= $this->delete_table($this->options_table_name);
        $res = array("del_iexp"=>$del_iexp, "del_exp"=> $del_exp, "del_res"=> $del_res,"del_options" => $del_options);
        echo '<pre>';
            print_r($res);
        echo '</pre>';
    }
    function delete_table($tbl_name){

        global $wpdb;
        $res=$wpdb->query("DROP TABLE IF EXISTS {$tbl_name}");
        return $res;
        
    }
    function print_message($message){
        echo "The constructor for database loader is just called";
        echo '<div class="notice notice-success is-dismissible">
     <h1> '.$message.'</h1>
    </div>';
    }
    function create_researcher_table(){
        global $wpdb;
        if ( $wpdb->get_var("SHOW TABLES LIKE '$this->researcher_table_name'") != $this->researcher_table_name ) {
            $this->print_message("Creating $this->researcher_table_name table...");
            $sql = "CREATE TABLE $this->researcher_table_name (
                $this->res_id_column mediumint(9) NOT NULL AUTO_INCREMENT,
                res_name varchar(100) NOT NULL,
                res_email varchar(150) NOT NULL,
                res_level varchar(50) NOT NULL,
                res_role varchar(50) NOT NULL,
                res_coordinator varchar(100) NOT NULL,
                res_department varchar(100) NOT NULL,
                res_university varchar(100) NOT NULL,
                res_graduation_year varchar(5),
                res_work_package varchar(100),
                res_leader varchar(100) NOT NULL,
                res_study_from DATE,
                res_study_to DATE,
                res_project_title varchar(100) NOT NULL,
                res_project_description varchar(250),
                res_picture varchar(150),
                PRIMARY KEY  (res_id)
            ) $this->charset_collate;";

            return dbDelta($sql);
        }else{
        
            
            return array("$this->researcher_table_name already exists");
        }
    }
    
    function create_expense_table(){      
        global $wpdb;
        if ( $wpdb->get_var("SHOW TABLES LIKE '$this->expenses_table_name'") != $this->expenses_table_name ) {
            $this->print_message("Creating $this->expenses_table_name table...");  
            $sql = "CREATE TABLE $this->expenses_table_name (
                $this->exp_id_column BIGINT NOT NULL AUTO_INCREMENT,
                exp_res_id mediumint(9) NOT NULL,
                exp_type VARCHAR(60) NOT NULL,
                exp_work_package varchar(100) NOT NULL,
                exp_period_from DATE NOT NULL,
                exp_period_to DATE NOT NULL,
                exp_total_amount DOUBLE PRECISION,
                exp_date DATE NOT NULL,
                exp_leader VARCHAR(100) NOT NULL,
                exp_approved_by VARCHAR(100) NOT NULL,
                PRIMARY KEY  ($this->exp_id_column),
                FOREIGN KEY (exp_res_id) REFERENCES $this->researcher_table_name ($this->res_id_column) ON DELETE CASCADE ON UPDATE CASCADE
            ) $this->charset_collate;";

            return dbDelta( $sql );
        }else{

            return array("$this->expenses_table_name already exists");
        }
    }

    function create_options_table(){
        global $wpdb;
        if ( $wpdb->get_var("SHOW TABLES LIKE '$this->options_table_name'") != $this->options_table_name ) {
            $this->print_message("Creating $this->options_table_name table...");        
            $sql = "CREATE TABLE $this->options_table_name (
                $this->option_id_column INT NOT NULL AUTO_INCREMENT,
                op_name VARCHAR(150) NOT NULL,
                op_values VARCHAR(500) NOT NULL,
                PRIMARY KEY  ($this->option_id_column) 
            ) $this->charset_collate;";

            return dbDelta( $sql );
        }else{

            return array("$this->itemized_expenses_table_name already exists");
        }
    }
    function insert_default_options(){
        global $wpdb;
        $table= $this->biruk_db->options_table_name;
        $query= "INSERT INTO `wp_biruk_custom_form_options` (`option_id`, `op_name`, `OP_values`) VALUES
        (1, 'Expense Type', 'Student Financial support,Salary,Meeting,Conference,Other'),
        (2, 'Role', 'Professor,Research Assistant,PostDoc,PhD,MsC,Undergraduate,Visiting Fellow,Visiting Student,Other'),
        (3, 'Work Package', '1.1,1.2,1.3,1.4,1.5,2.1,2.2,2.3'),
        (4, 'Leader', 'Liam Paull,Maio Marchand,Sébastien Gambs,Ettore Merlo,Foutse Khomh'),
        (5, 'Student Level', 'PostDoc,PhD,Master/Msc,Undergraduate,Stage'),
        (6, 'Department', 'DGIGL,CS,GI'),
        (7, 'University', 'PolyMTL,UDMTL,UL,McGill,UQUAM,ETS'),
        (8, 'Expense categories', 'Conf.Registration,Hotel,Travel,Per diem,
        License Fees,Hadrware,Office Supplies,Passport fee,Postage,Printer Cartridges,
        Printer Paper,Software,Stationery,Travellig to partners,Travelling co-pi,Travelling IRT,Workshop meetings,Computer and TI,GPU,Publication fees,Other');";
        $res=$wpdb->query($query);

    }
    function create_itemized_expense_table(){  
        global $wpdb;
        if ( $wpdb->get_var("SHOW TABLES LIKE '$this->itemized_expenses_table_name'") != $this->itemized_expenses_table_name ) {
            $this->print_message("Creating $this->itemized_expenses_table_name table...");        
            $sql = "CREATE TABLE $this->itemized_expenses_table_name (
                $this->eit_exp_id_column BIGINT NOT NULL AUTO_INCREMENT,
                eit_exp_id BIGINT NOT NULL,
                eit_date DATE NOT NULL,
                eit_description VARCHAR(250) NOT NULL,
                eit_category VARCHAR(50) NOT NULL,
                eit_cost DOUBLE PRECISION NOT NULL,
                PRIMARY KEY  ($this->eit_exp_id_column),
                FOREIGN KEY (eit_exp_id) REFERENCES $this->expenses_table_name ($this->exp_id_column) ON DELETE CASCADE ON UPDATE CASCADE
    
            ) $this->charset_collate;";

            return dbDelta( $sql );
        }else{

            return array("$this->itemized_expenses_table_name already exists");
        }
    }

    
}

