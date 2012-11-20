<?php
function heading($title, $description, $keywords)
{

    $base=BASE;
    /*$flash_message=get_flash_message();
    if($flash_message!=0)
    {
        if($flash_message['type']==1) $display_flash="<font color='green'><strong>".$flash_message['message']."</strong></font>";
        elseif($flash_message['type']==0) $display_flash="<font color='red'><strong>".$flash_message['message']."</strong></font>";
    }*/
    $menus=top_menus();

    echo <<<html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	
	<title>এস. এস. ফুড অটো  রাইস মিল</title>

   <meta http-equiv="content-type" content="text/html; charset=UTF-8" >
   
   
    <!-- Framework CSS -->
    <link rel="stylesheet" href="$base/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="$base/css/blueprint/print.css" type="text/css" media="print">
    <!--[if lt IE 8]><link rel="stylesheet" href="$base/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

    <!-- Import fancy-type plugin for the sample page. -->
    <link rel="stylesheet" href="$base/css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection">

   
   <link rel="stylesheet" type="text/css" href="$base/css/superfish.css" media="screen">
   
   <link rel="stylesheet" type="text/css" href="$base/css/style.css" media="screen">
   
   
   <style type="text/css">
	   table{width: auto;}
	   th{background-color: #dfdfdf;border: 1px solid #efefef}
   </style>
  
     <script type="text/javascript" src="$base/js/jquery-1.2.6.min.js"></script>
   <script type="text/javascript" src="$base/js/hoverIntent.js"></script>
   <script type="text/javascript" src="$base/js/superfish.js"></script>
   <script type="text/javascript" src="$base/js/ajax.js"></script>
   <script type="text/javascript">
  // initialise plugins
  jQuery(function(){
	jQuery('ul.sf-menu').superfish();
  });

  </script>
  

</head>

<body>


<div class="container">
<div class="span-24" style="padding: 0 15px">
	<div class="logo" style="padding: 4px 0"><h1>মেসার্স এস. এস. ফুড অটো রাইচ মিল</h1></div>
html;
	
 if(isset($_SESSION['auth_user']))
    {
 ?>    	
<div class="navigation" style="width:100%;height:2.6em; background:#BDD2FF;">
	<ul class="sf-menu">
			<li class="current">
				<a href="<?=$base?>/home">হোম</a>			
			</li>
    
			      <?php if($_SESSION['auth_user']['business']==0 || $_SESSION['auth_user']['business']==1): ?> 
					<li>
						<a href="<?=$base?>/invoices/1">ধান মিল-১</a>
						<ul>
							<li><a href="<?=$base?>/invoices/1/create/?type=service">কাজ গ্রহন</a></li>
							<li><a href="<?=$base?>/invoices/1/queue">ক্রম</a></li>
                     <li><a href="<?=$base?>/reports/receivables/?business=1">পাওনা তালিকা</a></li>
                     <li><a href="<?=$base?>/reports/payables/?business=1">দেনা তালিকা</a></li>
                     <li><a href="<?=$base?>/invoices/1/create/?type=parts">পার্টস ক্রয়</a></li>
                     <li><a href="<?=$base?>/parts/inventory/1">পার্টস তালিকা</a></li>
                     <li><a href="<?=$base?>/invoices/1/create/?type=banking">ব্যাংকিং</a></li>
                     <li><a href="<?=$base?>/invoices/1/create/?type=expense">ব্যয়</a></li>
                     <li><a href="<?=$base?>/invoices/1/pending/">অননুমোদিত কাজ</a></li>
                     <li><a href="<?=$base?>/stock/?disp=1">পার্টি স্টক</a></li>
                     <li><a href="<?=$base?>/reports/stock/1">মিল স্টক</a></li>
                     <li><a href="<?=$base?>/reports/summary/?business=1">আয়-ব্যয়</a></li>
						</ul>
					</li>			
					<?php endif; ?>
			
            
            <?php if($_SESSION['auth_user']['business']==0 || $_SESSION['auth_user']['business']==2): ?>
            <li>
						<a href="<?=$base?>/invoices/2">ধান মিল-২</a>
						<ul>
							<li><a href="<?=$base?>/invoices/2/create/?type=service">কাজ গ্রহন</a></li>
							<li><a href="<?=$base?>/invoices/2/queue">ক্রম</a></li>
<li><a href="<?=$base?>/reports/receivables/?business=2">পাওনা তালিকা</a></li>
<li><a href="<?=$base?>/reports/payables/?business=2">দেনা তালিকা</a></li>
<li><a href="<?=$base?>/invoices/2/create/?type=parts">পার্টস ক্রয়</a></li>
<li><a href="<?=$base?>/parts/inventory/2">পার্টস তালিকা</a></li>
<li><a href="<?=$base?>/invoices/2/create/?type=banking">ব্যাংকিং</a></li>
<li><a href="<?=$base?>/invoices/2/create/?type=expense">ব্যয়</a></li>
<li><a href="<?=$base?>/invoices/2/pending/">অননুমোদিত কাজ</a></li>
<li><a href="<?=$base?>/stock/?disp=2">পার্টি স্টক</a></li>
<li><a href="<?=$base?>/reports/stock/2">মিল স্টক</a></li>
<li><a href="<?=$base?>/reports/summary/?business=2">আয়-ব্যয়</a></li>
						</ul>
					</li>
               <?php endif; ?>
    
					<?php if($_SESSION['auth_user']['business']==0 || $_SESSION['auth_user']['business']==3): ?>
					<li>
						<a href="<?=$base?>/invoices/3">ময়দা মিল</a>
						<ul>
							<li><a href="<?=$base?>/invoices/3/create/?type=service">কাজ গ্রহন</a></li>
							<li><a href="<?=$base?>/invoices/3/queue">ক্রম</a></li>
<li><a href="<?=$base?>/reports/receivables/?business=3">পাওনা তালিকা</a></li>
<li><a href="<?=$base?>/reports/payables/?business=3">দেনা তালিকা</a></li>
<li><a href="<?=$base?>/invoices/3/create/?type=parts">পার্টস ক্রয়</a></li>
<li><a href="<?=$base?>/parts/inventory/3">পার্টস তালিকা</a></li>
<li><a href="<?=$base?>/invoices/3/create/?type=banking">ব্যাংকিং</a></li>
<li><a href="<?=$base?>/invoices/3/create/?type=expense">ব্যয়</a></li>
<li><a href="<?=$base?>/invoices/3/pending/">অননুমোদিত কাজ</a></li>
<li><a href="<?=$base?>/stock/?disp=3">পার্টি স্টক</a></li>
<li><a href="<?=$base?>/reports/stock/3">মিল স্টক</a></li>
<li><a href="<?=$base?>/reports/summary/?business=3">আয়-ব্যয়</a></li>
						</ul>
					</li>	
              <?php endif; ?>
                       
             <?php if($_SESSION['auth_user']['business']==0 || $_SESSION['auth_user']['business']==4): ?>          
                        <li>
				<a href="<?=$base?>/invoices/4">বাণিজ্য</a>
    
                                <ul>
					<li>
						<a href="<?=$base?>/invoices/4/create/?type=sales">বিক্রয়</a></li>
					<li>
						<a href="<?=$base?>/invoices/4/create/?type=purchase">ক্রয়</a></li>
<li><a href="<?=$base?>/reports/receivables/?business=4">পাওনা তালিকা</a></li>
<li><a href="<?=$base?>/reports/payables/?business=4">দেনা তালিকা</a></li>
<li><a href="<?=$base?>/invoices/4/create/?type=banking">ব্যাংকিং</a></li>
<li><a href="<?=$base?>/invoices/4/create/?type=expense">ব্যয়</a></li>
<li><a href="<?=$base?>/invoices/4/pending/">অননুমোদিত কাজ</a></li>
<li><a href="<?=$base?>/reports/business/4">স্টক</a></li>
<li><a href="<?=$base?>/reports/summary/?business=4">আয়-ব্যয়</a></li>				
				</ul>    
   
			</li>	
         <?php endif; ?>         
         
         <?php if($_SESSION['auth_user']['business']==0): ?>
			<li>
				<a href="#">প্রশাসন</a>
				<ul>
					<li>
						<a href="<?=$base?>/employee">কর্মচারী প্যানেল</a>
						<ul>
							<li><a href="<?=$base?>/employee/?option=add">নতুন সংযুক্তকরণ</a></li>
							<li><a href="<?=$base?>/employee/?option=edit">তালিকা</a></li>			<li><a href="#">বেতন</a>
<ul>

	<li><a href="<?=$base?>/invoices/1/create/?type=salary">ধান মিল-১</a></li>
	<li><a href="<?=$base?>/invoices/2/create/?type=salary">ধান মিল-২</a></li>
	<li><a href="<?=$base?>/invoices/3/create/?type=salary">ময়দা মিল</a></li>
        <li><a href="<?=$base?>/invoices/4/create/?type=salary">বাণিজ্য</a></li>

</ul>
</li>				
						</ul>
					</li>
					<li>
						<a href="<?=$base?>/products">পণ্য প্যানেল</a>
						<ul>
							<li><a href="<?=$base?>/products/add">নতুন সংযুক্তকরণ</a></li>
   <li><a href="<?=$base?>/products">পণ্য তালিকা</a></li><!--	
   <li><a href="<?=$base?>/products/business">মিল অনুযায়ী পণ্য তালিকা</a></li>-->						
						</ul>
					</li>
					<li>
						<a href="<?=$base?>/banks">ব্যাংক প্যানেল</a>
						<ul>
							<li><a href="<?=$base?>/banks/add">নতুন সংযুক্তকরণ</a></li>
							<li><a href="#">ব্যাংক তালিকা</a></li>							
						</ul>
					</li>
   
				</ul>
			</li>
			<?php endif; ?>
			
			
			<?php if($_SESSION['auth_user']['business']==0 ): ?>
			<li>
				<a href="#">রিপোর্ট</a>

<ul>
							<li><a href="<?=$base?>/reports/banking">ব্যাংকিং</a></li>
							<li><a href="<?=$base?>/reports/Sales">বিক্রয়</a></li>
							<li><a href="<?=$base?>/reports/purchase">ক্রয়</a></li>
                                                        <li><a href="<?=$base?>/reports/service">সার্ভিস</a></li>    
                                           <!--             <li><a href="#">দেনা পাওনা</a>

						<ul>
						<li><a href="#">ধান মিল-১</a></li>
						<li><a href="#">ধান মিল-২</a></li>
						<li><a href="#">ময়দা মিল</a>
						</li><li><a href="#">বাণিজ্য</a></li>                                        
						</ul>
</li>-->

<!--li><a href="#">গ্রাহক তথ্য</a>
						<ul>
						<li><a href="<?=$base?>/reports/client_info/1">ধান মিল-১</a></li>
						<li><a href="<?=$base?>/reports/client_info/2">ধান মিল-২</a></li>
						<li><a href="<?=$base?>/reports/client_info/3">ময়দা মিল</a></li>
						<li><a href="<?=$base?>/reports/client_info/4">বাণিজ্য</a></li>

						</ul>

</li-->    
<li><a href="<?=$base?>/reports/summary">আয়-ব্যয়</a></li>
<li><a href="<?=$base?>/reports/salaries">কর্মচারী বেতন</a></li>
<li><a href="<?=$base?>/reports/salaries/employee">সাম্প্রতিক বেতন পরিশোধ</a></li>
</ul>
			</li>
			<?php endif; ?>
			<li>
				<a href="<?=$base?>/login/?logout=true">বাহির</a>
			</li>	
		</ul>  

             </div>  <!----------------End: Navigation----------------->
<?php

}
?>	

     <div style="clear: both"></div>
<?php if(logged_in()){ ?>
      <div>
      <?php echo $display_flash; ?><br>
          স্বাগতম<b><i></i></b>
     </div>
     <div>


         আপনার অবস্থান: 
         <?php include("http://www.milltrade.precursortechnology.com/breadcrumbs.php"); ?>

     </div>
<?php } ?>     
     <hr />
     <div style="min-height: 400px;">
<?php
}



function footing()
{
    $base=BASE;
    echo <<<html
								</div>
	<div style="clear: both"></div>
	<hr/>
	<div class="footer">
				<div style="float:left; margin-top: 15px; padding: 5px">এস. এস. ফুড অটো রাইস মিল, বাটিয়াভিটা, লাকসাম, কুমিল্লা।</div>
				<div style="float:right; margin-top: 15px; padding: 5px">প্রস্তুতকারক: <a href="http://www.precursortechnology.com" style="text-decoration: none;" target="_blank">প্রিকার্সর টেকনোলজি</a></div>

			</div>
		</div>
	</div>		

	</body>
	</html>
html;
}

function top_menus()
{
    if(logged_in())
    {
        return "Hi, <strong><a href='".BASE."/profile'>".current_username()."</a></strong> | <a href='".BASE."/projects'>Projects</a> | <a href='".BASE."/videos/all'>Videos</a>| <a href='".BASE."/resources'>Resources</a> | <a href='".BASE."/login/?logout=true'>ÑÐ¶ÐŒÑÐ¶âÑÐ¶â£ÑÐ¶â?ÑÐ¶â</a>";
    }
    else return "<a href='".BASE."'>Home</a> | <a href='".BASE."/login'>ÑÐ¶ÐºÑÐ·?ÑÐ¶âÑÐ¶ÐŒÑÐ·ÐÑÐ¶â¢ ÑÐ¶Ð¥ÑÐ¶âÑÐ·?ÑÐ¶Ðž</a>";
}

function set_flash_message($message, $flag)
{
    $_SESSION['flash']['message']=$message;
    $_SESSION['flash']['type']=$flag;
}

function get_flash_message()
{
    if(isset($_SESSION['flash']))
    {
        $message=array('message'=>$_SESSION['flash']['message'], 'type'=>$_SESSION['flash']['type']);
        unset($_SESSION['flash']);
        return $message;
    }
    else return 0;
}

function logged_in()
{
    if(isset($_SESSION['auth_user']))
    {
        return 1;
    }
    else return 0;
}

//following function returns the id of current user
function current_user()
{
    if(isset($_SESSION['auth_user']['id'])) return $_SESSION['auth_user']['id'];
    else return false;
}

//following function returns the id of current user's usernam
function current_username()
{
    if(isset($_SESSION['auth_user']['username'])) return $_SESSION['auth_user']['username'];
    else return false;
}

function current_user_fullname()
{
    if(isset($_SESSION['auth_user']['name'])) return $_SESSION['auth_user']['name'];
    else return false;
}

function current_user_type()
{
    if(isset($_SESSION['auth_user']['type'])) return $_SESSION['auth_user']['type'];
    else return false;
}

//following function returns the id of current user
function current_user_info($parameter)
{
    if(isset($_SESSION['auth_user'][$parameter])) return $_SESSION['auth_user'][$parameter];
    else return false;
}

//following function creates a pagination
function paginate($total, $current_page, $total_every_page, $url)
{

    $total_pages=$total/$total_every_page;
    if($total_page>round($total_page)) $total_pages=round($total_pages)+1;

    if($current_page>1) echo "<a href='".$url."/page/".($current_page-1)."'><input type='submit' value='<<<ÑÐ¶ÐºÑÐ·ÐÑÐ¶âÑÐ·?ÑÐ¶ÐŒÑÐ·Ð'></a>";
    if($current_page<($total_pages)) echo "<a href='".$url."/page/".($current_page+1)."'><input type='submit' value='ÑÐ¶ÐºÑÐ¶âÑÐ·Ð>>>'></a>";
}

function invoice_by_id($invoice_id)
{
    $Q=mysql_query("select * from invoices where id='$invoice_id'");
    $row=mysql_fetch_array($Q);
    ?>
    <table>
        <tr>
            <td>Date In</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['date_in']); ?></td>
        </tr>
        
        <tr>
            <td>Date Out</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['date_out']); ?></td>
        </tr>
        
        <tr>
            <td>Invoice ID</td>
            <td>:</td>
            <td><?php echo aim_num_to_bn($row['id']); ?></td>
        </tr>
        
        <tr>
            <td>Customer Info</td>
            <td>:</td>
            <td><?php echo $row['party_info']; ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Product Name</th>
            <th>Cost Per Unit</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        <?php
        $grand_total=0;
        $Q=mysql_query("select pl.product_name,pi.cost_per_unit,pi.quantity,pi.status 
                from product_inventory as pi
                left join product_list as pl on pl.id=pi.product_no
                where id='$invoice_id'");
        
        while($row=mysql_fetch_array($Q))
        {
            $total=0;
        ?>
        <tr>
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
            <td colspan="3" align="center">Total</td>
            <td><?php echo aim_num_to_bn($grand_total); ?></td>
        </tr>
         
    </table>
<?php
}


function display_party_info($party_info)
{
    if(strpos($party_info, "{party:")!==false)
    {
        $br=explode("{party:", $party_info);
        $brr=explode("}", $br[1]);
        $party_id=$brr[0];
        $res=mysql_query("SELECT party_name, party_address, party_phone FROM party_info WHERE party_id='$party_id'");
        $arr=mysql_fetch_array($res);
        echo "<a href='".BASE."/reports/client/".$party_id."'>".$arr['party_name']."</a>, ".$arr['party_address'].", ".$arr['party_phone'];
    }
    elseif(strpos($party_info, "{self:")!==false)
    {
        $br=explode("{self:", $party_info);
        $brr=explode("}", $br[1]);
        $business_id=$brr[0];
        $res=mysql_query("SELECT name, description FROM businesses WHERE id='$business_id'");
        $arr=mysql_fetch_array($res);
        echo "<a href='".BASE."/reports/business/".$business_id."'>".$arr['name']."</a>, ".$arr['description'];
    }
    elseif(strpos($party_info, "{bank:")!==false)
    {
        $br=explode("{bank:", $party_info);
        $brr=explode("}", $br[1]);
        $bank_id=$brr[0];
        $res=mysql_query("SELECT bank_name, account_no FROM bank_list WHERE id='$bank_id'");
        $arr=mysql_fetch_array($res);
        echo "<a href='".BASE."/reports/banking/".$bank_id."'>".$arr['bank_name'].", ".$arr['account_no']."</a>";
    }
    elseif(strpos($party_info, "{emp:")!==false)
    {
        $br=explode("{emp:", $party_info);
        $brr=explode("}", $br[1]);
        $employee_id=$brr[0];
        $res=mysql_query("SELECT employee_info.name, employee_info.designation, businesses.name as business_name FROM businesses, employee_info WHERE employee_info.business=businesses.id AND employee_info.id='$employee_id'");
        $arr=mysql_fetch_array($res);
        echo "<a href='".BASE."/reports/salary/".$employee_id."'>".$arr['name'].", ".$arr['designation']." at ".$arr['business_name']."</a>";
    }
    else echo $party_info;
}

//stock information
function show_stock($party_info, $type)
{
    //if it is trading, then show it's own stock first
    if($type=="business") show_trading_stock($party_info);

    $sql="SELECT product_inventory.id, product_inventory.invoice_id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id FROM product_inventory, invoices WHERE ";
    if($type!='') $sql.="invoices.party_info='$party_info' AND ";
    $sql.="invoices.id=product_inventory.invoice_id AND product_inventory.status<>'processed' AND product_inventory.status<>'taken' AND invoices.invoice_type='service' AND invoices.status<>'deny' AND invoices.status<>'pending' AND invoices.status<>'delivered'";
    $result=mysql_query($sql) or die(mysql_error());
    while($row=mysql_fetch_array($result))
    {
	$business_id=$row['business_id'];
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];
	$invoice_id=$row['invoice_id'];

//if some amount of raw is already taken for grining and some portion is left
	if($parent=="")
	{
	    $pqs=mysql_query("SELECT SUM(quantity) as quan FROM product_inventory WHERE parent='$inventory_id' AND status='taken'") or die(mysql_error());
	    $pqsarr=mysql_fetch_array($pqs);	
	    $taken=$pqsarr['quan'];
	    $res[$business_id][$product_no]['taken']+=$taken;
	}
	
	if($status=='final')
	{
	    $shifres=mysql_query("SELECT SUM(quantity) as quantity FROM product_delivery WHERE invoice_id='$invoice_id' AND product_no='$product_no'") or die(mysql_error());
	    $shifarr=mysql_fetch_array($shifres);

	    //$res[$business_id][$product_no][$status]-=$shifarr['quantity'];
	    if(!isset($check_delivery[$invoice_id][$product_no])) 
	    {
		$res[$business_id][$product_no][$status]-=$shifarr['quantity'];
	        $check_delivery[$invoice_id][$product_no]=1;
	    }
	}

	$res[$business_id][$product_no][$status]+=$quantity;
    }  

    if(!isset($res)) echo "<br><br><strong>Empty Stock</strong>";
    else{
    foreach($res as $bus)
    {
	$business=array_search($bus, $res);
	$busres=mysql_query("SELECT id, name FROM businesses WHERE id='$business'") or die(mysql_error());
	$busarr=mysql_fetch_array($busres);
	echo "<br><strong><a href='".BASE."/stock/?disp=".$busarr['id']."'>".$busarr['name']."</a></strong><br>";
	
	echo "<table>";
        echo "<tr><th>Product</th><th>Raw</th><th>Processed</th></tr>";
	foreach($bus as $prod)
 	{
	    $product_no=array_search($prod, $bus);
	    $prores=mysql_query("SELECT product_name FROM product_list WHERE id='$product_no'") or die(mysql_error());
	    $prarr=mysql_fetch_array($prores);
	    echo "<tr>";
		echo "<td>".$prarr['product_name']."</td>";
		echo "<td>".aim_num_to_bn(($prod['']-$prod['taken']))."</td>";
		echo "<td>".aim_num_to_bn($prod['final'])."</td>";
	    echo "</tr>";
	}
	echo "</table>";
    }
    }//checking if not empty ends
    if(isset($check_delivery)) unset($check_delivery);
}

