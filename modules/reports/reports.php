<?php
/*
 * Project: DoubleP Framework
 * Version: 1.0
 * Script Version: 1.0
 * Author: Moin Uddin
 */

if(current_user_info('type')=="admin")
{
    heading("Reports", "", "");

        if(!isset($_REQUEST['from'])) $from=date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y"))); //one month before today
        else $from=$_REQUEST['from'];

        if(!isset($_REQUEST['to'])) $to=date("Y-m-d");
        else $to=$_REQUEST['to'];
       
        if(!isset($_REQUEST['business'])) $business="";
        else $business=$_REQUEST['business'];
        
        $self=BASE;
        if(isset($break[$start])) $self=$self."/".$break[$start];
        if(isset($break[$start+1])) $self=$self."/".$break[$start+1];
        if(isset($break[$start+2])) $self=$self."/".$break[$start+2];
        if(isset($break[$start+3])) $self=$self."/".$break[$start+3];

        $boptions="";
        $bsres=mysql_query("SELECT id, name FROM businesses");
        while($bsrow=mysql_fetch_array($bsres))
        {
            $boptions=$boptions."<option value='".$bsrow['id']."'>".$bsrow['name']."</option>";
        }
//$from=aim_num_to_bn($from);
//$to=aim_num_to_bn($to);
echo <<<EOT
<form action="$self/" >
<table>
	<tr>
		<td>তারিখ হইতে</td>
		<td>:</td>
		<td><input type="text" name="from" value="$from"></td>
	</tr>
	
	<tr>
		<td>পর্যন্ত</td>
		<td>:</td>
		<td><input type="text" name="to" value="$to"></td>
	</tr>
	
        <tr>
            <td>ব্যবসা</td>
            <td>:</td>
            <td><select name='business'><option value=''>ব্যবসা বাছাই</option>$boptions</select>
        </tr>

	<tr>
		<td></td>
		<td></td>
		<td><input type="submit" value="দাখিল করুন"></td>
	</tr>
</table>
</form>

EOT;


    if($break[$start+1]!="")
    {
        if($break[$start+1]=='client')
        {
            if($break[$start+2]>0)
            {
                $client=$break[$start+2];
                show_client_report($from, $to, $business, $client, "party");
            }
            else
            {
                //show list of all parties with link: BASE."/reports/client/".$party_id
            
            ?>
            <table>
            	<tr>
            		<th>পার্টি আইডি</th>
            		<th>নাম</th>
            		<th>ঠিকানা</th>
            		<th>ফোন</th>
			<th>Stock</th>
            	</tr>
            	<?php
            	$S="select * from party_info";
            	if(isset($_REQUEST['business'])) $S.=" WHERE business_id LIKE '".$_REQUEST['business']."'";
            	$S.=" ORDER BY party_name";
            	$Q=mysql_query($S) or die(mysql_error());
            	while($row=mysql_fetch_array($Q))
            	{
            	?>
            	
            	
            	<tr>
            		<td><?php echo aim_num_to_bn($party_id=$row['party_id']); ?></td>
            		<td><a class='anchor' href="<?php echo BASE."/reports/client/".$party_id;?>"><?php echo $row['party_name']; ?></a></td>
            		<td><?php echo $row['party_address']; ?></td>
            		<td><?php echo aim_num_to_bn($row['party_phone']); ?></td>
			<td><?php echo "<a href='".BASE."/stock/client/".$row['party_id']."'>Party Stock</a>"; ?></td>
            	</tr>
            	
            	<?php
            	}
            	?>
            </table>
            
            <?php
            }
        }
        if($break[$start+1]=='client_info')
        {
            if($break[$start+2]>0)
            {
                $client=$break[$start+2];
                show_client_summary($from, $to, $business, $client, "party");
            }
        }
        //following is a report of mill transaction within mills and trading centers of same company
        elseif($break[$start+1]=='business')
        {
            if((isset($break[$start+2]))&&($break[$start+2]!=""))
            {
                $client=$break[$start+2];
                show_client_report($from, $to, $business, $client, "self");
            }
        }
        elseif($break[$start+1]=="product")
        {
            if((isset($break[$start+2]))&&($break[$start+2]!=""))
            {
                show_product_report($break[$start+2], $from, $to, $business);
            }
            else
            {
                show_all_products($business);
            }
        }
        elseif($break[$start+1]=="summary")
        {
            //here will come the income and expense
            business_summary($from, $to, $business);
        }	
		elseif($break[$start+1]=="stock")
		{
			show_mill_stock('', '', $break[$start+2]);
		}
		elseif($break[$start+1]=="receivables")
		{
			show_receivable_options();
		}
		elseif($break[$start+1]=="payables")
		{
			show_payable_options();
		}
		elseif($break[$start+1]=="banking")
		{
			if(isset($break[$start+2])) show_bank_details($break[$start+2]);
			else show_bank_summaries();
		}
		elseif($break[$start+1]=='salaries')
		{
			if($break[$start+2]=='employee') 
			{
				if(isset($break[$start+3])) show_employee_salary($break[$start+3]);
				else show_employee_list();
			}
			else show_salary_report($from, $to);
		}
		else
		{
            show_report($from, $to, $business, $break[$start+1]);
		}
    }
    else
    {
        echo "<a class='anchor' href='".BASE."/reports/service'>সার্ভিস রিপোর্ট</a><br>";
        echo "<a class='anchor' href='".BASE."/reports/sales'>বিক্রয় রিপোর্ট</a><br>";
        echo "<a class='anchor' href='".BASE."/reports/purchase'ক্রয় রিপোর্ট</a>";
        echo "<a class='anchor' href='".BASE."/reports/client'>গ্রাহক রিপোর্ট</a>";
    }
    footing();
}
else header("location:".BASE."/home");

