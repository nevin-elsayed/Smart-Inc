<?php
//  Starts the session so session variables can be accessed and shared among pages
    session_start();
    if (isset($_SESSION['admin'])) {
    //  Shows admin navigation
        require("view/adminHeader.php");
    } elseif(isset($_SESSION['manager'])){
    //  Shows manager navigation
        require("view/managerHeader.php");
    } elseif(isset($_SESSION['employee'])){
    //  Shows employee navigation
        require("view/employeeHeader.php");
    } else{
    //  Shows customer navigation
        require("view/customerHeader.php");
    }
?>        
