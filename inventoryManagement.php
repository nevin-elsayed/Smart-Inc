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

<title>Inventory Management</title>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'dbconn.php';
    switch($_POST['submit']){
/******************************************************************************/
        case "add":
//  Get inputs            
            $inputN = trim($_POST['name']);
            $inputC = trim($_POST['category']);
            $inputD = trim($_POST['description']);
            $inputP = trim($_POST['price']);
            $inputQ = trim($_POST['quantity']);
//  Prevent Injections
            if(!get_magic_quotes_gpc()){ 
                $inputN = addslashes($inputN);
                $inputC = addslashes($inputC);
                $inputD = addslashes($inputD);
                $inputP = addslashes($inputP);
                $inputQ = addslashes($inputQ);
            }
//  Select all names that match input
            $query2 = "SELECT * FROM `products` WHERE `products`.`name` = ?";
            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param('s', $inputN);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
//  If there exists a product with that name, don't insert into DB; else insert it
            if($result2->num_rows > 0){
                $message = "Product name <em>".$inputN."</em> already taken. Try a different one.";
            } else{
//  Inserts product into DB
                $query = "INSERT INTO products (name, category, description, price, quantity) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("sssii", $inputN, $inputC, $inputD, $inputP, $inputQ);
                $stmt->execute();
                $message = "Successfully added product <em>".$inputN."</em> to Database.";
            }
        break;
/******************************************************************************/
        case "remove":
//  Get Inputs
            $inputU = trim($_POST['name']);
            $inputUU = trim($_POST['confirmName']);
//  Prevenet Injections
            if(!get_magic_quotes_gpc()){ 
                $inputU = addslashes($inputU);  
                $inputUU = addslashes($inputUU);      
            }
//  Verify inputs are equal

            if($inputU == $inputUU){
                $inputU = $inputUU;
//  Select all users with parameter     
                $query1 = "SELECT * FROM `products` WHERE `products`.`name` = ?";
                $stmt1 = $db->prepare($query1);
                $stmt1->bind_param("s", $inputU);
                $stmt1->execute();
                $result = $stmt1->get_result();
                $user = $result->fetch_assoc();
//  Check if product exists, if it does remove it else display message
                if($result->num_rows == 1){
                        $query = "DELETE FROM `products` WHERE `products`.`name` = ?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("s", $inputUU);
                        $stmt->execute();
                        $message = "Successfully deleted <em>".$inputUU."</em> from <em>Smart Inc.</em> Database.";
                } else{
                    $message = "No such product <em>".$inputU."</em> in our records.";
                }
            } else{
                $message = "Product Name's don't match. Try again.";
            }
         
        break;
/******************************************************************************/
        case "update":
//  Get inputs        
            $pName = trim($_POST['Pname']);
            $quantity = trim($_POST['quantity']);
//  Prevent Injections
            if(!get_magic_quotes_gpc()){ 
                $pName = addslashes($pName);  
                $quantity = addslashes($quantity);      
            }
//  Select all the products with inputted name
                $query5 = "SELECT * FROM `products` WHERE `products`.`name` = ?";
                $stmt5 = $db->prepare($query5);
                $stmt5->bind_param("s", $pName);
                $stmt5->execute();
                $result = $stmt5->get_result();
//  If product exists, update quantity
                if($result->num_rows == 1){
                        $query = "UPDATE products SET quantity = ? WHERE products.name = ?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("is", $quantity, $pName);
                        $stmt->execute();
                        $message = "Successfully updated product <em>".$pName."</em> to a quantity of ".$quantity;
                } else{
                    $message = "No such product <em>".$pName."</em> in our records.";
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
<h1 style='margin:0;text-align:center'><em>Smart Inc.</em> Database Inventory Management</h1><br>
<!----------------------------------------------------------------------------->
<form action="" method="post">
        <h2 style='margin:0;text-align:center'>Update Item Quantity</h2><br>
        <table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td align="right"><label>Product Name:</label></td>
				<td><input type="text" name="Pname" placeholder="Product Name" required><br></td>
            </tr>
            <tr>
				<td align="right"><label>Adjust Quantity:</label></td>
				<td><input type="number" name="quantity" placeholder="Quantity" min='0' required><br></td>
            </tr>
			<tr>
				<td align="center" colspan="2"><br><button name='submit' value="update" type="submit">Update Quantity</button></td>
			</tr>				
		</table>
    </form>
<br>
<!----------------------------------------------------------------------------->
<form action="" method="post">
        <h2 style='margin:0;text-align:center'>Add a New Product to <em>Smart Inc.</em> Database</h2><br>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
            <td align="right">Name</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
            <td align="right">Product Category</td>
                <td>
                    <select name="category" required>
                        <option value="Apparel">Apparel</option>
                        <option value="Footwear">Footwear</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Technology">Technology</option>
                        <option value="Household">Household</option>
                    </select>
                </td>
            </tr>
            <tr>
            <td align="right">Description</td>
                <td><input type="text" name="description" required></td>
            </tr>
            <tr>
            <td align="right">Price</td>
                <td><input type="number" name="price" placeholder="$" required></td>
            </tr>
            <tr>
            <td align="right">Quantity of Stock</td>
                <td><input type="number" name="quantity" placeholder="Enter a number" required></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><br><button name='submit' value="add" type="submit">Add Product to <em>Smart Inc.</em> Database</button></td>
            </tr>
        </table>
</form>
        <br>
<!----------------------------------------------------------------------------->
<form action="" method="post">
        <h2 style='margin:0;text-align:center'>Remove Product From <em>Smart Inc.</em> Database</h2><br>
        <table style="margin-left: auto; margin-right: auto;">
			<tr>
				<td align="right"><label>Product Name:</label></td>
				<td><input type="text" name="name" placeholder="Product Name" required><br></td>
            </tr>
            <tr>
				<td align="right"><label>Confirm Product Name:</label></td>
				<td><input type="text" name="confirmName" placeholder="Product Name" required><br></td>
            </tr>
			<tr>
				<td align="center" colspan="2"><br><button name='submit' value="remove" type="submit">Remove Item from <em>Smart Inc.</em> Database</button></td>
			</tr>				
		</table>
    </form>
</div>
</div>
</body>
</html>