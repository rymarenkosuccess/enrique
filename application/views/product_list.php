<!-- BEGIN PAGE -->
<div class="page-content">
    <div class="container-fluid" id="dashboard">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->            
                <div class="page-title">
                    Shop
                    <small></small>
                    <div class="btn-group pull-right">
                        <a id="sample_editable_1_new" href="<?php echo site_url('content/product/add'); ?>" class="btn green">
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
                            <th></th>
                            <th>Title</th>
                            <!--<th>Quantity</th>-->
                            <th class="hidden-320">Category</th>
                            <th class="hidden-480">Size</th>
                            <th class="hidden-480">Color</th>
                            <th class="hidden-480">Price</th>
                            <!--<th>Sale?</th>-->
                            <th class="">Tags</th>
                            <!--<th>Ref URL</th>-->
                            <th class="span2 hidden-480">Edit</th>
                            <th class="span2 hidden-480">Delete</th>
                        </tr>
                    </thead>
                    <tbody>     
                    <?php 
                    foreach($products as $product): ?>
                        <tr>
                            <td class="span2 center" >
                            <?php if(isset($product['imageArr'][0]) && is_file(UPLOAD_DIR.$product['imageArr'][0])){  ?>
                                <img class="product_thumbnail thumb0" src="<?php echo UPLOAD_URL.$product['imageArr'][0]; ?>">
                                <img class="product_thumbnail thumb1" src="<?php echo UPLOAD_URL.$product['imageArr'][1]; ?>" style="display: none;">
                                <img class="product_thumbnail thumb2" src="<?php echo UPLOAD_URL.$product['imageArr'][2]; ?>" style="display: none;">
                            <?php } ?>
                                <div class="row-fluid bx-wrapper" style="margin-bottom: -10px;">
                                    <div class="bx-pager-item"><a href="#" data-slide-index="0" class="bx-pager-link active">0</a></div>
                                    <div class="bx-pager-item"><a href="#" data-slide-index="1" class="bx-pager-link">1</a></div>
                                    <div class="bx-pager-item"><a href="#" data-slide-index="2" class="bx-pager-link">2</a></div></div>
                                </div>
                            </td>
                            <td><?php echo $product['title']; ?></td>
                            <td class="hidden-320 span2"><?php echo $product['category']; ?></td>
                            <!--<td><?php echo $product['quantity']; ?></td>-->
                            <td class="hidden-480"><?php echo $product['size']; ?></td>
                            <td class="hidden-480"><?php echo $product['color']; ?></td>
                            <td class="hidden-480"><?php echo $product['price']; ?></td>
                            <!--<td class="hidden-480"><?php echo $product['sale']; ?></td>-->
                            <td class=""><?php echo $product['tags']; ?></td>
                            <!--<td class="hidden-480"><?php echo $product['url']; ?></td>-->
                            <td class="center hidden-480">
                            <?php
                                echo 
                                '<a class="btn mini purple" href="'.site_url('content/product/edit/'.$product['id']).'">'.
                                '<i class="icon-edit"></i>Edit'.
                                '</a>';
                            ?>
                            </td>
                            <td class="center hidden-480">
                            <?php
                                echo 
                                '<a class="btn mini black" href="javascript:confirm_del(\''.$product['id'].'\')">'.
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
        if(confirm("Do you want to delete this product?")) {
            document.location.href = "<?php echo site_url('content/product_delete'); ?>/" + id;
        }
    }    
</script>