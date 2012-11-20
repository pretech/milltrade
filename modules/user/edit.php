<?php
$con=mysql_connect('localhost','root','');
mysql_select_db('mill',$con);
$results=mysql_query("select * from users WHERE business=1  ");

$row = mysql_fetch_array($results)
 ?>
<div align="center" style="border:1px solid black; width:60%; height:auto;">
<form action="update.php" method="POST">
<table  cellpadding="5" cellspacing="0" style="border-collapse: collapse" >
	<tr>
		<td>ব্যবসা</td> 	<td> <b>:</b>&nbsp;&nbsp;<input readonly="readonly" type="text" name="business"  value="<?php echo $row['business'] ?>"/></td> 
	</tr>
	<tr>
		<td>নাম</td>   <td> <b>:</b>&nbsp;&nbsp;<input type="text" name="name"  value="<?php echo $row['name'] ?>"/></td> 
	</tr>
	<tr>
		<td>ইউজারের নাম</td> <td> <b>:</b>&nbsp;&nbsp;<input type="text" name="user_name" value="<?php echo $row['user_name'] ?>"/></td> 
	</tr>
	<tr>
		<td>ই-মেইল</td>  <td> <b>:</b>&nbsp;&nbsp;<input type="text" name="email"  value="<?php echo $row['email'] ?>"/></td> 
	</tr>

	<tr>
		<td>পাসওয়ার্ড</td>  <td> <b>:</b>&nbsp;&nbsp;<input type="password" name="password" value="<?php echo $row['password'] ?>"/></td> 
	</tr>
	<tr>
		<td>মোবাইল</td>  <td> <b>:</b>&nbsp;&nbsp;<input type="number" name="tel" value="<?php echo $row['tel'] ?>"/></td> 
	</tr>
	<tr>
		<td>  </td> <td> <b>:</b>&nbsp;&nbsp;<input type="submit" name="" value="Update"/></td> 
	</tr>

</table>
</form>
</div>
<?php
?>