function show_trading_stock($party_info)
{
$business_id=str_replace("{self:", "", $party_info);
$business_id=str_replace("}", "", $business_id);

//purchases
    $sql="SELECT product_inventory.id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id FROM product_inventory, invoices WHERE invoices.business_id='$business_id' AND invoices.id=product_inventory.invoice_id AND product_inventory.status<>'processed' AND product_inventory.status<>'taken' AND invoices.invoice_type='purchase' AND invoices.status<>'deny' AND invoices.status<>'pending'";
    $result=mysql_query($sql) or die(mysql_error());
    while($row=mysql_fetch_array($result))
    {
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];

	$res[$business_id][$product_no][$status]+=$quantity;
    }  

//deducting products submitted to mills
    $sql="SELECT product_inventory.id, product_inventory.invoice_id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id, invoices.status as inv_status FROM product_inventory, invoices WHERE invoices.party_info='$party_info' AND invoices.id=product_inventory.invoice_id AND product_inventory.status<>'taken' AND invoices.invoice_type='service' AND invoices.status<>'deny' AND invoices.status<>'pending'";
    $result=mysql_query($sql) or die(mysql_error());
    while($row=mysql_fetch_array($result))
    {
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];
	$invoice_id=$row['invoice_id'];
	$inv_status=$row['inv_status'];

	if(($status=='final')&&($inv_status==''))
	{
	    $shifres=mysql_query("SELECT SUM(quantity) as quantity FROM product_delivery WHERE invoice_id='$invoice_id' AND product_no='$product_no'") or die(mysql_error());
	    $shifarr=mysql_fetch_array($shifres);

	    if(!isset($check_delivery[$invoice_id][$product_no])) 
	    {
		$res[$business_id][$product_no][$status]+=$shifarr['quantity'];
	        $check_delivery[$invoice_id][$product_no]=1;
	    }
	}
	elseif($inv_status=='delivered') $res[$business_id][$product_no][$status]+=$quantity;
	elseif(($status=='')||($status=='processed')) $res[$business_id][$product_no]['']-=$quantity;
    } 

