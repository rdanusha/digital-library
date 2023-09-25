<?php if (!empty($download_location)): ?>
<a id="download-document" href="<?php echo $download_location; ?>" download ></a>
<script>
    document.getElementById('download-document').click();
   window.close();
</script>
<?php endif; ?>