function show_report($from, $to, $business, $type)
{
    $sql="SELECT id, invoice_type, business_id, party_info, date_in, date_out, total_amount, status FROM invoices WHERE invoice_type='$type' AND status<>'deny' AND status<>'pending'";
    if(($type=='service')||($type=='purchase')||($type=='receive')) $sql=$sql." AND date_in>='$from' AND date_in<='$to'";
    elseif(($type=='sales')||($type=='payment')) $sql=$sql." AND date_out>='$from' AND date_out<='$to'";
    if($business!="") $sql=$sql." AND business_id='$business'";

    $result=mysql_query($sql) or die(mysql_error());
    
    echo "<table>";
    echo "<tr><th>চালান আইডি</th><th>তারিখ</th><th>পার্টি</th><th>মোট</th><th>গ্রহন</th><th>পরিশোধ</th><th>গ্রহনযোগ্য</th><th>পরিশোধযোগ্য</th></tr>";
    while($row=mysql_fetch_array($result))
    {
        $invoice_id=$row['id'];
        $total_amount=$row['total_amount'];
        echo "<tr>";
            echo "<td><a class='anchor' href='".BASE."/invoices/".$row['business_id']."/view/".$row['id']."'>".aim_num_to_bn($row['id'])."</a></td>";
            if($row['date_in']!="0000-00-00") echo "<td>".aim_num_to_bn($row['date_in'])."</td>";
            else echo "<td>".aim_num_to_bn($row['date_out'])."</td>";
            
            echo "<td>";
                display_party_info($row['party_info']);
            echo "</td>";
            echo "<td>".aim_num_to_bn($total_amount)."</td>";

            $paid=0;
            $received=0;
            $rresult=mysql_query("SELECT amount, type FROM transactions WHERE invoice_id='$invoice_id'") or die(mysql_error());
            while($rrow=mysql_fetch_array($rresult))
            {
                if($rrow['type']=='credit') $received=$received+$rrow['amount'];
                elseif($rrow['type']=='debit') $paid=$paid+$rrow['amount'];
            }

            echo "<td>".aim_num_to_bn($received)."</td>";
            echo "<td>".aim_num_to_bn($paid)."</td>";

            //calculating receivable and payable
            if(($type=='service')||($type=='sales')) $receivables=$total_amount-$received+$paid;
            elseif(($type=='purchase')||($type=='expense')) $receivables=$paid-($total_amount+$received);

            echo "<td>";
            if($receivables>0) echo aim_num_to_bn($receivables);
            else echo "n/a";
            echo "</td>";

            echo "<td>";
            if($receivables<0) echo (-$receivables);
            else echo "n/a";
            echo "</td>";
            
        echo "</tr>";
    }
    echo "</table>";
}

