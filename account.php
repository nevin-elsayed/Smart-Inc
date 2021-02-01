<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
    if(!isset($_SESSION['loggedIn'])){
		echo '<script type="text/javascript">window.location.href = "index.php";</script>';
    }
?>
<!--This page will display the logged in users account information-->


<title>Account</title>

<div class="center">
    <h1 style='margin:0;text-align:center'>My <em>Smart Inc.</em> Account</h1><br>
    <table style='margin-left:auto; margin-right:auto;'>
        <tr>
            <td>Username:</td>
            <td align="right">
                <?php echo $_SESSION['user'];?>
            </td>
        </tr>
        <tr>
            <td>Email:</td>
            <td align="right">
                <?php echo $_SESSION['email'];?>
            </td>
        </tr>
        <tr>
            <td>Address:</td>
            <td align="right">
                <?php echo $_SESSION['address'];?>
            </td>
        </tr>
    </table>
</div>
<br>
<?php
require('dbconn.php');
//  Display Order History if orders place
    $query = "SELECT * FROM `orders` WHERE `user` = ?";
    $stmt = $db->prepare($query);
//  Get user from SESSION variable
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
if($result->num_rows > 0){
    echo"<div class='center'><h1 style='margin:0;text-align:center'>My <em>Smart Inc.</em> Order History</h1><br>";
    for($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_assoc();
        echo    "<div class='gridItem'><h2>Order Number: ";
        echo    $row['cartID'];
        echo    "</h2>";
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
    echo "</div>";
} 
$db->close();
?>
</div>
</body>
</html>