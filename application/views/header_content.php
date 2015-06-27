<ul class="breadcrumb">
<?php
    foreach($submenus as $submenu):
?>
    <li class="submenu <?php if($submenu['url'] == $this->uri->segment(2)) echo " active "; ?>">
        <a class="green" href="<?php echo site_url("content/".$submenu['url']) ?>"><?php echo $submenu['name'] ?></a>
    </li>
<?php 
    endforeach;
?>
</ul>


