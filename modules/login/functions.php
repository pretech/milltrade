<?php
/*
 * Project: tuberPro
 * Owner: Kreative4
 * Version: 1.0
 * Script Version: 1.0
 * Author: Moin Uddin
 */

function login_form()
{
    $base=BASE;
    
    if(!isset($_SESSION['auth_user']))
    {
        heading("Tuberpro - Login", "", "");
        
        echo "<div align='center'><form action='".BASE."/login' method='post'>";
        echo <<<form
<br><br><div style='font-size: 24px; border: 5px solid #7d0e12; border-radius: 5px; padding: 5px; width: 340px'><strong>প্রবেশ করুন</strong></div>
<br><table style='border: 5px solid #7d0e12; border-radius: 5px; padding: 5px; width: 360px'>
    <tr>
        <td><strong>ব্যবহারকারীর নাম:</strong></td>
        <td><input type='text' name='username'></td>
    </tr>

    <tr>
        <td><strong>পাসওয়ার্ড:</strong>
        <td><input type='password' name='password'></td>
    </tr>

    <tr>
        <td></td>
        <td><a  href='$base/login/?option=forget'>পাসওয়ার্ড ভুলে গেছেন?</a>
    </tr>

    <tr>
        <td></td>
        <td><input type='submit' value='প্রবেশ করুন'></td>
    </tr>
</table>
form;
        echo "</form></div>";
        
        footing();
    }
}

function help_to_remember()
{
    heading("Tuberpro - Remember Password", "", "");

    if(isset($_POST['email']))
    {
        $sql="SELECT id, username, password, email FROM users WHERE email='".$_POST['email']."'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)>0)
        {
            $arr=mysql_fetch_array($result);
            $username=$arr['username'];
            $password=rand(100000,99999999);
            $result=mysql_query("UPDATE users SET password='".md5($password)."' WHERE id='".$arr['id']."'");
            $from="noreply@tuberpro.com";
            $to=$arr['email'];
            $subject="Password Retrieval Help - TuberPro";
            $headers="From: " . $from;
            
            $content=<<<mail
Hi,
This is an email regarding your request of password and user name retrieval from TuberPro. Your User Name and Auto-Generated password is given below. Please, change your password from your profile after login.
Your User Name: $username
Your Password: $password
Thank you for using TuberPro. If you need further help, feel free to contact our support.

Regards,
TuberPro Team
http://www.tuberpro.com
mail;
            if($result) mail($to,$subject,$content,$headers);

            echo "Your Username and Password has been Sent to Your Email. Please, Check Your Email Inbox/Spam Folder.";
        }
        else
        {
            echo "There is No User Account Associated with the Email You have Submitted. Please, <a class='anchor' href='".BASE."/login/?option=forget'>Try Again</a> with Proper Information.";
        }
    }
    else
    {

        echo "<br><br><form action='".BASE."/login/remember' method='post'>";
        echo "<strong>আপনার ইমেইল ঠিকানা:</strong> <input type='text' name='email'> <input type='submit' value='Submit'>";
        echo "</form>";
    }

    footing();
}

function login()
{
    //echo md5(SPICE.$_POST['password']); die();
    $result=mysql_query("SELECT * FROM users WHERE (username='".$_POST['username']."' OR email='".$_POST['username']."') AND password='".md5($_POST['password'])."'") or die(mysql_error());
    if(mysql_num_rows($result)>0)
    {
        $arr=mysql_fetch_array($result);
        $user_info['id']=$arr['id'];
        $user_info['username']=$arr['username'];
        $user_info['email']=$arr['email'];
        $user_info['type']=$arr['type'];
        $user_info['business']=$arr['business'];
        $user_info['name']=$arr['name'];
        $_SESSION['auth_user']=$user_info;
        

        set_flash_message("You have Successfully logged in", 1);
    }
    else
    {
        set_flash_message("Login Failed: Username or Password Mismatch", 0);
        header("location:".BASE."/login");
        die();
    }

    header("location:".BASE."/home");
}

function logout()
{
    session_destroy();
    header("location:".BASE);
}
?>
