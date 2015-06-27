<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid" id="dashboard">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Music Playlist
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?php echo site_url('content/music_player/add'); ?>" class="btn green">
                        Add New 
                        </a>
                    </div>
                </div>
                <?php // echo $message;?>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <div class="row-fluid">
            <div class="portlet-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="2" >Title of Song</th>
                            <th>Album title</th>
                            <th>Ref URL to buy</th>
                            <th>Publish</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>     
                    <?php 
                    foreach($musics as $music): ?>
                        <tr>
                            <td class="feed-thumbnail-td ">
                            <?php if(is_file(UPLOAD_DIR.$music['image_path'])){  ?>
                                <img class='feed-thumbnail pull-right' src="<?php echo UPLOAD_URL.$music['image_path']; ?>">
                            <?php } ?>
                            </td>
                            <td class="feed-thumbnail-noborder"><?php echo $music['title']; ?></td>
                            <td><?php echo $music['album']; ?></td>
                            <td><?php echo $music['url']; ?></td>
                            <td><?php echo $music['is_publish'] ? "YES" : "NO"; ?></td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini purple" href="'.site_url('content/music_player/edit/'.$music['id']).'">'.
                                '<i class="icon-edit"></i>Edit'.
                                '</a>';
                            ?>
                            </td>
                            <td class="center">
                            <?php
                                echo 
                                '<a class="btn mini black" href="javascript:confirm_del(\''.$music['id'].'\')">'.
                                '<i class="icon-trash"></i>Delete'.
                                '</a>';
                            ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function confirm_del(id) {
        if(confirm("Do you want to delete this song?")) {
            document.location.href = "<?php echo site_url('content/music_delete'); ?>/" + id;
        }
    }    
</script>