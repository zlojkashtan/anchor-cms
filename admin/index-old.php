<?php
  include('loader.php');
	
	$title = 'Edit Posts';

	include('inc/header.php');
?>

	<div id="content">
		<div id="left">
			<?php
			if(!isset($_GET['page'])) {
				
			} else {
			  $obj = Post::find($_GET['page']);
				
				$error = '';
				
				if(isset($_POST['submit'])) {
				
					//	CSS
					
					if(!empty($_FILES['postcss']['name'])) {
						$css = explode('.', $_FILES['postcss']['name']);
						if(end($css) == 'css') {
							$move = $path . '/uploads/' . basename($_FILES['postcss']['name']); 
							
							if(move_uploaded_file($_FILES['postcss']['tmp_name'], $move)) {
								$cssfile = $path . '/uploads/' . basename($_FILES['postcss']['name']);
							} else {
							    $error .= "There was an error uploading the file, please try again!<br />";
							}
						} else {
							$error = 'Please upload a CSS file.<Br />';
						}
					}
	
					//	JS
					
					if(!empty($_FILES['postjs']['name'])) {
						$js = explode('.', $_FILES['postjs']['name']);
						if(end($js) == 'js') {
							$move = $path . '/uploads/' . basename($_FILES['postjs']['name']); 
							
							if(move_uploaded_file($_FILES['postjs']['tmp_name'], $move)) {
								$jsfile = $path . '/uploads/' . basename($_FILES['postcss']['name']);
							} else {
								$error .= "There was an error uploading the file, please try again!<br />";
							}
						} else {
							$error .= 'Please upload a JavaScript file.<br />';
						}
					}
					
					//
					if($_POST['posttitle'] && ctype_alnum($_POST['postslug']) && $_POST['postexcerpt'] && $_POST['posthtml']) {
						$query = $db->prepare('update posts set slug = :slug, title = :title, content = :content, excerpt = :excerpt, css = :css, javascript = :js where id = :id');
						$exec = $query->execute(array(':title' => $_POST['posttitle'], ':slug' => $_POST['postslug'], ':excerpt' => $_POST['postexcerpt'], ':content' => $_POST['posthtml'], ':css' => $cssfile, ':js' => $jsfile, ':id' => $_GET['page']));
						if($exec) {
							$error .= 'Post updated.';
						} else {
							$error .= 'Post did not update.';
						}
					} elseif(!ctype_alnum($_POST['postslug'])) {
						$error .= 'Post slug must be alphanumeric.';
					} else {
						$error .= 'Don\'t leave any fields blank!<br />';
					}
				}
			?>
				<h1>Editing &lsquo;<?php echo $obj['title']; ?>&rsquo; <a href="<?php echo $urlpath; ?>admin">Cancel</a></h1>
				
				<?php if(isset($_POST['submit']) && $error) { ?><p id="error"><?php echo $error; ?></p><?php } ?>
				
				<form action="" method="post" enctype="multipart/form-data">
					<p>
						<label for="posttitle">Post title:</label>
						<input id="posttitle" name="posttitle" value="<?php echo $obj['title']; ?>" />
					</p>
					<p>
						<label for="postslug">Post slug:</label>
						<input id="postslug" class="monospace" name="postslug" value="<?php echo $obj['slug']; ?>" />
					</p>
					<p>
						<label for="postexcerpt">Post excerpt:</label>
						<textarea id="postexcerpt" name="postexcerpt"><?php echo $obj['excerpt']; ?></textarea>
					</p>
					<p>
						<label for="posthtml">Post HTML:</label>
						<textarea id="posthtml" class="monospace" name="posthtml"><?php echo $obj['content']; ?></textarea>
					</p>
					<p>
						<label for="postcss">Post CSS:</label>
						<input type="file" id="postcss" name="postcss" />
					</p>
					<p>
						<label for="postjs">Post Javascript:</label>
						<input type="file" id="postjs" name="postjs" />
					</p>
					<p>
						<a class="delete" href="<?php echo $urlpath; ?>admin/delete/<?php echo $_GET['page']; ?>">Delete this post?</a>
						<input name="submit" type="submit" value="Save changes" />
					</p>
				</form>
			<?php } ?>
		</div>
<?php include('inc/footer.php'); ?>