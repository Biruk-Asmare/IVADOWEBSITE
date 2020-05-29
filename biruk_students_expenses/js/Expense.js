var exp_ins_tbl_num=1;
var exp_ins_row_total=0.0;
var validator;
var detailed_itemized_expenses=[];

var _url = '/wp-json/tcsi4ai/v1/expense/';

$('document').ready(function (){
    
    $('#eb_date_from').datepicker({dateFormat: "yy-mm-dd"});
    $('#eb_date_to').datepicker({dateFormat: "yy-mm-dd"});
    $('#eb_exp_date').datepicker({dateFormat: "yy-mm-dd"});
    $('#eb_item_add_date').datepicker({dateFormat: "yy-mm-dd"});
    $('#eb_item_edit_date').datepicker({dateFormat: "yy-mm-dd"});
    validateExpenseAddUI();
   $('#row_add_btn').click(function(event){
       
       add_row_insert_table(event);
         
   });
   $('#show_expenses_table').DataTable();
   $('.add_exp').click(function (e){
    e.preventDefault();
    $('#ins_exp_table tbody').empty(); 
    id=$(this).data("exp_id");
    add_expense(id);
    
    });
   $('#eub_expense_ui').on("click",".del_row",function(event){
    var currentRow=$(this).closest("tr"); 
    var index=currentRow.data("index");
    
    var ret= detailed_itemized_expenses.splice(index,1);
    update_total_cost();
    $(this).closest('tr').remove();

   });

   $('.btn_edit_detail_expense').click(function(event){
    var id=$(this).data("exp_id");
    fetch_detail_expense(id);
   });
   
   $( "#ueb_show_item_id" ).change(function() {
        var index= $(this).val();
        populate_update_items_by_index(index);
  });

  $('#row_edit_btn').click(function(event){
    var validInput=validator.element('#eb_item_edit_date') && validator.element('#eb_item_edit_exp_cat') && validator.element('#eb_item_edit_cost');
    if(validInput){
      var index=$('#ueb_show_item_id').val();
       var date= $('#eb_item_edit_date').val(); 
       var desc= $('#eb_item_edit_desc').val();
       var cat= $('#eb_item_edit_exp_cat').val();
        var cost=$('#eb_item_edit_cost').val();
        var data={eit_date:date,eit_category:cat, eit_description:desc,eit_cost:cost};
      detailed_itemized_expenses.splice(index,1,data);
      update_total_cost();
    }else{
        alert("Date category and cost are required and can not be empty");
    }
   

  });
  $('#row_delete_btn').click(function(event){
    var index=$('#ueb_show_item_id').val();
    detailed_itemized_expenses.splice(index,1);
    update_total_cost();
  

});
   
$('.btn_show_modal_insert').click(function(){
    $('.show_on_update').hide();
    $('body').data("mode","insert");
    detailed_itemized_expenses.splice(0,detailed_itemized_expenses.length); //reset array
});
 
$('#edit_item_delete_btn').click(function (){
    if (confirm ("Are you sure to delete "+$('#ueb_show_item_id option:selected').text()+"?")){
        var index=$('#ueb_show_item_id').val();
        detailed_itemized_expenses.splice(index,1);
        update_total_cost();
    }

});
$(".btn_del_expense").click(function (){
    //delete the whole expense
    if(confirm("Are you sure to remove the whole expense record?")){
       
        id= $(this).data('exp_id');
        delete_expense_by_id(id);
        alert("Expense is successfully deleted.");
    }
});
$('#eb_total_expense').change(function(){
    $(this).animate({ "text-color": 'blue' }, 2000);
});
    //

 });

 function delete_expense_by_id(id){
    var nonce= $('#res_edit_nonce').val();
   
    if(!nonce){
        
        return;
    }
    var formData= new FormData();
    formData.append( "_method","DELETE");
    formData.append("_wpnonce",nonce);
    formData.append("id",id);
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
            return data;
           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            return errorThrown;
     
        }
    });
 }

 function populate_update_items_by_index(index){
    $('#eb_item_edit_date').val(detailed_itemized_expenses[index].eit_date); 
    $('#eb_item_edit_desc').val(detailed_itemized_expenses[index].eit_description);
    $('#eb_item_edit_exp_cat').val(detailed_itemized_expenses[index].eit_category);
    $('#eb_item_edit_cost').val(detailed_itemized_expenses[index].eit_cost);
 }