function show_client_report($from, $to, $business, $client, $type)
{
    if($type=='self') $stock_type="business";
    else $stock_type="client";

    if($type!="") $party_info="{".$type.":".$client."}";
    else $party_info=$client;

if($type!='self')
{
            $frbr=explode("-", $from);
            $tobr=explode("-", $to);
            echo "<h3>";
                display_party_info($party_info);
            echo "</h3>";
            echo "<h2>(".$frbr['2']."-".$frbr['1']."-".$frbr['0']." to ".$tobr['2']."-",$tobr['1']."-".$tobr['0'].")র রিপোর্ট</h2>";

?>
            	<table>
            		<tr>
            			<th>ক্রম</th>
            			<th>চালান আইডি</th>
                                <th>চালানের ধরণ</th>
            			<th>মোট পরিমাণ</th>
            			<th>মোট গ্রহন</th>
                                <thমোট পরিশোধ</th>
            			<th>বকেয়া</th>
            		</tr>
            <?php
            $sl=0;
            $total_due=0;
            $ptotal_due=0;

            $sql="select * from invoices where party_info='$party_info' AND status<>'deny' AND status<>'pending'";
            if($business>0) $sql=$sql." AND business_id='$business'";
            $Q=mysql_query($sql);

            while($row=mysql_fetch_array($Q))
            {
            	$invoice_id=$row['id'];
 		$business_id=$row['business_id'];
                $total_amount=$row['total_amount'];
                $type=$row['invoice_type'];
                if(($type=="purchase")||($type=="service")||($type=="receive")) $date=$row['date_in'];
                elseif(($type=="sales")||($type=="payment")) $date=$row['date_out'];

            	$Q1=mysql_query("select SUM(amount) as total_paid from transactions where invoice_id='$invoice_id' AND type='credit'");
            	$row1=mysql_fetch_array($Q1);
                $received=$row1['total_paid'];

            	$Q2=mysql_query("select SUM(amount) as total_paid from transactions where invoice_id='$invoice_id' AND type='debit'");
            	$row2=mysql_fetch_array($Q2);
                $paid=$row2['total_paid'];

                if(($type=="sales")||($type=="service")) echo aim_num_to_bn($due=$total_amount-$received+$paid);
                elseif($type=="purchase") $due=$total_amount+$received-$paid;
                else $due=0;

                $extra=0;
                if($type=="payment") $extra=$paid;
                elseif($type=="receive") $extra=0-$received;
                
                if(($date<=$to)&&($date>=$from))
                {
            ?>
            		<tr>
            			<td><?php echo aim_num_to_bn(++$sl); ?></td>
            			<td><?php echo "<a href='".BASE."/invoices/".$business_id."/view/".$invoice_id."' target='_blank'>".aim_num_to_bn($invoice_id)."</a>"; ?></td>
                                <td><?php echo $type; ?></td>
            			<td align="right"><?php echo aim_num_to_bn($total_amount); ?></td>
            			<?php if($received>0) {?><td align="right"><?php echo aim_num_to_bn($received); ?></td>
                                <?php }else {?><td align="right"><?php echo aim_num_to_bn($paid); ?></td><?php } ?>
            			<th align="right"><?php echo aim_num_to_bn($due)?></th>
            		</tr>
            	<?php
                    $ptotal_due=$ptotal_due+$due+$extra;
                }
            	$total_due=$total_due+$due+$extra;
            }
            	?>
            		<tr>
                                <th></th>
                                <th></th>
            			<th colspan="3" align="center">বকেয়া ছিল</th>
            			<th align="right"><?php echo aim_num_to_bn($ptotal_due); ?></th>
            		</tr>

                        <tr>
                                <th></th>
                                <th></th>
            			<th colspan="3" align="center">মোট বকেয়া</th>
            			<th align="right"><?php echo aim_num_to_bn($total_due); ?></th>
            		</tr>
            	</table>
<?php
}
//displaying the stock information
	show_stock($party_info, $stock_type);
}


