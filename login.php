<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
?>
<!--This should be the first page the client is directed to-->
<title>Login</title>

<?php
//  When a form is submitted run this code
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require 'dbconn.php';
	
//  Get input from form
		$inputU = trim($_POST['username']);
		$inputP = trim($_POST['password']);
		
//  Prevent SQL injections
		if(!get_magic_quotes_gpc()){
			$inputU = addslashes($inputU);    
		}
		
//  Check input with database record
		$query = ("SELECT * FROM user WHERE Username = ?");
		$stmt = $db->prepare($query);
		$stmt->bind_param("s", $inputU);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		if($result->num_rows == 1){
//  If 1 row is found, check the inputted password matches the hashed password
			if(password_verify($inputP, $user["Password"])){	
//  If logged in session_unset() will remove SESSION variables
			    session_unset();
//  Set the new users SESSION variables to access on multiple pages
				$_SESSION['loggedIn'] = true;
				$_SESSION['user'] = $user['Username'];
				$_SESSION['password'] = $user['Password'];
				$_SESSION['email'] = $user['Email'];
				$_SESSION['address'] = $user['Address'];
// Below will verify any special privileges
				if($user['Admin'] == 1){$_SESSION['admin'] = true;}
				if($user['Manager'] == 1){$_SESSION['manager'] = true;}
				if($user['Employee'] == 1){$_SESSION['employee'] = true;}
//  Set customers session cartID
                if(!isset($_SESSION['employee']) && !isset($_SESSION['manager']) &&!isset($_SESSION['admin'])){
                    $query = "SELECT MAX( cartID ) AS max  FROM cart WHERE associatedUser = ?";
            		$stmt = $db->prepare($query);
            		$stmt->bind_param("s", $_SESSION['user']);
            		$stmt->execute();
            		$result = $stmt->get_result();
            		$row = $result->fetch_assoc();
            		$_SESSION['cartID'] = $row['max'];
                }
//  Redirect user to their account information
				echo '<script type="text/javascript">window.location.href = "account.php";</script>';
			} else{
				$message = 'Incorrect Password. Try again.';
			}
		}else{
			$message = 'No user <em>'.$inputU.'</em> found.';
		}
	$db->close();
	}
?>
<?php
//  Display any messages created by PHP
    if (isset($message)) {
        echo '<div class="msgbox">';
        echo '<h3>' . $message . '</h3>';
        echo '</div>';
    } 
    ?>
<div class="center">
	<form action="login.php" method="post">
		<h1 style='margin:0;text-align:center'>Login to your <em>Smart Inc.</em></h1><br>
		<br>
		<table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td align="right"><label for="username">Username:</label></td>
				<td><input type="text" name="username" placeholder="Username" required autofocus></td>
			</tr>
			<tr>
				<td align="right"><label for="password" >Password:</label></td>
				<td><input type="password" name="password" placeholder="Password" required></td>
			</tr>
			<tr>
				<td align="center" colspan="2"><br><button  type="submit">Sign in</button></td>
			</tr>				
		</table>
	</form>
	<h3 style='text-align:center'><a style="display: inline;" class="homelink"href="register.php">Create a <em>Smart Inc.</em> Account</a></h3>
</div>
</div>
</body>
</html>