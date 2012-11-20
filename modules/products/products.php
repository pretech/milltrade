<?php
if(current_user_info('type')=="admin")
{       
    if(isset($_REQUEST['process']))
    {       
        if($_REQUEST['process']=="add")
        {
            //write codes of adding a new product here(action script of the form)
            $sql="insert into product_list(product_name, type) values ('$_POST[product_name]','$_POST[type]')";

            if(!mysql_query($sql,$link))
            {
                die('Error:'.mysql_error());
            }
            set_flash_message('àŠ?àŠàŠàŠ¿ àŠ€àŠ¥à§?àŠ¯ àŠžàŠàŠ¯à§?àŠà§?àŠ€ àŠ¹à§à§àŠà§', 1);
            header("location:".BASE."/products");
            die();
        }
        elseif($_REQUEST['process']=="update" && $_REQUEST['id'])
        {             
            if(isset($_REQUEST['save']))
            { 
                $id=$_REQUEST['id'];
                $business=aim_num_to_en($_POST['business']);
                $product=$_POST['product'];
                $business_id_save=aim_num_to_en($_POST['business_id']);      
                $product_name_save=$_POST['product_name'];
                $type_save=$_POST['type'];
                $price_save=aim_num_to_en($_POST['price_per_unit']);     

                $presult=mysql_query("UPDATE price_list SET price='$price_save' WHERE business='$business' AND product='$product'");
                if(mysql_affected_rows()<1) mysql_query("INSERT INTO price_list (id, business, product, price) VALUES ('', '$business', '$product', '$price_save')") or die(mysql_error());
                
                $sql=mysql_query("UPDATE product_list SET  product_name='$product_name_save', type='$type_save' WHERE id='$id'");
                if(!$sql)
                die(mysql_error());
                {       
                header("location:".BASE."/products/business/".$business);
                }
            }
        }
    
       elseif((($_REQUEST['process'])=="delete") && ($_REQUEST['id']))
         {       
            $id=$_REQUEST['id'];
       
            $sql=mysql_query("DELETE FROM product_list where id='$id'");
            if(!$sql){
            die(mysql_error());
                 }
            echo "সারি বাতিল হয়েছে";
}
        
        elseif(($_REQUEST['process'])=="show_product_list" && ($_REQUEST['business_id']))
           {
                heading("", "", "");
                $self=BASE."/products/business/";
?>
    <form method="REQUEST" action=<?= $self ?>>
        <input type="hidden" name="process" value="show_product_list">
        <table>     
          <h3>পণ্যের তালিকা</h3>  
           <tr>     
              <td>ব্যবসা:</td> 
              <td>
                  <select name ="business_id">
                             
                         <option value="">বাছাই করুন</option>
                                <?php
                                $result = mysql_query("SELECT * FROM businesses") or trigger_error('MySQL error: ' . mysql_error());
                                while($row = mysql_fetch_array($result))
                                {
                                    $id=$row['id'];
                                    $description=$row['description'];
                                ?>
                          <option value="<?php echo $id ?>"><?php echo $description; } ?></option>
                   </select>
               </td>
               <td><input type="submit" value="দাখিল করুন"></td>
            </tr>
        </table>
    </form>

         <?php           
            $id=$_REQUEST['business_id'];               
            $sql=mysql_query("select * from product_list where business_id=$id");
            echo "<table border='1'>
            
              <tr>    
                      <th>ব্যবসা</th>
	              <th>পণ্যের নাম</th>
	              <th>ধরণ</th>
	              <th>একক প্রতি মূল্য</th>	              
	              <th>সম্পাদনা</th>
	              <th>বাতিল</th>
              </tr>";
          
            while($row = mysql_fetch_array($sql))
            {   
           
           $id=$row['id'];
           $business_id=$row['business_id'];
           $product_name=$row['product_name'];
           $type=$row['type'];
           $price_per_unit=$row['price_per_unit'];

           echo "<tr>";
{
$sql1=mysql_query("select * from businesses where id=$business_id");
    while($row=mysql_fetch_array($sql1))
      $description=$row['description'];
           echo "<td>".$description. "</td>";
}
           echo "<td>".$product_name. "</td>";
           echo "<td>".$type. "</td>";
           echo "<td>".aim_num_to_bn($price_per_unit). "</td>";
           echo "<td><a class='anchor' href='".BASE."/products/?option=edit&id=$id'>"সম্প্রাদন করুন"</a></td>";
           echo "<td><a class='anchor' href='".BASE."/products/?process=delete&id=$id'>"মুছে ফেলুন"</a></td>";
           echo "</tr>";             
             }
         echo "</table>";             
        }
    }

   
    
    //show process end---------------------------------------------
    
    
    
    //show form start---------------------------------------------
    heading("", "", "");
    if($break[$start+1]=="add")
    {
      
        $base=BASE;
        echo <<<EOT
        <form action="$base/products/?process=add" method="POST">

    <h3>পণ্য সংযুক্তকরণ</h3>

    <table>

        <tr>
            <td>পণ্যের নাম</td>
            <td>:</td>
            <td><input type="text" name="product_name"></td>

        </tr>

        <tr>
            <td>পণ্যের ধরণ</td>
            <td>:</td>
            <td>
                 <select name="type">

                     <option value="">বাছাই</option>
                     <option value="raw">কাঁচামাল</option>
                     <option value="final">উৎপাদিত </option>

                 </select>

            </td>

        </tr>

        <tr>
            <td></td>
            <td></td>
            <td><input type="Submit" value="দাখিল করুন"></td>


        </tr>
    </table>
</form>
EOT;
    }
    elseif(($break[$start+1]=="business")&&($break[$start+2]!=""))
    {
        $business=$break[$start+2];
        $sql=mysql_query("select * from product_list");
            echo "<table border='1'>

             <h3>পণ্য তালিকা</h3>
              <tr>
	              <th>পণ্যের নাম</th>
	              <th>ধরণ</th>
	              <th>একক প্রতি মূল্য</th>
	              <th>সম্পাদনা</th>
                      <th>বাতিল</th>
              </tr>";

            while($row = mysql_fetch_array($sql))
            {
                $id=$row['id'];
                $priceres=mysql_query("SELECT price FROM price_list WHERE business='$business' AND product='$id'");
                $pricearr=mysql_fetch_array($priceres);
                $price=$pricearr['price'];

        echo "<tr>";
           echo "<td>".$row['product_name']. "</td>";
           echo "<td>".$row['type']. "</td>";
           echo "<td>".aim_num_to_bn($price). "</td>";
           echo "<td><a class='anchor' href='".BASE."/products/?option=edit&business=$business&id=$id'>".Edit."</a></td>";
echo "<td><a class='anchor' href='".BASE."/products/?process=delete&id=$id'>".Delete."</a></td>";
       echo "</tr>";
             }
         echo "</table>";
    }
    elseif(isset($_REQUEST['option'])=="edit" && isset($_REQUEST['id']))  //$_REQUEST['business'] should be set
    {
            $id=$_REQUEST['id'];
            $business=$_REQUEST['business'];
            $self=BASE."/products/?process=update&id=$id";
             
$sql=mysql_query("SELECT * from product_list where id='$id'");
$test=mysql_fetch_array($sql);

$prres=mysql_query("SELECT id, price FROM price_list WHERE business='$business' AND product='$id'");
$parr=mysql_fetch_array($prres);
$price_per_unit=$parr['price'];

if(!$sql)
    
{die("Error: Data not found..");}
   $business_id=$test['business_id'];
   $product_name=$test['product_name'];
   $type=$test['type'];
   
   production_cost($id, '', '', $business);

   ?>
<form action="<?=$self?>" method="POST" >
<input type="hidden" name="business" value="<?php echo $business ?>">
<input type="hidden" name="product" value="<?php echo $id ?>">
<table>
    <h3>সম্প্রাদন এবং সংরক্ষণ:</h3>
             
        <tr>
		<td>পণ্যের নাম</td>
                 <td><input type="text" name="product_name" value="<?=$product_name?>"/></td>
        </tr>
        <tr>
		<td>প্রকার</td>
                 <td><input type="text" name="type" value="<?=$type?>"/></td>
        </tr>
        <tr>
		<td>একক প্রতি মূল্য</td>
                 <td><input type="text" name="price_per_unit" value="<?=$price_per_unit?>"/></td>
        </tr>
        
        <tr>
              <td></td>
              <td><input type="submit" name="save" value="সংরক্ষণ করুন" /></td>
        </tr>
</table>
</form>    
        
 <?php  
    
    }
    
    elseif($break[$start+1]=="business" && $_REQUEST['process']!="show_product_list")
    {      
        $self=BASE."/products/business/";
?>
    <form method="REQUEST" action=<?= $self ?>>
        <input type="hidden" name="process" value="show_product_list">
        <table>       
           <tr>     
              <td>ব্যবসাসমূহ:</td> 
              <td>
                  <select name ="business_id">
                             
                         <option value="">বাছাই করুন</option>
                                <?php
                                $result = mysql_query("SELECT * FROM businesses") or trigger_error('MySQL error: ' . mysql_error());
                                while($row = mysql_fetch_array($result))
                                {
                                    $id=$row['id'];
                                    $description=$row['description'];
                                ?>
                          <option value="<?php echo $id ?>"><?php echo $description; } ?></option>
                   </select>
               </td>
               <td><input type="submit" value="দাখিল করুন"></td>
            </tr>
        </table>
    </form>

         <?php                
          }
   
        elseif($break[$start]=="products" && $_REQUEST['process']!="show_product_list")
        {
            $bres=mysql_query("SELECT id, name, description, type FROM businesses");
            echo "<table>";
            while($brow=mysql_fetch_array($bres))
            {
                echo "<tr>";
                    echo "<td><a href='".BASE."/products/business/".$brow['id']."'>".$brow['name']."</a></td>";
                    echo "<td>".$brow['name']."</td>";
                    echo "<td>".$brow['description']."</td>";
                    echo "<td>".$brow['type']."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    
    footing();
}
else header("location:".BASE."/home");

function production_cost($product, $from, $to, $business)
{
    $from=date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
    $to=date('Y-m-d');
    $fin_quantity=0;
    $raw_quantity=0;
    $procost=0;
    $rawcost=0;
    //getting the cost of grinding
    $sql="SELECT quantity, parent FROM product_inventory, invoices WHERE product_inventory.product_no='$product' AND product_inventory.invoice_id=invoices.id AND product_inventory.status='final' AND invoices.party_info='{self:$business}' AND invoices.date_in>='$from' AND invoices.date_in<='$to' AND invoices.status<>'pending' AND invoices.status<>'deny'";
    $result=mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($result)>0)
    {
        while($row=mysql_fetch_array($result))
        {
            $fin_quantity=$fin_quantity+$row['quantity'];
            $parent=$row['parent'];
            
            $rrsql="SELECT quantity, product_no, cost_per_unit FROM product_inventory WHERE id='$parent'";
            $rres=mysql_query($rrsql) or die(mysql_error());
            $rarr=mysql_fetch_array($rres);

            //usually parent product of a single same product is single, but following is for multiple parents
            if(isset($raw_prod_id))
            {
                if(!in_array($rarr['product_no'], $raw_prod_id)) $raw_prod_id[]=$rarr['product_no'];
            }
            else $raw_prod_id[]=$rarr['product_no'];

            $raw_quantity=$raw_quantity+$rarr['quantity'];
            $rawcost=$rawcost+$rarr['quantity']*$rarr['cost_per_unit'];
        }
        $i=0;
        $prate=0;
        foreach($raw_prod_id as $prod)
        {
            $i++;
            $prate=get_purchase_rate($prod, $from, $to, $business);
        }
        $prate=$prate/$i;
        $total_cost=($prate*$raw_quantity)+$rawcost;
        $per_unit_cost=$total_cost/$fin_quantity;
        echo "উৎপাদিত পরিমাণ: ".aim_num_to_bn($fin_quantity)."<br>";
        echo "কাঁচামাল পরিমাণ: ".aim_num_to_bn($raw_quantity)."<br>";
        echo "কাঁচামাল প্রক্রিয়া খরচ: ".aim_num_to_bn($rawcost)."<br>";
        echo "ক্রয় মূল্য: ".aim_num_to_bn($prate)."<br>";
        echo "মোট খরচ: ".aim_num_to_bn($total_cost)."<br>";
        echo "একক প্রতি উৎপাদন খরচ: ".aim_num_to_bn($per_unit_cost)."<br>";
    }    
}

function get_purchase_rate($id, $from, $to, $business)
{
    $type="purchase";
    $sql="SELECT product_inventory.cost_per_unit, product_inventory.quantity, product_inventory.type, product_inventory.type, product_inventory.parent, product_inventory.status, invoices.invoice_type FROM product_inventory, invoices WHERE  product_inventory.invoice_id=invoices.id AND invoices.status<>'pending' AND invoices.status<>'deny' AND product_inventory.product_no='$id'";
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

    if($ptotal_qty>0) return $ptotal_amount/$ptotal_qty;
    else return 0;
}
?>
