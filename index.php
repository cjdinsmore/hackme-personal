<?php
	include('connect.php');
	connect();
	if(valid_session())
	{
		header("Location: members.php");
	}

	$pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCjFLYEjRkUPeCFbTqQZaCeq5GU
xieUakf4MAkfBqWo9yZjAM0biaBdDmifDkCnKtJLBkmUnKFpSAQRxGY71+1Ln+Vi
GrGzSRpItMxEnpdPdP9Hn0UfgeZ451AOFnhvC8n/xJvYfSpZhqD7eRMFE9F750xT
N89VsJLYT9jGxoUThwIDAQAB
-----END PUBLIC KEY-----
';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>hackme</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('header.php');
?>
<script src="submit_form.js"></script>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
			<h2 class="title"><a href="#">Welcome to hackme </a></h2>
				<div class="entry">
		<?php
			$check = mysql_query("SELECT * FROM users WHERE username = '".$_COOKIE['hackme']."'")or die(mysql_error());
			$info = mysql_fetch_array($check);
			if(!password_verify($info['session'], $_COOKIE['hackmesess']) || hash_equals($info['session'], "nosession")){
				?>
	           	<form method="post" action="members.php">
				<h2> LOGIN </h2>
				<table>
					<tr> <td> Username </td> <td> <input id="UN-login" type="text" name="username" /> </td> </tr>
					<tr> <td> Password </td> <td> <input id="PW-login" type="password" name="password" /> </td>
                    <td> <input type="submit" name = "submit" value="Login" onclick="javascript: submitform('".$pubkey."')"/> </td></tr>
				</table>
				</form>

				<hr style=\"color:#000033\" />

			<p></p><p>If you are not a member yet, please click <a href="register.php">here</a> to register.</p>
           <?php
				}
		?>
	</div>
	</div>
	</div>
</div>
<!-- end #sidebar -->
	<?php
		include('footer.php');
	?>

</body>
</html>
