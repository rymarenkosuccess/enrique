<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Social Settings
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
                    echo form_open_multipart("social", 'class="chanel-design form-horizontal"');
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
                    <div class="space10"></div>
                    <div class="space10"></div>
                    <div class="row-fluid">
                        <div class="span12">
                        <table width="100%">
                            <tr>
                                <td width="30%" align="center">
                                    <div class="control-group">
                                        <a class="btn social" href="<?php echo site_url('social_account/index/twitter') ?>">Connect to twitter</a>
                                    </div>
                                </td>
                                <td align='left' colspan="6">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="twitter" <?php if($social['twitter']) echo "checked='true'"; ?>   >Automatically Post
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" align="center">
                                    <div class="control-group">
                                        <a class="btn social" href="<?php echo site_url('social_account/index/facebook') ?>" >Connect to Facebook</a>
                                    </div>
                                </td>
                                <td align='left' colspan="6">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="facebook" <?php if($social['facebook']) echo "checked='true'"; ?>   >Automatically Post
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" align="center">
                                    <div class="control-group">
                                        <a class="btn social" href="<?php echo site_url('social_account/index/google') ?>" >Connect to Google Plus</a>
                                    </div>
                                </td>
                                <td align='left' colspan="6">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="google" <?php if($social['google']) echo "checked='true'"; ?>   >Automatically Post
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" align="center">
                                    Push Notifications:
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="photos" <?php if($social['photos']) echo "checked='true'"; ?>   >Photos
                                        </label>
                                    </div>
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="videos" <?php if($social['videos']) echo "checked='true'"; ?>   >Videos
                                        </label>
                                    </div>
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="text" <?php if($social['text']) echo "checked='true'"; ?>   >Text
                                        </label>
                                    </div>
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="poll" <?php if($social['poll']) echo "checked='true'"; ?>   >Poll
                                        </label>
                                    </div>
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="quiz" <?php if($social['quiz']) echo "checked='true'"; ?>   >Quiz
                                        </label>
                                    </div>
                                </td>
                                <td align='left' width="12%">
                                    <div class="control-group">
                                        <label class="checkbox line" >
                                            <input class="social_check" type="checkbox" name="auction" <?php if($social['auction']) echo "checked='true'"; ?>   >Auction
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        </div>
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