function add_row_insert_table(event){
    event.preventDefault();
    //$("#formid").data('validator').element('#element').valid();
    var validInput=validator.element('#eb_item_add_date') && validator.element('#eb_item_add_exp_cat') && validator.element('#eb_item_add_cost');
    if(validInput){
            var ddate= $('#eb_item_add_date').val();
            var desc= $('#eb_item_add_desc').val();
            var cat= $('#eb_item_add_exp_cat').val();
            var cost= $('#eb_item_add_cost').val();
            var data={eit_date:ddate, eit_description:desc,eit_category:cat,eit_cost:cost};
            var len=  detailed_itemized_expenses.push(data);
            $('#ins_exp_table tbody').append('<tr data-index="'+(len-1).toString()+'" ><td>'+ddate+'</td><td>'+desc+'</td><td>'+cat+'</td><td class="cost_td">'+cost+'</td><td class="text-center"> <button   class="del_row btn  btn-small"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
            exp_ins_tbl_num=exp_ins_tbl_num+1;
            exp_ins_row_total=exp_ins_row_total +  parseFloat(cost);
            update_total_cost();
            
    }

}

function update_total_cost(){
    var total=0.0;
    $.each(detailed_itemized_expenses, function(index,val){
       total+=parseFloat(val.eit_cost);
    });
    $('#eb_total_expense').val(Math.abs(total.toFixed(3)));
    //$(this).animate({ color: '#E8DFCC' }, 1000).val('');
}


 function validateExpenseAddUI(){
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

    validator=$('#eub_expense_ui').validate({
        rules:{
            eb_res_name:{
                required:true

            },
            eb_expense_type:{
                required:true
            },
            
            eb_leader:{
                required:true
            },
            eb_approved_by:{
                required:true
            },
            ieb_item_add_cost:{
                required:true,
                number:true
            },
            ueb_item_add_cost:{
                required:true,
                number:true
            },
            eb_date_from:{
                required:true,
                date:true
            },
            eb_date_to:{
                required:true,
                date:true
            },
            ieb_item_add_date:{
                required:true,
                date:true
            },
            ueb_item_add_date:{
                required:true,
                date:true
            },
            ieb_item_add_exp_cat:{
                required:true
            },
            ueb_item_add_exp_cat:{
                required:true
            },
            eb_exp_date:{
                required:true,
                date:true
            },
            submitHandler: function(form) {
                
              },

        },
        messages:{

        },
        
          focusCleanup: true

    });
    $('#eub_expense_ui .form-control').blur(function(){
        validator.form();
        console.log("Form is validated");
    });
   
    
}

function fetch_detail_expense(id){
    detailed_itemized_expenses.splice(0,detailed_itemized_expenses.length); //reset array
    $('#ins_exp_table tbody').empty(); 
    $('#eub_expense_ui').trigger("reset");
    $('.show_on_update').show();
    var data={"_method":"GET", "id":id};
    $('body').data("mode","update");
    $('.add_exp').data("exp_id",id);
    
    console.log(data);
    $.get(_url,data,function(data){
        console.log(data);
        if(data.success){
                $('#eb_res_name').val(data.exp_data[0].exp_res_id);
                $("#eb_expense_type").val(data.exp_data[0].exp_type);
                $("#eb_date_from").val(data.exp_data[0].exp_period_from);
                $("#eb_date_to").val(data.exp_data[0].exp_period_to);
                $('#eb_total_expense').val(data.exp_data[0].exp_total_amount);
                $('#eb_work_package').val(data.exp_data[0].exp_work_package);
                $('#eb_exp_date').val(data.exp_data[0].exp_date);
                $('#eb_leader').val(data.exp_data[0].exp_leader);
                $('#eb_approved_by').val(data.exp_data[0].exp_approved_by);
				$('#ueb_show_item_id').empty();
                $.each(data.detail_exp_data, function(index,val){
                    len=detailed_itemized_expenses.push({eit_category:val.eit_category,eit_cost:val.eit_cost, eit_date:val.eit_date, eit_description:val.eit_description});
                    index=len-1;
                    $('#ueb_show_item_id').append("<option value='"+index+"'>Item "+ (index+1).toString()+" </option>");
                });
                update_total_cost();
                populate_update_items_by_index(0);
         }


    });

}

function add_expense(id){
    var nonce= $('#res_edit_nonce').val();
   
    if(!nonce){
        
        return;
    }
        var formData= new FormData();
        formData.append( "_method","POST");
        var mode =  $('body').data("mode");
        if(mode=="update"){
            //first delete existing expense
            res=delete_expense_by_id(id);
        }
        formData.append("_wpnonce",nonce);
        formData.append("res_id", $('#eb_res_name').val());
        formData.append("exp_type", $("#eb_expense_type").val());
        formData.append("exp_wp",$('#eb_work_package').val());
        formData.append("from",$("#eb_date_from").val());
        formData.append("to",$("#eb_date_to").val());
        formData.append("total_amount",$('#eb_total_expense').val());
        formData.append("_date",$('#eb_exp_date').val());
        formData.append("leader",$('#eb_leader').val());
        formData.append("approved_by", $('#eb_approved_by').val());
        formData.append("ite_exp", detailed_itemized_expenses);
        // var data={:ddate, eit_description:desc,eit_category:cat,eit_cost:cost};
        $.each( detailed_itemized_expenses,function(index,val){
            formData.append("ite_exp["+index+"][eit_date]",val.eit_date);
            formData.append("ite_exp["+index+"][eit_desc]",val.eit_description);
            formData.append("ite_exp["+index+"][eit_cat]",val.eit_category);
            formData.append("ite_exp["+index+"][eit_cost]",val.eit_cost);
        });

      
        $.ajax({
            url : _url,
            method: "POST",
            data : formData,
            processData: false,
            contentType: false,
            success: function(data)
            {
                console.log(data);
               
                //$('#uresponse').removeClass('alert alert-success').removeClass('alert alert-danger');
                //$('#uresponse').show();
                if(data.success){
                    if(mode=="update"){
                        //first delete existing expense
                        $('#expresponse').html('<i class="fa fa-check" aria-hidden="true"></i>Expense is successfully updated').addClass('alert alert-success');
						
                    }else{
						
                        $('#expresponse').html('<i class="fa fa-check" aria-hidden="true"></i>'+data.response).addClass('alert alert-success');
                    }
                    
                    window.setTimeout( function(){$('#expresponse').empty().hide();}, 5000 );
                    
                    
                }else{
                    $('#expresponse').html('<i class="fa fa-times" aria-hidden="true"></i>'+data.message).addClass('alert alert-danger');  
                }
    
    				
				window.setTimeout( function(){location.reload(true);}, 4000 );
               
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(errorThrown);
         
            }
        });
        
    
}