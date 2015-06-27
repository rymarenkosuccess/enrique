<!-- BEGIN PAGE -->
<div class="page-content">
    <?php 
        $this->load->view('header_feed');
    ?>
    <div class="container-fluid">
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
                    echo form_open_multipart("content/feed/text", 'class="form-horizontal"');
                    //    
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
                    ?>
                        <div class="control-group">
                            <label class="control-label" for="url">Text Post</label>
                            <div class="controls">
                            <?php  echo form_textarea("description",$post['description'],'class="span6 m-wrap"');?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="url">Tags</label>
                            <div class="controls">
                            <?php  echo form_input("tags",$post['tags'],'class="span6 m-wrap"');?>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="url">Credits</label>
                            <div class="controls">
                            <?php  echo form_input("credit",$post['credit'],'class="span6 m-wrap"');?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                            <label class="checkbox line" >
                                <input type="checkbox" name="is_publish" <?php  if($post['is_publish']) echo "checked='true'"; ?>   >Published
                            </label>
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
