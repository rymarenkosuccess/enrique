<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <h3 class="page-title">
                    <?php echo $user['first_name']." ".$user['last_name'] ?>
                    <small></small>
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <?php
                            if($is_active):
                        ?>
                            <a href="<?php echo site_url("fan/active"); ?>">Active Fans</a>
                        <?php
                            else:
                        ?>
                            <a href="<?php echo site_url("fan/block"); ?>">Block Fans</a>
                        <?php
                            endif;
                        ?>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class="row-fluid">
           <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box yellow">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>User Information</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <div class="tab-pane profile-classic row-fluid active" id="tab_1_2">
                        <div class="span2"><img src="<?php 
//                            echo UPLOAD_URL.$user->img_url;exit;
                        if(is_file(UPLOAD_DIR.$user['img_url'])) echo UPLOAD_URL.$user['img_url']; else echo "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=No+photo"; ?>" alt=""> </div>
                        <ul class="unstyled span10">
                            <li><span>User Name:</span> <?php echo $user['username']; ?></li>
                            <li><span>Email:</span> <a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a></li>
                            <li><span>Block:</span><?php if($user['active']) echo "UnBlocked"; else echo "Blocked"; ?></li>
                            <li><span>Suspend:</span><?php if($user['suspend']) echo "Suspended"; else echo "UnSuspended"; ?></li>
                            <li><span>Credit:</span><?php  echo $user['credit'];  ?></li>
                            <li><span>Join Date:</span><?php  echo $user['join_date'];  ?></li>
                            <li><span>Last Login:</span><?php  echo $user['last_login'];  ?></li>
                        </ul>
                    </div>
                 </div>
              </div>
              <div class="portlet box yellow">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>Recent Post</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th colspan="2">Post Type</th>
                                <th>Date</th>
                                <th>Username</th>
                                <th>Published</th>
                            </tr>
                        </thead>
                        <tbody>     
                        <?php 
                        foreach($community_feeds as $feed): ?>
                            <tr>
                                <td class="feed-thumbnail-td ">
                                <?php if(is_file(UPLOAD_DIR.$feed['image_path'])){  ?>
                                    <img class='feed-thumbnail pull-right' src="<?php echo UPLOAD_URL.$feed['image_path']; ?>">
                                <?php } ?>
                                </td>
                                <td class="feed-thumbnail-noborder"><?php echo $feed['post_type']; ?></td>
                                <td><?php echo $feed['date']; ?></td>
                                <td><?php echo $feed['username']; ?></td>
                                <td><?php echo $feed['is_publish'] ? "YES" : "NO"; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                 </div>
              </div>
              <!-- END SAMPLE FORM PORTLET-->
              </div>
        </div>
    </div>
</div>
