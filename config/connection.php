<?php

/**
 * @author moin uddin
 * @copyright kreative4, 2010
 */
define("BASE","http://milltrade.precursortechnology.com");
define("SPICE","ydtfm~");
define("START", 1);
$link=mysql_connect("localhost","precurso_saif","protected") or die('Could not connect:'.mysql_error());
mysql_select_db("precurso_mill",$link) or die('Could not select database:'.mysql_error());
?>
