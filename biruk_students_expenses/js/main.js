$('document').ready(function (){
    
    $('#b_date_from').datepicker({dateFormat: "yy-mm-dd"});
    $('#b_date_to').datepicker({dateFormat: "yy-mm-dd"});
    validateResearcherUI();
    $('#r_reset').click(function (){
     $('#b_register_ui').find('input, select').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
     $('#response').html("");
     $('#response').hide();
 
    });
    $('#ub_date_from').datepicker({dateFormat: "yy-mm-dd"});
    $('#ub_date_to').datepicker({dateFormat: "yy-mm-dd"});
    validateResearcherUpdateUI();
    
 
     $('.btn-res-edit').click(edit_researcher);
     $('.btn-res-del').click(delete_researcher);
 
 //handle login functionality
 /**
  * 
  *  <input type="text" class="form-control form-control-lg" id="bi_username" name="bi_username"  placeholder="username">
                 
             </div>
             <div class="form-group col-md-12">
                 <input type="password" class="form-control form-control-lg" id="bi_password" name="bi_password"  placeholder="password">
                 <input type="hidden" data-url="<?php echo admin_url('admin-ajax.php'); ?>" id="bi_nonce" value="<?php echo wp_create_nonce('biruk_login_ethiopia_orthodox'); ?>">
                 <input type="hidden"  id="bi_action" name="action" value="biruk_custom_login">
                 
             </div>
  */
 $("#bi_login_btn").click(function(e){
     e.preventDefault();
     var username= $('#bi_username').val();
     var password= $('#bi_password').val();
     var nonce=$('#bi_nonce').val();
     var url=$('#bi_nonce').data("url");
     var action= $('#bi_action').val();
     //bi_login_response
     if(username=="" || password==""){
         $('.bi_login_response').removeClass('text-success');
         $('.bi_login_response').html("Username and password is required").addClass('text-danger');
     }else{
         var formData= new FormData();
         formData.append('username',username);
         formData.append('password',password);
         formData.append('action',action)
         formData.append('bi_login_nonce',nonce);
        
         //return
         
         $.ajax({
             url : url,
             method: "POST",
             data : formData,
             cache:false,
             processData: false,
             contentType:false,
             success: function(data, textStatus, jqXHR)
             {
                 data= JSON.parse(data);
                 console.log(data);
 
                 if(data.success){
                     $('.bi_login_response').removeClass('text-danger');
                     $('.bi_login_response').html(data.response).addClass('text-success');
                    location.reload();
 
                 }else{
                     $('.bi_login_response').removeClass('text-success');
                     $('.bi_login_response').html(data.response).addClass('text-danger');
                 }
                
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
                 alert(errorThrown);
          
             }
         });
     }
 
 });
 
 $('#op_name').change(function(){
     var id=$(this).val();
    var nonce= $('#res_edit_nonce').val();
    
    if(!nonce){
        
        return;
    }
    
     var _url='/wp-json/tcsi4ai/v1/option';
     var data={"_method":"GET", "id":id,"_wpnonce":nonce};
     $.get(_url,data,function(data){
        console.log(data);
        if(data.success){
               $('#op_value').val(data.data[0].op_values);
         }


    });
 });

 $('#btn_edit_option').click(function (){

    var id=$('#op_name').val();
    var values=$('#op_value').val();
    var name=$( "#op_name option:selected" ).text();
    var nonce= $('#res_edit_nonce').val();
    
    if(!nonce && !values){
        
        return;
    }

    var _url='/wp-json/tcsi4ai/v1/option';
    var data={"_method":"PUT", "id":id,"_wpnonce":nonce,"op_values":values,"op_name":name};
    $.ajax({
        url : _url,
        method: "POST",
        data : data,
       
        success: function(data, textStatus, jqXHR)
        {
            console.log(data);
            if(data.success){
                alert(name+" is successfully updated.");

            }else{
                alert(data.message) 
            }
           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(errorThrown);
     
        }
    });
   
 });
 $('#btn_add_option').click(function (){

    
    var values=$('#iop_value').val();
    var name=$('#iop_name').val();
    var nonce= $('#res_edit_nonce').val();
    
    if(!nonce && !values && !name){
        
        return;
    }
    if(!check_if_new_option_is_unique(name)){
        alert(name+ " is already taken. Please use another name");
        $('#iop_name').val("");
        return;
    }


    var _url='/wp-json/tcsi4ai/v1/option';
    var data={"_method":"POST","_wpnonce":nonce,"op_values":values,"op_name":name};
    $.ajax({
        url : _url,
        method: "POST",
        data : data,
       
        success: function(data, textStatus, jqXHR)
        {
            console.log(data);
            if(data.success){
                alert(name+" is successfully added. Refreshing page..");
                window.setTimeout( function(){location.reload(true);}, 1000 );

            }else{
                alert(data.message) 
            }
           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(errorThrown);
     
        }
    });
   
 });

 $('#btn_del_option').click(function (){
    var name=$( "#op_name option:selected" ).text();
     if(confirm("Are you sure ? You are deleting `"+ name +"` option.")){
        
         var id=$('#op_name').val();
        var nonce= $('#res_edit_nonce').val();
        
    if(!nonce && !values){
        
        return;
    }

    var _url='/wp-json/tcsi4ai/v1/option';
    var data={"_method":"DELETE", "id":id,"_wpnonce":nonce};
    $.ajax({
        url : _url,
        method: "POST",
        data : data,
       
        success: function(data, textStatus, jqXHR)
        {
            console.log(data);
            if(data.success){
                alert(name+" is successfully deleted, Refreshing the page..");
                window.setTimeout( function(){location.reload(true);}, 1000 );

            }else{
                alert(data.message) 
            }
           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(errorThrown);
     
        }
    });
     }
 });
 
 });


 function check_if_new_option_is_unique(name){
        var unique=true;
        $("#op_name > option").each(function() {
           
            if(this.text==name){
                unique=false;
                return;
            }
        });
        return unique;
 }
 
 function edit_researcher(){
     
     var id=$(this).data('res_id');
     $('#ub_resercher_ui').attr("data-ures_id",id);
     //fetch and populate the update form here
     var _url = '/wp-json/tcsi4ai/v1/researcher/';
     var data={"b_method":"GET", "id":id.trim()};
     
     console.log(data);
     $.get(_url,data,function($data){
         console.log($data);
 
         
         
         $("#ub_fullName").val($data.data[0].res_name);
         $("#ub_email").val($data.data[0].res_email);
         $("#ub_level").val($data.data[0].res_level);
         $("#ub_role").val($data.data[0].res_role); //$data.data[0].res_role
         $("#ub_leader").val($data.data[0].res_leader);
         $("#ub_department").val($data.data[0].res_department);
         $("#ub_university").val($data.data[0].res_university);
         $("#ub_work_package").val($data.data[0].res_work_package);
         $("#ub_graduation").val($data.data[0].res_graduation_year);
         $("#ub_coordinator").val($data.data[0].res_coordinator);
         $("#ub_date_from").val($data.data[0].res_study_from);
         $("#ub_date_to").val($data.data[0].res_study_to);
         $("#ub_title").val($data.data[0].res_project_title);
         $("#ub_description").val($data.data[0].res_project_description);
         $('#ub_resercher_ui').attr("data-ures_picture",$data.data[0].res_picture);
         
 
 
 
     });
 }
 function delete_researcher(){
     var nonce= $('#res_edit_nonce').val();
 
     if(!nonce){
         return;
     }
     if(confirm("Are you sure you want to delete this researcher?")){
         var _url = '/wp-json/tcsi4ai/v1/researcher/';
         var formData= new FormData();
         formData.append("_wpnonce",nonce);
         formData.append( "b_method","DELETE");
         formData.append("id",$(this).data("res_id") );
         $.ajax({
             url : _url,
             method: "POST",
             data : formData,
             cache:false,
             processData: false,
             contentType:false,
             success: function(data, textStatus, jqXHR)
             {
                 console.log(data);
                 if(data.success){
                     alert("Reaseracher is successfully deleted");
 
                 }else{
                     alert(data.message) 
                 }
                
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
                 alert(errorThrown);
          
             }
         });
 
     }
     else{
         return false;
     }
    
 }
 function add_researcher(){
     var nonce= $('#res_edit_nonce').val();
    
     if(!nonce){
         
         return;
     }
     console.log(nonce);
     var _url = '/wp-json/tcsi4ai/v1/researcher/';
     
     var file_data=null;
     file_data = $('#b_picture')[0].files[0];
     var ext = $('#b_picture').val().split('.').pop();
    
    
     var formData= new FormData();
         formData.append("_wpnonce",nonce);
         formData.append( "b_method","POST");
         formData.append( "name", $("#b_fullName").val());
         formData.append( "email", $("#b_email").val());
         formData.append( "level", $("#b_level").val());
         formData.append( "role", $("#b_role").val());
         formData.append( "coordinator", $("#b_coordinator").val());
         formData.append( "department", $("#b_department").val());
         
         formData.append( "university", $("#b_university").val());
         formData.append( "grad_year", $("#b_graduation").val());
         formData.append( "work_package", $("#b_work_package").val());
         formData.append( "leader", $("#b_leader").val());
         formData.append( "from", $("#b_date_from").val());
         formData.append( "to", $("#b_date_to").val());
         formData.append( "proj_title", $("#b_title").val());
         formData.append( "proj_desc", $("#b_description").val());
         if(ext!==""){
             formData.append( "picture", file_data, file_data.name+"."+ext);
          }
 
    
     console.log(formData)
     $.ajaxSetup({
         contentType:"multipart/form-data"
     });
     $.ajax({
         url : _url,
         method: "POST",
         data : formData,
         cache:false,
         processData: false,
         contentType:false,
         success: function(data, textStatus, jqXHR)
         {
             console.log(data);
            
             $('#response').removeClass('alert alert-success').removeClass('alert alert-danger');
             $('#response').show();
             if(data.success){
                 $('#response').html('<i class="fa fa-check" aria-hidden="true"></i>'+data.response).addClass('alert alert-success');
                 window.setTimeout( function(){location.reload(true);}, 2000 );
             }else{
                 $('#response').html('<i class="fa fa-times" aria-hidden="true"></i>'+data.message).addClass('alert alert-danger');  
             }
            
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
             alert(errorThrown);
             console.log(errorThrown);
             $('#response').html('<i class="fa fa-times" aria-hidden="true"></i> Access denied').addClass('alert alert-danger'); 
      
         }
     });
 }
 
 function validateResearcherUI(){
     jQuery.validator.setDefaults({
         highlight: function(element) {
             jQuery(element).closest('.form-control').addClass('is-invalid');
         },
         unhighlight: function(element) {
             jQuery(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
         },
         errorElement: 'span',
         errorClass: 'text-danger',
         errorPlacement: function(error, element) {
             if(element.parent('.input-group').length) {
                 error.insertAfter(element.parent());
             } else {
                 error.insertAfter(element);
             }
         }
     });
 
     var validator=$('#b_resercher_ui').validate({
         rules:{
             b_fullName:{
                 required:true
 
             },
             b_email:{
                 required:true,
                 email:true
             },
             b_level:{
                 required:true,
             },
             b_role:{
                 required:true,
             },
             b_coordinator:{
                 required:true,
             },
             b_department:{
                 required:true,
             },
             b_university:{
                 required:true,
             },
             b_graduation:{
                 digits:true,
                 min:2020,
                 max:2100
             },
             b_leader:{
                 required:true
             },
             b_title:{
                 required:true
             },
             b_date_from:{
                 date:true
             },
             b_date_to:{
                 date:true
             },
             b_picture:{
                 accept:true
             }
         
 
 
         },
         messages:{
 
         },
         submitHandler: function(form) {
             add_researcher();
           },
           focusCleanup: true
 
     });
     $('#b_resercher_ui .form-control').blur(function(){
         validator.form();
         console.log("Form is validated");
     });
     
 }
 
 function validateResearcherUpdateUI(){
     jQuery.validator.setDefaults({
         highlight: function(element) {
             jQuery(element).closest('.form-control').addClass('is-invalid');
         },
         unhighlight: function(element) {
             jQuery(element).closest('.form-control').removeClass('is-invalid').addClass('is-valid');
         },
         errorElement: 'span',
         errorClass: 'text-danger',
         errorPlacement: function(error, element) {
             if(element.parent('.input-group').length) {
                 error.insertAfter(element.parent());
             } else {
                 error.insertAfter(element);
             }
         }
     });
 
     var validator=$('#ub_resercher_ui').validate({
         rules:{
             ub_fullName:{
                 required:true
 
             },
             ub_email:{
                 required:true,
                 email:true
             },
             ub_level:{
                 required:true,
             },
             ub_role:{
                 required:true,
             },
             ub_coordinator:{
                 required:true,
             },
             ub_department:{
                 required:true,
             },
             ub_university:{
                 required:true,
             },
             ub_graduation:{
                 digits:true,
                 min:2020,
                 max:2100
             },
             ub_leader:{
                 required:true
             },
             ub_title:{
                 required:true
             },
             ub_date_from:{
                 date:true
             },
             ub_date_to:{
                 date:true
             },
             ub_picture:{
                 accept:true
             }
         
 
 
         },
         messages:{
 
         },
         submitHandler: function(form) {
             
             updateResearcher();
           },
           focusCleanup: true
 
     });
     $('#ub_resercher_ui .form-control').blur(function(){
         validator.form();
         console.log("Form is validated");
     });
     
 }
 
 function updateResearcher(){
     var nonce= $('#res_edit_nonce').val();
     if(!nonce){
         return;
     }
     var id=$('#ub_resercher_ui').attr("data-ures_id");
     var _url = '/wp-json/tcsi4ai/v1/researcher/';
    
     
     var file_data=null;
     file_data = $('#ub_picture')[0].files[0];
     var ext = $('#ub_picture').val().split('.').pop();
    
    
     var formData= new FormData();
         formData.append("_wpnonce",nonce);
         formData.append( "b_method","PUT");
         formData.append( "id",id);
         formData.append( "name", $("#ub_fullName").val());
         formData.append( "email", $("#ub_email").val());
         formData.append( "level", $("#ub_level").val());
         formData.append( "role", $("#ub_role").val());
         formData.append( "coordinator", $("#ub_coordinator").val());
         formData.append( "department", $("#ub_department").val());
         
         formData.append( "university", $("#ub_university").val());
         formData.append( "grad_year", $("#ub_graduation").val());
         formData.append( "work_package", $("#ub_work_package").val());
         formData.append( "leader", $("#ub_leader").val());
         formData.append( "from", $("#ub_date_from").val());
         formData.append( "to", $("#ub_date_to").val());
         formData.append( "proj_title", $("#ub_title").val());
         formData.append( "proj_desc", $("#ub_description").val());
         if(ext===""){
             formData.append( "picture", $('#ub_resercher_ui').attr("data-ures_picture"));
         }else{
             formData.append( "picture", file_data, file_data.name+"."+ext);
             
         }
 
     console.log(formData)
     $.ajaxSetup({
         contentType:"multipart/form-data"
     });
     $.ajax({
         url : _url,
         method: "POST",
         data : formData,
         cache:false,
         processData: false,
         contentType:false,
         success: function(data, textStatus, jqXHR)
         {
             console.log(data);
            
             $('#uresponse').removeClass('alert alert-success').removeClass('alert alert-danger');
             $('#uresponse').show();
             if(data.success){
                 $('#uresponse').append('<i class="fa fa-check" aria-hidden="true"></i>'+""+$("#ub_fullName").val()+" is updated successfully").addClass('alert alert-success');
                 window.setTimeout( function(){location.reload(true);}, 2000 );
                 
             }else{
                 $('#uresponse').append('<i class="fa fa-times" aria-hidden="true"></i>'+data.message).addClass('alert alert-danger');  
             }
 
 
            
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
             alert(errorThrown);
      
         }
     });
 
 
     
 }