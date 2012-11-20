<?php
if(current_user_info("id")!="")
{

    heading("", "", "");
    ?>
    
    <div style="">
    <?php
        if(!logged_in()) echo "<a class='anchor' href='".BASE."/login'>àŠªà§?àŠ°àŠ¬à§àŠ¶</a>";
    else  "<a class='anchor' href='".BASE."/login/?logout=true'>বাহির </a>";

    if(current_user_info('type')=="admin")
    {
       ?>
       <h2>মিল স্টক</h2>
            <div class="box-container">
                <!------------------- Stock for rice mill1 start ----------------->                
                <div class="left bdr mgn-right-20">
                    <ul class="ul-box-block">
                        <li class="box-title">মিল ১</li>
                        <li><?php show_mill_stock('', '', 1); ?></li>
                    </ul>
                </div>
                <!------------------- Stock for rice mill1 end ----------------->
                <!------------------- Stock for rice mill2 start----------------->
                <div class="left bdr mgn-right-20">
                    <ul class="ul-box-block">
                        <li class="box-title">মিল ২</li>
                        <li><?php show_mill_stock('', '', 2); ?></li>
                    </ul>
                </div>
                <!------------------- Stock for rice mill2 end----------------->
                <!------------------- Stock for flower mill start----------------->
                <div class="left bdr mgn-right-20">
                    <ul class="ul-box-block">
                        <li class="box-title">ময়দা মিল</li>
                        <li><?php show_mill_stock('', '', 3); ?></li>
                    </ul>
                </div>
                <!------------------- Stock for flower mill end----------------->
                <!------------------- Stock for trading start----------------->
                <div class="left bdr">
                    <ul class="ul-box-block">
                        <li class="box-title">বাণিজ্য</li>
                        <li><?php show_mill_stock('', '', 4); ?></li>
                    </ul>
                </div>
                <!------------------- Stock for trading end----------------->            
            </div>        
            
            
            <div class="clear mgn-bottom-20"></div> 
            
            <div class="left bdr mgn-right-20">
            <h2>দেনা</h2>
            <table>
                <tr>
                    <th>মিল</th>
                    <th>বকেয়া</th>
                </tr>
                
                <tr>
                    <td>ধান মিল ১</td>
                    <td><?php echo aim_num_to_bn($mill1_payable=total_mill_payable(1)); ?></td>
                </tr>
                
                <tr>
                    <td>ধান মিল ২</td>
                    <td><?php echo aim_num_to_bn($mill2_payable=total_mill_payable(2)); ?></td>
                </tr>
                
                <tr>
                    <td>ময়দা মিল</td>
                    <td><?php echo aim_num_to_bn($mill3_payable=total_mill_payable(3)); ?></td>
                </tr>
                
                <tr>
                    <td>বাণিজ্য</td>
                    <td><?php echo aim_num_to_bn($trading_payable=total_mill_payable(4)); ?></td>
                </tr>
                
                <tr>
                    <th>মোট</th>
                    <th><?php echo aim_num_to_bn($mill1_payable+$mill2_payable+$mill3_payable+$trading_payable); ?></th>
                </tr>
            </table>
            </div>
            
            <div class="left bdr">
            <h2>পাওনা</h2>
            <table>
                <tr>
                    <th>মিল</th>
                    <th>বকেয়া</th>
                </tr>
                
                <tr>
                    <td>ধান মিল ১</td>
                    <td><?php echo aim_num_to_bn($mill1_receivable=total_mill_receivable(1)); ?></td>
                </tr>
                
                <tr>
                    <td>ধান মিল ২</td>
                    <td><?php echo aim_num_to_bn($mill2_receivable=total_mill_receivable(2)); ?></td>
                </tr>
                
                <tr>
                    <td>ময়দা মিল</td>
                    <td><?php echo aim_num_to_bn($mill3_receivable=total_mill_receivable(3)); ?></td>
                </tr>
                
                <tr>
                    <td>বাণিজ্য</td>
                    <td><?php echo aim_num_to_bn($trading_receivable=total_mill_receivable(4)); ?></td>
                </tr>
                
                <tr>
                    <th>মোট</th>
                    <th><?php echo aim_num_to_bn($mill1_receivable+$mill2_receivable+$mill3_receivable+$trading_receivable); ?></th>
                </tr>
            </table> 
            </div>
            <div class="clear mgn-bottom-20"></div>
       <?php
    }
    else
    {
    	
       //$result=mysql_query("SELECT id, name, type FROM businesses WHERE id='".current_user_info("business")."'");
       $business=current_user_info("business");
       $result=mysql_query("SELECT id, party_info, date_in, status FROM invoices WHERE business_id='$business' AND invoice_type='service' AND status=''");
                echo "<table border='1'>";
                echo "<tr><th>তারিখ</th><th>গ্রাহক</th><th>পণ্য</th><th>অবস্থা</th><th>বিকল্প</th><th>বিস্তারিত</th></tr>";
                while($row=mysql_fetch_array($result))
                {
                    $invoice_id=$row['id'];
                    echo "<tr>";			
                        echo "<td>".aim_num_to_bn($row['date_in'])."</td>";
                        echo "<td>"; display_party_info($row['party_info']); echo "</td>";
                        echo "<td></td><td>".$row['status']."</td>";
                        echo "<td><a class='anchor' href='".BASE."/invoices/?process=deliver&business=".$business."&id=".$row['id']."'><strong><i>প্রদান</i></strong></a></td>";
			echo "<td><a class='anchor' href='".BASE."/invoices/".$business."/view/".$invoice_id."'>বিস্তারিত</a></td>";
                    echo "</tr>";
                    $presult=mysql_query("SELECT product_inventory.id, product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.status, product_list.product_name FROM product_inventory, product_list WHERE product_inventory.product_no=product_list.id AND invoice_id='$invoice_id' AND status<>'final'");
                    while($prow=mysql_fetch_array($presult))
                    {
                        $inventory_id=$prow['id'];
                        if($prow['status']=="") $status="In Queue";
                        else $status=$prow['status'];

                        echo "<tr>";
                            echo "<td></td><td></td>";
                            echo "<td><a class='anchor' href='".BASE."/invoices/".$business."/process/".$inventory_id."'>".$prow['product_name']."</a></td>";
                            echo "<td>".$status."</td>";
                            echo "<td></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
    }
    ?>
    
    </div>
    <?php

}
else header("location:".BASE."/login");
footing();
?>


<?php
function total_mill_receivable($business) {
	$result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
        $total_due=0;
        while($row=mysql_fetch_array($result))
        {
        	   
            $due_amount=display_total_due($row['party_id'], 'party');
            if($due_amount>0)
            {
            $total_due+=$due_amount;
            }
        }
        return $total_due;
        
	}
	
function total_mill_payable($business) {
	$result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
       $total_due=0;
        while($row=mysql_fetch_array($result))
        {
            
            $due_amount=display_total_due($row['party_id'], 'party');
            if($due_amount<0)
            {
            $total_due+=$due_amount;
            }
        }
        
        return -$total_due;
        }
        
function display_total_due($client, $type)
    {
    if($type=='self') $stock_type="business";
    else $stock_type="client";

    if($type!="") $party_info="{".$type.":".$client."}";
    else $party_info=$client;
            
            $sl=0;
            $total_due=0;
            $ptotal_due=0;

            $sql="select * from invoices where party_info='$party_info' AND status<>'deny' AND status<>'pending'";
            //if($business>0) $sql=$sql." AND business_id='$business'";
            $Q=mysql_query($sql);

            while($row=mysql_fetch_array($Q))
            {
            	$invoice_id=$row['id'];
 		$business_id=$row['business_id'];
                $total_amount=$row['total_amount'];
                $type=$row['invoice_type'];

            	$Q1=mysql_query("select SUM(amount) as total_paid from transactions where invoice_id='$invoice_id' AND type='credit'");
            	$row1=mysql_fetch_array($Q1);
                $received=$row1['total_paid'];

            	$Q2=mysql_query("select SUM(amount) as total_paid from transactions where invoice_id='$invoice_id' AND type='debit'");
            	$row2=mysql_fetch_array($Q2);
                $paid=$row2['total_paid'];

                if(($type=="sales")||($type=="service")) $due=$total_amount-$received+$paid;
                elseif($type=="purchase") $due=$total_amount+$received-$paid;
                else $due=0;

                $extra=0;
                if($type=="payment") $extra=$paid;
                elseif($type=="receive") $extra=0-$received;
                
                if(($date<=$to)&&($date>=$from))
                {                   	
                    $ptotal_due=$ptotal_due+$due+$extra;
                }
            	$total_due=$total_due+$due+$extra;
            }
            
            return $total_due;
}        

?>
