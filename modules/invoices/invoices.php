<?php
if(current_user_info('id')!="")
{
    if(isset($_REQUEST['process']))
    {
        if($_REQUEST['process']=="make_row_total")
        {
            $result=mysql_query("SELECT price_per_unit FROM product_list WHERE id='".$_REQUEST['product']."'");
            $arr=mysql_fetch_array($result);
            echo "<input type='text' name='price".$_REQUEST['row']."' value='".($arr['price_per_unit']*$_REQUEST['qty'])."' size='5' onkeyup=\"remove_total()\">";
            die();
        }
        if($_REQUEST['process']=="get_per_unit")
        {
            $result=mysql_query("SELECT price FROM price_list WHERE business='".$_REQUEST['business']."' AND product='".$_REQUEST['product']."'");
            $arr=mysql_fetch_array($result);
            echo "<input type='text' id='pper_unit".$_REQUEST['row']."' name='per_unit".$_REQUEST['row']."' value='".$arr['price']."' size='5' onkeyup=\"remove_total()\">";
            die();
        }
        elseif($_REQUEST['process']=="grand_total")
        {
            $i=0;
            $total=0;
            while($i<=15)
            {
                $total=$total+$_REQUEST['price'.$i];
                $i++;
            }

	    $total=$total+$_REQUEST['previous_total'];
            echo aim_num_to_bn($total);
            die();
        }
        elseif($_REQUEST['process']=='process_invoice')
        {
            $business=$_POST['business'];
            $type=$_POST['type'];
            $party_name=mysql_real_escape_string($_POST['party_name']);
            $party_address=mysql_real_escape_string($_POST['party_address']);
            $party_phone=mysql_real_escape_string($_POST['party_phone']);
            $dbr=explode("-", $_POST['date']);
            $date=$dbr[2]."-".$dbr[1]."-".$dbr[0];
            $info="";
            if(isset($_POST['paid'])) $paid=$_POST['paid'];
            $status="pending";
            if(($type=="purchase")||($type=="service")||($type=="receive")) $date_in=$date;
            elseif(($type=="sales")||($type=="payment")||($type=="salary")) $date_out=$date;
            elseif($type=="banking")
            {
                if($_POST['transaction_type']=="Withdrawl")
                {
                    $date_in=$date;
                    $tr_type="credit";
                }
                elseif($_POST['transaction_type']=="Savings")
                {
                    $date_out=$date;
                    $tr_type="debit";
                }
                $paid=$_POST['transaction_amount'];
	        $info=$_POST['info'];
                $status=$_POST['procedure_type'];
            }
            elseif(($type=="salary")||($type=='expense'))
            {
		$date_in=$date;
                $tr_type="debit";
            }
            elseif($type=="parts")
            {
                $date_in=$date;
                $tr_type="debit";
            }
	   
            //product log
            $i=0;
            $total=0;
            while($i<5)
            {
                $i++;
                $total=$total+$_POST['price'.$i];
            }

            if(isset($_POST['total_amount'])) $total=$_POST['total_amount'];
            if($total==0) $total=$paid;
	
	    if(isset($_POST['reference'])) 
	    {
		$invoice_id=$_POST['reference'];
		$prevtotres=mysql_query("SELECT total_amount FROM invoices WHERE id='$invoice_id'") or die(mysql_error());
		$prevtotarr=mysql_fetch_array($prevtotres);
		$total=$total+$prevtotarr['total_amount'];
	 	mysql_query("UPDATE invoices SET total_amount='$total' WHERE id='$invoice_id'") or die(mysql-error());
	    }
            elseif(($business!="")&&($type!=""))
            {
                if($_POST['select_party']!="") $party_info="{party:".$_POST['select_party']."}";
                elseif($_POST['select_business']!="") $party_info="{self:".$_POST['select_business']."}";
                elseif($_POST['select_account']!="")
                {
                    $party_info="{bank:".$_POST['select_account']."}";
                }
                elseif($_POST['select_employee']!="") $party_info="{emp:".$_POST['select_employee']."}";
                elseif(($_POST['save_party']=='on')||($paid!=$total))
                {
                    mysql_query("INSERT INTO party_info (party_id, party_name, party_address, party_phone, business_id) VALUES ('', '$party_name', '$party_address', '$party_phone', '$business')");
                    $ttres=mysql_query("SELECT party_id FROM party_info ORDER BY party_id DESC LIMIT 1");
                    $ttarr=mysql_fetch_array($ttres);
                    $party_info="{party:".$ttarr['party_id']."}";
                }
                elseif(isset($_POST['expdesc'])) $party_info=mysql_real_escape_string($_POST['expdesc']);
                else $party_info=$party_name.", ".$party_address.", ".$party_phone;

                mysql_query("INSERT INTO invoices (id, business_id, invoice_type, party_info, date_in, date_out, total_amount, info, status) VALUES ('', '$business', '$type', '$party_info', '$date_in', '$date_out', '$total', '$info', '$status')") or die(mysql_error());

		$result=mysql_query("SELECT id FROM invoices WHERE business_id='$business' AND (date_in='$date_in' OR date_out='$date_out') ORDER BY id DESC LIMIT 1") or die(mysql_error());
            	$arr=mysql_fetch_array($result);
            	$invoice_id=$arr['id'];
            }	    
            else die();

            
            if(($type=="purchase")||($type=="service"))
            {
                $inv_type="credit";
            }
            elseif($type=="sales") $inv_type="debit";

            if(($type=="saving")||($type=="purchase")||($type=="payment")||($type=="salary")||($type=='expense'))
            {
                $tr_type="debit";
            }
            elseif(($type=="sales")||($type=="receive")||($type=="service")) $tr_type="credit";

            //transaction (payment log)
            mysql_query("INSERT INTO transactions (id, date, invoice_id, amount, type) VALUES ('', '".date("Y-m-d")."', '$invoice_id', '$paid', '$tr_type')") or(die(mysql_error()));
            
            if(($type=="sales")||($type=="purchase")||($type=="service"))
            {
                //product log
                $i=0;
                while($i<5)
                {
                    $i++;
                    $product_no=$_POST['product_no'.$i];
                    $quantity=$_POST['qty'.$i];

                    if($_POST['product_no'.$i]!="")
                    {
			$chp=mysql_query("SELECT * FROM product_inventory WHERE product_no='$product_no' AND invoice_id='$invoice_id'") or die(mysql_error());
			if(mysql_num_rows($chp)>0)
			{
			    $piarr=mysql_fetch_array($chp);
			    $piid=$piarr['id'];
			    $piqty=$piarr['quantity'];
			    $picost=$piqty*$piarr['cost_per_unit'];
			    $quantity=$quantity+$piqty;
			    $tprice=$_POST['price'.$i]+$picost;
			    $cost_per_unit=$tprice/$quantity;
			    mysql_query("UPDATE product_inventory SET cost_per_unit='$cost_per_unit', quantity='$quantity' WHERE id='$piid'") or die(mysql_error());
			}
			else
			{
                        	$cost_per_unit=$_POST['price'.$i]/$_POST['qty'.$i];
                        	mysql_query("INSERT INTO product_inventory (id, date, invoice_id, product_no, cost_per_unit, quantity, type) VALUES ('', '".date("Y-m-d")."', '$invoice_id', '$product_no', '$cost_per_unit', '$quantity', '$inv_type')") or die(mysql_error());
			}
                    }
                }
            }
            elseif($type=="salary")
            {
                mysql_query("INSERT INTO salaries (id, employee_id, invoice_id, month, year) VALUES ('', '".$_POST['select_employee']."', '$invoice_id', '".$_POST['salmonth']."', '".$_POST['salyear']."')") or die(mysql_error());
            }
            elseif($type=="parts")
            {
                if($_POST['pnos']>0) $pnos=$_POST['pnos'];
                else $pnos=1;
                if($_POST['parts_name']!="") $pname=mysql_real_escape_string($_POST['parts_name']);
                else $pname=mysql_real_escape_string($_POST['parts_name_t']);
                if($total>0) $price=$total/$pnos;

                $i=0;
                while($i<$pnos)
                {
                    $i++;
                    $partsin=mysql_query("INSERT INTO parts_inventory (`id`, `invoice_id`, `business`, `parts_name`, `price_per_unit`) VALUES ('', '$invoice_id', '$business', '$pname', '$price')");
                }
                set_flash_message("নতুন পার্টস সংযুক্ত হয়েছে", 1);
                header("location:".BASE."/parts/inventory/".$business);
                die();
            }

            set_flash_message("লেনদেন সফলভাবে সম্পন্ন হয়েছে", 1);
            header("location:".BASE."/invoices/".$business."/view/".$invoice_id);
        }
        elseif($_REQUEST['process']=="raw_processing")
        {
            $i=0;
            $flag=0;
            $business=$_POST['business'];
            $invoice_id=$_POST['invoice_id'];
            $inventory_id=$_POST['inventory_id'];	    

            while($i<=4)
            {
                $i++;
                $product_no=$_POST['product'.$i];
                $quantity=$_POST['quantity'.$i];
                if($product_no=="") $product_no=0;
                if($quantity=="") $quantity=0;

                if(($product_no>0)&&($quantity>0))
                {
                    mysql_query("INSERT INTO product_inventory (id, date, invoice_id, product_no, quantity, type, parent, status) VALUES ('', '".date("Y-m-d")."', '$invoice_id', '$product_no', '$quantity', 'credit', '$inventory_id', 'final')") or die(mysql_error());
                    $flag++;
                }
            }

	    //how much is processed
	    $pqs=mysql_query("SELECT SUM(quantity) as quan FROM product_inventory WHERE parent='$inventory_id' AND status='taken'") or die(mysql_error());
	    $pqsarr=mysql_fetch_array($pqs);	
	    $taken=$pqsarr['quan'];
	    if($taken+$_POST['proc_quan']<$_POST['inv_tot_quan']) $flag=0;
	    if($taken+$_POST['proc_quan']>$_POST['inv_tot_quan']) $now_taken=$_POST['inv_tot_quan']-$taken;
	    if(!isset($now_taken)) $now_taken=$_POST['proc_quan'];
	    mysql_query("INSERT INTO product_inventory (id, date, invoice_id, product_no, quantity, type, parent, status) VALUES ('', '".date("Y-m-d")."', '$invoice_id', '$product_no', '$now_taken', 'debit', '$inventory_id', 'taken')") or die(mysql_error());

            if($flag>0) mysql_query("UPDATE product_inventory SET status='processed' WHERE id='$inventory_id'");

	    //if processing cost is added while processing
	    $processing_cost=$_POST['processing_cost'];
	    $ocr=mysql_query("SELECT cost_per_unit, quantity FROM product_inventory WHERE id='$inventory_id'") or die(mysql_error());
	    $ocrarr=mysql_fetch_array($ocr);
	    $processing_cost=$processing_cost+$taken*$ocrarr['cost_per_unit'];
	    if($taken<1) $processing_cost+=$ocrarr['cost_per_unit']*$ocrarr['quantity']; 
	    $new_cpu=$processing_cost/($taken+$now_taken);
	    mysql_query("UPDATE invoices SET total_amount='$processing_cost' WHERE id='$invoice_id'") or die(mysql_error());
	    mysql_query("UPDATE product_inventory SET cost_per_unit='$new_cpu' WHERE id='$inventory_id'") or die(mysql_error()); 

            header("location:".BASE."/invoices/".$business."/process/".$inventory_id);
            die();
        }
        elseif($_REQUEST['process']=="deliver")
        {
            $invoice_id=$_REQUEST['id'];
            $business=$_REQUEST['business'];
	    $flag=1;

	    $pqres=mysql_query("SELECT id, product_no, quantity, status FROM product_inventory WHERE invoice_id='$invoice_id' AND `status`<>'final' AND `status`<>'taken'") or die(mysql_error());

	    while($pqrow=mysql_fetch_array($pqres))
	    {
		$pqros=mysql_query("SELECT SUM(quantity) as quant FROM product_inventory WHERE `parent`='".$pqrow['id']."' AND status='taken'") or die(mysql_error());
		$pqrosarr=mysql_fetch_array($pqros);

		if(($pqrosarr['quant']<$pqrow['quantity'])&&($pqrow['status']!='processed')) $flag=0;
	    }

            if($flag==1) 
	    {
		mysql_query("UPDATE invoices SET status='delivered', date_out='".date("Y-m-d")."' WHERE id='$invoice_id'");
		set_flash_message("লেনদেন সফলভাবে সম্পন্ন হয়েছে", 1);
	    }
	    else set_flash_message("Failed, some products are in processing queue", 0); 
            header("location:".BASE."/invoices/".$business."/queue");
	    die();
        }
	elseif($_REQUEST['process']=="partial_deliver")
	{
	    if($_POST['quantity']<=$_POST['total']) mysql_query("INSERT INTO product_delivery (id, date, invoice_id, product_no, quantity) VALUES ('', '".date('Y-m-d')."', '".$_REQUEST['invoice']."', '".$_REQUEST['product']."', '".$_POST['quantity']."')") or die(mysql_error());

//check if all products are delivered 
            $res=mysql_query("SELECT invoice_id FROM product_inventory WHERE id='".$_REQUEST['inventory']."'") or die(mysql_error());
            $arr=mysql_fetch_array($res);
            $invoice_id=$arr['invoice_id'];          

            $res1=mysql_query("SELECT id, status FROM product_inventory WHERE invoice_id='$invoice_id'") or die(mysql_error());
            $res1_num=mysql_num_rows($res1);
            if($res1_num>0)
            {
                $i=0;
                $j=0;
                while($row1=mysql_fetch_array($res1))
                {
                    if($row1['status']=='processed')
                    {
                        $res2=mysql_query("SELECT product_no, SUM(quantity) as manquan FROM product_inventory WHERE status='final' AND invoice_id='$invoice_id' GROUP BY product_no") or die(mysql_error());
                        $res2_num=mysql_num_rows($res2);
                        $j+=$res2_num;
                        while($row2=mysql_fetch_array($res2))
                        {
                            $prod=$row2['product_no'];
                            $quan=$row2['manquan'];
                            
                            $res3=mysql_query("SELECT SUM(quantity) as delquan FROM product_delivery WHERE invoice_id='$invoice_id' AND product_no='$prod'") or die(mysql_error());
                            $arr3=mysql_fetch_array($res3);
                            $delivered=$arr3['delquan'];
                            if(($delivered==$quan)||($delivered>$quan)) $i++;
                        }                        
                    }
                }
                if(($i>0)&&($i==$j))
                {
                    mysql_query("UPDATE invoices SET status='delivered' WHERE id='$invoice_id'") or die(mysql_error());
                    header("location:".BASE."/invoices/$business/queue");
                    die();
                }
            }

	    header("location:".BASE."/invoices/$business/process/".$_REQUEST['inventory']);
	    die();
	}
        elseif($_REQUEST['process']=="due_payment")
        {
            if(($_POST['invoice_id']!="")&&($_POST['pay_amount']>0))
            {
                $ddres=mysql_query("SELECT business_id FROM invoices WHERE id='".$_POST['invoice_id']."'");
                $ddarr=mysql_fetch_array($ddres);
                $business=$ddarr['business_id'];

                $proc=mysql_query("INSERT INTO transactions (id, date, invoice_id, amount, type) VALUES ('', '".date("Y-m-d")."', '".$_POST['invoice_id']."', '".$_POST['pay_amount']."', '".$_POST['type']."')");
                header("location:".BASE."/invoices/".$business."/view/".$_POST['invoice_id']);
                die();
            }
        }
        elseif($_REQUEST['process']=="approval")
        {
            if($_REQUEST['do']=='approve') $do="";
            else $do=$_REQUEST['do'];
            if((current_user_info("type")=="admin")&&($_REQUEST['invoice']!="")) $result=mysql_query("UPDATE invoices SET status='".$do."' WHERE id='".$_REQUEST['invoice']."'");
            if($result) set_flash_message("চালান সম্পাদনা সম্পন্ন হয়েছে", 1);
            header("location:".BASE."/invoices/".$_REQUEST['business']."/view/".$_REQUEST['invoice']);
            die();
        }
        elseif($_REQUEST['process'])
        {
            display_total_due($_REQUEST['defaulter'], $_REQUEST['type']);
            die();
        }
    }

    heading("", "", "");
    if(isset($break[$start+1]))
    {
        $business=$break[$start+1];
        if((current_user_info("type")=="admin")||(current_user_info("business")==$business))
        {
            if($break[$start+2]=="create")
            {
               $base=BASE;
               $today=date("d-m-Y");
               if(isset($_REQUEST['type']))
               {
                    $type=$_REQUEST['type'];
               }

               $poption="";
               $pres=mysql_query("SELECT party_id, party_name, party_address FROM party_info WHERE business_id='$business'");
               while($prow=mysql_fetch_array($pres))
               {
                   $poption=$poption."<option value='".$prow['party_id']."'>".$prow['party_name'].", ".$prow['party_address']."</option>";
               }

               $boption="";
               $bres=mysql_query("SELECT id, name, description FROM businesses");
               while($brow=mysql_fetch_array($bres))
               {
                   if($brow['id']!=$business) $boption=$boption."<option value='".$brow['id']."'>".$brow['name'].", ".$brow['description']."</option>";
               }

                echo <<<EOT
<form action='$base/invoices/?process=process_invoice' name='invoice' method='post' id='invoice'>
<input type='hidden' name='business' value='$business'>
<input type='hidden' name='type' value='$type'>
<table>
    <tr><td><strong>তারিখ: </strong></td><td><input type='text' name='date' value='$today'></td></tr>
EOT;
                if($type=="banking")
                {
                    $bankres=mysql_query("SELECT id, bank_name, account_no FROM bank_list");
                    echo "<tr><td><strong>Bank Detail: </strong></td><td><select name='select_account'>";
                    while($bankrow=mysql_fetch_array($bankres))
                    {
                        echo "<option value='".$bankrow['id']."'>".$bankrow['bank_name'].", ".aim_num_to_bn($bankrow['account_no'])."</option>";
                    }
                    echo "</select></td></tr>";
                }
                elseif($type=="salary")
                {
                    $empres=mysql_query("SELECT id, name FROM employee_info WHERE business='$business'");
                    echo "<tr><td><strong>Employee: </strong></td><td><select name='select_employee'>";
                    while($emprow=mysql_fetch_array($empres))
                    {
                        echo "<option value='".$emprow['id']."'>".$emprow['name']."</option>";
                    }
                    echo "</select></td></tr>";
                }
                elseif($type=='expense')
                {
                    echo <<<EOT
                    <tr><td><strong>ব্যয়ের খাত</strong></td><td><input type='text' name='expdesc' size='25'></td></tr>
                    <tr><td><strong>মোট ব্যয়</strong>: </td><td><input type='text' name='total_amount' size='3'></td></tr>
                    <tr><td><strong>মোট পরিশোধ</strong>: </td><td><input type='text' name='paid' size='3'>
                    <tr><td></td><td><input type='submit' value='সংরক্ষন করুন'></td></tr>
EOT;
                }
		elseif(isset($_REQUEST['reference']))
		{
//if adding some more products with a previous invoice			
			$ref=$_REQUEST['reference'];
			$refres=mysql_query("SELECT party_info, total_amount FROM invoices WHERE id='$ref'") or die(mysql_error());
			$refarr=mysql_fetch_array($refres);
			display_party_info($refarr['party_info']);
			echo "<input type='hidden' name='reference' value='$ref'>";
			echo "<input type='hidden' name='previous_total_amount' value='".$refarr['total_amount']."'>";
		}
                else
                {
                echo "<input type='hidden' name='previous_total_amount' value='0'>";
                echo <<<EOT
    <tr>
        <td><strong>নাম: </strong></td><td><strong>ঠিকানা: </strong></td><td><strong>ফোন: </strong></td></tr>
    <tr><td><input type='text' name='party_name' class='validate[required]'></td><td><input type='text' name='party_address'></td><td><input type='text' name='party_phone'></td></tr>
    <tr><td><strong>পার্টি তথ্য সংরক্ষন করুন:</strong></td><td><input type='checkbox' name='save_party' checked></td></tr>
    <tr><td>Or, <select name='select_party' onchange="javascript:display_dues(this.value, '$base', 'party'); return false;"><option value=''>পার্টি বাছাই করুন</option>$poption</select></td><td>Or, <select name='select_business' onchange="javascript:display_dues(this.value, '$base', 'self'); return false;"><option value=''>বাণিজ্য বাছাই করুন</option>$boption</td></tr>
</table>

EOT;
                }

                if(($type=='purchase')||($type=='sales')||($type=='service'))
                {
                    if($type!='service') $header_text_per_unit="<th>একক প্রতি মূল্য</th>";
$header_text_per_unit=aim_num_to_bn($header_text_per_unit);
                    echo <<<EOT
   <table>
        <tr width='100%' align='left'><th width='10%'>ক্রম&nbsp;&nbsp;</th><th width='40%'>পণ্য</th>$header_text_per_unit<th width='10%'>পরিমাণ</th><th width='10%'>বস্তার মূল্য</th><th width='10%'>মূল্য</th></tr>

EOT;
                    $i=0;
                    while($i<5)
                    {
                        $i++;
                        echo "<tr>";
                            echo "<td>".aim_num_to_bn($i)."</td>";
                            if($type=='purchase') echo "<td><select name='product_no".$i."' onchange=\"javascript:refresh_other_pfields($i); return false;\">".product_list_options($business)."</select></td>";
                            elseif($type=='sales') echo "<td><select name='product_no".$i."' onchange=\"javascript:refresh_other_fields('".BASE."', $i, $business); return false;\">".product_list_options($business)."</select></td>";
                            elseif($type=='service') echo "<td><select name='product_no".$i."'>".product_list_options($business)."</select></td>";
                            if($type=='service') echo "<input type='hidden' name='per_unit".$i."' id='pper_unit".$i."'>";
                            else echo "<td id='per_unitf_".$i."'><input type='text' name='per_unit".$i."' id='pper_unit".$i."' size='3'></td>";
                            echo "<td id='qtyf_$i'><input type='text' name='qty".$i."' id='pqty".$i."' size='3' onkeyup=\"javascript:calculate_single_ptotal($i); return false;\">";
                            echo "<td id='others_$i'><input type='text' name='others".$i."' id='others".$i."' size='3' value='0' onkeyup=\"javascript:calculate_single_ptotal($i); return false;  \">";
/*
  "Bosta othoba onnanno khorocher jonne ekta field banan. calculate_single_ptotal function (js) e ei field jog kore den. ar ei field e o onkeyup e same function orthat calculate_single_ptotal call kore den, jate eita change hoileo abar puro hishab kore nei
*/
                            echo "<td id='tpr_$i'><input type='text' name='price".$i."' id='pprice".$i."' size='5' onkeyup=\"javascript:remove_total(); return false;\">";
                        echo "</tr>";
                    }
                    echo "<tr><td></td><td></td><td></td><td><a class='anchor' href='#' onclick=\"javascript:grand_total('".BASE."'); return false;\"><strong>মোট</strong></a></td><td id='gtotal'></td></tr>";
                    echo "<tr><td></td><td></td><td></td><td>মোট পরিশোধ:</td><td><input type='text' name='paid' size='4'></td></tr>";
                    echo "<tr><td></td><td></td><td></td><td id='procbut'></td></tr>";
                }
                elseif(($type=="payment")||($type=="receive"))
                {
                    echo "<h3>নগদ পরিশোধ <span id='duedisp'></span></h3>";
                    echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>পরিশোধের পরিমাণ</strong>: <input type='text' name='paid'> <input type='submit' value='সংরক্ষন করুন'>";
                }
                elseif($type=='parts')
                {
                    echo "<h3>পার্টস ক্রয় </h3>";
                    echo "<table>";

                        echo "<tr><strong>পার্টসের নাম</strong></td></tr>";

                        echo "<tr>";
                            echo "<td><select name='parts_name'><option value=''>পার্টস বাছাই</option>";
                                $partsres=mysql_query("SELECT DISTINCT parts_name FROM parts_inventory");
                                while($partsrow=mysql_fetch_array($partsres))
                                {
                                    echo "<option value=\"".$partsrow['parts_name']."\">".$partsrow['parts_name']."</option>";
                                }
                            echo "</td>";

                        echo "<td>তালিকায় না থাকলে<br><input type='text' name='parts_name_t'></td></tr>";
                        echo "<tr><td><strong>পার্টস সংখ্যা</strong></td></tr>";
                        echo "<tr><td><input type='text' name='pnos'></td></tr>";
                        echo "<tr><td><strong>মোট দাম</strong></td></tr>";
                        echo "<tr><td><input type='text' name='paid'></td></tr>";
                        echo "<tr><td><input type='submit' value='সংরক্ষন করুন'></td></tr>";
                    echo "</table>";
                }
                elseif($type=='banking')
                {
                    echo "<tr>";
                        echo "<td>ধরন: <br><select name='procedure_type'><option value='Cash'>নগদ</option><option value='Check'>চেক</option></select></td>";
                        echo "<td>বর্ণনা: <br><select name='transaction_type'><option value='Savings'>টাকা সঞ্চয়</option><option value='Withdrawl'>টাকা উঠানো</option></select></td>";
			echo "<td>চেক/রশিদ নং: <br><input type='text' name='info'></td>";
                        echo "<td>পরিমাণ: <br><input type='text' name='transaction_amount'></td>";
                    echo "</tr>";
                    echo "<tr><td></td><td></td><td></td><td><input type='submit' value='সংরক্ষণ করুন'></td></tr>";
                }
                elseif($type=="salary")
                {
                    echo "<tr><td><strong>মাস: </strong></td><td><select name='salmonth'><option value='January'>জানুয়ারী</option><option value='February'>ফেব্রুয়ারী</option><option value='March'>মার্চ</option><option value='April'>এপ্রিল</option><option value='May'>মে</option><option value='June'>জুন</option><option value='July'>জুলাই</option><option value='August'>আগষ্ট</option><option value='September'>সেপ্টেম্বর</option><option value='October'>অক্টোবর</option><option value='November'>নবেম্বর</option><option value='December'>ডিসেম্বর</option></select></td></tr>";
                    echo "<tr><td><strong>বছর: </strong></td><td><select name='salyear'><option value='2012'>2012</option><option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><option value='2019'>2019</option><option value='2020'>2020</option></select></td></tr>";
                    echo "<tr><td><strong>বেতন: </strong></td><td><input type='text' name='paid'></td></tr>";
                    echo "<tr><td></td><td><input type='submit' value='সংরক্ষণ করুন'></td></tr>";
                }
                echo <<<EOT
</table>
</form>
EOT;
            }
            elseif($break[$start+2]=="queue")
            {
                $result=mysql_query("SELECT id, party_info, date_in, status FROM invoices WHERE business_id='$business' AND invoice_type='service' AND status=''");
                echo "<table border='1'>";
                echo "<tr><th>তারিখ</th><th>গ্রাহক</th><th>পণ্য</th><th>অবস্থা</th><th>বিকল্প</th><th>Detail</th></tr>";
                while($row=mysql_fetch_array($result))
                {
                    $invoice_id=$row['id'];
                    echo "<tr>";			
                        echo "<td>".aim_num_to_bn($row['date_in'])."</td>";
                        echo "<td>"; display_party_info($row['party_info']); echo "</td>";
                        echo "<td></td><td>".$row['status']."</td>";
                        echo "<td><a class='anchor' href='".BASE."/invoices/?process=deliver&business=".$business."&id=".$row['id']."'><strong><i>প্রদান</i></strong></a></td>";
			echo "<td><a class='anchor' href='".BASE."/invoices/".$business."/view/".$invoice_id."'>Detail</a></td>";
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
            elseif($break[$start+2]=="pending")
            {
                $result=mysql_query("SELECT id, invoice_type, party_info, date_in, status FROM invoices WHERE business_id='$business' AND status='pending'");
                echo "<table border='1'>";
                echo "<tr><th>তারিখ</th><th>Type</th><th>গ্রাহক</th><th>পণ্য</th><th>অবস্থা</th><th>বিকল্প</th></tr>";
                while($row=mysql_fetch_array($result))
                {
                    $invoice_id=$row['id'];
                    echo "<tr>";
                        if($row['date_in']!='0000-00-00') echo "<td>".aim_num_to_bn($row['date_in'])."</td>";
                        elseif($row['date_out']!='0000-00-00') echo "<td>".aim_num_to_bn($row['date_out'])."</td>";
                        
                        if($row['invoice_type']=='parts') echo "<td>পার্টস ক্রয়</td>";
                        elseif($row['invoice_type']=='service') echo "<td>কাজ গ্রহন</td>";
                        elseif($row['invoice_type']=='purchase') echo "<td>ক্রয়</td>";
                        elseif($row['invoice_type']=='sales') echo "<td>বিক্রয়</td>";
                        elseif($row['invoice_type']=='payment') echo "<td>টাকা প্রদান</td>";
                        elseif($row['invoice_type']=='receive') echo "<td>টাকা গ্রহন</td>";
                        else echo "<td>".$row['invoice_type']."</td>";
                        
                        echo "<td>"; display_party_info($row['party_info']); echo "</td>";
                        echo "<td></td><td>".$row['status']."</td>";
                        echo "<td><a class='anchor' href='".BASE."/invoices/".$business."/view/".$row['id']."'><strong><i>অনুমোদন</i></strong></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            elseif($break[$start+2]=="process")
            {
                $inventory_id=$break[$start+3];
                $result=mysql_query("SELECT product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.status, product_list.product_name, invoices.party_info, invoices.id FROM product_inventory, product_list, invoices WHERE product_inventory.product_no=product_list.id AND product_inventory.invoice_id=invoices.id AND product_inventory.id='$inventory_id' AND product_inventory.status<>'final'") or die(mysql_error());
                $arr=mysql_fetch_array($result);
                $invoice_id=$arr['id'];

		//check how much product is taken already
		$pqs=mysql_query("SELECT SUM(quantity) as quan FROM product_inventory WHERE parent='$inventory_id' AND status='taken'") or die(mysql_error());
	        $pqsarr=mysql_fetch_array($pqs);	
	        $taken=$pqsarr['quan'];

                echo "<table>";
                        echo "<tr><td><strong>গ্রাহক: </strong></td><td>"; display_party_info($arr['party_info']); echo "</td></tr>";
                        echo "<tr><td><strong>কাঁচা মাল: </strong></td><td>".$arr['product_name']." (".$arr['quantity']." Units)</td>";
			if($taken>0) echo "<tr><td><strong>Already Processed: </strong></td><td>".$arr['product_name']." (".$taken." Units)</td>";

                        echo "<tr><td><strong>প্রক্রিয়াজাত পণ্য:</strong></td></tr>";
                if($arr['status']!="processed")
                {
                    echo "<form action='".BASE."/invoices/?process=raw_processing' method='post'>";
                        echo "<input type='hidden' name='business' value='$business'>";
                        echo "<input type='hidden' name='invoice_id' value='$invoice_id'>";
                        echo "<input type='hidden' name='inventory_id' value='$inventory_id'>";
			echo "<input type='hidden' name='inv_tot_quan' value='".$arr['quantity']."'>";
			
			echo "<tr><td><strong>Processing Quantity:</strong></td><td><input type='text' name='proc_quan' size='3'>একক</td></tr>";
			echo "<tr><td><strong>Processing Cost:</strong></td><td><input type='text' name='processing_cost' size='3'>একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ১: <select name='product1'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity1' size='3'>একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ২: <select name='product2'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity2' size='3'>একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ৩: <select name='product3'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity3' size='3'> একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ৪: <select name='product4'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity4' size='3'>একক</td></tr>";
                        echo "<tr><td></td><td><input type='submit' value='সংরক্ষণ করুন'></td></tr>";
                    echo "</form>";
                }
                
                    $que="SELECT product_inventory.id, product_inventory.invoice_id, product_inventory.product_no, SUM(product_inventory.quantity) as quantity, product_list.product_name FROM product_list, product_inventory WHERE status='final' AND parent='$inventory_id' AND product_inventory.product_no=product_list.id GROUP BY product_inventory.product_no";
                    $res=mysql_query($que);
		    
 		    if(mysql_num_rows($res)>0) echo "<tr><th>Product Name</th><th>Processed Quantity</th><th>Delivered Quantity</th><th>Left Quantity</th><th>Deliver</th></tr>";
                    while($frow=mysql_fetch_array($res))
                    {
                        if($frow['quantity']>0) 
			{
			//now we will see how much of processed products already delivered to client
			    $prod_no=$frow['product_no'];
			    $inv_id=$frow['invoice_id'];
			    $shifres=mysql_query("SELECT SUM(quantity) as quantity FROM product_delivery WHERE invoice_id='$inv_id' AND product_no='$prod_no'") or die(mysql_error());
			    $shifarr=mysql_fetch_array($shifres);
			    
			    echo "<tr><td><strong>".$frow['product_name']."</strong></td><td>".aim_num_to_bn($frow['quantity'])." Units</td><td>".aim_num_to_bn($shifarr['quantity'])." Units</td><td>".aim_num_to_bn(($frow['quantity']-$shifarr['quantity']))." Units</td><td><form action='".BASE."/invoices/?process=partial_deliver&invoice=$inv_id&product=$prod_no&business=$business&inventory=$inventory_id' method='post'><input type='hidden' name='total' value='".($frow['quantity']-$shifarr['quantity'])."'><input type='text' name='quantity' size='2'><input type='submit' value='Deliver'></form></tr>";
			}
                    }
                
                echo "</table>";

		echo "<h3>Processing List</h3>";
                $processings=mysql_query("SELECT product_inventory.date, product_inventory.quantity, product_list.product_name FROM product_inventory, product_list WHERE product_inventory.invoice_id='$invoice_id' AND status='final' AND product_list.id=product_inventory.product_no") or die(mysql_error());
                if(mysql_num_rows($processings)>0)
                {
                    echo "<table>";
                    echo "<tr><th>Date</th><th>Name</th><th>Quantity</th></tr>";
                    while($row=mysql_fetch_array($processings))
                    {
                        echo "<tr>";
                            echo "<td>".$row['date']."</td>";
                            echo "<td>".$row['product_name']."</td>";
                            echo "<td>".$row['quantity']."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }		

		echo "<h3>Delivery List</h3>";
                $delivery=mysql_query("SELECT product_delivery.date, product_delivery.quantity, product_list.product_name FROM product_delivery, product_list WHERE product_delivery.invoice_id='$invoice_id' AND product_delivery.product_no=product_list.id") or die(mysql_error());
                if(mysql_num_rows($delivery)>0)
                {
                    echo "<table>";
                    echo "<tr><th>Date</th><th>Name</th><th>Quantity</th></tr>";
	            while($row=mysql_fetch_array($delivery))
                    {
                        echo "<tr>";
			    echo "<td>".$row['date']."</td>";
			    echo "<td>".$row['product_name']."</td>";
                            echo "<td>".$row['quantity']."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else echo "No Product delivered yet";
		
            }
            elseif($break[$start+2]=="view")
            {
                $invoice_id=$break[$start+3];
                //now here display report on a specific invoice
                invoice_report($invoice_id);
            }
            else
            {
                $result=mysql_query("SELECT id, name, description, type FROM businesses WHERE id='$business'") or die(mysql_error());
                $arr=mysql_fetch_array($result);

                if($arr['id']==5)
                {
                    ?>
                    <div style="float: left; padding:10px; margin-left:200px;">
                    <?php                    
echo "<a  class='anchor' href='".BASE."/invoices/$business/create/?type=banking'>ব্যাংকিং<a>";
echo "<br>";
echo "<a class='anchor' href='".BASE."/invoices/".$business."/create/?type=expense'>ব্যয়</a>";
echo "<br>";
echo "<a class='anchor' href='".BASE."/reports/sales'>Sales Report</a>";
echo "<br>";
?>
</div>
<?php
                }
                elseif(trim($arr['type'])=='mill')
                {
                    ?>
                    <div style="float: left; padding:10px; margin-left:200px;">
                    <?php
                    echo "<a class='anchor' href='".BASE."/invoices/".$business."/create/?type=service'>কাজ গ্রহণ</a>";
                    echo "<br><a class='anchor' href='".BASE."/invoices/".$business."/queue/'>ক্রম</a>";
                
	            echo "<br><a class='anchor' href='".BASE."/invoices/".$business."/pending/'>অননুমোদিত কাজ</a>";
                echo "<br>";
 echo "<a class='anchor' href='".BASE."/reports/receivables/?business=$business'>পাওনা তালিকা</a>";
 echo "<br>"; 
 echo "<a class='anchor' href='".BASE."/reports/payables/?business=$business'>দেনা তালিকা</a>";
echo "<br>";
?>
 </div>
 <div style="float: left; padding:10px;">
 <?php
echo "<a class='anchor' href='".BASE."/invoices/".$business."/create/?type=parts'>পার্টস ক্রয়</a>";
 echo "<br>";
echo "<a class='anchor' href='".BASE."/parts/inventory/$business'>পার্টস তালিকা</a>";
 echo "<br>";
 echo "<a  class='anchor' href='".BASE."/invoices/$business/create/?type=banking'>ব্যাংকিং<a>";
echo "<br>";
echo "<a class='anchor' href='".BASE."/invoices/".$business."/create/?type=expense'>ব্যয়</a>";
echo "<br>";
echo "<a class='anchor' href='".BASE."/reports/stock/'>স্টক</a>";
?>
</div>
<?php
             }
                elseif(trim($arr['type'])=="trading")
                {
                    ?>
                    <div style="float: left; padding:10px; margin-left:200px;">
                    <?php
                    echo "<a class='anchor' href='".BASE."/invoices/".$business."/create/?type=sales'>বিক্রয়</a>";
                    echo "<br><a class='anchor' href='".BASE."/invoices/".$business."/create/?type=purchase'>ক্রয়</a>";
		    echo "<br><a class='anchor' href='".BASE."/invoices/".$business."/pending/'>অননুমোদিত কাজ</a>";     
		    echo "<br>";        
  echo "<a class='anchor' href='".BASE."/reports/receivables/?business=$business'>পাওনা তালিকা</a>";
 echo "<br>"; 
 echo "<a class='anchor' href='".BASE."/reports/payables/?business=$business'>দেনা তালিকা</a>";
echo "<br>";
 ?>
            </div>
            <div style="float: left; padding:10px;">
            <?php
echo "<a class='anchor' href='".BASE."/invoices/$business/create/?type=payment'>দেনা পরিশোধ</a>"; 
echo "<br><a class='anchor' href='".BASE."/invoices/$business/create/?type=banking'>ব্যাংকিং<a>";
echo "<br><a class='anchor' href='".BASE."/milltrade/invoices/$business/create/?type=expense'>ব্যয়</a>";
echo "<br><a class='anchor' href='".BASE."/stock'>স্টক</a>";
?>
</div>
<?php
                }
            }
        }
        else echo "প্রবেশাধিকার সংরক্ষিত";
    }
    footing();
}
else header("location:".BASE."/login");

function product_list_options($business)
{
    $result=mysql_query("SELECT price_list.product, product_list.product_name FROM price_list, product_list WHERE price_list.product=product_list.id AND business='$business' AND price>0");
    $options="<option value=''>পছন্দ করুন</option>";
    while($row=mysql_fetch_array($result))
    {
        $options=$options."<option value='".$row['product']."'>".$row['product_name']."</option>";
    }
    return $options;
}

function invoice_report($invoice_id)
{
    $Q=mysql_query("select * from invoices where id='$invoice_id' AND status<>'deny'");
    if(mysql_num_rows($Q)>0){
    $row=mysql_fetch_array($Q);
    $type=$row['invoice_type'];
    $business=$row['business_id'];

    $ttres=mysql_query("SELECT type, amount FROM `transactions` WHERE `invoice_id`='$invoice_id'") or die(mysql_error());
    
    $camount=0;
    $damount=0;
    while($ttrow=mysql_fetch_array($ttres))
    {
        if($ttrow['type']=='credit') $camount=$camount+$ttrow['amount'];
        elseif($ttrow['type']=='debit') $damount=$damount+$ttrow['amount'];
    }

    if($row['status']=='pending')
    {
        echo "<h3><font color='red'>অননুমোদিত কাজ ";
        if(current_user_info('type')=='admin') 
        {
            echo "<a href='".BASE."/invoices/".$row['business_id']."/view/".$invoice_id."/?process=approval&do=approve&invoice=$invoice_id&business=".$row['business_id']."'>[ কাজ অনুমোদন  ]</a>";
            echo "<a href='".BASE."/invoices/".$row['business_id']."/view/".$invoice_id."/?process=approval&do=deny&invoice=$invoice_id&business=".$row['business_id']."'>[ কাজ বাতিল ]</a>";            
        }

        echo "</font></h3>";
    }
    else
    {
        if(current_user_info('type')=='admin') echo "<a href='".BASE."/invoices/".$row['business_id']."/view/".$invoice_id."/?process=approval&do=deny&invoice=$invoice_id&business=".$row['business_id']."'>[ কাজ বাতিল ]</a>";
    }
    ?>
    <table>
        <tr>
            <td>চালানের ধরণ</td>
            <td>:</td>
            <td><?php echo $type; ?></td>
        </tr>
        <?php if($row['date_in']!='0000-00-00'){ ?>
        <tr>
            <td>প্রবেশের তারিখ</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['date_in']); ?></td>
        </tr>
        <?php } elseif($row['date_out']!='0000-00-00'){?>
        <tr>
            <td>বের হওয়ার তারিখ</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['date_out']); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td>চালান আইডি</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['id']); ?></td>
        </tr>

        <tr>
            <td>কাষ্টমার তথ্য</td>
            <td>:</td>
            <td>
            <?php
                    display_party_info($row['party_info']);
            ?>
            </td>
        </tr>
    </table>

    <?php
        if($type=="salary")
        {
            $ssres=mysql_query("SELECT salaries.month, salaries.year, employee_info.name, employee_info.designation, businesses.name as business_name FROM salaries, businesses, employee_info WHERE employee_info.business=businesses.id AND salaries.employee_id=employee_info.id AND salaries.invoice_id='$invoice_id'") or die(mysql_error());
            $ssarr=mysql_fetch_array($ssres);
            echo "<br>";
            echo "<table>";
                echo "<tr><th>বর্ণনা</th><th>পরিমাণ</th></tr>";
                echo "<tr><td> ".$ssarr['name']."র বেতন</td><td>".aim_num_to_bn($row['total_amount'])."</td></tr>";
            echo "</table>";


        }
        elseif($type=="banking")
        {
            $bbres=mysql_query("SELECT type, amount FROM `transactions` WHERE `invoice_id`='$invoice_id'") or die(mysql_error());
            $bbarr=mysql_fetch_array($bbres);
             echo "<br>";
            echo "<table>";
                echo "<tr><th>বর্ণনা</th><th>পরিমাণ</th></tr>";
                echo "<tr><td>";
                if($bbarr['type']=="debit") echo "একাউন্টে জমা হয়েছে";
                elseif($bbarr['type']=="credit") echo "একাউন্ট থেকে উঠানো হয়েছে";
                echo "</td><td>".aim_num_to_bn($bbarr['amount'])."</td></tr>";
            echo "</table>";
        }
        elseif($type=="expense")
        {
            $due=$row['total_amount']+$camount-$damount;
            echo "<table>";
                echo "<tr><th>বর্ণনা</th><th>মোট</th><th>পরিশোধ</th><th>বকেয়া</th></tr>";
                echo "<tr><td>";
                    display_party_info($row['party_info']);
                echo "</td><td>".aim_num_to_bn($row['total_amount'])."</td>";
                echo "<td>".aim_num_to_bn($damount)."</td>";
                echo "<td>".aim_num_to_bn($due)."</td></tr>";
            echo "</table>";
        }
        else
        {
    ?>
    <table>
        <tr>
            <th>ক্রম</th>
            <th>পণ্যের নাম</th>
            <th>একক প্রতি খরচ</th>
            <th>পরিমাণ</th>
            <th>মোট</th>
            <th>অবস্থা</th>
        </tr>
        <?php
        $grand_total=0;
        $sl=0;
        $Q=mysql_query("select pl.product_name,pi.cost_per_unit,pi.quantity,pi.status
                from product_inventory as pi
                left join product_list as pl on pl.id=pi.product_no
                where invoice_id='$invoice_id' AND pi.status<>'final' AND pi.status<>'taken'") or die(mysql_error());

        while($row=mysql_fetch_array($Q))
        {
            $total=0;
        ?>
        <tr>
            <td><?php echo aim_num_to_bn(++$sl); ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo aim_num_to_bn($row['cost_per_unit']); ?></td>
            <td><?php echo aim_num_to_bn($row['quantity']); ?></td>
            <th><?php echo aim_num_to_bn($total=$row['cost_per_unit']*$row['quantity']); ?></th>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php
        $grand_total+=$total;
        }
        ?>
        <tr>
            <th colspan="4" align="center">Total</th>
            <th><?php echo aim_num_to_bn($grand_total); ?></th>
            <th></th>
        </tr>
        <?php            
            if($camount>0)
            {
        ?>
        <tr>
            <th colspan="4" align="center">গ্রহন হয়েছে</th>
            <th><?php echo aim_num_to_bn($camount); ?></th>
            <th></th>
        </tr>
        <?php
            }
            
            if($damount>0)
            {
        ?>
        <tr>
            <th colspan="4" align="center">পরিশোধ</th>
            <th><?php echo aim_num_to_bn($damount) ?></th>
            <th></th>
        </tr>
        <?php
            }

            if($type=="purchase") $reception=$damount-$camount;
            elseif(($type=="sales")||($type=="service")) $reception=$camount-$damount;
        ?>
        <tr>
            <th colspan="4" align="center">বকেয়া</th>
            <th><?php echo aim_num_to_bn($due=$grand_total-$reception); ?></th>
            <th></th>
        </tr>
    </table>
<?php
        }

        if(($type=='purchase')||($type=='expense'))
        {
            if($due<0) echo "<a href='#' onclick=\"javascript:appear_due_form(".$invoice_id.", 'credit', '".BASE."'); return false;\">বকেয়া গ্রহন</a><br><div id='dform'></div>";
            if($due>0) echo "<a href='#' onclick=\"javascript:appear_due_form(".$invoice_id.", 'debit', '".BASE."'); return false;\">বকেয়া পরিশোধ</a><br><div id='dform'></div>";	    
        }
        else
        {
            if($due>0) echo "<a href='#' onclick=\"javascript:appear_due_form(".$invoice_id.", 'credit', '".BASE."'); return false;\">বকেয়া গ্রহন</a><br><div id='dform'></div>";
            if($due<0) echo "<a href='#' onclick=\"javascript:appear_due_form(".$invoice_id.", 'debit', '".BASE."'); return false;\">বকেয়া পরিশোধ</a><br><div id='dform'></div>";
        }
	if(($type=='service')||($type=='sales')||($type=='puchase')) echo "<a href='".BASE."/invoices/".$business."/create/?type=".$type."&reference=".$invoice_id."'>Add More</a>";
    }
    else echo "কিছুই পাওয়া যায়নি";
}

function display_total_due($client, $type)
{
    if($type=='self') $stock_type="business";
    else $stock_type="client";

    if($type!="") $party_info="{".$type.":".$client."}";
    else $party_info=$client;

//if($type!='self')
//{            
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
            	?> ( মোট বকেয়া: <?php echo aim_num_to_bn($total_due); ?>  টাকা ) 
<?php
//}

}
?>
