<?php include 'header.php'; ?>
<?php include "constraint_display.php"; ?>
<?php include "sub-header.php"; ?>
<?php paginateLinks($db, 'pages_top'); ?>
<?php $i = 0; ?>
<table class="screenshot">
<?php
if($show_all_links):
	while($row = $show_all_links->fetch(PDO::FETCH_ASSOC)): ?>
			<tr><td><img src="<?php echo $row['screenshot_path']; ?>" alt="No image found" /></td></tr>
			<tr><td>
				URL: <a id="screen_link_<?php echo $i; ?>" href="http://<?php echo $row['url']; ?>" target="_blank"><?php echo $row['url']; ?></a>
			</td></tr>
			<tr><td class="form_row">
				<form action="#" method="post" name="urls_<?php echo $i; ?>">
					<label for="constraint">Constrain: </label>
					<input type="radio" value="1" name="constrain_<?php echo $i; ?>" 
					<?php showCheckedRadio(1, $row['constrain']) ?> /> Yes
					<input type="radio" value="0" name="constrain_<?php echo $i; ?>" 
					<?php showCheckedRadio(0, $row['constrain']) ?> /> No
					
					 <label for="seed" id="seed">Possible Seed: </label>
					<input type="radio" value="1" name="seed_<?php echo $i; ?>" 
					<?php showCheckedRadio(1, $row['seed']) ?> /> Yes
					<input type="radio" value="0" name="seed_<?php echo $i; ?>" 
					<?php showCheckedRadio(0, $row['seed']) ?> /> No
					
					<label for="short url" id="short_url">Short URL: </label>
					<input type="text" name="url_<?php echo $i; ?>" size="55" value="<?php echo $row['url']; ?>" /><br />
					<input type="hidden" name="url_id_<?php echo $i; ?>" value="<?php echo $row['id']; ?>" />
					<input type="submit" name="save_url" value="Save" />
				</form>
				<span id="save_message_<?php echo $i; ?>"></span>
			</td></tr>
<?php 	$i++; ?>
<?php endwhile; ?>
</table>
<?php paginateLinks($db, 'pages_bottom'); 
endif; ?>
</body>
</html>