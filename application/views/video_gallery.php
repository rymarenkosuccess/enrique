
<div id="fancybox_container" style="width:640px; height: 360px;display: none;">
    <?php foreach($videos as $key => $video): ?>
        <?php
            $novideo = false;
            if($video['video_url']){
                $url = $video['video_url'];
            }else{
                if(is_file(UPLOAD_DIR.$video['destination'])){
                    $url = UPLOAD_URL.$video['destination'];
                }else{
                    $novideo = true;
                    $url = ASSETS_DIR.'/img/no-video-found.jpg';
                }
            }
            $is_youtube = strpos($url, 'youtube.com') || strpos($url, 'youtu.be') !== false ? 1 : 0;
        ?>
        <div class="video center" id='video_container_<?php echo $video['id']; ?>' style='height:100%;'>
            <?php if($novideo){?>
                <img src="<?php echo $url; ?>" style="max-height:100%;height:100%">
            <?php }else{ ?>
                <video width="640" height="360" id="player<?php echo $key; ?>">
                    
                    <!-- Pseudo HTML5 -->
                    <source id='htmlvideoplayer' <?php if($is_youtube) echo 'type="video/youtube"'; ?> src="<?php echo $url; ?>" />
                    <!--<source  src="http://127.0.0.1/dan/1.flv" />-->

                </video>
            <?php } ?>
        </div>
    <?php endforeach; ?>
    <!--    <span id="player1-mode"></span>-->
            
        
<!--    <span id="player2-mode"></span>-->
</div>
<input type="hidden" value="<?php echo site_url('content/video_gallery'); ?>" id='base_url'>
<div class="page-content">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title form-horizontal">
                    Video Gallery
                    <small></small>
                    
                    <div class="btn-group pull-right">

                        <a id="sample_editable_1_new" href="<?PHP echo site_url('content/feed/video') ?>" class="btn green">
                        New Video
                        </a>
                    </div>
                    <div class="btn-group pull-right control-group" style="margin-right: 30px;">
                        <label class="control-label">
                        Filter By Tags
                        </label>
                        <div class="controls">
                            <?php echo form_dropdown("video_tags_search", $tagOptions, $selectedTags, 'class="video_tags_search medium m-wrap"');?>
                        </div>
                    </div>
                    <div class="btn-group pull-right" style="margin-right: 30px;">
                    </div>
                </div>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>    

        <?php if(0): ?>
        <div class="row-fluid">
            <div class="portlet-body">
                <!-- BEGIN GALLERY MANAGER PANEL-->
                <!-- BEGIN GALLERY MANAGER LISTING-->
                <div class="row-fluit" >
                    <div style="display:none;margin-left: auto;margin-right: auto;" class="html5gallery" data-skin="darkness" data-width="480" data-height="272" >
                        <?php foreach($videos as $video): ?>
                        <?php
                            if($video['video_url']){
                                $url = $video['video_url'];
                            }else{
                                if(is_file(UPLOAD_DIR.$video['destination'])){
                                    $url = UPLOAD_URL.$video['destination'];
                                }else{
                                    $url = "http://youtu.be/";
                                    $url = ASSETS_DIR.'/img/no-video-found.jpg';
                                }
                            }
                        ?>
                            <a href="<?php echo $url; ?>"><img src="<?php echo ((isset($video['image_path']) && $video['image_path']) ? UPLOAD_URL.$video['image_path'] : ASSETS_DIR.'/img/no-image-found.jpg') ?>" alt="Tags : <?php echo $video['description']."<br>".$video['description']; ?>" /></a>
                        <?php endforeach; ?>
                    </div>                
                </div>
                <div class="space10"></div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row-fluid">
            <div class="portlet-body">
                <!-- BEGIN GALLERY MANAGER LISTING-->
                <?php 
                    $is_row = true;
                    $endkey = 0;
                    while($endkey < count($videos)): 
                ?>
                <div class="row-fluid">
                    <?php for($key=$endkey; $key<count($videos); $key++): ?>
                    <?php // foreach($photos as $key => $photo): 
                        $video = $videos[$key];
//                        $video['image_path'] = (isset($video['image_path']) && $video['image_path']) ? UPLOAD_URL.$video['image_path'] : ASSETS_DIR.'/img/no-image-found.jpg';
                    ?>
                    <div class="span3">
                        <div >
                            <a  title="<?php echo $video['description']; ?>" class="fancybox" style='cursor: pointer;'>
                                <input type="hidden" class="video_info" value="<?php echo $video['id']; ?>">
                                <div > 
                                    <img style="width:120px;height:90px;max-width:98%;border:1px solid black;" src="<?php echo $video['image_path'] ?>" alt="<?php echo $video['description']; ?>" />
                                   <div class="zoom-icon"></div>
                                    <div class="gallery-image-outer">
                                        <div class="gallery-image-inner"><?php echo $video['description']; ?></div>    
                                    </div>
                                </div>
                            </a>
                        </div>                       
                    </div>
                    <?php if((($key+1)%4)==0 || ($key+1)>=count($videos)) {
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
<link href="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
<script src="<?php echo ASSETS_DIR; ?>/fancybox/source/jquery.fancybox.pack.js" ></script>
<script type="text/javascript" src="<?php echo ASSETS_DIR; ?>/htmlplayer/mediaelement-and-player.min.js"></script>
<link href="<?php echo ASSETS_DIR; ?>/htmlplayer/mediaelementplayer.min.css" rel="stylesheet" />

<script>
    $('video').mediaelementplayer({
        success: function(media, node, player) {
            $('#' + node.id + '-mode').html('mode: ' + media.pluginType);
        }
    });


</script>
