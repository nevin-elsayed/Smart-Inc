<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
    
    //  If no user is assigned redirect to index page
    if(!isset($_SESSION['loggedIn'])){
		echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
    //  If user isn't a customer redirect to account page
    if(isset($_SESSION['employee']) || isset($_SESSION['manager']) || isset($_SESSION['admin'])){
		echo '<script type="text/javascript">window.location.href = "account.php";</script>';
    }
?>
<title>Cart</title>
<div class="center">
    <h1 style='margin:0;text-align:center'>My <em>Smart Inc.</em> Cart</h1><br>
<?php
    require('dbconn.php');
//  Connect cart.cartID to cartitems.cartID
    $query = "SELECT * FROM cartitems WHERE cartID = ?";
    $stmt = $db->prepare($query);
//  Get user from SESSION variable
    $stmt->bind_param("i", $_SESSION['cartID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $numItems = $result->num_rows;
//  Shows number of grouped item 
    echo "<h2 style='text-align:center'>Number of grouped items in cart: ".$numItems."</h2>";
        echo "<h2 style='text-align:center'>Cart ID: ".$_SESSION['cartID']."</h2>";
    if($numItems > 0){
        echo "<div class='gridContainer'>";
//  Initialize total quantity and order total 
        $total = 0;
        $totalQ = 0;
        for($i=0; $i < $numItems ; $i++){
            $row = $result->fetch_assoc();
            echo "<div class='gridItem'><strong><h2>";
            echo htmlspecialchars(stripslashes($row['productName']));
            echo "</h2><img src='placeholder.jpg' width='100%'><br>";
            echo "<br>Price: <span style='color: green;'>$";
            echo stripslashes($row['price']);
            echo "</span><br>Quantity: ";
            echo $row['quantity'];
            echo "</strong><br><br><form action='' method='POST'>";
            echo '<select style="display: none" name="item"><option value="';
            echo  stripslashes($row['productName']);
            echo '"></option></select>';
            echo "<button name='submit' type='submit' value='remove'>Remove Item(s)</button></form></div>";
            $likeTotal = ($row['quantity']) * ($row['price']);
            $totalQ += $row['quantity'];
            $total += $likeTotal;
            $query2 = "SELECT * FROM `products` WHERE `name` = ?";
            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param("s", $row['productName']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $pRow = $result2->fetch_assoc();
//  This if statement prevents a customer from ordering more than what is in stock
            if($row['quantity'] > $pRow['quantity']){
                $stockError = true;
                $itemToRemove = $row['productName'];
            } 
        }
        echo "</div>";
        echo "<div id='cartSummary' class='gridItem'><h3 style='text-align:center'>Total: <span style='color:green'>$";
        echo    $total;
        echo "</span><br><br><form action='' method='POST'><button name='submit' type='submit' value='empty'>Empty Entire Cart</button></form><br>";
        if($stockError){
                echo "Cannot order <em>".$itemToRemove."</em> at current quantity.</h3></div>";
            }
            else{
                echo "<form action='' method='POST'><button name='submit' type='submit' value='order'>Place Order</button></form></h3></div>";
            }
    } else{
    echo "<div id='cartSummary' class='gridItem'><h3 style='text-align:center'>Your cart is currently empty. Go to the <em>Products</em> tab to add items to your cart.</h3></div>";
}
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       
    switch($_POST['submit']){
/******************************************************************************/
    case 'remove':
//  Delete selected items
        $query = "DELETE FROM cartitems WHERE cartID = ? and productName = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("is", $_SESSION['cartID'], $_POST['item']);
        $stmt->execute();
		echo '<script type="text/javascript">window.location.href = "cart.php";</script>';
        break;
/******************************************************************************/
    case 'empty':
//  Delete all from cart
        $query = "DELETE FROM cartitems WHERE cartID = ?";
        $stmt = $db->prepare($query);
//  Get user from SESSION variable
        $stmt->bind_param("i", $_SESSION['cartID']);
        $stmt->execute();
        echo '<script type="text/javascript">window.location.href = "cart.php";</script>';
        break;
/******************************************************************************/
    case 'order';
//  Place Order
		$query = "INSERT INTO `orders` (`cartID`, `user`, `total`, `datePlaced`) VALUES (?, ?, ?, current_timestamp())";
        $stmt = $db->prepare($query);
//  Get user from SESSION variable
        $stmt->bind_param("isi", $_SESSION['cartID'], $_SESSION['user'], $total);
        $stmt->execute(); 
//  Update Inventory of products
//  Grab item quantity from cart and subtract from quantity in products
        $query4 = "SELECT * FROM `cartitems` WHERE `cartID` = ?";
        $stmt4 = $db->prepare($query4);
        $stmt4->bind_param("i", $_SESSION['cartID']);
        $stmt4->execute();
        $result4 = $stmt4->get_result();
        for($i = 0; $i < $result4->num_rows; $i++){
            $cartRow = $result4->fetch_assoc();     
            $query = "UPDATE `products` SET `quantity` = `quantity` - ? WHERE `name` = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("is", $cartRow['quantity'], $cartRow['productName']);
            $stmt->execute();
        }
//  Create a new cart ID
		$query2 = "INSERT INTO `cart` (`associatedUser`, `cartID`, `date`) VALUES (?, NULL, current_timestamp())";
		$stmt2 = $db->prepare($query2);
		$stmt2->bind_param("s", $_SESSION['user']);
		$stmt2->execute();
//  Set the new cart ID
        $query3 = "SELECT MAX( cartID ) AS max  FROM cart WHERE associatedUser = ?";
        $stmt3 = $db->prepare($query3);
        $stmt3->bind_param("s", $_SESSION['user']);
        $stmt3->execute();        
        $result = $stmt3->get_result();
        $row = $result->fetch_assoc();
        echo '<script type="text/javascript">window.location.href = "account.php";</script>';
        $_SESSION['cartID'] = $row['max'];
        break;
    }
}
    $db->close();
?>
</div>
</div>
</body>
</html>

