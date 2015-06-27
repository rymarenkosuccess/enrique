<link href="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
<script src="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.pack.js" ></script>
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title form-horizontal">
                    Photo Gallery
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?PHP echo site_url('content/feed/photo') ?>" class="btn green">
                        New Photo
                        </a>
                    </div>
                    <div class="btn-group pull-right control-group" style="margin-right: 30px;">
                        <label class="control-label">
                        Filter By Tags
                        </label>
                        <div class="controls">
                            <?php echo form_dropdown("photo_tags_search", $tagOptions, $selectedTags, 'class="photo_tags_search medium m-wrap"');?>
                        </div>
                    </div>
                </div>
                                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>    
        <input type="hidden" value="<?php echo site_url('content/photo_gallery'); ?>" id='base_url'>
        <div class="row-fluid">
            <div class="portlet-body">
                <!-- BEGIN GALLERY MANAGER LISTING-->
                <?php 
                    $is_row = true;
                    $endkey = 0;
                    while($endkey < count($photos)): 
                ?>
                <div class="row-fluid">
                    <?php for($key=$endkey; $key<count($photos); $key++): ?>
                    <?php // foreach($photos as $key => $photo): 
                        $photo = $photos[$key];
                    ?>
                    <div class="span3">
                        <div class="item">
                            <a class="fancybox-button" data-rel="fancybox-button" title="<?php echo $photo['description']; ?>" href="<?php echo $photo['destination']; ?>">
                                <div class="zoom gallery-image-container" style="text-align: center;"> 
                                    <img src="<?php echo $photo['destination']; ?>" alt="Photo" />                                           <div class="zoom-icon"></div>
                                    <div class="gallery-image-outer">
                                        <div class="gallery-image-inner"><?php echo $photo['description']; ?></div>    
                                    </div>
                                </div>
                            </a>
                            <div class="details">
                                <a href="#" class="icon"><i class="icon-paper-clip"></i></a>
                                <a href="#" class="icon"><i class="icon-link"></i></a>
                                <a href="#" class="icon"><i class="icon-pencil"></i></a>
                                <a href="#" class="icon"><i class="icon-remove"></i></a>        
                            </div>
                        </div>                       
                    </div>
                    <?php if((($key+1)%4)==0 || ($key+1)>=count($photos)) {
                        $endkey = $key+1;
                        break;
                    } ?>
                    <?php endfor; ?>
                </div>
                <div class="space10"></div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <!-- END GALLERY MANAGER PORTLET-->
</div>