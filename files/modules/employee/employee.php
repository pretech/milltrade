<style type="text/css" >

   table,th,td{border-collapse:collapse; }
    th{font-weight:bold;}    
</style>


<?php
/*
 * Project: DoubleP
 * Version: 1.0
 * Script Version: 1.0
 * Author: Moin Uddin
 */

if(current_user_type()=='admin')
{
    //everything will remain here
    if(isset($_REQUEST['option']))
    {
        if($_REQUEST['option']=="add")
        {
            $res=mysql_query("SELECT id, name FROM businesses");
            $select_business="";
            while($row=mysql_fetch_array($res))
            {
                $select_business=$select_business."<option value='".$row['id']."'>".$row['name']."</option>";
            }

            $self=BASE."/employee/?process=add";
            echo <<<EOT
<form action="$self" method="POST" enctype="multipart/form-data">

    <h3>কর্মচারী বৃত্তান্ত</h3>

    <table>
        <tr>
            <td>ব্যবসা</td>
            <td>:</td>
            <td><select name='business'>$select_business</select></td>
        </tr>

        <tr>
            <td>নিয়োগের তারিখ</td>
            <td>:</td>
            <td><input type="text" name="join_date"></td>

        </tr>

        <tr>
            <td>নাম</td>
            <td>:</td>
            <td><input type="text" name="name"></td>

        </tr>

        <tr>
            <td>পিতা/স্বামীর নাম</td>
            <td>:</td>
            <td><input type="text" name="fhname"></td>

        </tr>
         <tr>
            <td>মাতার নাম</td>
            <td>:</td>
            <td><input type="text" name="mother_name"></td>

        </tr>

        <tr>
            <td>বর্তমান ঠিকানা</td>
            <td>:</td>
            <td><input type="text" name="present_address"></td>

        </tr>

        <tr>
            <td>স্থায়ী ঠিকানা</td>
            <td>:</td>
            <td><input type="text" name="permanent_address"></td>

        </tr>

         <tr>
            <td>শিক্ষাগত যোগ্যতা</td>
            <td>:</td>
            <td><input type="text" name="educational_qualification"></td>

        </tr>

        <tr>
            <td>জন্ম তারিখ</td>
            <td>:</td>
            <td><input type="text" name="date_of_birth"></td>

        </tr>

        <tr>
            <td>জাতীয় পরিচয় পত্র নং</td>
            <td>:</td>
            <td><input type="text" name="national_id"></td>

        </tr>

         <tr>
            <td>মুঠোফোন নং</td>
            <td>:</td>
            <td><input type="text" name="cell_no"></td>

        </tr>

        <tr>
            <td>পাসপোর্ট ছবি</td>
            <td>:</td>
            <td><input type="file" name="file" id="file"></td>

        </tr>

        <tr>
            <td>পদবী</td>
            <td>:</td>
            <td><input type="text" name="designation"></td>

        </tr>

        <tr>
            <td>বেতন</td>
            <td>:</td>
            <td><input type="text" name="salary"></td>

        </tr>


        <tr>
            <td></td>
            <td></td>
            <td><input type="submit" name="submit"> <input type="reset" name="reset"> </td>

        </tr>
    </table>
</form>
EOT;
        }
        
        elseif($_REQUEST['option']=="edit" &&  !isset($_REQUEST['id']))
        {
            //first query employee record of id='$id' and then make an edit form with values from query
            
             $sql=mysql_query("select * from employee_info");

              echo "<table border='1'>
              <tr>    
                      <th>ব্যবসা</th>
	              <th>নিয়োগের তারিখ</th>
	              <th>নাম</th>
	              <th>পিতা/স্বামীর নাম</th>
	              <th>মাতার নাম</th>
	              <th>বর্তমান ঠিকানা</th>
	              <th>স্থায়ী ঠিকানা</th>
	              <th>শিক্ষাগত যোগ্যতা</th>
	              <th>জাতীয় পরিচয় পত্র নং</th>
	              <th>মুঠোফোন নং</th>
	              <th>পদবী</th>
	              <th>বেতন</th>
	              <th>সম্পাদনা</th>
	              <th>বাতিল</th>
              </tr>";
          
            while($row = mysql_fetch_array($sql))
            {
             $id=$row['id'];

        echo "<tr>";
           echo "<td>".$row['business']. "</td>";
           echo "<td>".$row['join_date']. "</td>";
           echo "<td>".$row['name']. "</td>";
           echo "<td>".$row['fhname']. "</td>";
           echo "<td>".$row['mother_name']."</td>";
           echo "<td>".$row['present_address']. "</td>";
           echo "<td>".$row['permanent_address']."</td>";
           echo "<td>".$row['educational_qualification']. "</td>";
           echo "<td>".$row['date_of_birth']."</td>";
           echo "<td>".$row['cell_no']."</td>";
           echo "<td>".$row['designation']. "</td>";
           echo "<td>".$row['salary']."</td>";
           echo "<td><a href='".BASE."/employee/?option=edit&id=$id'>".সম্পাদনা."</a></td>";
           echo "<td><a href='".BASE."/employee/?process=delete&id=$id'>".বাতিল."</a></td>";
       echo "</tr>";
             }
         echo "</table>";       
         
        }
        
        
        elseif(isset($_REQUEST['option'])=="edit" && isset($_REQUEST['id'])) 
    {
            $id=$_REQUEST['id']; 
            $self=BASE."/employee/?process=update&id=$id";
             
$sql=mysql_query("SELECT * from employee_info where id='$id'");
$test=mysql_fetch_array($sql);

if(!$sql)
    
{
    die("Error: Data not found..");
    
}
   $business=$test['business'];
   $date=$test['join_date'];
   $name=$test['name'];
   $fhname=$test['fhname'];
   $mother_name=$test['mother_name'];
   $present_address=$test['present_address'];
   $permanent_address=$test['permanent_address'];
   $educational_qualification=$test['educational_qualification'];
   $date_of_birth=$test['date_of_birth'];
   $cell_no=$test['cell_no'];
   $designation=$test['designation'];
   $salary=$test['salary'];
   ?>
<form action="<?=$self?>" method="POST" >
<table>
    <h3>সম্পাদনা ও সংরক্ষণ:</h3>
    
        <tr>
		<td>ব্যবসা</td>
                 <td><input type="text" name="business" value="<?=$business?>"/></td>
        </tr>
	<tr>
		<td>নিয়োগের তারিখ</td>
                 <td><input type="text" name="join_date" value="<?=$date?>"/></td>
        </tr>
        <tr>
		<td>নাম</td>
                 <td><input type="text" name="name" value="<?=$name?>"/></td>
        </tr>
        <tr>
		<td>পিতা/স্বামীর নাম</td>
                 <td><input type="text" name="fhname" value="<?=$fhname?>"/></td>
        </tr>
        <tr>
		<td>মাতার নাম</td>
                 <td><input type="text" name="mother_name" value="<?=$mother_name?>"/></td>
        </tr>
        <tr>
		<td>বর্তমান ঠিকানা</td>
                <td><input type="text" name="present_address" value="<?=$present_address?>"/></td>
        </tr> 
        <tr>
                <td>স্থায়ী ঠিকানা</td>
                <td><input type="text" name="permanent_address" value="<?=$permanent_address?>"/></td>
        </tr>
        <tr>
		<td>শিক্ষাগত যোগ্যতা</td>
                 <td><input type="text" name="educational_qualification" value="<?=$educational_qualification?>"/></td>
        </tr>
        <tr>
		<td>জন্ম তারিখ</td>
                <td><input type="text" name="date_of_birth" value="<?=$date_of_birth?>"/></td>
        </tr>
        <tr>
                <td>মুঠোফোন নং</td>
                <td><input type="text" name="cell_no" value="<?=$cell_no?>"/></td>
        </tr>
        <tr>
		<td>পদবী</td>
                <td><input type="text" name="designation" value="<?=$designation?>"/></td>
        </tr>
        <tr>
		<td>বেতন</td>
                <td><input type="text" name="salary" value="<?=$salary?>"/></td>
        </tr>  
        
        <tr>
              <td></td>
              <td><input type="submit" name="save" value="সংরক্ষণ করুন" /></td>
        </tr>
</table>
</form>    
        
 <?php  
    
    }
   
     

 else{
            //show all employee grouped by their business id with a edit link like (domain/employee/?option=edit&id=$id)        
               
        }
    }
    elseif(isset($_REQUEST['process']))
    {
        //every form processing code will go here
        if($_REQUEST['process']=="add")
        {
            if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"]=="image/png"))
            && ($_FILES["file"]["size"] < 2000000))

            $path="files/images/employees/";
            $file=$_FILES["file"]["name"];
            $img_link="$path"."$file";


            move_uploaded_file($_FILES["file"]["tmp_name"], $img_link);

            $sql="INSERT INTO employee_info(business, join_date, name, fhname, mother_name, present_address, permanent_address, educational_qualification, date_of_birth, national_id, cell_no,image_link, designation,salary) VALUES('$_POST[business]', '$_POST[join_date]','$_POST[name]','$_POST[fhname]','$_POST[mother_name]','$_POST[present_address]','$_POST[permanent_address]','$_POST[educational_qualification]','$_POST[date_of_birth]','$_POST[national_id]','$_POST[cell_no]','$img_link','$_POST[designation]','$_POST[salary]')";

            if(!mysql_query($sql,$link))
            {
                die('Error:'.mysql_error());
            }

            echo '1 record added';
        }
        
        
   elseif($_REQUEST['process']=="update" && ($_REQUEST['id']))
            {       
      
   if(isset($_REQUEST['save']))
   { 
       $id=$_REQUEST['id'];             
       $business_save=$_POST['business'];
       $join_date_save=$_POST['join_date'];
       $name_save=$_POST['name'];
       $fhname_save=$_POST['fhname'];
       $mother_name_save=$_POST['mother_name'];
       $present_address_save=$_POST['present_address'];
       $permanent_address_save=$_POST['permanent_address'];
       $educational_qualification_save=$_POST['educational_qualification'];
       $date_of_birth_save=$_POST['date_of_birth'];
       $cell_no_save=$_POST['cell_no'];
       $designation_save=$_POST['designation'];
       $salary_save=$_POST['salary'];
      
        
       $sql=mysql_query("UPDATE employee_info SET business='$business_save',join_date='$join_date_save', name='$name_save', fhname='$fhname_save', mother_name='$mother_name_save',
               present_address='$present_address_save', permanent_address='$permanent_address_save', educational_qualification='$educational_qualification_save',
               date_of_birth='$date_of_birth_save', cell_no='$cell_no_save', designation='$designation_save', salary='$salary_save' WHERE id='$id'" );
       if(!$sql)
       die(mysql_error());
       echo "Row Updated"; 
       header("location:".BASE."/employee");
     }
    }
    
    elseif((($_REQUEST['process'])=="delete") && ($_REQUEST['id']))
    {
       
       $id=$_REQUEST['id'];
       
        $sql=mysql_query("DELETE FROM employee_info where id='$id'");
       if(!$sql){
       die(mysql_error());
       }
       echo "Row Deleted";
       header("location:".BASE."/employee");   
    }
  
    
    
    }
    else  echo "<a href='".BASE."/employee/?option=add'>নতুন কর্মচারী সংযুক্ত করুন</a>"; 
    
}

else
{
    header("location:".BASE."/login");
}
?>
