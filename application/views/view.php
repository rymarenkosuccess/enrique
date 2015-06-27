<!-- BEGIN PAGE -->
<div class="page-content">
</div>
<div style="display: none;">
    <textarea id="usagesbyregion" ><?php echo $usagesbyregion; ?></textarea>
    <textarea id="usagesbyusertype" ><?php echo $usagesbyusertype; ?></textarea>
</div>
<!-- END PAGE -->
<script>
    function confirm_del(id) {
        if(confirm("Do you want to delete this channel?")) {
            document.location.href = "<?php echo site_url('dashboard/chanel_del'); ?>/" + id;
        }
    }
</script>
