<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Category
                    <small></small>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>    
        <div class="row-fluid">
           <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box blue">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>Add Form</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("attribute/category/add", 'class="form-horizontal"');
                        echo form_error("name");
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
                    <div class="row-fluid">
                        <div class="control-group">
                            <label class="control-label" for="url">Name</label>
                            <div class="controls customarea">
                            <?php  echo form_input("name",$post['name'],'class="large m-wrap"');?>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <?php echo form_submit('submit', "Post",' class="btn blue"');?>
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
