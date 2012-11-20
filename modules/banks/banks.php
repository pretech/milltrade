<?php
    if(current_user_info('type')=="admin")
    {
        heading("", "", "");
        if(isset($_REQUEST['process']))
        {
            if($_REQUEST['process']=="add")
            {
				$ac_no=aim_num_to_en($_POST[account_no]);
                $sql="insert into bank_list(bank_name,account_no) values ('$_POST[bank_name]','$ac_no')";

                if(!mysql_query($sql,$link))
                {
                    die('Error:'.mysql_error());
                }
               // header("location:".BASE."/banks");
            }
            elseif($_REQUEST['process']=="delete")
            {
                $id=$_REQUEST['id'];
                //delete the record which has $id as it's primary key
            }
        }

        if($break[$start+1]=="add")
        {
            $base=BASE;
            echo <<<EOT
<form action="$base/banks/?process=add" method="POST">
    
    <h3>ব্যাংক সংযুক্ত করুন</h3>

    <table>

        <tr>
            <td>ব্যাংকের নাম</td>
            <td>:</td>
            <td><input type="text" name="bank_name"></td>

        </tr>


        <tr>
            <td>একাউন্ট নং</td>
            <td>:</td>
            <td><input type="text" name="account_no"></td>

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
        else
        {
            echo "<a class='anchor' href='".BASE."/banks/add'>নতুন ব্যাংক সংযুক্ত করুন</a>";
            //show all bank accounts with a link to delete as base/?process=delete&id=key (key means primary key of the record)
        }
    }
    else header("location:".BASE."/home");
?>
