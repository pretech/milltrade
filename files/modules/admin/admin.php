<?php
if(current_user_info('type')=="admin")
{
    heading("", "", "");
    echo "<a href='".BASE."/products'>পণ্য প্যানেল</a><br>";
    echo "<a href='".BASE."/employee'>কর্মচারী প্যানেল</a><br>";
    echo "<a href='".BASE."/banks'>ব্যাংক প্যানেল</a><br>";
    footing();
}
else header("location:".BASE."/login");
?>
