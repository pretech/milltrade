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
        elseif($_REQUEST['process']=="grand_total")
        {
            $i=0;
            $total=0;
            while($i<=15)
            {
                $total=$total+$_REQUEST['price'.$i];
                $i++;
            }
            echo $total;
            die();
        }
        elseif($_REQUEST['process']=='process_invoice')
        {
            $business=$_POST['business'];
            $type=$_POST['type'];
            $party=mysql_real_escape_string($_POST['party_info']);
            $dbr=explode("-", $_POST['date']);
            $date=$dbr[2]."-".$dbr[1]."-".$dbr[0];
            if(($type=="purchase")||($type=="service")) $date_in=$date;
            elseif($type=="sales") $date_out=$date;
            //product log
            $i=0;
            $total=0;
            while($i<5)
            {
                $i++;
                $total=$total+$_POST['price'.$i];
            }

            if(($business!="")&&($type!="")) mysql_query("INSERT INTO invoices (id, business_id, invoice_type, party_info, date_in, date_out, total_amount) VALUES ('', '$business', '$type', '$party', '$date_in', '$date_out', '$total')") or die(mysql_error());
            $result=mysql_query("SELECT id FROM invoices WHERE business_id='$business' AND (date_in='$date_in' OR date_out='$date_out') ORDER BY id DESC LIMIT 1") or die(mysql_error());
            $arr=mysql_fetch_array($result);
            $invoice_id=$arr['id'];
            //echo $invoice_id; die();
            if(($type=="purchase")||($type=="service"))
            {
                $inv_type="credit";
            }
            else $inv_type="debit";

            if(($type=="saving")||($type=="purchase"))
            {
                $tr_type="credit";
            }
            else $tr_type="debit";

            //product log
            $i=0;
            while($i<5)
            {
                $i++;
                $product_no=$_POST['product_no'.$i];
                $quantity=$_POST['qty'.$i];
                
                if($_POST['price'.$i]>0)
                {
                    $cost_per_unit=$_POST['price'.$i]/$_POST['qty'.$i];
                    mysql_query("INSERT INTO product_inventory (id, invoice_id, product_no, cost_per_unit, quantity, type) VALUES ('', '$invoice_id', '$product_no', '$cost_per_unit', '$quantity', '$inv_type')") or die(mysql_error());
                }
            }
            //transaction (payment log)
            mysql_query("INSERT INTO transactions (id, invoice_id, amount, type) VALUES ('', '$invoice_id', '$total', '$tr_type')") or(die(mysql_error()));
            set_flash_message("Transaction Completed Successfully", 1);
            header("location:".BASE."/invoices/".$business);
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
                    mysql_query("INSERT INTO product_inventory (id, invoice_id, product_no, quantity, type, parent, status) VALUES ('', '$invoice_id', '$product_no', '$quantity', 'credit', '$inventory_id', 'final')") or die(mysql_error());
                    $flag++;
                }
            }
            if($flag>0) mysql_query("UPDATE product_inventory SET status='processed' WHERE id='$inventory_id'");
            header("location:".BASE."/invoices/".$business."/process/".$inventory_id);
            die();
        }
        elseif($_REQUEST['process']=="deliver")
        {
            $invoice_id=$_REQUEST['id'];
            $business=$_REQUEST['business'];
            mysql_query("UPDATE invoices SET status='delivered', date_out='".date("Y-m-d")."' WHERE id='$invoice_id'");
            header("location:".BASE."/invoices/".$business."/queue");
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
                echo <<<EOT
<form action='$base/invoices/?process=process_invoice' name='invoice' method='post'>
<input type='hidden' name='business' value='$business'>
<input type='hidden' name='type' value='$type'>
<table>
    <tr><td><strong>তারিখ: </strong></td><td><input type='text' name='date' value='$today'></td></tr>
    <tr><td><strong>গ্রাহকের নাম ও ঠিকানা: </strong></td><td><input type='text' name='party_info'></td></tr>
</table>

<table>
    <tr width='100%' align='left'><th width='10%'>Serial&nbsp;&nbsp;</th><th width='40%'>জিনিস</th><th width='10%'>পরিমাণ</th><th width='10%'>মূল্য</th></tr>
EOT;
                
                if(($type=='service')||($type=='sales')||($type=='purchase'))
                {
                    $i=0;
                    while($i<5)
                    {
                        $i++;
                        echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td><select name='product_no".$i."' onchange=\"javascript:refresh_other_fields('".BASE."', $i); return false;\">".product_list_options($business)."</select></td>";
                            echo "<td id='qtyf_$i'><input type='text' name='qty".$i."' size='3' onkeyup=\"javascript:calculate_single_total('".BASE."', this.value, $i); return false;\">";
                            echo "<td id='tpr_$i'><input type='text' name='price".$i."' size='5' onkeyup=\"javascript:remove_total(); return false;\">";
                        echo "</tr>";                        
                    }
                    echo "<tr><td></td><td></td><td><a href='#' onclick=\"javascript:grand_total('".BASE."'); return false;\"><strong>মোট</strong></a></td><td id='gtotal'></td></tr>";
                    echo "<tr><td></td><td></td><td></td><td id='procbut'></td></tr>";
                }
                echo <<<EOT
</table>
</form>
EOT;
            }
            elseif($break[$start+2]=="queue")
            {
                $result=mysql_query("SELECT id, party_info, date_in, status FROM invoices WHERE business_id='$business'");
                echo "<table border='1'>";
                echo "<tr><th>তারিখ</th><th>গ্রাহক</th><th>পণ্য</th><th>অবস্থা</th><th>বিকল্প</th></tr>";
                while($row=mysql_fetch_array($result))
                {
                    $invoice_id=$row['id'];
                    echo "<tr>";
                        echo "<td>".$arr['date_in']."</td>";
                        echo "<td>".$row['party_info']."</td>";
                        echo "<td></td><td>".$row['status']."</td>";
                        echo "<td><a href='".BASE."/invoices/?process=deliver&business=".$business."&id=".$row['id']."'><strong><i>প্রদান</i></strong></a></td>";
                    echo "</tr>";
                    $presult=mysql_query("SELECT product_inventory.id, product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.status, product_list.product_name FROM product_inventory, product_list WHERE product_inventory.product_no=product_list.id AND invoice_id='$invoice_id' AND status<>'final'");
                    while($prow=mysql_fetch_array($presult))
                    {
                        $inventory_id=$prow['id'];
                        if($prow['status']=="") $status="In Queue";
                        else $status=$prow['status'];
                        
                        echo "<tr>";
                            echo "<td></td><td></td>";
                            echo "<td><a href='".BASE."/invoices/".$business."/process/".$inventory_id."'>".$prow['product_name']."</a></td>";
                            echo "<td>".$status."</td>";
                            echo "<td></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            }
            elseif($break[$start+2]=="process")
            {
                $inventory_id=$break[$start+3];
                $result=mysql_query("SELECT product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.status, product_list.product_name, invoices.party_info, invoices.id FROM product_inventory, product_list, invoices WHERE product_inventory.product_no=product_list.id AND product_inventory.invoice_id=invoices.id AND product_inventory.id='$inventory_id' AND product_inventory.status<>'final'") or die(mysql_error());
                $arr=mysql_fetch_array($result);
                $invoice_id=$arr['id'];
                               
                echo "<table>";
                        echo "<tr><td><strong>গ্রাহক: </strong></td><td>".$arr['party_info']."</td></tr>";
                        echo "<tr><td><strong>কাঁচা মাল: </strong></td><td>".$arr['product_name']."</td>";
                        echo "<tr><td><strong>প্রক্রিয়াজান পণ্য:</strong></td></tr>";
                if($arr['status']!="processed")
                {                    
                    echo "<form action='".BASE."/invoices/?process=raw_processing' method='post'>";
                        echo "<input type='hidden' name='business' value='$business'>";
                        echo "<input type='hidden' name='invoice_id' value='$invoice_id'>";
                        echo "<input type='hidden' name='inventory_id' value='$inventory_id'>";
                        echo "<tr><td><strong>পণ্য ১: <select name='product1'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity1' size='3'> একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ২: <select name='product2'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity2' size='3'> একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ৩: <select name='product3'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity3' size='3'> একক</td></tr>";
                        echo "<tr><td><strong>পণ্য ৪: <select name='product4'>".product_list_options($business)."</select></strong></td><td><input type='text' name='quantity4' size='3'> একক</td></tr>";
                        echo "<tr><td></td><td><input type='submit' value='সংরক্ষণ করুন'></td></tr>";
                    echo "</form>";
                }
                else
                {
                    $res=mysql_query("SELECT product_inventory.quantity, product_list.product_name FROM product_list, product_inventory WHERE status='final' AND parent='$inventory_id' AND product_inventory.product_no=product_list.id");
                    while($frow=mysql_fetch_array($res))
                    {
                        echo "<tr><td><strong>".$frow['product_name']."</strong></td><td>".$frow['quantity']." Units</td></tr>";
                    }
                }
                echo "</table>";
                
            }
            else
            {
                $result=mysql_query("SELECT name, description, type FROM businesses WHERE id='$business'") or die(mysql_error());
                $arr=mysql_fetch_array($result);
                
                if(trim($arr['type'])=='mill')
                {
                    echo "<a href='".BASE."/invoices/".$business."/create/?type=service'>কাজ গ্রহণ</a>";
                    echo "<br><a href='".BASE."/invoices/".$business."/queue/'>ক্রম</a>";
                }
                elseif(trim($arr['type'])=="trading")
                {
                    echo "<a href='".BASE."/invoices/".$business."/create/?type=sales'>বিক্রয়</a>";
                    echo "<br><a href='".BASE."/invoices/".$business."/create/?type=purchase'>ক্রয়</a>";
                }
            }
        }
    }
    footing();
}
else header("location:".BASE."/login");

function product_list_options($business)
{
    $result=mysql_query("SELECT id, product_name FROM product_list WHERE business_id='$business'");
    $options="<option value=''>পছন্দ করুন</option>";
    while($row=mysql_fetch_array($result))
    {
        $options=$options."<option value='".$row['id']."'>".$row['product_name']."</option>";
    }
    return $options;
}
?>
