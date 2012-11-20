function calculate_single_total(base, qty, id)
{
  var product=document.forms["invoice"]["product_no"+id].value;
  document.getElementById("tpr_"+id).innerHTML="Loading...";

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("tpr_"+id).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",base+"/invoices/?process=make_row_total&product="+product+"&qty="+qty+"&row="+id,true);
xmlhttp.send();
}

function calculate_single_ptotal(id)
{

  var per_unit=document.getElementById("pper_unit"+id).value;
  var qty=document.getElementById("pqty"+id).value;
  
  var others=document.getElementById("others"+id).value;
  
  
  others=parseInt(others);
  var total= (per_unit*qty)+others;
  document.getElementById("tpr_"+id).innerHTML="<input type='text' name='price"+id+"' value='"+total+"' size='5' onkeyup=\"remove_total()\">";
}

/*
function refresh_other_fields(base, id)
{
    document.getElementById("qtyf_"+id).innerHTML="<input type='text' name='qty"+id+"' size='3' onkeyup=\"calculate_single_total('"+base+"', this.value, "+id+")\" value=''>";
    document.getElementById("tpr_"+id).innerHTML="<input type='text' name='price"+id+"' size='5' onkeyup=\"remove_total()\">";
    document.getElementById("procbut").innerHTML="";
    document.getElementById("gtotal").innerHTML="";
}
*/
function refresh_other_fields(base, id, business)
{
    var product=document.forms["invoice"]["product_no"+id].value;

    document.getElementById("qtyf_"+id).innerHTML="<input type='text' id='pqty"+id+"' name='qty"+id+"' size='3' onkeyup=\"javascript:calculate_single_ptotal("+id+"); return false;\" value=''>";
    document.getElementById("tpr_"+id).innerHTML="<input type='text' id='pprice"+id+"' name='price"+id+"' size='5' onkeyup=\"remove_total()\">";
    document.getElementById("others"+id).innerHTML="<input type='text' id='others_"+id+"' name='others"+id+"' size='3' onkeyup=\"javascript:calculate_single_ptotal("+id+"); return false;\" value=''>";
    document.getElementById("procbut").innerHTML="";
    document.getElementById("gtotal").innerHTML="";

if(product!=""){

    document.getElementById("per_unitf_"+id).innerHTML="Loading..";

    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        document.getElementById("per_unitf_"+id).innerHTML=xmlhttp.responseText;
    }
  }
    xmlhttp.open("GET",base+"/invoices/?process=get_per_unit&product="+product+"&business="+business+"&row="+id,true);
    xmlhttp.send();
}
}

function refresh_other_pfields(id)
{
    document.getElementById("qtyf_"+id).innerHTML="<input type='text' id='pqty"+id+"' name='qty"+id+"' size='3' onkeyup=\"javascript:calculate_single_ptotal("+id+"); return false;\" value=''>";
    document.getElementById("per_unitf_"+id).innerHTML="<input type='text' id='pper_unit"+id+"' name='per_unit"+id+"' size='3' value=''>";
    document.getElementById("others"+id).innerHTML="<input type='text' id='others_"+id+"' name='others"+id+"' size='3' onkeyup=\"javascript:calculate_single_ptotal("+id+"); return false;\" value=''>";
    document.getElementById("tpr_"+id).innerHTML="<input type='text' id='pprice"+id+"' name='price"+id+"' size='5' onkeyup=\"remove_total()\">";
    document.getElementById("procbut").innerHTML="";
    document.getElementById("gtotal").innerHTML="";
}

function remove_total()
{
    document.getElementById("gtotal").innerHTML="";
    document.getElementById("procbut").innerHTML="";
}

function grand_total(base)
{
    var prev_total=document.forms["invoice"]["previous_total_amount"].value;
    var i=0;
    var temp;
    i=i+1;
    var req="process=grand_total&";
    
    while(i<=5)
        {
            temp=document.forms["invoice"]["price"+i].value
            req=req+"&price"+i+"="+temp;
            i=i+1;
        }

	req=req+"&previous_total="+prev_total;

    document.getElementById("gtotal").innerHTML="Loading...";
    document.getElementById("procbut").innerHTML="<input type='submit' value='প্রক্রিয়া করুন'>";

    if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("gtotal").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",base+"/invoices/?"+req,true);
xmlhttp.send();
}

function appear_due_form(id, type, base)
{
    document.getElementById("dform").innerHTML="<form action='"+base+"/invoices/?process=due_payment' method='post'><input type='hidden' name='invoice_id' value='"+id+"'><input type='hidden' name='type' value='"+type+"'><strong>Payment Amount: </strong><input type='text' name='pay_amount'> <input type='submit' value='সংরক্ষণ করুন'></form>";
}

function display_dues(defaulter, base, type)
{
    document.getElementById("duedisp").innerHTML="Loading...";

    if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("duedisp").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",base+"/invoices/?process=duedisp&type="+type+"&defaulter="+defaulter,true);
xmlhttp.send();
}
