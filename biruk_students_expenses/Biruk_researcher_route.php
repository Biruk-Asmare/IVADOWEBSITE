<?php
// this file is responsible for creating A REST end point to mange researchers data

require_once( plugin_dir_path( __FILE__ ) . 'Biruk_Database.php');
        

//initialize the custom rest API
add_action( 'rest_api_init', 'init_router');

function init_router(){
    register_rest_route('tcsi4ai/v1/','researcher', array(

        'methods'=> WP_REST_SERVER::ALLMETHODS,
        'callback'=>'researcher_process_posts'
    ));
    

}

function researcher_process_posts($data){
    
   
    $res=new Researcher ();
    return $res->dispatch_request($data);
}



Class Researcher{
    public $biruk_db;
    private $res_nonce="Eyesus Kerstos Ye Baheri Amlak New!!!";

    function __construct(){
        $this->biruk_db= new BirukDatabase();
    }

    function handle_image_uploads($data){
        if(!empty($data->get_file_params())){
            $type= $data->get_file_params()['picture']['type'];
            $name= $data->get_file_params()['picture']['name'];
            $size= $data->get_file_params()['picture']['size'];
            $temp= $data->get_file_params()['picture']['tmp_name'];
            $validate = wp_check_filetype_and_ext( $temp, $name);
            $error= $data->get_file_params()['picture']['error'];
            $allowed_mimes= array('image/png','image/jpeg','image/gif');
            $allowed_ext=array('png','jpg','jpeg','gif');
            if($ext=$validate['ext'] ){
                if( in_array($ext,$allowed_ext) &&  in_array($type,$allowed_mimes) && $size < 10485760){
                    $new_name= md5(uniqid (rand (),true)).'.'.$ext;
                    $upload = wp_upload_bits($new_name, null, file_get_contents($temp));
                    if(!$upload['error']){
                        return array('success'=>true, 'picture_url'=>$upload['url']);
                    }else{
                        return array('success'=>false, 'message'=>'Image upload failed');
                    }
                    
                }else{
                    return array('success'=>false, 'message'=>'File type/size is not allowed');
                }
            }else{
                return array('success'=>false, 'message'=> "$name File extension is not allowed");
            }

      
        }else{
            if(!empty($data['picture'])){
                $pic= sanitize_text_field($data['picture']);
                return array('success'=>true, 'picture_url'=>$pic);  //handle cases where the user updates without changing the profile picture.
            }
            return array('success'=>true, 'picture_url'=>"");
        }
    }

    

    function dispatch_request($data){
        
       
        if(!isset($data['b_method'])){
            $errormsg="Id is required";
            $res=array("success"=> false, "response" => $data->get_headers());
            return $res; 
        }
        if($data['b_method']== "GET"){
            if(isset($data['id'])){
                return $this->get_researcher_by_id($data);
            }else{
                return $this->get_all_researchers($data);
            }
        } else if ($data['b_method']=="POST"){
           
           
                return $this->add_resercher($data);
            
            
        }else if($data['b_method']=="PUT"){
            
                return $this->update_researcher($data);
           
            
        }else if ($data['b_method']=="DELETE"){
            
                return $this->delete_researcher($data);
            
            
        }

    }
    function validate_researcher_data_for_delete_search($data){
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
    
    function validate_researcher_data_for_insert_or_update($data){
        $valid=false;
        $errormsg="";
        $res=array();
        if(isset($data['name']) && 
        isset($data['email']) &&
        isset($data['level']) &&
        isset($data['role']) &&
        isset($data['coordinator'])&&
        isset($data['department'])&&
        isset($data['university'])&&
        isset($data['leader'])&&
        isset($data['proj_title'])){

            //sanitaize
            $name= sanitize_text_field($data['name']);
            $email= sanitize_text_field($data['email']);
            $level= sanitize_text_field($data['level']);
            $role= sanitize_text_field($data['role']);
            $coordinator= sanitize_text_field($data['coordinator']);
            $department= sanitize_text_field($data['department']);
            $university= sanitize_text_field($data['university']);
            $grad_year= sanitize_text_field($data['grad_year']);
            $work_package= sanitize_text_field($data['work_package']);
            $leader= sanitize_text_field($data['leader']);
            $from= sanitize_text_field($data['from']);
            $to= sanitize_text_field($data['to']);
            $proj_title= sanitize_text_field($data['proj_title']);
            $proj_desc= sanitize_textarea_field($data['proj_desc']);
            $picture_res= $this->handle_image_uploads($data);
            $picture="";
            if($picture_res['success']){
                $picture=$picture_res['picture_url'];
            }else{
                return $picture_res;
            }
            if(isset($data['id'])){
                $id= sanitize_text_field($data['id']);
            }else{
                $id=null;
            }

            $res=array(
                'res_name'=>$name,
                'res_email'=>$email,
                'res_level'=>$level,
                'res_role'=>$role,
                'res_coordinator' => $coordinator,
                'res_department'=> $department,
                'res_university'=>$university,
                'res_graduation_year'=>$grad_year,
                'res_work_package' => $work_package,
                'res_leader' => $leader,
                'res_study_from' => $from,
                'res_study_to' => $to,
                'res_project_title'=> $proj_title,
                'res_project_description' => $proj_desc,
                'res_picture'=> $picture

            );
            return array('success'=>true, 'data'=>$res, 'id'=> $id);

        }else{
            $errormsg="Some fields are empty";
            $res=array("success"=> false, "message" => 
            $errormsg);
            return $res;
        }

        
    }
    function add_resercher($data){
       $res= $this->validate_researcher_data_for_insert_or_update($data); 
        
       if($res['success']){
            
            global $wpdb;
            $wpdb->show_errors();
            $table= $this->biruk_db->researcher_table_name;
            $result=$wpdb->insert($table,$res['data']);
            if($result){
                $new_id = $wpdb->insert_id;
                return array('success'=>true, 'response'=>'Researcher successfully inserted', 'new_id'=> $new_id);
            }else{
                return array('success'=>false, 'response'=> $wpdb->last_query);
            }

       }else{
           return $res;
       }
    }
    function update_researcher($data){
        $res=$this->validate_researcher_data_for_insert_or_update($data);
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->researcher_table_name;
            $query= array($this->biruk_db->res_id_column => $res['id']);
            $err=$wpdb->update($table,$res['data'],$query);
            if($err===false){
                return array('success'=>false, 'response'=>'row update failed for unknown error');
                
            }else{
                $new_id = $wpdb->insert_id;
                return array('success'=>true, 'response'=>'Researcher id '.$res['id'].' successfully updated');
            }

       }else{
           return $res;
       }

        
    }
    function delete_researcher($data){
        $res=$this->validate_researcher_data_for_delete_search($data);
        if($res['success']){
            global $wpdb;
            $table= $this->biruk_db->researcher_table_name;
            $query= array($this->biruk_db->res_id_column => $res['id']);
            if($wpdb->delete($table,$query)){
                $new_id = $wpdb->insert_id;
                return array('success'=>true, 'response'=>'Researcher id '.$res['id'].' successfully deleted');
            }else{
                return array('success'=>false, 'response'=>'row deletion failed for unknown error');
            }

       }else{
           return $res;
       }

        
    }
    
    function get_all_researchers($data){
        
            global $wpdb;
            $table= $this->biruk_db->researcher_table_name;
            $query= "SELECT * FROM $table;";
            if($res=$wpdb->get_results($query,ARRAY_A)){
                
                return array('success'=>true, "data" => $res);
            }else{
                return array('success'=>false, 'response'=>'Searchig failed with unknown error');
            }

       

    }
    function get_researcher_by_id($data){
        $resval=$this->validate_researcher_data_for_delete_search($data);
        if($resval['success']){
            global $wpdb;
            $id= $resval['id'];
            $table= $this->biruk_db->researcher_table_name;
            $col=$this->biruk_db->res_id_column;
            $query= "SELECT * FROM $table WHERE $col=$id;";
            if($res=$wpdb->get_results($query,ARRAY_A)){
                
                return array('success'=>true, "data" => $res);
            }else{
                return array('success'=>false, 'response'=>'Searchig failed with unknown error');
            }

        }
        
    }
}
