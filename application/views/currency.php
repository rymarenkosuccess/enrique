<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Currency Settings
                    <small></small>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>    
        <div class="row-fluid">
            <div class="span12">
<!-- BEGIN SAMPLE FORM PORTLET-->   
                 <div class="portlet-body form">
                    <?php 
                    echo form_open_multipart("currency", 'class="chanel-design form-horizontal"');
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
                    <div class="row-fluid">
                        <div class="span12">
                        <table width="100%">
                            <?php for($i=0; $i<8; $i++): ?>
                                <?php if($i<count($rows)): 
                                    $row = $rows[$i];
                                ?>
                                    <tr>
                                        <td width="33%">
                                            <div class="control-group">
                                                <label class="control-label">Package<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('package[]',$row['package'],'class="span10 m-wrap"');?>
                                                </div>
                                            </div>

                                        </td>
                                        <td width="33%">
                                            <div class="control-group">
                                                <label class="control-label">Cost<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('cost[]',$row['cost'],'class="span10 m-wrap"');?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="control-group">
                                                <label class="control-label">Credits<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('credit[]',$row['credit'],'class="span10 m-wrap"');?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td width="33%">
                                            <div class="control-group">
                                                <label class="control-label">Package<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('package[]', '','class="span10 m-wrap"');?>
                                                </div>
                                            </div>

                                        </td>
                                        <td width="33%">
                                            <div class="control-group">
                                                <label class="control-label">Cost<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('cost[]','','class="span10 m-wrap"');?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="control-group">
                                                <label class="control-label">Credits<?php echo $i+1; ?>:</label>
                                                <div class="controls">
                                                <?php echo form_input('credit[]','','class="span10 m-wrap"');?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </table>
                        </div>
                    </div>
                    <div class="form-actions">
                        <?php echo form_submit('submit', "Post",' class="btn blue"');?>
                    </div>
                    <?php echo form_close();?>
                    <!-- END FORM-->
                 </div>
              <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
    </div>
</div>
<link href="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
<script src="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.pack.js" ></script>
<script src="<?php echo ASSETS_DIR; ?>/jscolor/jscolor.js" ></script>
