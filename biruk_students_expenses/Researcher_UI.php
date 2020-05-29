<?php
$biruk_allowed=false;

function load_researcher_form(){

    //fetch options data from database
    global $wpdb;
    $biruk_db= new BirukDatabase();
    $table= $biruk_db->options_table_name;
    $query= "SELECT * FROM $table;";
    $res=$wpdb->get_results($query,ARRAY_A); 
    
    
?>

<div class="modal fade col-md-12" id="addResearcherUiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Add new Researcher</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
        <div class="container-fluid">  
            
            <form class="col-md-12" id="b_resercher_ui">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="b_fullName">Full Name*</label>
                        <input type="text" class="form-control" id="b_fullName" name="b_fullName"  placeholder="Enter Full Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_email">Email address*</label>
                        <input type="email" class="form-control  " id="b_email" name="b_email" placeholder="Enter email">
                    </div>
            </div>


            <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="b_level">Level*</label>
                            <select class="form-control  " name="b_level" id="b_level">
                                <?php populate_list_items($res,'Student Level');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_role">Role*</label>
                            <select class="form-control  " name="b_role" id="b_role">
                                <?php populate_list_items($res,'Role');?>
                            </select>
                        
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="b_coordinator">Coordinator Name*</label>
                        <input type="text" class="form-control  " id="b_coordinator" name="b_coordinator"  placeholder="Your coordinator Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_department">Department*</label>
                            <select class="form-control  " name="b_department" id="b_department">
                                <?php populate_list_items($res,'Department');?>
                            </select>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="b_university">University*</label>
                            <select class="form-control  " name="b_university" id="b_university">
                                <?php populate_list_items($res,'University');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_graduation">Graduation year</label>
                        <input type="text" class="form-control  " id="b_graduation" name="b_graduation"  placeholder="2020">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="b_work_package">Work Package*</label>
                            <select class="form-control  " name="b_work_package" id="b_work_package">
                                <?php populate_list_items($res,'Work Package');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_leader">Project Leader*</label>
                            <select class="form-control  " name="b_leader" id="b_leader">
                                <?php populate_list_items($res,'Leader');?>
                            </select>
                    
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-md-6">
                            <label for="b_date_from">Study period from</label>
                            <input type="text" class="form-control  " id="b_date_from" name="b_date_from"  placeholder="yyyy-mm--dd">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="b_date_to">Study period to</label>
                        <input type="text" class="form-control  " id="b_date_to" name="b_date_to"  placeholder="yyyy-mm--dd">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="b_title">Project title *</label>
                        <input type="text" class="form-control  " id="b_title" name="b_title"  placeholder="Project title">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="b_description">Project description</label>
                        <textarea class="form-control  " id="b_description" name="b_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="row form-group col-md-12">  
                    <div class="form-group col-md-6">
                        <label for="b_picture">Profile picture</label>
                        <input type="file" id="b_picture", name="b_picture" class="form-control  ">
                    </div>

                     
        
            
                </div>
                <div class="row col-md-12" id="response"></div>
                
        </div>
            </div>
            <div class="modal-footer">
            <div class="col-md-2 offset-md-2 col-sm-12">
                        <button type="submit" style="font-size : 15px; margin-bottom:10px;" class="btn btn-success btn-block p-3">Register</button>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <button type="button" style="font-size : 15px; margin-bottom:10px;" class="btn btn-lg btn-secondary p-3" data-dismiss="modal">Close</button>
                    </div>
                </div>
                
        </form>  
   
            </div>
            </div>
        </div>
    </div>









   
<div class="modal fade col-md-12" id="updateResearcherUiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Update Researcher</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="container-fluid">  
    
            <form class="col-md-12" id="ub_resercher_ui" data-ures_id="" data-ures_picture="">
                
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="ub_fullName">Full Name*</label>
                        <input type="text" class="form-control  " id="ub_fullName" name="ub_fullName"  placeholder="Enter Full Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_email">Email address*</label>
                        <input type="email" class="form-control  " id="ub_email" name="ub_email" placeholder="Enter email">
                    </div>
            </div>


            <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="ub_level">Level*</label>
                            <select class="form-control  " name="ub_level" id="ub_level">
                                <?php populate_list_items($res,'Student Level');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_role">Role*</label>
                            <select class="form-control  " name="ub_role" id="ub_role">
                                <?php populate_list_items($res,'Role');?>
                            </select>
                        
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="ub_coordinator">Coordinator Name*</label>
                        <input type="text" class="form-control  " id="ub_coordinator" name="ub_coordinator"  placeholder="Your coordinator Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_department">Department*</label>
                            <select class="form-control  " name="ub_department" id="ub_department">
                                <?php populate_list_items($res,'Department');?>
                            </select>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="ub_university">University*</label>
                            <select class="form-control  " name="ub_university" id="ub_university">
                                <?php populate_list_items($res,'University');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_graduation">Graduation year</label>
                        <input type="text" class="form-control  " id="ub_graduation" name="ub_graduation"  placeholder="2020">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="ub_work_package">Work Package*</label>
                            <select class="form-control  " name="ub_work_package" id="ub_work_package">
                                <?php populate_list_items($res,'Work Package');?>
                            </select>
                        
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_leader">Project Leader*</label>
                            <select class="form-control  " name="ub_leader" id="ub_leader">
                                <?php populate_list_items($res,'Leader');?>
                            </select>
                    
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-md-6">
                            <label for="ub_date_from">Study period from</label>
                            <input type="text" class="form-control  " id="ub_date_from" name="ub_date_from"  placeholder="yyyy-mm--dd">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="ub_date_to">Study period to</label>
                        <input type="text" class="form-control  " id="ub_date_to" name="ub_date_to"  placeholder="yyyy-mm--dd">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="ub_title">Project title *</label>
                        <input type="text" class="form-control  " id="ub_title" name="ub_title"  placeholder="Project title">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="ub_description">Project description</label>
                        <textarea class="form-control  " id="ub_description" name="ub_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="row form-group col-md-12">  
                    <div class="form-group col-md-6">
                        <label for="ub_picture">Profile picture</label>
                        <input type="file" id="ub_picture", name="ub_picture" class="form-control  ">
                    </div>
                    
                </div>
                <div class="row col-md-12" id="uresponse"></div> 
                
                
       
     
 </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lg btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="update_btn" class="btn btn-lg btn-primary"><i class="fa fa-save"></i> Save changes</button>
                </form>  
   
            </div>
            </div>
        </div>
    </div>

<?php } 


