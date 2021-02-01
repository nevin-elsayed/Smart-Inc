<!--This file adds a submittable button to products to add to cart-->
<?php
    echo '<br><form action="" method="POST">';    
    echo '<table style="margin-left: auto; margin-right: auto;">';
    echo '<tr>';
    echo   '<td><strong>Quantity:<strong></td>';
    echo    '<td>';
//  Don't allow add to cart if quantity is 0
    if(stripslashes($row['quantity'])<1){
        echo   '<td><strong><em style="color:red">OUT OF STOCK</em><strong></td></tr>';
    } else{
//  Make product name accesible at POST
        echo    '<select style="display: none" name="item"><option value="';
        echo    stripslashes($row['name']);
        echo    '"></option></select>';
//  Make product price accesible at POST
        echo    '<select style="display: none" name="itemPrice"><option value="';
        echo    $row['price'];
        echo    '"></option></select>';
        echo    '<select name="quantity">';
        echo    '<option value="1">1</option>';
        echo    '<option value="2">2</option>';
        echo    '<option value="3">3</option>';
        echo    '<option value="4">4</option>';
        echo    '<option value="5">5</option>';
        echo    '<option value="6">6</option>';
        echo    '<option value="7">7</option>';
        echo    '<option value="8">8</option>';
        echo    '<option value="9">9</option>';
        echo    '</select>';
        echo    '</td>';
        echo    '</tr>';
        echo     '<tr>';
        echo    '<td align="center" colspan="2"><br><button type="submit" name="submit" value="addCart">Add to Cart</button></td>';
        echo    '</tr>';
    }
    echo    '</table>';
    echo    '</form>';
?>