<?php include 'header.php'; ?>
<?php include "constraint_display.php"; ?>
<?php include "sub-header.php"; ?>
<!-- Downloading Files from a list -->
	<h3>Note: Please put each url on a separate line<br />
    and save the file as .txt or .csv to upload and process correctly</h3>
	<div id="upload_form">
        <form name="upload_text_file" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="upload_text_file">
            <label for="upload">Select a File to Upload: </label>
            <input type="file" name="upload" size="50 "id="upload" />
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" /><br /><br />
            <input type="submit" name="submit_urls" value="Upload Text File" />
        </form>
    </div>
    <?php include 'upload_file_process.php'; ?>
</body>
</html>