function populate_list_items($res,$op_name){
    foreach($res as $result){
        if(strcmp($result['op_name'], $op_name) == 0){
            $options= explode(",",$result['OP_values']);
            echo '<option value="">...select...</option>';
            foreach($options as $option){
                echo '<option value="'.$option.'">'.$option.'</option>';   
            }
        }
    }
}

function display_all_researchers(){
    if(is_user_logged_in()){
        $current_user= wp_get_current_user();
        
        
        if ( in_array( 'editor', (array) $current_user->roles )  or in_array( 'administrator', (array) $current_user->roles ) ) {
            global $biruk_allowed;  
            $biruk_allowed=true;
            load_researcher_form();
            
            ?>
            <input type="hidden" id="res_edit_nonce" value="<?php echo wp_create_nonce("wp_rest"); ?>" />

        <?php }
    }
    
    //fetch options data from database
    global $wpdb;
    $biruk_db= new BirukDatabase();
    $table= $biruk_db->researcher_table_name;
    $query= "SELECT `res_id`,`res_name`,`res_email`,`res_level`,`res_university`,`res_department`,`res_project_title`,`res_project_description`,`res_picture` FROM $table;";
    $researchers=$wpdb->get_results($query,ARRAY_A);
    
    $count=1;  
    ?>
    <div class="container">  
        <div class="row">
			<?php global $biruk_allowed; if($biruk_allowed){?>
			<div class="row col-md-12">
				
				<button type="button" class="btn btn-lg btn-info" data-toggle="modal" data-target="#addResearcherUiModal">
                
                <i class="fa fa-plus-square"></i> Add New Researcher
                    </button>
			</div>
			<?php }?>
            <div class="row col-md-12">
                <h3 class="text-muted text-center"> Current Member Researchers</h3>
                    
                
                
            </div>
          
            <?php
                 echo "<div class='row col-md-12' style='margin-bottom:20px'>" ;
                foreach($researchers as $researcher){
                    
            ?>   
            <div class="col-md-4">

                <div class="card">
                    <?php if(true){ ?> <img class="card-img-top img-thumbnail" style="max-height:250px; width:auto"  src=" <?php echo $researcher['res_picture']; ?>"  onerror= "this.src= 'https://cdn.pixabay.com/photo/2016/04/01/10/11/avatar-1299805_960_720.png'" alt="Profile Picture" \> <?php }?>
                        
                        <div class="card-body text-muted">
                            <h3 class="card-title text-center text-muted"><?php echo $researcher['res_name']; ?> </h3>
                                
                            <p class="card-text" ><strong>Email:</strong> <?php echo $researcher['res_email'] ?></p>
                                <p class="card-text"><strong>Level:</strong> <?php echo $researcher['res_level'] ?></p>
                                <p class="card-text" ><strong>University:</strong> <?php echo $researcher['res_university'] ?></p>
                                <p class="card-text"><strong>Department:</strong> <?php echo $researcher['res_department'] ?></p>
                                <p class="card-text"><strong>Project title:</strong> <?php echo $researcher['res_project_title'] ?></p>
                                <p class="card-text"><strong>Project Description:</strong> </p>
                                <p class="card-text">
                                <?php echo $researcher['res_project_description'] ?>
                                </p>
                                
                        </div>
                        <?php global $biruk_allowed; if($biruk_allowed){?>
                        <div class="card-footer text-muted">
                                    <button  class="card-link btn btn-lg btn-outline-success float-right ml-2 btn-res-edit" data-toggle="modal" data-target="#updateResearcherUiModal" data-res_id=" <?php echo $researcher['res_id'] ?>"><i class="fa fa-edit" aria-hidden="true" ></i>Edit</button>
                                    <button  class="card-link btn btn-lg btn-outline-danger float-right btn-res-del" data-res_id=" <?php echo $researcher['res_id'] ?>"> <i class="fas fa-trash-alt"></i> Delete</button>
                        </div>
                        <?php } ?>
                    
                </div>
            </div>

                <?php 
            
           
            if($count%3==0){
                echo "</div>  <div style='margin-bottom:20px' class='row col-md-12'>";
            }
            $count=$count+1;
            
            } ?>
            
            
            
            
        </div>
        
        
    </div>

    

    <?php
    
    echo "</div>"; 
} 
?>

