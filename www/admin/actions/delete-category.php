<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
include("../include/db_connect.php"); 

          $delete = mysql_query("DELETE FROM category WHERE id = '{$_POST["id"]}'",$link); 
          echo "delete";   

}
?>