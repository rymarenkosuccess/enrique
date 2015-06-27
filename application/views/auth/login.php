<!-- BEGIN LOGIN -->
<div class="login">
  <div class="content">
  
<h3 class="form-title"><?php echo lang('login_heading');?></h3>
<p><?php echo lang('login_subheading');?></p>

<?php echo $message;?>

<?php echo form_open("auth/login", 'class="form-vertical login-form"');?>

      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-user"></i>
            <?php echo form_input($identity,'',' class="m-wrap" placeholder="'.lang('login_identity_label').'" ');?>
          </div>
        </div>
      </div>

      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-lock"></i>
            <?php echo form_input($password,'',' class="m-wrap" placeholder="'.lang('login_password_label').'" ' );?>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <label class="checkbox">
        	<?php 
        		echo form_checkbox('remember', '1', FALSE, 'id="remember"');
        		echo lang('login_remember_label');
        	?>
        </label>
		<?php echo form_submit('submit', lang('login_submit_btn'), ' class="btn green pull-right" '  );?>
      </div>

<?php echo form_close();?>

<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>

	</div>
</div>
<script>
    $("body").addClass('login');
</script>
<!-- END LOGIN -->