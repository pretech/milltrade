<?php


$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
$business=$_POST[business];
$name=$_POST[name];
$user_name=$_POST[user_name];
$email=$_POST[email];
$password=$_POST[password];
$tel=$_POST[tel];
mysql_select_db("mill", $con);

mysql_query("UPDATE users SET name='$name', user_name='$user_name', email='$email',password='$password', tel='$tel'
WHERE Business='$business' ");
echo "You have successfully edited";
mysql_close($con);
?>