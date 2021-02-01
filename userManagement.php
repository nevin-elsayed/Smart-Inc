<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
    
//  If not an authorized user, redirected to account page
    if(!isset($_SESSION['employee']) && !isset($_SESSION['manager']) &&!isset($_SESSION['admin'])){
		echo '<script type="text/javascript">window.location.href = "account.php";</script>';
    } else{
        include('dbconn.php');  
        $db->close();
    }
?>

<title>User Management</title>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'dbconn.php';
    switch($_POST['submit']){
        
/******************************************************************************/
        case "add":
//  Get inputs
            $inputU = trim($_POST['username']);
            $hashedpassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $inputAdr1 = trim($_POST['street']);
            $inputAdr2 = trim($_POST['city']);
            $inputAdr3 = trim($_POST['state']);
            $inputAdr4 = trim($_POST['zip']);
            $inputE = $_POST['email'];
//  Prevent Injections
            if(!get_magic_quotes_gpc()){ 
                $inputE = addslashes($inputE);
                $inputAdr1 = addslashes($inputAdr1);    
                $inputAdr2 = addslashes($inputAdr2);    
                $inputAdr3 = addslashes($inputAdr3);    
                $inputAdr4 = addslashes($inputAdr4);      
                $inputU = addslashes($inputU);    
            }
//  Concatenate Adddress
            $inputAdr = $inputAdr1.", ".$inputAdr2.", ".$inputAdr3." ".$inputAdr4;
//  Select all usernames with given parameter   
            $query2 = "SELECT * FROM `user` WHERE `user`.`Username` = ?";
            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param('s', $inputU);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
//  Select all email with given parameter   
            $query3 = "SELECT * FROM `user` WHERE `user`.`Email` = ?";
            $stmt3 = $db->prepare($query3);
            $stmt3->bind_param('s', $inputE);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
//  Checks if user already exists, else continue
            if($result2->num_rows > 0){
                $message = "Username <em>".$inputU."</em> already taken. Try a different one.";
            } else{
                if($result3->num_rows > 0){
//  Checks email is unique
                    $message = "Email <em>".$inputE."</em> already assigned. Try a different one.";
                } else{
//  Inserts user into databse
                    $query = "INSERT INTO user (Username, Password, Email, Address) VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("ssss", $inputU, $hashedpassword, $inputE, $inputAdr);
                    $stmt->execute();
                    $query2 = "INSERT INTO `cart` (`associatedUser`, `cartID`, `date`) VALUES (?, NULL, current_timestamp())";
                    $stmt2 = $db->prepare($query2);
                    $stmt2->bind_param("s", $inputU);
                    $stmt2->execute();
                    $message = "Successfully added user: <em>".$inputU."</em> to Database.";
                }
            }
        break;
/******************************************************************************/
        case "remove":
//  Get inputs
            $inputU = trim($_POST['username']);
            $inputUU = trim($_POST['confirmUsername']);
//  Prevent Injections
            if(!get_magic_quotes_gpc()){ 
                $inputU = addslashes($inputU);  
                $inputUU = addslashes($inputUU);      
            }
//  Verify inputs are equal
            if($inputU == $inputUU){
                $inputU = $inputUU;
                $query1 = "SELECT * FROM `user` WHERE `user`.`Username` = ?";
                $stmt1 = $db->prepare($query1);
                $stmt1->bind_param("s", $inputU);
                $stmt1->execute();
                $result = $stmt1->get_result();
                $user = $result->fetch_assoc();
//  Make sure user exists
                if($result->num_rows == 1){
//  Verify user is a manager, admin or employee
                    if(isset($user['Employee']) || isset($user['Manager']) || isset($user['Admin'])){
                        $message = "You are denied access to remove <em>".$inputU."</em>.";
                    }else{
//  Remove the user from the database
                        $query = "DELETE FROM `user` WHERE `user`.`Username` = ?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("s", $inputUU);
                        $stmt->execute();
                        $query2 = "DELETE FROM `cart` WHERE `cart`.`associatedUser` = ?";
                        $stmt2 = $db->prepare($query2);
                        $stmt2->bind_param("s", $inputUU);
                        $stmt2->execute();
                        $message = "Successfully deleted <em>".$inputUU."</em> from our database.";
                    }
                } else{
                    $message = "No such user <em>".$inputU."</em> in our records.";
                }
            } else{
                $message = "Username's don't match. Try again.";
            }
        break;

    }
    $db->close(); 
}
/******************************************************************************/
//  Display any set messages
if (isset($message)) {
        echo '<div class="msgbox">';
        echo '<h3>' . $message . '</h3>';
        echo '</div>';
    } 
    ?>
