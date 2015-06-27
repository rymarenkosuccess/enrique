<!--<ul class="breadcrumb portlet box green feed">-->
<ul class="breadcrumb portlet box green feed">
    <li class="submenu <?php if(strpos($this->uri->segment(3), 'text') !== false) echo " active "; ?>">
        <a class="" href="<?php echo site_url("content/feed/text") ?>">Text</a>
    </li>
    <li class="submenu <?php if(strpos($this->uri->segment(3), 'photo') !== false) echo " active "; ?>">
        <a class="" href="<?php echo site_url("content/feed/photo") ?>">Photo</a>
    </li>
    <li class="submenu <?php if(strpos($this->uri->segment(3), 'video') !== false) echo " active "; ?>">
        <a class="" href="<?php echo site_url("content/feed/video") ?>">Video</a>
    </li>
    <li class="submenu <?php if(strpos($this->uri->segment(3), 'poll') !== false) echo " active "; ?>">
        <a class="" href="<?php echo site_url("content/feed/poll") ?>">Poll</a>
    </li>
    <li class="submenu <?php if(strpos($this->uri->segment(3), 'quiz') !== false) echo " active "; ?>">
        <a class="" href="<?php echo site_url("content/feed/quiz") ?>">Quiz</a>
    </li>
</ul>
<div class="container-fluid" style="margin-top: -20px;">
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
            <div class="page-title">
                <?php echo isset($chanel['name']) ? $chanel['name'] : "" ?>            
            </div>
                            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
</div>
