<?php 

if($this->Auth->IsAuth()) {
    $this->RequestHelper->Redirect('/dashboard-admin');
}

?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include VIEWPATH . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'Head.php'
	?>
</head>
<body>
    <?php 
        include VIEWPATH . '/' . 'Partials' . '/' . 'Admin' . '/' . 'LoadScreen.php';
    ?>
    <div class="admin-login-container">
        <div class="admin-login-form-box">
            <form ajax-controller="AdminProfile" ajax-action="Login">
                <div class="login-box">
                    <label>Login:</label>
                    <input type="text" id="admin-login-form-name" name="login" required>
                </div>
                <div class="password-box">
                    <label>Password:</label>
                    <input type="password" id="admin-login-form-pass" name="password" required>
                </div>
                
                <input type="submit" id="admin-login-form-submit" value="Zaloguj się" required>
                <span class="info-text" style="color:red"></span>
            </form>
        </div>
    </div>
</body>
</html>