<div class="center">
<h1 style='margin:0;text-align:center'><em>Smart Inc.</em> User Management</h1><br>
<!----------------------------------------------------------------------------->
<form action="" method="post">
        <h2 style='margin:0;text-align:center'>Create a <em>Smart Inc.</em> User</h2><br>
        <table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td align="right"><label>Email Address:</label></td>
				<td><input type="text" name="email" placeholder="example@gmail.com" required>	<br></td>
			</tr>
			<tr>
				<td align="right"><label>Username:</label></td>
				<td><input type="text" name="username" placeholder="Username" required autofocus></td>
			</tr>
			<tr>
				<td align="right"><label>Password:</label></td>
				<td><input type="password" name="password" placeholder="Password" required>	<br></td>
            </tr>
            <td align="right">
					<strong>Address</strong>
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="right">
					Number & Street:
				</td>
				<td>
					<input name="street" type="text" placeholder="1 Street" required/>
				</td>
			</tr>
			<tr>
			<td align="right">
					City:
				</td>
				<td>
					<input name="city" type="text" placeholder="City" required/>
				</td>
			</tr>
			<tr>
			<td align="right">
					State:
				</td>
				<td>
					<select name="state" required>
						<option default></option>
						<option value="AL">Alabama</option>
						<option value="AK">Alaska</option>
						<option value="AZ">Arizona</option>
						<option value="AR">Arkansas</option>
						<option value="CA">California</option>
						<option value="CO">Colorado</option>
						<option value="CT">Connecticut</option>
						<option value="DE">Delaware</option>
						<option value="DC">District Of Columbia</option>
						<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
						<option value="HI">Hawaii</option>
						<option value="ID">Idaho</option>
						<option value="IL">Illinois</option>
						<option value="IN">Indiana</option>
						<option value="IA">Iowa</option>
						<option value="KS">Kansas</option>
						<option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option>
						<option value="ME">Maine</option>
						<option value="MD">Maryland</option>
						<option value="MA">Massachusetts</option>
						<option value="MI">Michigan</option>
						<option value="MN">Minnesota</option>
						<option value="MS">Mississippi</option>
						<option value="MO">Missouri</option>
						<option value="MT">Montana</option>
						<option value="NE">Nebraska</option>
						<option value="NV">Nevada</option>
						<option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option>
						<option value="NM">New Mexico</option>
						<option value="NY">New York</option>
						<option value="NC">North Carolina</option>
						<option value="ND">North Dakota</option>
						<option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option>
						<option value="OR">Oregon</option>
						<option value="PA">Pennsylvania</option>
						<option value="RI">Rhode Island</option>
						<option value="SC">South Carolina</option>
						<option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option>
						<option value="TX">Texas</option>
						<option value="UT">Utah</option>
						<option value="VT">Vermont</option>
						<option value="VA">Virginia</option>
						<option value="WA">Washington</option>
						<option value="WV">West Virginia</option>
						<option value="WI">Wisconsin</option>
						<option value="WY">Wyoming</option>
					</select>        
				</td>
			</tr>
			<tr>
			<td align="right">
					Zip Code
				</td>
				<td>
					<input name="zip" type="num" placeholder="#####" pattern="\d{5}" required/>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2"><br><button name='submit' value="add" type="submit">Add User to Database</button></td>
			</tr>				
        </table>
</form>
        <br>
<!----------------------------------------------------------------------------->
<form action="" method="post">
        <h2 style='margin:0;text-align:center'>Delete a <em>Smart Inc.</em> User</h2><br>
        <table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td align="right"><label>Username:</label></td>
				<td><input type="text" name="username" placeholder="Username" required><br></td>
            </tr>
            <tr>
				<td align="right"><label>Confirm Username:</label></td>
				<td><input type="text" name="confirmUsername" placeholder="Username" required><br></td>
            </tr>
			<tr>
				<td align="center" colspan="2"><br><button name='submit' value="remove" type="submit">Remove User from Database</button></td>
			</tr>				
		</table>
    </form>
</div>
</div>
</body>
</html>