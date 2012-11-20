<?php
heading("", "", "");
if(!logged_in()) echo "<a href='".BASE."/login'>প্রবেশ করুন</a>";
else echo "<a href='".BASE."/login/?logout=true'>বাহির</a>";

if(current_user_info("id")!="")
{
    if(current_user_info('type')=="admin")
    {
        echo "<br><a href='".BASE."/admin'>প্রশাসন প্যানেল</a>";
        $result=mysql_query("SELECT id, name, type FROM businesses") or die(mysql_error());
    }
    else
    {
        $result=msyql_query("SELECT id, name, type FROM businesses WHERE id='".current_user_type("business"));
    }

    $i=0;
    echo "<table>";
    while($row=mysql_fetch_array($result))
    {
        if($i==0) echo "<tr><td>";
        elseif($i==3) echo "<tr><td>";
        else echo "<td>";
        echo "<a href='".BASE."/invoices/".$row['id']."'>".$row['name']."</a>";
        echo "</td>";
        if($i==2) echo "</tr>";
        $i++;
    }
    echo "</table>";    
}
//else header("location:".BASE."/login");
footing();
?>
