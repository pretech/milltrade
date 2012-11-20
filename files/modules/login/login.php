<?php
/*
 * Project: tuberPro
 * Owner: Kreative4
 * Version: 1.0
 * Script Version: 1.0
 * Author: Moin Uddin
 */

include("functions.php");

if(isset($_POST['username']))
{
    login();
}
elseif(isset($_GET['logout']))
{
    logout();
}
elseif(isset($break[$start+1]))
{
    if((isset($_POST['email'])) || (isset($_GET['option']))) help_to_remember();
    else header("location:".BASE."/login");
}
else
{
    if(!logged_in()) login_form();
    else header("location:".BASE."/home");
}
?>
