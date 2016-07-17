
<?php
/**
 * @Author: 伟强
 * @Date:   2016-05-26 13:44:45
 * @Last Modified by:   伟强
 * @Last Modified time: 2016-05-27 15:31:46
 */
  $host = "localhost";
  $username = "root";
  $password = "";
  $database = "dailySoup";

  $db = mysql_connect($host, $username, $password) or die('Error connecting to MySQL server: ' . mysql_error());
  mysql_select_db($database, $db) or die('Error selecting MySQL database: ' . mysql_error());



?>