//adding products processed from mills
    $sql="SELECT product_inventory.id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id FROM product_inventory, invoices WHERE invoices.party_info='$party_info' AND invoices.id=product_inventory.invoice_id AND product_inventory.status='final' AND product_inventory.status<>'taken' AND invoices.invoice_type='service' AND invoices.status<>'deny' AND invoices.status<>'pending' AND invoices.status='delivered'";
    $result=mysql_query($sql) or die(mysql_error());

    while($row=mysql_fetch_array($result))
    {
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];

	$res[$business_id][$product_no]['']+=$quantity;
    } 

//deducting products that are sold
    $sql="SELECT product_inventory.id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id FROM product_inventory, invoices WHERE invoices.business_id='$business_id' AND invoices.id=product_inventory.invoice_id AND product_inventory.status<>'final' AND product_inventory.status<>'taken' AND invoices.invoice_type='sales' AND invoices.status<>'deny' AND invoices.status<>'pending'";
    $result=mysql_query($sql) or die(mysql_error());

    while($row=mysql_fetch_array($result))
    {
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];

	$res[$business_id][$product_no]['']-=$quantity;
    }

    if(!isset($res)) echo "<br><br><strong>Empty Stock</strong>";
    else{
    foreach($res as $bus)
    {
	$business=array_search($bus, $res);
	$busres=mysql_query("SELECT id, name FROM businesses WHERE id='$business'") or die(mysql_error());
	$busarr=mysql_fetch_array($busres);
	echo "<br><strong><a href='".BASE."/stock/?disp=".$busarr['id']."'>".$busarr['name']."</a></strong><br>";
	
	echo "<table>";
        echo "<tr><th>Product</th><th>Raw</th><th>Processed</th></tr>";
	foreach($bus as $prod)
 	{
	    $product_no=array_search($prod, $bus);
	    $prores=mysql_query("SELECT product_name FROM product_list WHERE id='$product_no'") or die(mysql_error());
	    $prarr=mysql_fetch_array($prores);
	    echo "<tr>";
		echo "<td>".$prarr['product_name']."</td>";
		echo "<td>".aim_num_to_bn(($prod['']-$prod['taken']))."</td>";
		echo "<td>".aim_num_to_bn($prod['final'])."</td>";
	    echo "</tr>";
	}
	echo "</table>";
    }
    }//checking if not empty ends

    if(isset($check_delivery)) unset($check_delivery);
}

