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

	<meta name="description" content="<?=$vars->Get('description')?>">
    <meta itemprop="description" content="<?=$vars->Get('description')?>">
    <meta property="og:description" content="<?=$vars->Get('description')?>">
    <meta name="twitter:description" content="<?=$vars->Get('description')?>">

	<meta name="og:title" property="og:title" content="<?=$vars->Get('title')?>">

	<meta property="og:image"  content="<?= $requestScheme ?>://<?= $serverName . $vars->Get('seo_image') ?>">
    <meta itemprop="image" content="<?= $requestScheme ?>://<?= $serverName . $vars->Get('seo_image')?>">
    <meta name="twitter:image:src" content="<?= $requestScheme ?>://<?= $serverName . $vars->Get('seo_image')?>">
    <meta id="request-method" name="request-method" content="<?= htmlentities($_SERVER['REQUEST_METHOD'])?>">

	<?php $this->AppendFiles->Append(false); ?>
    
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&display=swap" rel="stylesheet">
    
    
	
</head>