<?php

function show_options(){
    $biruk_db= new BirukDatabase();
    global $wpdb;
    $table= $biruk_db->options_table_name;
    $query= "SELECT * FROM $table;";
    $op_res=$wpdb->get_results($query,ARRAY_A);
    if($op_res===false){
        echo 'Searching options is failed, for unknown reason';
        
    }
    

?>
   <div class="container">
                <div class="modal" tabindex="-1" id="op_ins_modal" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add new Option</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="iop_name">Option Name</label>
                                    <input type="text"  class="form-control " id="iop_name" name="iop_name" >
                                    <small id="emailHelp" class="form-text text-muted">Option name must be unique.</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="op_value">Option values</label>
                                    <textarea class="form-control" id="iop_value" name="op_value" rows="5"></textarea>
                                    <small id="optionsHelp" class="form-text text-muted">Separate option names with comma(,). No space in between options</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_add_option" class="btn btn-info">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>
            <form>
            <div class="row">
                    <div class="col-md-3 offset-md-4">
                    <label for="btn_add_option"></label>
                        <button type="button" id="" class="btn btn-lg btn-info" data-toggle="modal" data-target="#op_ins_modal"><i class="fa fa-plus" style="margin-right:10px;"></i>Add new Option</button>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="op_name">Option Name</label>
                        <select class="form-control " name="op_name" id="op_name">
                                  <?php foreach($op_res as $op) { ?>
                                    <option value="<?php echo $op['option_id']; ?>"><?php echo $op['op_name'];?></option>  
                                  <?php }?>
                                </select>
                        <small id="emailHelp" class="form-text text-muted">Option name can not be edited.</small>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="op_value">Option values</label>
                        <textarea class="form-control  " id="op_value" name="op_value" rows="5"><?php echo $op_res[0]['op_values']; ?>
                        </textarea>
                        <small id="optionsHelp" class="form-text text-muted">Separate option names with comma(,). No space in between options</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-md-4">
                    <label for="btn_edit_option"></label>
                        <button type="button" id="btn_edit_option" class="btn btn-lg btn-info"><i class="fa fa-save" style="margin-right:10px;"></i>save Changes</button>
                    </div>
                    <div class="col-md-4">
                        <label for="btn_edit_option"></label>
                        <button type="button" id="btn_del_option" class="btn btn-lg btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Delete Option</button>
                    </div>
                </div>
               
                
                
               
        </form>
    </div> 
<?php 
    }
?>