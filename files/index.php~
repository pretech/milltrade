<?php
/* Tuberpro Project by Kreative4, author: Moin Uddin
 *
 */
    $url=$_SERVER['REQUEST_URI'];
    //starting a secured session
    session_start();

    include("config/connection.php");
    include("includes/functions.php");
    //breaking the url to many parts
    $break=explode("/", $url);

    //broken useful parts starts from the array position $start
    $start=START;

    $uurl=""; //universal/global use purpose url
    $i=$start;
    while($i<count($break))
    {
        $uurl=$break[$i]."/";
        $i++;
    }
    $GLOBALS['url']=$uurl;
    /*------------------------------------Operations are started from here----------------------------------------*/


    $option=$break[$start];

    if(($option!="")&&(is_dir("modules/".$option))) $module="modules/".$option;
    else 
    {
        $module="modules/default";
        $option="default";
    }   

    include($module."/".$option.".php");
 ?>
