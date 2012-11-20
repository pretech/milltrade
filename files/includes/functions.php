<?php
function heading($title, $description, $keywords)
{
    $base=BASE;
   /* $flash_message=get_flash_message();
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

<head><title>এস. এস অটো রাইস মিল</title>

   <meta http-equiv="content-type" content="text/html;charset=utf-8">
   <link rel="stylesheet" type="text/css" href="$base/css/superfish.css" media="screen">
   <script type="text/javascript" src="$base/js/jquery-1.2.6.min.js"></script>
   <script type="text/javascript" src="$base/js/hoverIntent.js"></script>
   <script type="text/javascript" src="$base/js/superfish.js"></script>
   <script type="text/javascript">

  // initialise plugins
  jQuery(function(){
	jQuery('ul.sf-menu').superfish();
  });

  </script>

</head>

<body>


</script>

	<div class="logo"><img src="$base/files/images/logo.jpg"/></div>

<ul class="sf-menu">
			<li class="current">
				<a href="$base/home">Home</a>			
			</li>
    
			<li>
				<a href="#">Mills</a>
    
                                <ul>
					<li>
						<a href="$base/invoices/1">Rice Mills 1</a>
						<ul>
							<li><a href="$base/invoices/1/create/?type=service">Take Order</a></li>
							<li><a href="$base/invoices/1/queue">Queue</a></li>
						</ul>
					</li>
					<li>
						<a href="$base/invoices/2">Rice Mills 2</a>
						<ul>
							<li><a href="$base/invoices/1/create/?type=service">Take Order</a></li>
							<li><a href="$base/invoices/1/queue">Queue</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Flour Mills</a>
						<ul>
							<li><a href="#">Take Order</a></li>
							<li><a href="#">Queue</a></li>
						</ul>
					</li>				
					
				</ul>
    
			</li>
    
    
    
                        <li>
				<a href="$base/invoices/3">Trading</a>
    
                                <ul>
					<li>
						<a href="$base/invoices/3/create/?type=sales">Sales</a></li>
					<li>
						<a href="$base/invoices/3/create/?type=purchase">Purchase</a></li>					
					
				</ul>
    
    
			</li>	
    
			<li>
				<a href="#">Administration</a>
				<ul>
					<li>
						<a href="$base/employee">Employee Panel</a>
						<ul>
							<li><a href="$base/employee/?option=add">Add New</a></li>
							<li><a href="#">Employee List</a></li>							
						</ul>
					</li>
					<li>
						<a href="$base/products">Products Panel</a>
						<ul>
							<li><a href="$base/products/add">Add New</a></li>
							<li><a href="$base/products">Products List</a></li>							
						</ul>
					</li>
					<li>
						<a href="$base/banks">Bank Panel</a>
						<ul>
							<li><a href="$base/banks/add">Add New</a></li>
							<li><a href="#">Bank List</a></li>							
						</ul>
					</li>
    
    <li>
						<a href="#">Reports</a>
						<ul>
							<li><a href="#">Add</a></li>
							<li><a href="#">List</a></li>							
						</ul>
					</li>
					
				</ul>
			</li>
			<li>
				<a href="$base/login/?logout=true">Log Out</a>
			</li>	
		</ul>

	   <!----------------End: Navigation----------------->

     <div style="clear: both"></div>

     <div>

         স্বাগতম <b><i></i></b>
     </div>
     <div>
         আপনার অবস্থান:

     </div>
     <hr />
     <div style="min-height: 400px;">
html;
}

function footing()
{
    $base=BASE;
    echo <<<html
								</div>
	<div style="clear: both"></div>
	<hr/>
	<div class="footer">
				<div style="float:left; margin-top: 15px; padding: 5px">Copyright &copy; এস. এস অটো রাইস মিল</div>
				<div style="float:right; margin-top: 15px; padding: 5px">Developed By: <a href="http://www.precursortechnology.com" style="text-decoration: none;" target="_blank">Precursor Technology</a></div>

			</div>

	</body>
	</html>
html;
}

function top_menus()
{
    if(logged_in())
    {
        return "Hi, <strong><a href='".BASE."/profile'>".current_username()."</a></strong> | <a href='".BASE."/projects'>Projects</a> | <a href='".BASE."/videos/all'>Videos</a>| <a href='".BASE."/resources'>Resources</a> | <a href='".BASE."/login/?logout=true'>বাহির</a>";
    }
    else return "<a href='".BASE."'>Home</a> | <a href='".BASE."/login'>প্রবেশ করুন</a>";
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

    if($current_page>1) echo "<a href='".$url."/page/".($current_page-1)."'><input type='submit' value='<<<পূর্বে'></a>";
    if($current_page<($total_pages)) echo "<a href='".$url."/page/".($current_page+1)."'><input type='submit' value='পরে>>>'></a>";
}
?>