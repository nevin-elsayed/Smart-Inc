<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
    
//  If no user is assigned redirect to index page
    if(!isset($_SESSION['loggedIn'])){
		echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
?>
<title>Products</title>
<div class="center">
    <h1 style='margin:0;text-align:center'><em>Smart Inc.</em> Products</h1><br>
    <div class='gridContainer'>
    <div class='gridItem'> 
<!----------------------------------------------------------------------------->
        <form action="" method='POST'>
        <h2 style='text-align: center;'>Search</h2>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td  colspan="2">Enter Search Term:</td>
            </tr>
            <tr>
                <td><input style="width: 80%" name="searchterm" type="text"></td>
            </tr>
            <tr>
                <td align='center' colspan="2"><br><button type="submit" name="submit" value="search">Search</button></td>
            </tr>
        </table>
        </form>
    </div>
    <div class='gridItem'> 
<!----------------------------------------------------------------------------->
        <form action="" method='POST'>
        <h2 style='text-align: center;'>Categorize</h2>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td colspan="2">Refine by Category:</td>
            </tr>    
            <tr>
                <td>
                    <select name="category">
                        <option value="Apparel">Apparel</option>
                        <option value="Footwear">Footwear</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Technology">Technology</option>
                        <option value="Household">Household</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align='center' colspan="2"><br><button type="submit" name="submit" value="categorize">Refine</button></td>
            </tr>
        </table>
        </form>
    </div>
<!----------------------------------------------------------------------------->
    <div class='gridItem'>
        <form action="" method='POST'>
        <h2 style='text-align: center;'>View All</h2>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td align='center' colspan="2"><br><br><br><button type="submit" name="submit" value="all">View All</button></td>
            </tr>
        </table>
        </form>
    </div>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require('dbconn.php');
        switch($_POST['submit']){
/******************************************************************************/
//  THIS IS EXACT SEARCH --> NOT GENERAL SEARCH --> NEEDS TO BE FIXED
            case 'search':
//  Get input
                $product = $_POST['searchterm'];
//  Find product with given name
                $query = "SELECT * FROM `products` WHERE name LIKE '%".$product."%'";
                $result = $db->query($query);
//  Check if any results, else none found
                if($result->num_rows > 0){
                for($i=0; $i < $result->num_rows; $i++){
                    $row = $result->fetch_assoc();
                    echo "<div class='gridItem'><strong><h2>";
                    echo htmlspecialchars(stripslashes($row['name']));
                    echo "</h2><img src='placeholder.jpg' width='100%'><br>";
                    echo "<br>Price: <span style='color: green;'>$";
                    echo stripslashes($row['price']);
                    echo"</span><br>Description: ";
                    echo stripslashes($row['description']);
                    echo "<br>Category: ";
                    echo  stripslashes($row['category']);
                    echo '</strong>';
//  If a customer, allow add to cart button
                    if(!isset($_SESSION['employee']) && !isset($_SESSION['manager']) &&!isset($_SESSION['admin'])){
                        include('addCart.php');
                    } else{
//  Display quantity --> if out of stock display it
                        echo '<br><strong>Current Stock: ';
                        if(stripslashes($row['quantity']) < 1){
                            echo '<em style="color:red">OUT OF STOCK</em>';
                        } else{
                        echo stripslashes($row['quantity']);
                        }
                        echo '</strong>';        
                    } 
                    echo  "</div>";
                    }   
                } else{
                    $message = 'No product found. Try again.';
                }
            break;
/******************************************************************************/
            case 'categorize':
//  Get input
                $category = $_POST['category'];
//  Select products where category eqauls input
                $query = "SELECT * FROM `products` WHERE category = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('s', $category);
                $stmt->execute();
                $result = $stmt->get_result();

//  Check if any results, else none found
                if($result->num_rows > 0){
//  Loop through results
                for($i=0; $i < $result->num_rows; $i++){
                    $row = $result->fetch_assoc();
                    echo "<div class='gridItem'><strong><h2>";
                    echo htmlspecialchars(stripslashes($row['name']));
                    echo "</h2><img src='placeholder.jpg' width='100%'><br>";
                    echo "<br>Price: <span style='color: green;'>$";
                    echo stripslashes($row['price']);
                    echo"</span><br>Description: ";
                    echo stripslashes($row['description']);
                    echo "<br>Category: ";
                    echo  stripslashes($row['category']);
                    echo '</strong>';
//  If a customer, allow add to cart button
                    if(!isset($_SESSION['employee']) && !isset($_SESSION['manager']) &&!isset($_SESSION['admin'])){
                        include('addCart.php');
                    } else{
//  Display quantity --> if out of stock display it
                        echo '<br><strong>Current Stock: ';
                        if(stripslashes($row['quantity']) < 1){
                            echo '<em style="color:red">OUT OF STOCK</em>';
                        } else{
                        echo stripslashes($row['quantity']);
                        }
                        echo '</strong>';        
                    } 
                    echo  "</div>";
                    }
                } else{
                    $message = 'No product found. Try again.';
                }
            break;
/******************************************************************************/
            case 'all':
//  Selects all items
                $query = "SELECT * FROM `products` WHERE 1";
                $result = $db->query($query);
            
// Loop Through Results
                for($i=0; $i < $result->num_rows; $i++){
                    $row = $result->fetch_assoc();
                    echo "<div class='gridItem'><strong><h2>";
                    echo htmlspecialchars(stripslashes($row['name']));
                    echo "</h2><img src='placeholder.jpg' width='100%'><br>";
                    echo "<br>Price: <span style='color: green;'>$";
                    echo stripslashes($row['price']);
                    echo"</span><br>Description: ";
                    echo stripslashes($row['description']);
                    echo "<br>Category: ";
                    echo  stripslashes($row['category']);
                    echo '</strong>';
//  If a customer, allow add to cart button
                    if(!isset($_SESSION['employee']) && !isset($_SESSION['manager']) &&!isset($_SESSION['admin'])){
                        include('addCart.php');
                    } else{
//  Display quantity --> if out of stock display it
                        echo '<br><strong>Current Stock: ';
                        if(stripslashes($row['quantity']) < 1){
                            echo '<em style="color:red">OUT OF STOCK</em>';
                        } else{
                        echo stripslashes($row['quantity']);
                        }
                        echo '</strong>';           
                    }                        
                    echo  "</div>";
                };
            break;
/******************************************************************************/
//  Below is the function called when add Cart button is clicked
            case 'addCart';
//  Select all from cart with associated username
                $query = "SELECT MAX( cartID ) AS max  FROM cart WHERE associatedUser = ?";
                $stmt = $db->prepare($query);
//  Get user from SESSION variable
                $stmt->bind_param("s", $_SESSION['user']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
//  Fetch cartID from username
                $cartID = $row['max'];
                $quantity = $_POST['quantity'];
                $product = $_POST['item'];
                $productPrice = $_POST['itemPrice'];
//  Add items to cart items table
                $query2 = "INSERT INTO `cartitems` (`cartID`, `productName`, `quantity`, `price`) VALUES (?, ?, ?, ?)";
                $stmt2 = $db->prepare($query2);
                $stmt2->bind_param("isii", $cartID, $product, $quantity, $productPrice);
                $stmt2->execute();
	    echo '<script type="text/javascript">window.location.href = "cart.php";</script>';
            break;
        }
        $db->close();
    }
?>        
    </div>
</div>
<?php
//  Display any set messages
    if (isset($message)) {
        echo '<br><div class="msgbox">';
        echo '<h3>' . $message . '</h3>';
        echo '</div>';
    } 
?>
</div>
</body>
</html>