function show_client_summary($from, $to, $business, $client, $type)
{
    if($type=='self') $stock_type="business";
    else $stock_type="client";

    if($type!="") $party_info="{".$type.":".$client."}";
    else $party_info=$client;

if($type!='self')
{
            $frbr=explode("-", $from);
            $tobr=explode("-", $to);
            echo "<h3>";
                display_party_info($party_info);
            echo "</h3>";
            echo "<h2>(".$frbr['2']."-".$frbr['1']."-".$frbr['0']." to ".$tobr['2']."-",$tobr['1']."-".$tobr['0'].")র রিপোর্ট</h2>";

?>
            	<table>
            		
            <?php
            $sl=0;
            $total_due=0;
            $ptotal_due=0;

            $sql="select * from invoices where party_info='$party_info' AND status<>'deny' AND status<>'pending'";
            if($business>0) $sql=$sql." AND business_id='$business'";
            $Q=mysql_query($sql);

            while($row=mysql_fetch_array($Q))
            {
            	$invoice_id=$row['id'];
 		$business_id=$row['business_id'];
                $total_amount=$row['total_amount'];
                $type=$row['invoice_type'];
                if(($type=="purchase")||($type=="service")||($type=="receive")) $date=$row['date_in'];
                elseif(($type=="sales")||($type=="payment")) $date=$row['date_out'];

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
                /*
            ?>
            		<tr>
            			<td><?php echo ++$sl; ?></td>
            			<td><?php echo "<a href='".BASE."/invoices/".$business_id."/view/".$invoice_id."' target='_blank'>".$invoice_id."</a>"; ?></td>
                                <td><?php echo $type; ?></td>
            			<td align="right"><?php echo $total_amount; ?></td>
            			<?php if($received>0) {?><td align="right"><?php echo $received; ?></td>
                                <?php }else {?><td align="right"><?php echo $paid; ?></td><?php } ?>
            			<th align="right"><?php echo $due?></th>
            		</tr>
            	<?php
            	        */    	
                    $ptotal_due=$ptotal_due+$due+$extra;
                }
            	$total_due=$total_due+$due+$extra;
            }
            	?>            		
                        <tr>
                                <th></th>
                                <th></th>
            			<th colspan="3" align="center">মোট বকেয়া</th>
            			<th align="right"><?php echo aim_num_to_bn($total_due); ?></th>
            		</tr>
            	</table>
<?php
}
}





function show_product_report($id, $from, $to, $business)
{
    $sql="SELECT product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.type, product_inventory.type, product_inventory.parent, product_inventory.status, invoices.invoice_type FROM product_inventory, invoices WHERE  product_inventory.invoice_id=invoices.id AND product_inventory.product_no='$id' AND invoices.status<>'pending' AND invoices.status<>'deny'";
    if($business>0) $sql=$sql." AND business_id='$business'";
    if($type!="") $sql=$sql." AND invoice_type='$type'";
    if(($type=='service')||($type=='purchase')||($type=='receive')) $sql=$sql." AND date_in>='$from' AND date_in<='$to'";
    elseif(($type=='sales')||($type=='payment')) $sql=$sql." AND date_out>='$from' AND date_out<='$to'";

    $result=mysql_query($sql);
    $ptotal_qty=0;
    $ptotal_amount=0;
    $stotal_qty=0;
    $stotal_amount=0;
    while($row=mysql_fetch_array($result))
    {
        if($row['invoice_type']=="purchase")
        {
            $ptotal_qty=$ptotal_qty+$row['quantity'];
            $ptotal_amount=$ptotal_amount+$row['quantity']*$row['cost_per_unit'];
        }
        elseif($row['invoice_type']=="sales")
        {
            $stotal_qty=$stotal_qty+$row['quantity'];
            $stotal_amount=$stotal_amount+$row['quantity']*$row['cost_per_unit'];
        }
    }

    $pnres=mysql_query("SELECT product_name FROM product_list WHERE id='$id'");
    $pnarr=mysql_fetch_array($pnres);
    echo "<h3>".$pnarr['product_name']."</h3>";
    echo "<table>";
        echo "<tr><td><strong>মোট ক্রয় </strong></td><td>".aim_num_to_bn($ptotal_qty)." একক</td><td> BDT ".aim_num_to_bn($ptotal_amount)."</td><td><strong>( average BDT ".aim_num_to_bn(($ptotal_amount/$ptotal_qty))." একক প্রতি)</strong></td></tr>";
        echo "<tr><td><strong>মোট বিক্রয়</strong></td><td>".aim_num_to_bn($stotal_qty)." একক</td><td>BDT ".aim_num_to_bn($stotal_amount)." </td><td><strong>(average BDT ".aim_num_to_bn(($stotal_amount/$stotal_qty))." per unit)</strong></td></tr>";

	echo "<tr><td><strong>বাকি</strong></td><td>".aim_num_to_bn(($ptotal_qty-$stotal_qty))." একক</td></tr>";
    echo "</table>";
}

