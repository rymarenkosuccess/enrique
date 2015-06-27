<!-- BEGIN PAGE -->
<div class="page-content">
    <?php 
    ?>
    <div class="container-fluid">
        <div class="row-fluid">
           <div class="span12">
              <h3 class="page-title">
              </h3>
              <ul class="breadcrumb">
                <li><a href="<?php echo site_url("configuration/email_list"); ?>">Auto Email List</a></li>
              </ul>
           </div>
        </div>
        <div class="row-fluid">
           <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box yellow">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>Update auto email</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("configuration/email_edit/".$post['id'], 'class="form-horizontal"');
                        echo form_error("description");
                        if(isset($show_errors)) {
                            if (is_array($show_errors)) {
                                foreach($show_errors as $error) {
                                    echo "<div class='alert alert-error'>".$error."</div>";
                                }
                            }
                            else{
                                echo "<div class='alert alert-error'>".$show_errors."</div>";
                            }
                        }
                        if(isset($success_message)){
                            echo "<div class='alert alert-block alert-success fade in'>{$success_message}</div>";
                        }
                    ?>
                        <div class="control-group">
                            <label class="control-label" for="message">Message</label>
                            <div class="controls">
<!--                                <div class="span5">-->
                                    <?php echo form_textarea("message",$post['message'], 'class="span5 m-wrap"'); ?>
<!--                                </div>-->
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="subject">Subject</label>
                            <div class="controls">
                            <?php echo form_input("subject",$post['subject'], 'class="span5 m-wrap"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="state">State</label>
                            <div class="controls">
                            <?php echo form_dropdown("state",$stateOptions,$post['state'], 'class="span5 m-wrap"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="city">City</label>
                            <div class="controls">
                            <?php echo form_dropdown("city",$cityOptions,$post['city'], 'class="span5 m-wrap"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="gender">Gender</label>
                            <div class="controls">
                            <?php echo form_dropdown("gender",array("2"=>"all", "0"=>"male", "1"=>"female"), $post['gender'], 'class="span5 m-wrap"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="position">I am a </label>
                            <div class="controls">
                            <?php echo form_dropdown("position",$positionOptions,$post['position'], 'class="span5 m-wrap"'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email_date">Email Date</label>
                            <div class="controls">
                                <?php  echo form_input(array("id"=>"email_date", "name"=>"email_date"), $post['email_date'],'class="span5 m-wrap m-ctrl-medium date-picker" ');?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="checkbox" name="is_soon" id="is_soon" >
                                <label class="checkbox" for='is_soon'>Immediately</label>
                            </div>
                        </div>
                        <div class="form-actions">
                            <?php echo form_submit('submit', "Save",' class="btn blue"');?>
                        </div>
                    <?php echo form_close();?>
                    <!-- END FORM-->
                 </div>
              </div>
              <!-- END SAMPLE FORM PORTLET-->
              </div>
        </div>
    </div>
</div>
<script>
    $("#is_soon").click(function(){
        if($(this).is(':checked')){
            $("#email_date").attr('disabled',true);
            $("#email_date").css('color','#888888');
        }else{
            $("#email_date").removeAttr('disabled');
            $("#email_date").css('color','#555555');
        }
    });
</script>

