<?php
$db_host        = 'localhost';
$db_user        = 'lena-shop';
$db_pass        = '12345';
$db_database    = 'sleep-shop';

$link = mysql_connect($db_host, $db_user, $db_pass);

mysql_select_db($db_database, $link) or die("Нет соединения с БД " . mysql_error());
mysql_set_charset('utf8');