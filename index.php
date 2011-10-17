<?php include 'header.php'; ?>
<div id="loginHeader">
	<h2>Constraint Analysis - Login Page</h2>
</div>
<div id="loginBox">
	<form name="login" id="login" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
    	<label for="username">Username: </label>
    	<input type="text" name="username" id="username" size="35" value="<?php if(isset($_POST['username'])) { echo $_POST['username']; } ?>" autofocus placeholder="Username" /><br /><br />
        <label for="username">Password: </label>
        <input type="password" name="user_pass" id="user_pass" size="36" value="" placeholder="Password" /><br /><br />
        <input type="submit" name="loginSubmit" id="loginSubmit" value="Log In" />
    </form>
</div>
<?php  include "login_process.php"; ?>
</body>
</html>