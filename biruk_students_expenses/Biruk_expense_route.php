<?php
// this file is responsible for creating A REST end point to mange researchers data

require_once( plugin_dir_path( __FILE__ ) . 'Biruk_Database.php');
        

//initialize the custom rest API
add_action( 'rest_api_init', 'init_exp_router');

function init_exp_router(){
    register_rest_route('tcsi4ai/v1/','expense', array(

        'methods'=> WP_REST_SERVER::ALLMETHODS,
        'callback'=>'expense_process_posts'
    ));
    

}

function expense_process_posts($data){
    
    //return array("params"=>$data->get_params(), "file_params"=>$data->get_file_params());
    $res=new Expense ();
    return $res->dispatch_request($data);
}


Class Expense{
    public $biruk_db;

    function __construct(){
        $this->biruk_db= new BirukDatabase();
    }
function dispatch_request($data){
    if(!isset($data['_method'])){
        $errormsg="Id is required";
        $res=array("success"=> false, "message" => 
        "Invalid request");
        return $res; 
    }
    if($data['_method']== "GET"){
        if(isset($data['id'])){
            return $this->get_expense_ByID($data);
        }else{
            
            return $this->get_all_expenses($data);
        }
    } else if ($data['_method']=="POST"){
        return $this->add_expense($data);
    }else if($data['_method']=="PUT"){
        //return "update is called";
        return $this->update_expense($data);
    }else if ($data['_method']=="DELETE"){
        return $this->delete_expense($data);
    }

}
function validate_expense_data_for_delete_search($data){
    if(isset($data['eit_ids'])){
        $eit_ids=array();
        foreach($data['eit_ids'] as $eit_id){
            array_push($eit_ids, sanitize_text_field($eit_id));
        }
        return array('success'=>true, 'eit_ids'=>$eit_ids);
       

    }
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
function validate_detailed_expense_items($data){
    if(isset($data['ite_exp'])){
        $res=array();
        $eit_id=array();
        $expenses= $data['ite_exp'];
        foreach($expenses as $value){
           
            if(isset($value['eit_date']) &&
            isset($value['eit_cat']) &&
            isset($value['eit_cost']))
            {
                if(isset($value['eit_id'])){
                    array_push($eit_id, sanitize_text_field($value['eit_id'])) ;
                }else{
                    array_push($eit_id,null);
                }
                array_push($res, array(
                'eit_exp_id'=> -1,   
                'eit_date' => sanitize_text_field($value['eit_date']),
                'eit_description' => sanitize_text_field($value['eit_desc']),
                'eit_category' => sanitize_text_field($value['eit_cat']),
                'eit_cost' => sanitize_text_field($value['eit_cost'])
                
            ));
            }else{
                $errormsg="Some fields are empty";
                $res=array("success"=> false, "message" => 
                $errormsg);
                return $res;
            }
        }
        return array('success'=>true, 'eit_data'=>$res,'eit_id'=>$eit_id);
    }else{
        $errormsg="Some fields are empty";
        $res=array("success"=> false, "message" => 
        $errormsg);
        return $res;
    }
}



function validate_expense_data_for_insert_or_update($data){
    
    $valid=false;
    $errormsg="";
    $res=array();
    if(isset($data['res_id']) && 
    isset($data['exp_type']) &&
    isset($data['exp_wp']) &&
    isset($data['from'])&&
    isset($data['to'])&&
    isset($data['total_amount'])&&
    isset($data['_date'])&&
    isset($data['leader']) &&
    isset($data['approved_by']))
    {

        //sanitaize
        $res_id= sanitize_text_field($data['res_id']);
        $exp_type= sanitize_text_field($data['exp_type']);
        $wp= sanitize_text_field($data['exp_wp']);
        $from= sanitize_text_field($data['from']);
        $to= sanitize_text_field($data['to']);
        $total= sanitize_text_field($data['total_amount']);
        $date= sanitize_text_field($data['_date']);
        $leader= sanitize_text_field($data['leader']);
        $approved= sanitize_text_field($data['approved_by']);
        
        if(isset($data['id'])){
            $id= sanitize_text_field($data['id']);
        }else{
            $id=null;
        }

        $it_exp= $this->validate_detailed_expense_items($data);
        if(!$it_exp['success']){
            return $it_exp;
        }
        else{
            $res=array(
                'exp_res_id'=>$res_id,
                'exp_type'=>$exp_type,
                'exp_work_package'=>$wp,
                'exp_period_from' => $from,
                'exp_period_to'=> $to,
                'exp_total_amount'=>$total,
                'exp_date'=>$date,
                'exp_leader' => $leader,
                'exp_approved_by' => $approved

            );
            return array('success'=>true, 'data'=>$res, 'eit_data'=> $it_exp['eit_data'] ,'id'=> $id,'eit_id'=>$it_exp['eit_id']);
      }
    }else{
        $errormsg="Some fields are empty";
        $res=array("success"=> false, "message" => 
        $errormsg);
        return $res;
    }

    
}

function add_expense($data){
    $expense=$this->validate_expense_data_for_insert_or_update($data);
    //array('success'=>true, 'data'=>$res, 'eit_data'=> $it_exp['eit_data'] ,'id'=> $id);
    if($expense['success']){
        global $wpdb;
        $table= $this->biruk_db->expenses_table_name;
        $eit_table= $this->biruk_db->itemized_expenses_table_name;
       
        if($wpdb->insert($table,$expense['data'],array('%d','%s','%s','%s','%s','%f','%s','%s','%s'))){
            $exp_id = $wpdb->insert_id;
                foreach($expense['eit_data'] as $item_row){
                    $item_row['eit_exp_id'] = $exp_id;
                    if(!$wpdb->insert($eit_table,$item_row,array('%d','%s','%s','%s','%f'))) {
                        return array('success'=>false, 'response'=>'data Insertion failed for itemized_expenses');
                    }

                }

            return array('success'=>true, 'response'=>'expense is successfully inserted', 'new_id'=> $exp_id);
        }else{
            return array('success'=>false, 'response'=>'data Insertion failed for expenses table');
        }
    }else{
        return $expense;
    }
    
}
function update_expense($data){
    $res=$this->validate_expense_data_for_insert_or_update($data);
    //return array('success'=>true, 'data'=>$res, 'eit_data'=> $it_exp['eit_data'] ,'id'=> $id,'eit_id'=>$it_exp['eit_id']);
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->expenses_table_name;
            $eit_table= $this->biruk_db->itemized_expenses_table_name;
            $query= array($this->biruk_db->exp_id_column => $res['id']);
            $err=$wpdb->update($table,$res['data'],$query,array('%d','%s','%s','%s','%s','%f','%s','%s','%s'));
            if($err===false){
                
                return array('success'=>false, 'response'=>'Update failed for unknown reason');
                
            }else{
                foreach($res['eit_data'] as $key=>$item_row){
                    $item_row['eit_exp_id'] = $res['id'];
                    if(isset($res['eit_id'][$key]) && !empty($res['eit_id'][$key])){
                        $query= array($this->biruk_db->eit_exp_id_column => $res['eit_id'][$key]);
                        $sub_err=$wpdb->update($eit_table,$item_row,$query,array('%d','%s','%s','%s','%f'));
                        if($sub_err===false) {
                            return array('success'=>false, 'response'=>'data update failed for itemized_expenses');
                        }
                    }
                    
                }
                return array('success'=>true, 'response'=>'Expense id '.$res['id'].' successfully updated');
                
            }

       }else{
           return $res;
       }
    
}

function delete_expense($data){

    $res=$this->validate_expense_data_for_delete_search($data);
    if(isset($res['eit_ids'])){
          return $this->delete_itemized_expense($res);
       }
    else{
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->expenses_table_name;
            $query= array($this->biruk_db->exp_id_column => $res['id']);
            if($wpdb->delete($table,$query)){
                
                return array('success'=>true, 'response'=>'Expense id '.$res['id'].' successfully deleted');
            }else{
                return array('success'=>false, 'response'=>'row deletion failed for unknown error');
            }

       }else{
            return $res;
       }
         
    }
}


