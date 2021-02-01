<?php
    require 'getView.php';
/*  getView.php starts the session, determines if a user is logged in and what 
    type of user they are, then returns the appropriate navigation.     */
?>

<title>Home</title>
    <div class="center">
        <h1 style='margin:0;text-align:center'>Welcome to <em>Smart Inc.</em></h1><br>
        <h3 style="text-align:center;"><em>Smart Inc.</em> is a modern technology and clothing company focused on providing comfort, convenience and style.
        <br><br><br>
        <a style="display: inline;" class="homelink"href="login.php">Login to your <em>Smart Inc.</em> Account</a><br><br><br>
<?php
    if(isset($_SESSION['loggedIn'])){
        //  If logged in create link to products
        echo '<a style="display: inline;" class="homelink"href="products.php">View Products</a><br><br><br>';
    } else{
        //  If not logged in display options to create an account
        echo '<a style="display: inline;" class="homelink"href="register.php">Create a <em>Smart Inc.</em> Account</a><br><br>';
    }
?>
        </h3>
    </div>
</div>
</body>
</html>