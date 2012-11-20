<?php
if(current_user_info('id')!="")
{
    if(isset($_REQUEST['process']))
    {
        if($_REQUEST['process']=='use')
        {
            if($_POST['used_by']!="")
            {
             	mysql_query("UPDATE parts_inventory SET used_by='".mysql_real_escape_string($_POST['used_by'])."' WHERE id='".$_POST['parts']."'") or die(mysql_error());
                set_flash_message("1 পিস দেওয়া হয়েছে", 1);
                header("location:".BASE."/parts/".$_POST['mill']."/view/".$_POST['parts']);
                die();            
            }
        }
        elseif($_REQUEST['process']=='sell')
        {
            if($_POST['sold']!="")
            {
             	mysql_query("UPDATE parts_inventory SET sold='".$_POST['sold']."' WHERE id='".$_POST['parts']."'") or die(mysql_error());
                set_flash_message("1 পিস Sell দেওয়া হয়েছে", 1);
                header("location:".BASE."/parts/".$_POST['mill']."/view/".$_POST['parts']);
                die();            
            }
        }
    }
    elseif(isset($_REQUEST['damaged']))
    {
        if($_REQUEST['parts']!="") 
        {
             	mysql_query("UPDATE parts_inventory SET damaged=1 WHERE id='".$_REQUEST['parts']."'") or die(mysql_error());
                set_flash_message("1 পিস Sell দেওয়া হয়েছে", 1);
                header("location:".BASE."/parts/".$_REQUEST['business']."/view/".$_REQUEST['parts']);
                die();            
         } 
    }

    if(isset($break[$start+1]))
    {
        heading("", "", "");
        $business=$break[$start+2];
        if((current_user_info('business')==$business)||(current_user_info('type')=='admin'))
        {
            if($break[$start+2]=="view")
            {
                $ref_id=$break[$start+3];
                show_single_parts($ref_id, $break[$start+1]);
            }
            elseif($break[$start+2]=="sell")
            {
                echo "যন্ত্রাংশ বিক্রয়";
            } 
            else
            {
                echo "<h3>পার্টস তালিকা</h3>";
                show_all_parts($business);
            }
        }
        else echo "প্রবেশাধিকার সংরক্ষিত";
        footing();
    }    
}
else header("location:".BASE."/login");

function show_all_parts($business)
{
    $result=mysql_query("SELECT id, parts_name, used_by, sold, damaged FROM parts_inventory WHERE business='$business'");
    echo "<table>";
    echo "<tr><td>আইডি</td><td>যন্ত্রাংশের নাম</td><td>অবস্থান</td></tr>";
    while($row=mysql_fetch_array($result))
    {
        echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td><a href='".BASE."/parts/".$business."/view/".$row['id']."'>".$row['parts_name']."</a></td>";
            echo "<td>";
            if($row['used_by']!="") echo "ব্যবহারকারী: ".$row['used_by'];
            elseif($row['sold']>0) echo "বিক্রিকারী: ".$row['sold']." টাকা";
            elseif($row['damaged']>0) echo "নষ্ট";
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function show_single_parts($ref, $business)
{
    $result=mysql_query("SELECT * FROM parts_inventory WHERE id='$ref'") or die(mysql_error());
    $arr=mysql_fetch_array($result);
    $i=0;
    echo "<strong>আইডি: ".$arr['id']."<br>";
    echo "<strong>যন্ত্রাংশের নাম: ".$arr['parts_name']."</strong><br>";

    if($arr['used_by']!="") 
    {
        echo "Used by ".$arr['used_by']."<br>";
        $i++;
    }

    if($arr['sold']>0)
    {
        echo "বিক্রিকারী ".$arr['sold']." টাকা<br>";
        $i++;
    }

    if($arr['damaged']>0)
    {
        echo "নষ্ট যন্ত্রাংশ<br>";
        $i++;
    }

    if($i==0)
    {
        $base=BASE;
        echo <<<form
<strong>Use the parts</strong><br>
<form action="$base/parts/?process=use" method="post">
<input type='hidden' name='mill' value='$business'>
<input type='hidden' name='parts' value='$ref'>
<strong>Used by</strong>: <input type='text' name='used_by'> <input type='submit' value='Save'>
</form><br>

<strong>Sell the parts</strong><br>
<form action="$base/parts/?process=sell" method="post">
<input type='hidden' name='mill' value='$business'>
<input type='hidden' name='parts' value='$ref'>
<strong>Selling Price</strong>: <input type='text' name='sold'> <input type='submit' value='সংরক্ষন করুন'>
</form>

<br><br><strong>যন্ত্রাংশ কি নষ্ট? <a href="$base/parts/?damaged=true&parts=$ref&business=$business">হা</strong><br>
form;
    }
    
}
?>