function delete_itemized_expense($res){
    if($res['success']){
        foreach($res['eit_ids'] as $id){
            global $wpdb;
            $table= $this->biruk_db->itemized_expenses_table_name;
            $query= array($this->biruk_db->eit_exp_id_column => $id);
            if(!$wpdb->delete($table,$query)){
                return array('success'=>false, 'response'=>'row deletion failed for unknown error');
            }
        }
        return array('success'=>true, 'response'=>'Itemized expenses successfully deleted');   
    }
}
function get_all_expenses(){
    
    $data=array();
    global $wpdb;
    $table_exp= $this->biruk_db->expenses_table_name;
    $query= "SELECT * FROM $table_exp;";
    $total_result= array();
    
    if($res=$wpdb->get_results($query,ARRAY_A)){
    
        foreach($res as $expense){
            
            $exp_id= $expense['exp_id'];
            $table_eit_exp= $this->biruk_db->itemized_expenses_table_name;
            $query= "SELECT * FROM $table_eit_exp WHERE {$this->biruk_db->eit_exp_fk_exp_id_column} = $exp_id;";
            if($res_sub=$wpdb->get_results($query,ARRAY_A)){
                array_push($total_result, array('expense'=>$expense, 'ite_expense'=>$res_sub));
            }
            
            
        }
        
        return array('success'=>true, "data" => $total_result);
    }else{
        return array('success'=>false, 'response'=>'Searchig failed with unknown error');
    }
}
function get_expense_ByID($data){
    
    $res=$this->validate_expense_data_for_delete_search($data);
    if($res['success']){
        $id= $res['id'];
        $data=array();
        global $wpdb;
        $table_exp= $this->biruk_db->expenses_table_name;
        $exp_id_col= $this->biruk_db->exp_id_column;
        $table_detail_exp= $this->biruk_db->itemized_expenses_table_name;

        $query= "SELECT * FROM $table_exp where $exp_id_col=$id;";
        
        
        if($result=$wpdb->get_results($query,ARRAY_A)){
        
            //get the detail expense
            $query= "SELECT * FROM $table_detail_exp where eit_exp_id=$id;";
            if($result2=$wpdb->get_results($query,ARRAY_A)){
                return array('success'=>true, "exp_data" => $result,"detail_exp_data"=>$result2);
            }else{
                return array('success'=>false, 'response'=>$query);
            }

            
            
        }else{
            return array('success'=>false, 'response'=>'Searchig failed with unknown error');
        }




    }else{
        return $res;
    }














    
}
}