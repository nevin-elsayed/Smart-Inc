<?php
    // Connects to database
    @ $db = new mysqli('localhost', 'smartUser', 'mysmartpassword','smart-inc');
    
    
    // Checks Database Connection
    if(mysqli_connect_errno()) {
        echo '<div class="msgbox">Error: Could not connect to database. Please try agian later.</div>';
        exit;
    }
?>