function business_summary($from, $to, $business)
{
    $sql="SELECT id, invoice_type, total_amount FROM invoices WHERE status<>'deny' AND status<>'pending' AND (date_in>='$from' OR date_out>='$from') AND (date_in<='$to' OR date_out<='$to')";
    if($business>0) $sql=$sql." AND business_id='$business'";
    $result=mysql_query($sql) or die(mysql_error());
    $purchase=0;
    $sales=0;
    $service=0;
    $expense=0;
    $parts=0;

    while($row=mysql_fetch_array($result))
    {        
        /*not calculated how much is already received, but only the total amount wheather it is received or in due, 
        now only received amount will be taken*/   
        
        //credit     
        $invoice_id=$row['id'];
        $cres=mysql_query("SELECT SUM(amount) as credit FROM transactions WHERE invoice_id='$invoice_id' AND type='credit'") or die(mysql_error());
        $cresarr=mysql_fetch_array($cres);
        $credit=$cresarr['credit'];
        
        //debit
        $debs=mysql_query("SELECT SUM(amount) as debit FROM transactions WHERE invoice_id='$invoice_id' AND type='debit'") or die(mysql_error());
        $cresarr=mysql_fetch_array($debs);
        $debit=$cresarr['debit'];
        
        $amount=$credit-$debit;
        
        $type=$row['invoice_type'];
        //$amount=$row['total_amount'];
        if($type=='purchase') $purchase+=$amount;
        elseif($type=='sales') $sales+=$amount;
        elseif($type=='service') $service+=$amount;
        elseif($type=='parts') $parts+=$amount;
        elseif($type=='expense') $expense+=$amount;
    }

    $exp_total=$purchase+$parts+$expense;
    $inc_total=$sales+$service;
    $balance=$inc_total-$exp_total;

/**********bangla conversion***********/
$from=aim_num_to_bn($from);
$to=aim_num_to_bn($to);
$sales=aim_num_to_bn($sales);
$service=aim_num_to_bn($service);
$purchase=aim_num_to_bn($purchase);
$parts=aim_num_to_bn($parts);
$expense=aim_num_to_bn($expense);
$inc_total=aim_num_to_bn($inc_total);
$exp_total=aim_num_to_bn($exp_total);
$balance=aim_num_to_bn($balance);
/*************************************/




    echo <<<EOT
<h2>Report of $from to $to</h2>
<table>
    <tr>
        <th>আয়</th><th>ব্যয়</th>
    </tr>

    <tr>
        <td valign='top'>
            <table>
                <tr>
                    <td>বিক্রয় আয়</td>
                    <td>$sales</td>
                </tr>

                <tr>
                    <td>সার্ভিস আয়</td>
                    <td>$service</td>
                </tr>
            </table>
        </td>

        <td>
            <table>
                <tr>
                    <td>পণ্য ক্রয় </td>
                    <td>$purchase</td>
                </tr>

                <tr>
                    <td>পার্টস ক্রয়</td>
                    <td>$parts</td>
                </tr>

                <tr>
                    <td>অন্যান্য খরচ</td>
                    <td>$expense</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td><strong>মোট: </strong>$inc_total</td>
        <td><strong>মোট: </strong>$exp_total</td>
    </tr>
    <tr>
    	<td></td>
    	<td><strong>ব্যলেন্স: </strong>$balance</td>
    </tr>

</table>
EOT;
}

function show_all_products($business)
{
    $sql="SELECT DISTINCT product_list.id, product_list.product_name FROM product_list, price_list";
    if($business>0)
    {
        $sql=$sql." WHERE price_list.product=product_list.id AND price_list.price<>'' AND price_list.business='$business'";
        $add_link="/?business=$business";
    }
    
    echo "<h2>পণ্য তালিকা</h2>";
    $result=mysql_query($sql) or die(mysql_error());
    while($row=mysql_fetch_array($result))
    {
        echo "<a href='".BASE."/reports/product/".$row['id'].$add_link."'>".$row['product_name']."</a><br>";
    }
}

