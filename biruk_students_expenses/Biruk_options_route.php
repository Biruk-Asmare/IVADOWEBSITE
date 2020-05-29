<?php
// this file is responsible for creating A REST end point to mange researchers data

require_once( plugin_dir_path( __FILE__ ) . 'Biruk_Database.php');
        

//initialize the custom rest API
add_action( 'rest_api_init', 'init_options_router');

function init_options_router(){
    register_rest_route('tcsi4ai/v1/','option', array(

        'methods'=> WP_REST_SERVER::ALLMETHODS,
        'callback'=>'option_process_posts'
    ));
    

}

function option_process_posts($data){
    
    //return array("params"=>$data->get_params(), "file_params"=>$data->get_file_params());
    $res=new Options ();
    return $res->dispatch_request($data);
}



Class Options{
    public $biruk_db;

    function __construct(){
        $this->biruk_db= new BirukDatabase();
    }

    

    function dispatch_request($data){
        if(!isset($data['_method']) and !isset($data['_wpnonce'])){
            
            $res=array("success"=> false, "message" => 
            "Invalid request");
            return $res; 
        }
        if($data['_method']== "GET"){
            if(isset($data['id'])){
                return $this->get_option_by_id($data);
            }else{
                return $this->get_all_options($data);
            }
        } else if ($data['_method']=="POST"){
            return $this->add_option($data);
        }else if($data['_method']=="PUT"){
            return $this->update_option($data);
        }else if ($data['_method']=="DELETE"){
            return $this->delete_option($data);
        }

    }
    function validate_option_data_for_delete_search($data){
        if(isset($data['id'])){
            $id= sanitize_text_field($data['id']);
            $res= array('success'=>true, 'id'=>$id);
            return $res;
        }else{
            $errormsg="Id is required";
            $res=array("success"=> false, "message" => 
            $errormsg);
            return $res; 
        }
    }
    
    function validate_option_data_for_insert_or_update($data){
        $valid=false;
        $errormsg="";
        $res=array();
        if(isset($data['op_values']) && isset($data['op_name'])){

            //sanitaize
            $name= sanitize_text_field($data['op_name']);
            $values= sanitize_text_field($data['op_values']);
            
            if(isset($data['id'])){
                $id= sanitize_text_field($data['id']);
            }else{
                $id=null;
            }

            $res=array(
                'op_name'=>$name,
                'op_values'=>$values
            );
            return array('success'=>true, 'data'=>$res, 'id'=> $id);

        }else{
            $errormsg="Some fields are empty";
            $res=array("success"=> false, "message" => 
            $errormsg);
            return $res;
        }

        
    }
    function add_option($data){
       $res= $this->validate_option_data_for_insert_or_update($data); 
       if($res['success']){
            
            global $wpdb;
            $table= $this->biruk_db->options_table_name;
            if($wpdb->insert($table,$res['data'])){
                $new_id = $wpdb->insert_id;
                return array('success'=>true, 'response'=>'option successfully inserted', 'new_id'=> $new_id);
            }else{
                return array('success'=>false, 'response'=>'data Insertion failed for unknown error');
            }

       }else{
           return $res;
       }
    }
    function update_option($data){
        $res=$this->validate_option_data_for_insert_or_update($data);
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->options_table_name;
            $query= array($this->biruk_db->option_id_column => $res['id']);
            $err=$wpdb->update($table,$res['data'],$query);
            if($err===false){
                return array('success'=>false, 'response'=>'row update failed for unknown error');
                
            }else{
               
                return array('success'=>true, 'response'=>'Researcher id '.$res['id'].' successfully updated');
            }

       }else{
           return $res;
       }

        
    }
    function delete_option($data){
        $res=$this->validate_option_data_for_delete_search($data);
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->options_table_name;
            $query= array($this->biruk_db->option_id_column => $res['id']);
            if($wpdb->delete($table,$query)){
                return array('success'=>true, 'response'=>'Option id '.$res['id'].' successfully deleted');
            }else{
                return array('success'=>false, 'response'=>'row deletion failed for unknown error');
            }

       }else{
           return $res;
       }

        
    }
    function get_option_by_id($data){
        $res=$this->validate_option_data_for_delete_search($data);
        global $wpdb;
        $table= $this->biruk_db->options_table_name;
        $op_id_col=$this->biruk_db->option_id_column;
        $id=$res['id'];
        $query= "SELECT * FROM $table WHERE $op_id_col=$id";
        $res=$wpdb->get_results($query,ARRAY_A);
        if($res===false){
            return array('success'=>false, 'response'=>'Searchig options failed with unknown error');
            
        }else{
            
            return array('success'=>true, "data" => $res);
        }

   

}
    function get_all_options($data){
        
            global $wpdb;
            $table= $this->biruk_db->options_table_name;
            $query= "SELECT * FROM $table;";
            $res=$wpdb->get_results($query,ARRAY_A);
            if($res===false){
                return array('success'=>false, 'response'=>'Searchig options failed with unknown error');
                
            }else{
                
                return array('success'=>true, "data" => $res);
            }

       

    }
}
