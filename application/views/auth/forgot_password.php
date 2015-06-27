<div class="login">
  <div class="content">

<h3><?php echo lang('forgot_password_heading');?></h3>
<p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

<?php echo $message;?>

<?php echo form_open("auth/forgot_password", 'class="form-vertical forget-form"' ); ?>

      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-envelope"></i>
            <?php echo form_input($email,'',' class="m-wrap" placeholder="'.sprintf(lang('forgot_password_email_label'), $identity_label).'"  ');?>
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <a href="<?php echo site_url("auth/login"); ?>" id="back-btn" class="btn">
        <i class="m-icon-swapleft"></i> Back
        </a>
        <?php echo form_submit('submit', lang('forgot_password_submit_btn'),'class="btn green pull-right"');?>
        
      </div>
      
<?php echo form_close();?>

	</div>
</div>