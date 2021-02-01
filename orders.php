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

<title>Order Records</title>

<div class='center'>
<h1 style='margin:0;text-align:center'><em>Smart Inc.</em> Order Records</h1><br>
<?php
require('dbconn.php');
//  Display all Order
    $query = "SELECT * FROM `orders`";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        echo    "<div class='gridItem'><h2>Order Number: ";
        echo    $row['cartID'];
        echo    "<br>Placed by user: <em>";
        echo    $row['user'];
        echo    "</em></h2>";
//  Get items from order
        $query1 = "SELECT * FROM `cartitems` WHERE `cartID` = ?";
        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param("s", $row['cartID']);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        for($j = 0; $j < $result1->num_rows; $j++){
            $row1 = $result1->fetch_assoc();
            echo    "<strong>".$row1['productName']."</strong><br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUnit Price: <span style='color: green;'>$".$row1['price']."</span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspQuantity: ".$row1['quantity']."<br>";
        }
        echo    "<br>Total: <span style='color:green'>$";
        echo    $row['total'];
        echo    "</span>";
        echo    "<br>Date Placed: ".$row['datePlaced'];
        echo    "</div>";
    }

    $db->close();
?>


</div>
</div>
</body>
</html>