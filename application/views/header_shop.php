<ul class="breadcrumb">
    <li class="submenu <?php if('product' == $this->uri->segment(3)) echo " active "; ?>">
        <a class="green" href="<?php echo site_url("content/shop/product") ?>">Product</a>
    </li>
    <li class="submenu <?php if('category' == $this->uri->segment(3)) echo " active "; ?>">
        <a class="green" href="<?php echo site_url("content/shop/category") ?>">Category</a>
    </li>
    <li class="submenu <?php if('' == $this->uri->segment(3)) echo " active "; ?>">
        <a class="green" href="<?php echo site_url("content/shop/color") ?>">Color</a>
    </li>
    <li class="submenu <?php if('size' == $this->uri->segment(3)) echo " active "; ?>">
        <a class="green" href="<?php echo site_url("content/shop/size") ?>">Size</a>
    </li>
</ul>


