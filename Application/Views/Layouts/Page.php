<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
		include VIEWPATH . '/'. 'Partials' . '/' . 'Head.php'
	?>
</head>
<body>
	<div class="modules">
		<?php
			$module->LoadModules();
		?>
	</div>
	<?php
		include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'LoadScreen.php';
		include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'AddonLiveEdit.php';
	?>
</body>
</html>