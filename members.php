<?php
	// Connects to the Database
	include('connect.php');
	connect();

	//if the login form is submitted
	if (isset($_POST['submit'])) {

		$_POST['username'] = trim($_POST['username']);
		if(!$_POST['username'] | !$_POST['password']) {
			die('<p>You did not fill in a required field.
			Please go back and try again!</p>');
		}

		$check = mysql_query("SELECT * FROM users WHERE username = '".$_POST['username']."'")or die(mysql_error());

 		//Gives error if user already exist
 		$check2 = mysql_num_rows($check);
		if ($check2 == 0) {
			die("<p>Sorry, user name does not exist.</p>");
		}
		else
		{
			while($info = mysql_fetch_array( $check )) 	{
				if($info['log_attempts'] == 3) {
					die("<p>Reached max number of login attempts. Call 1(800)LOL-OLOL to request a reset.</p>");
				}
			 	//gives error if the password is wrong
				if (password_verify($_POST['password'], $info['pass'])) {
					$query = sprintf("UPDATE users SET log_attempts = %d WHERE username = '".$_POST['username']."'", 0);
					mysql_query($query)or die(mysql_error());
				} else {
					$query = sprintf("UPDATE users SET log_attempts = %d WHERE username = '".$_POST['username']."'", $info['log_attempts'] + 1);
					mysql_query($query)or die(mysql_error());
					die(sprintf('<p>Incorrect password, please try again. Number of login attempts: %d</p>', $info['log_attempts'] + 1));
				}
			}
			$hour = time() + 3600;
			$session_id = rand();
			$query = sprintf("UPDATE users SET session = '".$session_id."' WHERE username = '".$_POST['username']."'");
			mysql_query($query)or die(mysql_error());
			setcookie(hackme, $_POST['username']);
			setcookie(hackmesess, password_hash($session_id, PASSWORD_BCRYPT), $hour);
			header("Location: members.php");
		}
	}
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('header.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme bulletin board</h2>
        	<?php
            if(!valid_session()){
				 			die('Why are you not logged in?!');
						}else
						{
							print("<p>Logged in as <a>$_COOKIE[hackme]</a></p>");
						}
						?>
        </div>
    </div>
</div>

<?php
	$threads = mysql_query("SELECT * FROM threads ORDER BY date DESC")or die(mysql_error());
	while($thisthread = mysql_fetch_array( $threads )){
?>
	<div class="post">
	<div class="post-bgtop">
	<div class="post-bgbtm">
		<h2 class="title"><a href="show.php?pid=<? echo $thisthread[id] ?>"><? echo $thisthread[title]?></a></h2>
							<p class="meta"><span class="date"> <? echo date('l, d F, Y',$thisthread[date]) ?> - Posted by <a href="#"><? echo $thisthread[username] ?> </a></p>

	</div>
	</div>
	</div>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="bin/jsencrypt.js"></script>
    <script type="text/javascript">

      // Call this code when the page is done loading.
      $(function() {

        // Run a quick encryption/decryption when they click.
        $('#testme').click(function() {

          // Encrypt with the public key...
          var encrypt = new JSEncrypt();
          encrypt.setPublicKey($('#pubkey').val());
          var encrypted = encrypt.encrypt($('#input').val());

          // Decrypt with the private key...
          var decrypt = new JSEncrypt();
          decrypt.setPrivateKey($('#privkey').val());
          var uncrypted = decrypt.decrypt(encrypted);

          // Now a simple check to see if the round-trip worked.
          if (uncrypted == $('#input').val()) {
            alert('It works!!!');
          }
          else {
            alert('Something went wrong....');
          }
        });
      });
    </script>
  </head>
  <body>
    <label for="privkey">Private Key</label><br/>
    <textarea id="privkey" rows="15" cols="65">-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDlOJu6TyygqxfWT7eLtGDwajtNFOb9I5XRb6khyfD1Yt3YiCgQ
WMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76xFxdU6jE0NQ+Z+zEdhUTooNR
aY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4gwQco1KRMDSmXSMkDwIDAQAB
AoGAfY9LpnuWK5Bs50UVep5c93SJdUi82u7yMx4iHFMc/Z2hfenfYEzu+57fI4fv
xTQ//5DbzRR/XKb8ulNv6+CHyPF31xk7YOBfkGI8qjLoq06V+FyBfDSwL8KbLyeH
m7KUZnLNQbk8yGLzB3iYKkRHlmUanQGaNMIJziWOkN+N9dECQQD0ONYRNZeuM8zd
8XJTSdcIX4a3gy3GGCJxOzv16XHxD03GW6UNLmfPwenKu+cdrQeaqEixrCejXdAF
z/7+BSMpAkEA8EaSOeP5Xr3ZrbiKzi6TGMwHMvC7HdJxaBJbVRfApFrE0/mPwmP5
rN7QwjrMY+0+AbXcm8mRQyQ1+IGEembsdwJBAN6az8Rv7QnD/YBvi52POIlRSSIM
V7SwWvSK4WSMnGb1ZBbhgdg57DXaspcwHsFV7hByQ5BvMtIduHcT14ECfcECQATe
aTgjFnqE/lQ22Rk0eGaYO80cc643BXVGafNfd9fcvwBMnk0iGX0XRsOozVt5Azil
psLBYuApa66NcVHJpCECQQDTjI2AQhFc1yRnCU/YgDnSpJVm1nASoRUnU8Jfm3Oz
uku7JUXcVpt08DFSceCEX9unCuMcT72rAQlLpdZir876
-----END RSA PRIVATE KEY-----</textarea><br/>
    <label for="pubkey">Public Key</label><br/>
    <textarea id="pubkey" rows="15" cols="65">-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDlOJu6TyygqxfWT7eLtGDwajtN
FOb9I5XRb6khyfD1Yt3YiCgQWMNW649887VGJiGr/L5i2osbl8C9+WJTeucF+S76
xFxdU6jE0NQ+Z+zEdhUTooNRaY5nZiu5PgDB0ED/ZKBUSLKL7eibMxZtMlUDHjm4
gwQco1KRMDSmXSMkDwIDAQAB
-----END PUBLIC KEY-----</textarea><br/>
    <label for="input">Text to encrypt:</label><br/>
    <textarea id="input" name="input" type="text" rows=4 cols=70>This is a test!</textarea><br/>
    <input id="testme" type="button" value="Test Me!!!" /><br/>
<?php
}
	include('footer.php');
?>
</body>
</html>