//stock information
function show_mill_stock($party_info, $type, $mill)
{
    //if it is trading, then show it's own stock first
    if($type=="business") show_trading_stock($party_info);

    $sql="SELECT product_inventory.id, product_inventory.invoice_id, product_inventory.parent, product_inventory.product_no, product_inventory.quantity, product_inventory.status, invoices.business_id FROM product_inventory, invoices WHERE ";
    if($type!='') $sql.="invoices.party_info='$party_info' AND ";
    $sql.="invoices.id=product_inventory.invoice_id AND product_inventory.status<>'processed' AND product_inventory.status<>'taken' AND invoices.invoice_type='service' AND invoices.status<>'deny' AND invoices.status<>'pending' AND invoices.status<>'delivered'";
    if($mill!='') $sql.=" AND invoices.business_id='$mill'";

    $result=mysql_query($sql) or die(mysql_error());
    while($row=mysql_fetch_array($result))
    {
	$business_id=$row['business_id'];
  	$product_no=$row['product_no'];
	$status=$row['status'];
	$quantity=$row['quantity'];
	$parent=$row['parent'];
	$inventory_id=$row['id'];
	$invoice_id=$row['invoice_id'];

//if some amount of raw is already taken for grining and some portion is left
	if($parent=="")
	{
	    $pqs=mysql_query("SELECT SUM(quantity) as quan FROM product_inventory WHERE parent='$inventory_id' AND status='taken'") or die(mysql_error());
	    $pqsarr=mysql_fetch_array($pqs);	
	    $taken=$pqsarr['quan'];
	    $res[$business_id][$product_no]['taken']+=$taken;
	}
	
	if($status=='final')
	{
	    $shifres=mysql_query("SELECT SUM(quantity) as quantity FROM product_delivery WHERE invoice_id='$invoice_id' AND product_no='$product_no'") or die(mysql_error());
	    $shifarr=mysql_fetch_array($shifres);

	    //$res[$business_id][$product_no][$status]-=$shifarr['quantity'];
	    if(!isset($check_delivery[$invoice_id][$product_no])) 
	    {
		$res[$business_id][$product_no][$status]-=$shifarr['quantity'];
	        $check_delivery[$invoice_id][$product_no]=1;
	    }
	}

	$res[$business_id][$product_no][$status]+=$quantity;
    }  

    if(!isset($res)) echo "<br><br><strong>Empty Stock</strong>";
    else{

    if($mill=='')
    {
    	foreach($res as $bus)
    	{	    
	    foreach($bus as $prod)
 	    {
	        $product_no=array_search($prod, $bus);
                $mill_stock[$product_no]['raw']+=$prod['']-$prod['taken'];
                $mill_stock[$product_no]['final']+=$prod['final'];	    
	    }	
    	}

	echo "<strong>Total Stock</strong>";
	echo "<table>";
	echo "<tr><th>Product</th><th>Raw</th><th>Processed</th></tr>";
 	foreach($mill_stock as $mill_product)
	{
            $product_no=array_search($mill_product, $mill_stock);
	    $prores=mysql_query("SELECT product_name FROM product_list WHERE id='$product_no'") or die(mysql_error());
	    $prarr=mysql_fetch_array($prores);
	    echo "<tr>";
		echo "<td>".$prarr['product_name']."</td>";
		echo "<td>".aim_num_to_bn($mill_product['raw'])."</td>";
		echo "<td>".aim_num_to_bn($mill_product['final'])."</td>";
	    echo "</tr>";
	}
	echo "</table>";
    }

    foreach($res as $bus)
    {
	$business=array_search($bus, $res);
	$busres=mysql_query("SELECT id, name FROM businesses WHERE id='$business'") or die(mysql_error());
	$busarr=mysql_fetch_array($busres);
	echo "<br><strong><a href='".BASE."/stock/?disp=".$busarr['id']."'>".$busarr['name']."</a></strong><br>";
	
	echo "<table>";
        echo "<tr><th>Product</th><th>Raw</th><th>Processed</th></tr>";
	foreach($bus as $prod)
 	{
	    $product_no=array_search($prod, $bus);
	    $prores=mysql_query("SELECT product_name FROM product_list WHERE id='$product_no'") or die(mysql_error());
	    $prarr=mysql_fetch_array($prores);
	    echo "<tr>";
		echo "<td>".$prarr['product_name']."</td>";
		echo "<td>".aim_num_to_bn(($prod['']-$prod['taken']))."</td>";
		echo "<td>".aim_num_to_bn($prod['final'])."</td>";
	    echo "</tr>";
	}
	echo "</table>";
    }
    }//checking if not empty ends
    if(isset($check_delivery)) unset($check_delivery);
}


function aim_num_to_en($raw_number)
{
/*******************************
**Name: aim_num_to_en v1
**Developer: Azhar Ibn Mostafiz
********************************/


$english=array(0,1,2,3,4,5,6,7,8,9);
$bangla=array('০','১','২','৩','৪','৫','৬','৭','৮','৯');

return str_replace($bangla, $english,$raw_number);

}

function aim_num_to_bn($raw_number)
{
/*******************************
**Name: aim_num_to_bn v1
**Developer: Azhar Ibn Mostafiz
********************************/


$english=array(0,1,2,3,4,5,6,7,8,9);
$bangla=array('০','১','২','৩','৪','৫','৬','৭','৮','৯');

return str_replace($english,$bangla,$raw_number);

}


?>
