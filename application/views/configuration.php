      <!-- BEGIN PAGE -->  
      <div class="page-content">

         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                 <!-- <h3 class="page-title">
                     Configuration
                  </h3> -->
<!--                  <small><li>&raquo;</li></small>-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

            <div class="row-fluid">
               <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
                  <div class="portlet box yellow">
                     <div class="portlet-title">
                        <h4><i class="icon-reorder"></i>Quanfiguration</h4>
                        <div class="tools">
                        </div>
                     </div>
                     <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <?php 
                            echo form_open_multipart("configuration/updateConfiguration",'class="form-horizontal"');
//                                echo form_error($question_name['name']);
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
                        ?>
                        <?php
                            foreach($configs as $config){
                        ?>
                            <div class="control-group">
                                <label class="control-label" for="<?php echo $config['name'] ?>" style="text-align: center"><?php echo $config['label']; ?>:</label>
                                <div class="controls">
                                    <?php  
                                        if($config['is_textarea'])
                                            echo form_textarea("vals[{$config['name']}]", $config['value'], 'class="span6 m-wrap"');    
                                        else
                                            echo form_input("vals[{$config['name']}]", $config['value'], 'class="span6 m-wrap"');    
                                    ?>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                            <div class="form-actions">
                                <?php echo form_submit('submit', "Submit",' class="btn blue"');?>
                            </div>
                                <!--<div class="form-actions">
                                      
                                  </div>-->
                        <?php echo form_close();?>
                        <!-- END FORM-->
                     </div>
                  </div>
                  <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT--> 
    
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE --> 
<script>
    function confirm_del(id) {
        if(confirm("Do you want to delete this answer?")) {
            document.location.href = "<?php echo site_url('answer/answer_del'); ?>/" + id;
        }
    }
</script>
