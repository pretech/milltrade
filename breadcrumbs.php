<?php
$ul_id='crumbs';
$bc=explode("/",$_SERVER["PHP_SELF"]);
echo '<ul id="'.$ul_id.'"><li><a href="/">Home</a></li>';
while(list($key,$val)=each($bc)){
 $dir='';
 if($key > 1){
  $n=1;
  while($n < $key){
   $dir.='/'.$bc[$n];
   $val=$bc[$n];
   $n++;
  }
  if($key < count($bc)-1) echo '<ul id="'.$ul_id.'"><li><a href="'.$dir.'">'.$val.'</a></li></ul>';
 }
}
echo '</ul>';
?> 