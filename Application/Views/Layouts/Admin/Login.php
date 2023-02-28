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
                <div class="admin-login-input-box">
                    <label>Login:</label>
                    <input type="text" class="admin-login-form-input" id="admin-login-login" name="login" required>
                </div>
                <div class="admin-login-input-box">
                    <label>Password:</label>
                    <input type="password" class="admin-login-form-input" id="admin-login-pass" name="password" required>
                </div>

                <span class="info-text" style="color:#970303;display: flex;justify-content: center;"></span>
                <div class="admin-login-submit-container">
                    <input type="submit" value="Zaloguj się" required>
                </div>
                
            </form>
        </div>
    </div>
</body>
</html>