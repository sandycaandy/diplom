<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('connect_db.php');
    include('../functions/functions.php');

    $result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);

        do {
            $int = $int + ($row["cart_price"] * $row["cart_count"]);
        } while ($row = mysql_fetch_array($result));


        echo group_numerals($int);
    }
}
