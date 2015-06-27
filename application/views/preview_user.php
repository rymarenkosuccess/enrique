<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <h3 class="page-title">
                    <?php echo $user->username ?>
                    <small></small>
                </h3>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <div class="row-fluid">
           <div class="span12">

<!-- BEGIN SAMPLE FORM PORTLET-->   
              <div class="portlet box blue">
                 <div class="portlet-title">
                    <h4><i class="icon-reorder"></i>User Information</h4>
                    <div class="tools">
                    </div>
                 </div>
                 <div class="portlet-body form">
                    <div class="tab-pane profile-classic row-fluid active" id="tab_1_2">
                        <div class="span2"><img src="<?php 
//                            echo UPLOAD_URL.$user->img_url;exit;
                        if(is_file(UPLOAD_DIR.$user->img_url)) echo UPLOAD_URL.$user->img_url; else echo "http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=No+photo"; ?>" alt=""> </div>
                        <ul class="unstyled span10">
                            <li><span>User Name:</span> <?php echo $user->username; ?></li>
                            <li><span>Email:</span> <a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></li>
                            <li><span>Gender:</span> <?php echo $user->gender ? "Female" : "Male"; ?></li>
                            <li><span>birthday:</span> <?php echo $user->birthday; ?></li>
                            <li><span>City:</span> <?php echo $user->city; ?></li>
                            <li><span>Position:</span> <?php echo $user->position; ?></li>
                            <!--<li><span>First Name:</span> John</li>
                            <li><span>Last Name:</span> Doe</li>-->
                            <li><span>AccessCode:</span> <?php echo $user->accesscode; ?></li>
                            <li><span>AccessFlag:</span><?php echo $user->accessflag ? "On" : "Off"; ?></li>
                        </ul>
                    </div>
                 </div>
              </div>
              <!-- END SAMPLE FORM PORTLET-->
              </div>
        </div>
    </div>
</div>
