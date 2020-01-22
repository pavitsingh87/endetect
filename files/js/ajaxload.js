function dividbox()
{
if (str=="") {
    document.getElementById("divbox").innerHTML="";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("divbox").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","services/homestream.php",true);
  xmlhttp.send();
}