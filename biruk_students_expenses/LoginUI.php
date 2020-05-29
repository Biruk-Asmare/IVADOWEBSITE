<?php 
function add_login_modal(){
	?>
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
            <div class="form-group col-md-12">
                
                <input type="text" class="form-control form-control-lg" id="bi_username" name="bi_username"  placeholder="username">
                
            </div>
            <div class="form-group col-md-12">
                <input type="password" class="form-control form-control-lg" id="bi_password" name="bi_password"  placeholder="password">
                <input type="hidden" data-url="<?php echo admin_url('admin-ajax.php'); ?>" id="bi_nonce" value="<?php echo wp_create_nonce('biruk_login_ethiopia_orthodox'); ?>">
                <input type="hidden"  id="bi_action" name="action" value="biruk_custom_login">
                
            </div>
            
    </div>
      </div>
      <div class="modal-footer">
        <div class="bi_login_response"></div>
        
        <button type="button"  id="bi_login_btn" class="btn btn-info">Log in</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}
function show_login_form(){

    //check if user is logged in, if true immedietly return, 
    if(is_user_logged_in()){
        $current_user= wp_get_current_user();
        show_admin_bar(false);
        ?>
        <span> Welcome: <?php echo $current_user->user_login;  ?> </span>
        <a class="" href="<?php echo wp_logout_url( home_url() ); ?>">
            Logout
            </a>

    <?php }else{
?>
 <button type="button" class="btn btn-info" data-toggle="modal" data-target="#loginModal">
  Login
</button>
<?php
				add_login_modal();
			   
	}}


function biruk_login(){
    //check nonce and handle the error
    $res=wp_verify_nonce($_POST['bi_login_nonce'],"biruk_login_ethiopia_orthodox");
    if(!sres){
        send_response(false,"Access denied");
    }
    //login the user
    $info=array();
    $info['user_login']= sanitize_text_field($_POST['username']);
    $info['user_password']= sanitize_text_field($_POST['password']);
    $info['remember']=true;
    $user_signin=wp_signon( $info, false );
    if(is_wp_error( $user_signin )){
        send_response(false,"Incorrect username/password");
    }
    send_response(true, "Login successful redirecting...");
    
}

function send_response($success, $message){
    echo json_encode(array(
        "success"=>$success,
        "response"=>$message


    ));

    die();
}

?>