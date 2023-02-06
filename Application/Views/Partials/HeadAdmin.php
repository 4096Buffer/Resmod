<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php
    	$requestScheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
    	$serverName = $_SERVER['SERVER_NAME'];

  	?>	

  	<link rel="shortcut icon" href="<?= $vars->Get('favicon') ?>" type="image/x-icon">
  	<link rel="icon" href="<?= $vars->Get('favicon') ?>" type="image/x-icon">
    
  	<meta property="og:url"  content="<?= $requestScheme ?>://<?= $serverName ?>">
 	<meta property="og:type" content="website">
    
	<title>
		<?= $vars->Get('title')?>
	</title>
	
    <meta id="request-method" name="request-method" content="<?= htmlentities($_SERVER['REQUEST_METHOD'])?>">
    <script type="text/javascript" src="<?=$vars->Get('addon')?>"></script>
	<?php $this->AppendFiles->Append(true); ?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&display=swap" rel="stylesheet">
	
</head>