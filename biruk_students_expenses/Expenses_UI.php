<?php

$biruk_allowed_exp=false;
   
function populate_list_itemse($res,$op_name){
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

function populate_researcher_name(){
    
    global $wpdb;
    $biruk_db= new BirukDatabase();
    $table= $biruk_db->researcher_table_name;
    $query= $query="SELECT `res_id`,`res_name`FROM $table";
    $rese=$wpdb->get_results($query,ARRAY_A); 
    echo '<option value="">...select...</option>';
    foreach($rese as $result){
            
        echo '<option value="'.$result['res_id'].'">'.$result['res_name'].'</option>';   
            
        
    }

}
function display_expenses_Registration_form(){
    global $wpdb;
    $biruk_db= new BirukDatabase();
    $table= $biruk_db->options_table_name;
    $query= "SELECT * FROM $table;";
    $rese=$wpdb->get_results($query,ARRAY_A); 
    ?>
	<div class="row col-md-12">
	<button type="button" class="btn btn-lg btn-info btn_show_modal_insert" data-toggle="modal" data-target="#addExpenseUiModal">
                <i class="fa fa-plus-square"></i> Add New Expense
                    </button> 

	</div>
    <div class="row">
        <h3 class="text-muted text-center col-md-12"> Expenses </h3>  
                
            </div>
    <div class="modal fade col-md-12" id="addExpenseUiModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add/Edit Expense</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <div class="container-fluid">  
    
    <form class="col-md-12" id="eub_expense_ui" >
        
        <div class="row">
            <div class="form-group col-md-6">
                <label for="eb_fullName">Researcher Name* </label>
                <select class="form-control  " name="eb_res_name" id="eb_res_name">
                        <?php populate_researcher_name() ;?>
                </select>
                
            </div>
            <div class="form-group col-md-6">
                <label for="eb_expense_type"> Expense Type* </label>
                    <select class="form-control  " name="eb_expense_type" id="eb_expense_type">
                        <?php  populate_list_itemse($rese,"Expense Type");?>
                    </select>
                
            </div>
            
    </div>


    <div class='row'>
            
            
            <div class="form-group col-md-6">
                <label for="eb_work_package">Work Package*</label>
                    <select class="form-control  " name="eb_work_package" id="eb_work_package">
                        <?php populate_list_itemse($rese,"Work Package"); ?>
                    </select>
                
            </div>
            <div class="form-group col-md-6">
                <label for="eb_leader">Project Leader*</label>
                    <select class="form-control  " name="eb_leader" id="eb_leader">
                        <?php  populate_list_itemse($rese,"Leader"); ?>
                    </select>
            
            </div>

        </div>

    
        
        
        <div class="row">
            <div class="form-group col-md-6">
                    <label for="eb_date_from">Expense date from</label>
                    <input type="text" class="form-control  " id="eb_date_from" name="eb_date_from"  placeholder="yyyy-mm--dd">
            </div>
            <div class="form-group col-md-6">
                <label for="eb_date_to">Expense date to</label>
                <input type="text" class="form-control  " id="eb_date_to" name="eb_date_to"  placeholder="yyyy-mm--dd">
            </div>
        </div>
        <div class="row">
            
            
            <div class="form-group col-md-6">
                    <label for="eb_exp_date">Expense issue date</label>
                    <input type="text" class="form-control  " id="eb_exp_date" name="eb_exp_date"  placeholder="yyyy-mm--dd">
            </div>
            <div class="form-group col-md-6">
                <label for="eb_approved_by" >Approved By</label>
                <input type="text"  class="form-control  " id="eb_approved_by" name="eb_approved_by" >
            </div>


        </div>
        <div class="row">
            
            <div class="form-group col-md-6">
                <label for="eb_total_expense">Total amount ($)</label>
                <input type="text" readonly  class="form-control  " id="eb_total_expense" name="eb_total_expense" >
            </div>
            
        </div>
        <hr/>
        <div class="row">
            <h3 class="col-md-6 text-center">Add New Items</h3>

        </div>
        <div class="row">
                <div class="form-group col-md-3">
                    <label for="eb_item_add_date" >Date</label>
                    <input type="text"  class="form-control  " id="eb_item_add_date" name="ieb_item_add_date" >
                </div>
                <div class="form-group col-md-3">
                    <label for="eb_item_add_desc" >Description</label>
                    <input type="text"  class="form-control  " id="eb_item_add_desc" name="ieb_item_add_desc" >
                </div>
                <div class="form-group col-md-3">
                    <label for="eb_item_add_exp_cat">Category</label>
                    <select class="form-control  " name="ieb_item_add_exp_cat" id="eb_item_add_exp_cat">
                        <?php populate_list_itemse($rese,"Expense categories");?>
                    </select>
            
                 </div>
                 <div class="form-group col-md-2">
                    <label for="eb_item_add_cost" >Cost($)</label>
                    <input type="text"  class="form-control  " id="eb_item_add_cost" name="ieb_item_add_cost" >
                </div>
                <div class="col-md-1 text-center">
                    <label for="eb_item_add_desc" ></label>
                    <button  type="button" class="btn btn-info" id="row_add_btn"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
        </div>

        <hr />

        <div class="row show_on_update">
            <h3 class="col-md-6 text-center">Edit Existing Items</h3>
            <div class="form-group col-md-4">
                    <label for="eb_item_add_exp_cat">Select item to edit</label>
                    <select class="form-control  " name="ueb_show_item_id" id="ueb_show_item_id">
                        
                    </select>
            
                 </div>
                 <div class="col-md-1 text-center">
                    <label for="" ></label>
                    <button  type="button" class="btn btn-danger" id="edit_item_delete_btn"><i class="fa fa-trash" aria-hidden="true"></i> Delete item</button>
                </div>

        </div>
        <div class="row show_on_update">
                
                <div class="form-group col-md-3">
                    <label for="eb_item_add_date" >Date</label>
                    <input type="text"  class="form-control  " id="eb_item_edit_date" name="ueb_item_add_date" >
                </div>
                <div class="form-group col-md-3">
                    <label for="eb_item_add_desc" >Description</label>
                    <input type="text"  class="form-control  " id="eb_item_edit_desc" name="ueb_item_add_desc" >
                </div>
                <div class="form-group col-md-3">
                    <label for="eb_item_add_exp_cat">Category</label>
                    <select class="form-control  " name="ueb_item_add_exp_cat" id="eb_item_edit_exp_cat">
                        <?php populate_list_itemse($rese,"Expense categories");?>
                    </select>
            
                 </div>
                 <div class="form-group col-md-2">
                    <label for="eb_item_add_cost" >Cost($)</label>
                    <input type="text"  class="form-control  " id="eb_item_edit_cost" name="ueb_item_add_cost" >
                </div>
                <div class="col-md-1 text-center">
                    <label for="eb_item_add_desc" ></label>
                    <button  type="button" class="btn btn-info" id="row_edit_btn"><i class="fa fa-save fa-2x" aria-hidden="true"></i></button>
                </div>
        </div>
        <div class="row col-md-12" id="expresponse"></div> 
        <div class="row">
            <table class="table table-striped"  id="ins_exp_table">
                <thead class="thead-dark" >
                    <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Description</th>
                    <th scope="col">Category</th>
                    <th scope="col">Cost ($)</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody >
                    
                    
                </tbody>
            </table>
        </div>        
        
</div>
            
            </div>
            <div class="modal-footer">
            <div class="col-md-2 offset-md-2 col-sm-12">
                        <button data-exp_id="" type="button" style="font-size : 15px; margin-bottom:10px;" class="btn btn-success btn-block p-3 add_exp"><i class="fa fa-save" style="margin-right:10px;"></i>Save</button>
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

<?php
} 

function show_expenses(){


    if(is_user_logged_in()){
        $current_user= wp_get_current_user();
        
        
        if ( in_array( 'editor', (array) $current_user->roles )  or in_array( 'administrator', (array) $current_user->roles ) ) {
            global $biruk_allowed_exp;  
            $biruk_allowed_exp=true;
            display_expenses_Registration_form();
            
            ?>
            <input type="hidden" id="res_edit_nonce" value="<?php echo wp_create_nonce("wp_rest"); ?>" />

        <?php }
    }else{
        return;
    }
    
    $query="SELECT wp_researcher.res_name, wp_expense.exp_id,
    wp_expense.exp_type, wp_expense.exp_date, wp_expense.exp_total_amount, wp_expense.exp_approved_by, wp_expense.exp_leader
    FROM wp_expense JOIN wp_researcher
    ON wp_expense.exp_res_id=wp_researcher.res_id";
    global $wpdb;
    $expenses=$wpdb->get_results($query,ARRAY_A); 
    
    
    ?>
    
        <div class="container-fluid">
            <div class="row">
             <div class="col-md-12">
                <table class="table table-striped"  id="show_expenses_table">
                    <thead class="thead-dark" >
                        <tr>
                        <th scope="col">Researcher</th>
                        <th scope="col">Expense type</th>
                        <th scope="col">Expense date</th>
                        <th scope="col">Amount($)</th>
                        <th scope="col">Project Leader</th>
                        <th scope="col">Approved By</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php foreach($expenses as $exp){?>

                            <tr>
                                <td><?php echo $exp['res_name']; ?></td>
                                <td><?php echo $exp['exp_type']; ?></td>
                                <td><?php echo $exp['exp_date']; ?></td>
                                <td><?php echo $exp['exp_total_amount']; ?></td>
                                <td><?php echo $exp['exp_leader']; ?></td>
                                <td><?php echo $exp['exp_approved_by']; ?></td>
                                <td> 
                                <button data-exp_id="<?php echo $exp['exp_id']; ?>" class="btn btn-info btn_edit_detail_expense" data-toggle="modal" data-target="#addExpenseUiModal"><i class="fa fa-edit" aria-hidden="true"></i> </button>
                                <button data-exp_id="<?php echo $exp['exp_id']; ?>" class="btn btn-danger btn_del_expense"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>

                        <?php } ?>
                        
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        





<?php

}

?>










