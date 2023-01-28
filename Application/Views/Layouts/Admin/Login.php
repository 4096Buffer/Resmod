<!DOCTYPE html>
<html>
<head>
	
	<?php 
		include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'Head.php'
	?>

</head>
<body>
    <form class="admin-login-form" ajax-controller="Login" ajax-action="Login">
        <div class="login-box">
            <input type="text" id="admin-login-form-name" name="login" required>
            <label>Login:</label>
        </div>
        <div class="password-box">
            <input type="password" id="admin-login-form-pass" name="password" required>
            <label>Password:</label>
        </div>
        
        <input type="submit" id="admin-login-form-submit" value="Zaloguj się" required>
        <span class="info-text" style="color:red"></span>
    </form>
</body>
</html>