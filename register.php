<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('connect.php');
	connect();
	include('header.php');
	$path = 'phpseclib';
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	include_once('Crypt/RSA.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme Registration</h2>
        <?php
		//if the registration form is submitted
		if (isset($_POST['submit'])) {

			$_POST['uname'] = trim($_POST['uname']);
			if(!$_POST['uname'] | !$_POST['password'] |
				!$_POST['fname'] | !$_POST['lname']) {
 				die('<p>You did not fill in a required field.
				Please go back and try again!</p>');
 			}

			if(preg_match("/.*[$@$!%*#?&].*/", $_POST['password']) == 0 || strlen($_POST['password']) < 8) {
				die("<p>Password must contain at least one special character and be at least 8 characters long.</p>");
			}

			$passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT); //password_hash is more secure

			$check = mysql_query("SELECT * FROM users WHERE username = '".$_POST['uname']."'")or die(mysql_error());

 		//Gives error if user already exist
 		$check2 = mysql_num_rows($check);
		if ($check2 != 0) {
			die('<p>Sorry, user name already exisits.</p>');
		}
		else
		{
			$rsa = new Crypt_RSA();
			$rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
			$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);
			extract($rsa->createKey(1024)); /// makes $publickey and $privatekey available
			mysql_query("INSERT INTO users (username, pass, fname, lname, log_attempts, session, key_for_next_login) VALUES ('".$_POST['uname']."', '". $passwordHash ."', '". $_POST['fname']."', '". $_POST['lname'] ."', 0, '". "nosession" ."', '". $privatekey ."');")or die(mysql_error());

			echo "<h3> Registration Successful!</h3> <p>Welcome ". $_POST['fname'] ."! Please log in...</p>";
		}
        ?>
        <?php
		}else{
        ?>
        	<form  method="post" action="register.php">
            <table>
                <tr>
                    <td> Username </td>
                    <td> <input type="text" name="uname" maxlength="20"/> </td>
                    <td> <em>choose a login id</em> </td>
                </tr>
                <tr>
                    <td> Password </td>
                    <td> <input type="password" name="password" maxlength="40" /> </td>
                </tr>
                <tr>
                    <td> First Name </td>
                    <td> <input type="text" name="fname" maxlength="25"/> </td>
                </tr>
                 <tr>
                    <td> Last Name </td>
                    <td> <input type="text" name="lname" maxlength="25"/> </td>
                </tr>
                <tr>
                    <td> <input type="submit" name="submit" value="Register" /> </td>
                </tr>
            </table>
            </form>
        <?php
		}
		?>
        </div>
    </div>
</div>
<?php
	include('footer.php');
?>
</body>
</html>
