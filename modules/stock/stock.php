<?php
/*
 * Project: DoubleP Framework
 * Version: 1.0
 * Script Version: 1.0
 * Author: Moin Uddin
 */

if(current_user_info('id')!="")
{
    heading("Stock", "", "");

    echo "<a href='".BASE."/stock'><b>স্টক</a> &gt;&gt;";

    if(isset($break[$start+2]))
    {
        $clid=$break[$start+2];
    	if($break[$start+1]=="business")
    	{	  
            $party_info="{self:".$clid."}";

	    echo "এর স্টক :";
	    display_party_info($party_info);

	    show_stock($party_info, "business");
        }
        elseif($break[$start+1]=="client")
        {
            $party_info="{party:".$clid."}";

 	    echo "এর স্টক:";
 	    display_party_info($party_info);

	    show_stock($party_info, "client");
        }

    }
    elseif(isset($_REQUEST['disp']))
    {
	$bizlist=mysql_query("SELECT id, name FROM businesses WHERE type='mill'") or die(mysql_error());
	$barr=mysql_fetch_array($bizlist);
	echo $barr['name']."<br>";
	echo "<br><h2>Stocks at ".$barr['name']."</h2>";
	
	echo "<br>";
	$clres=mysql_query("SELECT * FROM party_info WHERE business_id='".$_REQUEST['disp']."'") or die(mysql_error());
	echo "<table>";
	    while($row=mysql_fetch_array($clres))
	    {
		echo "<tr>";
		echo "<td><a href='".BASE."/stock/client/".$row['party_id']."'>".$row['party_name']."</a></td>";
		echo "<td>".$row['party_address']."</td>";
		echo "<td>".aim_num_to_bn($row['party_phone'])."</td>";
		echo "</tr>";
	    }
	echo "</table>";	
    }
    else
    {
	echo "<br><h2>মিলেন স্টক</h2>";
	$bizlist=mysql_query("SELECT id, name FROM businesses WHERE type='mill'") or die(mysql_error());
	    
	echo "<ul>";
	while($brow=mysql_fetch_array($bizlist))
	{
	    echo "<li><a href='".BASE."/stock/?disp=".$brow['id']."'>".$brow['name']."</a></li>";
	}
    }

    footing();
}
?>