function show_receivable_options()
{
if(!isset($_REQUEST['business'])) $business="";
        else $business=$_REQUEST['business'];	

echo "<h2>বকেয়া</h2>";
    if(isset($_REQUEST['client']))
    {


		echo "<a href='".BASE."/reports/receivables'>--Back</a>";
        $result=mysql_query("SELECT party_id, business_id, party_name, party_address FROM party_info WHERE party_id='".$_REQUEST['client']."'");
        echo "<table>";
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            if($due_amount>0)
            {
            	echo "<tr><td>নাম: </td><td>".$row['party_name']."</td></tr>";
            	echo "<tr><td>ঠিকানা:</td><td>".$row['party_address']."</td></tr>";
            	echo "<tr><td>বকেয়ার পরিমান:</td><td>".aim_num_to_bn($due_amount)."</td></tr>";
				
				echo "<form action='".BASE."/invoices/?process=process_invoice' method='post'>";
				echo "<input type='hidden' name='business' value='".$row['business_id']."'><input type='hidden' name='type' value='receive'>";
				echo "<input type='hidden' name='date' value='03-10-2012'>";
				echo "<input type='hidden' name='previous_total_amount' value='0'>";
				echo "<input type='hidden' name='select_party' value='".$_REQUEST['client']."'>";
				echo "<tr><td>পরিশোধের পরিমান: </td><td><input type='text' name='paid'></td></tr>";
				echo "<tr><td></td><td><input type='submit' value='সংরক্ষণ করুন'></td></tr>";
				echo "</form>";
				
            	echo "</tr>";
            }
        }
		echo "</table>";
    }
    else
    {

        $result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
        echo "<table>";
        echo "<tr><th>ক্রম</th><th>নাম</th><th>ঠিকানা</th><th>মোট বকেয়া</th><th>বকেয়া গ্রহন</th></tr>";
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            if($due_amount>0)
            {
                echo "<tr>";
            	echo "<td>".++$i."</td>";
            	echo "<td>".$row['party_name']."</td>";
            	echo "<td>".$row['party_address']."</td>";
            	echo "<td>".aim_num_to_bn($due_amount)."</td>";
            	echo "<td><a href='".BASE."/reports/receivables/?client=".$row['party_id']."'>বকেয়া গ্রহন</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }
}

function show_payable_options()
{	

if(!isset($_REQUEST['business'])) $business="";
        else $business=$_REQUEST['business'];
    echo "<h2>দেনা</h2>";
    if(isset($_REQUEST['client']))
    {	
		echo "<a href='".BASE."/reports/receivables'>--Back</a>";
        $result=mysql_query("SELECT party_id, business_id, party_name, party_address FROM party_info WHERE party_id='".$_REQUEST['client']."'");
        echo "<table>";
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            if($due_amount<0)
            {
            	echo "<tr><td>নাম: </td><td>".$row['party_name']."</td></tr>";
            	echo "<tr><td>ঠিকানা:</td><td>".$row['party_address']."</td></tr>";
            	echo "<tr><td>দেনার পরিমান:</td><td>".aim_num_to_bn($due_amount)."</td></tr>";
				
				echo "<form action='".BASE."/invoices/?process=process_invoice' method='post'>";
				echo "<input type='hidden' name='business' value='".$row['business_id']."'><input type='hidden' name='type' value='payment'>";
				echo "<input type='hidden' name='date' value='03-10-2012'>";
				echo "<input type='hidden' name='previous_total_amount' value='0'>";
				echo "<input type='hidden' name='select_party' value='".$_REQUEST['client']."'>";
				echo "<tr><td>জমার পরিমান: </td><td><input type='text' name='paid'></td></tr>";
				echo "<tr><td></td><td><input type='submit' value='Submit'></td></tr>";
				echo "</form>";
				
            	echo "</tr>";
            }
        }
		echo "</table>";
    }
    else
    {
        $result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
        echo "<table>";
        echo "<tr><th>ক্রম</th><th>নাম</th><th>ঠিকানা</th><th>মোট দেনা</th><th>দেনা পরিশোধ</th></tr>";
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            if($due_amount<0)
            {
                echo "<tr>";
            	echo "<td>".aim_num_to_bn(++$i)."</td>";
            	echo "<td>".$row['party_name']."</td>";
            	echo "<td>".$row['party_address']."</td>";
            	echo "<td>".aim_num_to_bn($due_amount)."</td>";
            	echo "<td><a href='".BASE."/reports/receivables/?client=".$row['party_id']."'>দেনা পরিশোধ</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }
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

function show_bank_details($id)
{
    if(($break[$start+1]=='page')&&($break[$start+2]!=""))
    {
        $page=$break[$start+2];
    }
    else $page=1;
    $y=20;
    $x=($page-1)*$y;

    $result=mysql_query("SELECT id, bank_name, account_no FROM bank_list WHERE id='$id'") or die(mysql_error());
    $arr=mysql_fetch_array($result);
    echo "<table>";
    echo "<tr><td><strong>ব্যাংক: </strong></td><td>".$arr['bank_name']."</td></tr>";
    echo "<tr><td><strong>হিসাব নং: </strong></td><td>".aim_num_to_bn($arr['account_no'])."</td></tr>";
    echo "</table>";

    echo "<strong>লেনদেন</strong>";
    echo "<table>";
    echo "<tr><th>চালান আইডি</th><th>তারিখ</th><th>প্রকার</th><th>মোট লেনদেন</th></tr>";
    $sql2="SELECT invoices.id, invoices.business_id, invoices.date_in, invoices.date_out, transactions.amount, transactions.type FROM invoices, transactions WHERE invoices.invoice_type='banking' AND invoices.party_info='{bank:$id}' AND invoices.id=transactions.invoice_id AND invoices.status<>'deny' AND invoices.status<>'pending'";
    $result2=mysql_query($sql2) or die(mysql_error());
    $total=mysql_num_rows($result2);

    $bcredit=0;
    $bdebit=0;
    $i=0;
    while($row2=mysql_fetch_array($result2))
    {
	if($row2['type']=='credit')
	{
	    $bcredit+=$row2['amount'];
	    $date=$row2['date_in'];
	    $desc="উত্তোলন";
	}
	elseif($row2['type']=='debit')
	{
	    $bdebit+=$row2['amount'];
	    $date=$row2['date_out'];
	    $desc="জমা";
	}
	
        if(($i>=$x)||($i<=$y))
        {
	    echo "<tr>";
	    echo "<td><a href='".BASE."/invoices/".$row2['business_id']."/view/".$row2['id']."'>".aim_num_to_bn($row2['id'])."</a></td>";
	    echo "<td>".aim_num_to_bn($date)."</td>";
	    echo "<td>$desc</td>";
	    echo "<td>".aim_num_to_bn($row2['amount'])." টাকা</td>";
	    echo "</tr>";
        }
        $i++;	
    }	
    echo "</table>";
    paginate($total, $page, $y, BASE."/reports/banking/$id");

    echo "<br><table><tr><td><strong>মোট জমা: <strong></td><td>".aim_num_to_bn($bdebit)." টাকা</td></tr><tr><td><strong>মোট উত্তোলন</strong></td><td>".aim_num_to_bn($bcredit)." টাকা</td></tr><tr><td><strong>ব্যাংকে মোট জমা আছে:</strong></td><td>".aim_num_to_bn($bdebit-$bcredit)." টাকা</td></tr></table>";
	
}

function show_bank_summaries()
{    
    $result=mysql_query("SELECT id, bank_name, account_no FROM bank_list") or die(mysql_error());
    echo "<table>";
    echo "<tr><th>ব্যংকের নাম</th><th>একাউন্ট নং</th><th>জমা</th><th>উত্তোলন</th><th>ব্যলেন্স</th><th>বিস্তারিত</th></tr>";
    while($row=mysql_fetch_array($result))
    {
 	$sql2="SELECT transactions.amount, transactions.type FROM invoices, transactions WHERE invoices.invoice_type='banking' AND invoices.party_info='{bank:".$row['id']."}' AND invoices.id=transactions.invoice_id AND invoices.status<>'deny' AND invoices.status<>'pending'";
	$result2=mysql_query($sql2) or die(mysql_error());
	$bcredit=0;
	$bdebit=0;
	while($row2=mysql_fetch_array($result2))
	{
	    if($row2['type']=='credit') $bcredit+=$row2['amount'];
	    elseif($row2['type']=='debit') $bdebit+=$row2['amount'];
	}
	
	echo "<tr>";
	    echo "<td>".$row['bank_name']."</td>";
	    echo "<td>".aim_num_to_bn($row['account_no'])."</td>";
	    echo "<td>".aim_num_to_bn($bdebit)."</td>";
	    echo "<td>".aim_num_to_bn($bcredit)."</td>";
	    echo "<td>".aim_num_to_bn(($bdebit-$bcredit))."</td>";
	    echo "<td><a href='".BASE."/reports/banking/".$row['id']."'>বিস্তারিত</a></td>";
    	echo "</tr>";
    }
    echo "</table>";    
}

//salary reports
function show_salary_report($from, $to)
{
	$sql="SELECT party_info, SUM(invoices.total_amount) as amount FROM invoices WHERE invoice_type='salary' AND date_out>='$from' AND date_out<='$to' GROUP BY party_info ORDER BY id DESC";
	$result=mysql_query($sql) or die(mysql_error());
	$i=0;
	echo "<h2>বেতনের রিপোর্ট (".aim_num_to_bn($from)." to ".aim_num_to_bn($to).")</h2>";
	echo "<table>";
	echo "<tr><th>ক্রম</th><th>কর্মচারী নাম</th><th>পদবি</th><th>মোট পরিশোধ</th><th>বিস্তারিত</th></tr>";
	while($row=mysql_fetch_array($result))
	{
		$i++;
		$total+=$row['amount'];
		$emp_id=str_replace("{emp:", "", $row['party_info']);
		$emp_id=str_replace("}", "", $emp_id);
		$res=mysql_query("SELECT id, name, designation FROM employee_info WHERE id='$emp_id'") or die(mysql_error());		
		$arr=mysql_fetch_array($res);
		echo "<tr>";
			echo "<td>".aim_num_to_bn($i)."</td>";
			echo "<td>".$arr['name']."</td>";
			echo "<td>".$arr['designation']."</td>";
			echo "<td>".aim_num_to_bn($row['amount'])."</td>";
			echo "<td><a href='".BASE."/reports/employee/".$arr['id']."'>বিস্তারিত</a></td>";
		echo "</tr>";
	}
	echo "<tr><td></td><td></td><td>মোট পরিশোধ</td><td>".aim_num_to_bn($total)."</td><td></td></tr>";
	echo "</table>";
}

function show_employee_salary($id)
{
	$result=mysql_query("SELECT id, name, fhname, mother_name, designation, present_address FROM employee_info WHERE id='$id'") or die(mysql_error());
	$arr=mysql_fetch_array($result);
	
	echo "<h2>কর্মচারীর তথ্য</h2>";
	echo "<table>";
		echo "<tr><td><strong>নাম: </strong></td><td>".$arr['name']."</td></tr>";
		echo "<tr><td><strong>পদবি: </strong></td><td>".$arr['designation']."</td></tr>";
		echo "<tr><td><strong>পিতার নাম: </strong></td><td>".$arr['fhname']."</td></tr>";
		echo "<tr><td><strong>মাতার নাম: </strong></td><td>".$arr['mother_name']."</td></tr>";
		echo "<tr><td><strong>বর্তমান ঠিকানা: </strong></td><td>".$arr['present_address']."</td></tr>";
	echo "</table>";
	
	$res=mysql_query("SELECT date_out, total_amount FROM invoices WHERE invoice_type='salary' AND party_info='{emp:".$arr['id']."}' ORDER BY date_out DESC") or die(mysql_error());
	echo "<h2>বেতন বিস্তারিত</h2>";
	echo "<table>";
	echo "<tr><th>তারিখ</th><th>মোট টাকা</th></tr>";
	while($row=mysql_fetch_array($res))
	{
		$total+=$row['amount'];
		echo "<tr>";
			echo "<td>".aim_num_to_bn($row['date_out'])."</td><td>".aim_num_to_bn($row['total_amount'])."</td>";
		echo "</tr>";
	}
	echo "<tr><td>মোট পরিশোধিত</td><td>".aim_num_to_bn($total)."</td></tr>";
	echo "</table>";	
}

function show_employee_list()
{
	$result=mysql_query("SELECT id, name, designation FROM employee_info") or die(mysql_error());
	$i=0;
	echo "<table>";
	echo "<tr><th>ক্রম</th><th>নাম</th><th>পদবি</th><th>সর্বশেষ পরিশোধের তারিখ</th><th>সর্বশেষ পরিশোধের টাকা</th><th>বিস্তারিত</th></tr>";
	while($row=mysql_fetch_array($result))
	{
		$i++;
		$res=mysql_query("SELECT date_out, total_amount FROM invoices WHERE invoice_type='salary' AND party_info='{emp:".$row['id']."}' ORDER BY date_out DESC LIMIT 1") or die(mysql_error());
		$arr=mysql_fetch_array($res);
		$payment_date=$arr['date_out'];
		$amount=$arr['total_amount'];
		
		echo "<tr>";
			echo "<td>".aim_num_to_bn($i)."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".$row['designation']."</td>";
			echo "<td>".aim_num_to_bn($payment_date)."</td>";
			echo "<td>".aim_num_to_bn($amount)."</td>";	
			echo "<td><a href='".BASE."/reports/salaries/employee/".$row['id']."'>বিস্তারিত</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}


function total_mill_receivable($business) {
	$result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
        $total_due=0;
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            $total_due+=$due_amount;
        }
        return $total_due;
        
	}
	
	function total_mill_payable($business) {
	$result=mysql_query("SELECT party_id, party_name, party_address FROM party_info where business_id='$business'");
       $total_due=0;
        while($row=mysql_fetch_array($result))
        {
            $due_amount=display_total_due($row['party_id'], 'party');
            
            $total_due+=$due_amount;
            
        }
        
        return $total_due;
        }
?>
