function snapshotgal(enduserid)
{
    document.getElementById('galenduserid').value=enduserid;
    document.getElementById('galleryform').submit();
}
function watchlistfiles(str)
{
     document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif').style.display="none";
          document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/watchlistfiles.php?enduserid="+str ,true);
    xmlhttp.send();
}
function snapshotgallery(enduserid)
{
  document.getElementById("galenduserid").value=enduserid;
  document.getElementById('galleryform').submit();
}
function notifycount(ownrid)
{
  var ownerid = ownrid;
    if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp1 = new XMLHttpRequest();
      } else {
          // code for IE6, IE5
          xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp1.onreadystatechange = function() {
          if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
              var countcheck = xmlhttp1.responseText;
              var countres = parseInt(countcheck);
              if(countres>0)
              {
                document.getElementById("notificationcount").style.display="block";
                document.getElementById("notificationcount").innerHTML = xmlhttp1.responseText;
                document.getElementById("notifycnt").value=xmlhttp1.responseText;
              }
          }
      }
      xmlhttp1.open("POST","services/notificationcount.php?notifyme=1&ownerid="+ownerid,true);
      xmlhttp1.send();
}
function notifycntupdate(countnoti)
{
  
  var addval = parseInt(countnoti)+1;
  document.getElementById("notificationcount").style.display="block";
  document.getElementById("notificationcount").innerHTML = addval;
}
function homestreamdata()
{
    
    document.getElementById('loadinggif').style.display="block";
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
              document.getElementById('loadinggif').style.display="none";
              
                document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","services/homestream.php",true);
        xmlhttp.send();
}

function loadmore(id)
{
  id.style.display="none";
  document.getElementById('loadinggif').style.display="none";
  var incrementer = document.getElementById('streamuserres').value;
  var addition = parseInt(incrementer)+1;
  document.getElementById('streamuserres').value=addition;
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('loadinggif').style.display="none";
            
            $('html, body').animate({
              scrollTop: $(window).scrollTop() + $( window ).height()
            },{
              duration: 1000});
            
            $( "#streamuser" ).append(xmlhttp.responseText);
        }
    }
    xmlhttp.open("POST","services/homestream.php?streamolder=1&lastrecordid="+addition,true);
    xmlhttp.send();
}

function notistreamdata()
{
    var page = document.getElementById("streamuserres").value;
    document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif').style.display="none";
          console.log((xmlhttp.responseText).trim());
          if(((xmlhttp.responseText).trim())!='')
          {
            $("#streamuser").removeClass("hide");
            $("#load-content").removeClass("hide");
            $("#streamuser").append(xmlhttp.responseText);
            $("#ascrec").addClass("hide");
            page=parseInt(page)+1;
            document.getElementById("streamuserres").value=page;
          }
          else
          {
            $("#streamuser").addClass("hide");
            $("#load-content").addClass("hide");
            $("#ascrec").removeClass("hide");
          }
        }
    }
    xmlhttp.open("POST","services/notistream.php?p="+page,true);
    xmlhttp.send();
}
function notify(ownerid)
{
  document.getElementById('loadinggif1').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif1').style.display="none"; 
          document.getElementById("notifyme").innerHTML = xmlhttp.responseText;
      console.log(xmlhttp.responseText);
        }
    }
    xmlhttp.open("POST","services/function.php?notifyowner="+ownerid,true);
    xmlhttp.send();
  
}
function unnotify(ownerid,str)
{
  console.log(ownerid);
  console.log(str);
  console.log("services/function.php?unnotifyid="+str+"&notifyowner1="+ownerid);
  document.getElementById('loadinggif1').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif1').style.display="none"; 
                
            document.getElementById("notifyme").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/function.php?unnotifyid="+str+"&notifyowner1="+ownerid,true);
    xmlhttp.send();
}
function snapshot(enduserid,action)
{
  
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var getactionid = xmlhttp.responseText;
               
                findactionres(getactionid,'0');
            }
        }
    console.log("services/function.php?enduserid="+enduserid+"&action="+action);
        xmlhttp.open("GET","services/function.php?enduserid="+enduserid+"&action="+action,true);
        xmlhttp.send();
}

function findactionres(getactionid,counter)
{
  
  snapshotres="";
  counter++;
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttppopup = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
      xmlhttppopup = new ActiveXObject("Microsoft.XMLHTTP");
    }
  xmlhttppopup.onreadystatechange = function() {
        if (xmlhttppopup.readyState == 4 && xmlhttppopup.status == 200) {
         var snapshotres = xmlhttppopup.responseText;
         var snapshotdata =  snapshotres.split(",");
         var snapshotlen = (snapshotres.length);
        
       if(snapshotlen>15)
     {
         document.getElementById('loadinggif2').style.display="none";
         document.getElementById("popUpDiv").innerHTML="<div id='popUpDivgallery1'><div class='image' ><span class='roll hide'  onclick='stretchimgftr("+getactionid+")'></span><img src='thumbnail.php?src="+snapshotdata[0]+"&w=1129&h=635' id='"+getactionid+"' class='galimg' style='border:1px solid #000;'></div><div class='col-md-10' align='left'>"+snapshotdata[2]+" - "+snapshotdata[3]+"</div><div align='left'>"+snapshotdata[1]+"</div><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> </div>";
         decrementfunction(getactionid);
     }
         else
         {
          setTimeout(function () { findactionres1(getactionid,counter) }, 20000);
         }
          
        }
    }
  console.log("services/ondemandsnapshot.php?loadimage=1&actionid="+getactionid);
  xmlhttppopup.open("POST","services/ondemandsnapshot.php?loadimage=1&actionid="+getactionid,true);
  xmlhttppopup.send();  
}
function findactionres1(getactionid,counter)
{
  
  snapshotres="";
  counter++;
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttppopup = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
      xmlhttppopup = new ActiveXObject("Microsoft.XMLHTTP");
    }
  xmlhttppopup.onreadystatechange = function() {
        if (xmlhttppopup.readyState == 4 && xmlhttppopup.status == 200) {
         var snapshotres =  xmlhttppopup.responseText;
         var snapshotdata = snapshotres.split(",");
         var snapshotlen =  (snapshotres.length);
        
         if(snapshotlen>15)
         {
           document.getElementById('loadinggif2').style.display="none";
           document.getElementById("popUpDiv").innerHTML="<div id='popUpDivgallery1'><div class='image' ><span class='roll'  onclick='stretchimgftr("+getactionid+")'></span><img src='thumbnail.php?src="+snapshotdata[0]+"&w=1129&h=635' id='"+getactionid+"' class='galimg' style='border:1px solid #000;'></div><div align='left'>"+snapshotdata[2]+" - "+snapshotdata[3]+"</div><div align='left'>"+snapshotdata[1]+"</div><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> </div>";
           decrementfunction(getactionid);
         }
         else
         {
          document.getElementById("blanket").style="display:none";
         document.getElementById('loadinggif2').style.display="none";  
         $("#onDemandScreenshotModal").modal("show");          
         document.getElementById("errorOnDemandScreenshot").innerHTML="<center><b>Process timeout.<br /><br /> Please check notification bar in few seconds.<br /><br /></b></center>";
         }
          
        }
    }
  xmlhttppopup.open("POST","services/ondemandsnapshot.php?loadimage=1&actionid="+getactionid,true);
  xmlhttppopup.send();  
}
function decrementfunction(getactionid)
{
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttpondemandnotify = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
      xmlhttpondemandnotify = new ActiveXObject("Microsoft.XMLHTTP");
    }
  xmlhttpondemandnotify.onreadystatechange = function() {
        if (xmlhttpondemandnotify.readyState == 4 && xmlhttpondemandnotify.status == 200) 
        {
           var snapshotres = xmlhttpondemandnotify.responseText;
           if(snapshotres=='1' || snapshotres==1) 
           {
            var addval = parseInt(document.getElementById('notifycnt').value)-1;
            if(addval<0)
            {

            }
            else
            {
              document.getElementById("notificationcount").style.display="block";
              document.getElementById("notificationcount").innerHTML = addval;
            }
           }
        }
    }
  xmlhttpondemandnotify.open("POST","services/ondemandsnapshot.php?updatenotify=1&actionid="+getactionid,true);
  xmlhttpondemandnotify.send(); 
  
}
function changeimage(event)
{
  var myeventid = event.id;
  var childid = myeventid.split("-");

  $("#"+event.id).slideToggle(200,function()
    {
      document.getElementById(event.id).style.display="none";
    document.getElementById("childdiv-"+childid['1']).style.display="block";
    });
  
}
function changeimage1(event)
{
  var myeventid = event.id;
  var childid = myeventid.split("-");

  $("#"+event.id).slideToggle(200,function()
    {
      document.getElementById(event.id).style.display="none";
    document.getElementById("frontimage-"+childid['1']).style.display="block";
    });
  
}

function keyupre1()
{
  var optsearch = document.getElementById('optionsearch').value;
  var textsearch = document.getElementById('searchInput').value;
  $("#searchInput").autocomplete({
    source: "autocompleteb.php?dtype="+optsearch,
    delay:0,
    select: function(event, ui) {
    var getUrl = ui.item.id;
    var ser = getUrl.split("-");
    document.getElementById('optionsearch').value=ser[0];
    document.getElementById('frm1').submit();
    },
    html: true, 
    open: function(event, ui) {
    $(".ui-autocomplete").css("z-index", 1000);
    
    }
    });
  
}

function optionsearchbln()
{
  var optionsrch = document.getElementById("optionsearch").value;
  
  if(optionsrch>0)
  {
    document.getElementById("optionsearch").value="";
  }
  
}
function searchinchn(searchinval)
{
  document.getElementById("optionsearch").value=searchinval;
}
function testchange1()
{
  var enduserid;
  var dtype;
  var enduserid;
  var stringlen;
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  
  ddatasearch=ddatasearch.trim();
  

  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  
  
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg);
  

  $("#ui-id-1").removeAttr('style');
  $("#ui-id-1").css('display', 'none');
      
  
}
function advancesearchoption()
{
  enduserid= document.getElementById("getusersearchid").value;
  dtype=document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg);
}
function allchangedtype(dtype)
{

  enduserid= document.getElementById("getusersearchid").value;
  document.getElementById("optionsearch").value=dtype;
  
  ddatasearch = "";
  
  ddatasearch=ddatasearch.trim();
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  
  
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg);
}
function changedtype(dtype)
{
  document.getElementById("searchinobj").value = dtype;
  enduserid= document.getElementById("getusersearchid").value;
  document.getElementById("optionsearch").value=dtype;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg);
  if(dtype=="11")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in all data";
  }
  else if(dtype=="2")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in typed text";
  }
  else if(dtype=="3")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in copied text";
  }
  else if(dtype=="4")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in copied files";
  }
  else if(dtype=="5")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in pendrive files";
  }
  else if(dtype=="13")
  {
      if(document.getElementById("categorytag"))
      document.getElementById("categorytag").innerHTML="Search in watchlist files";
  }
}
function changeinludeimg()
{
  if((document.getElementById("notincludeimg").checked)==true)
  {
    document.getElementById("ntincludeimg").value="1";
  }
  else
  {
    document.getElementById("ntincludeimg").value="0";
  }
  
}
function searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,notincludeimg,searchtypes)
{
    
    document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif').style.display="none";
          
          document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/searchresult.php?enduserid="+enduserid+"&dtype="+dtype+"&ddatasearch="+ddatasearch+"&musthave="+musthave+"&mustnothave="+mustnothave+"&fromda="+fromda+"&toda="+toda+"&ntinclude="+notincludeimg+"&searchtypes="+searchtypes,true);
    xmlhttp.send();
}
function loadmorestream(id)
{
  var enduserid;
  var dtype;
  var enduserid;

  id.style.display="none";
  
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  
  
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  
  
  ddatasearch=ddatasearch.trim();
  
  document.getElementById('loadinggif').style.display="block";
  var incrementer = document.getElementById('streamuserres').value;
  var addition = parseInt(incrementer)+1;
  document.getElementById('streamuserres').value=addition;
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('loadinggif').style.display="none";
            $('html, body').animate({
              scrollTop: $(window).scrollTop() + $( window ).height()
            },{
              duration: 1000});
            document.getElementById("streamuser").innerHTML = document.getElementById("streamuser").innerHTML + xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/searchresult.php?enduserid="+enduserid+"&dtype="+dtype+"&ddatasearch="+ddatasearch+"&musthave="+musthave+"&mustnothave="+mustnothave+"&fromda="+fromda+"&toda="+toda+"&ntinclude="+ntincludeimg+"&streamolder=1&lastrecordid="+addition+"&searchtypes="+searchtypes,true);
    
    xmlhttp.send();
}
function gallerydata(str)
{
  document.getElementById('loadinggif').style.display="block";
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();
  } else {
    // code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
      document.getElementById('loadinggif').style.display="none";
      document.getElementById("galleryimages").innerHTML = xmlhttp.responseText;
    }
  }
  xmlhttp.open("POST","services/gallery.php?enduserid="+str,true);
  xmlhttp.send();
}

function GetofGallery(str)
{
  
  document.getElementById('loadinggif1').style.display="block";
  document.getElementById('imagecnt').value=parseInt(document.getElementById("imagecnt").value)+1;
  var galcount = document.getElementById('imagecnt').value;
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          if((xmlhttp.responseText).trim()=="")
          {
            $(".panel-heading").addClass("hide");
          }
          else
          {
            document.getElementById('loadinggif1').style.display="none"; 
            $('html, body').animate({
                  scrollTop: $(window).scrollTop() + $( window ).height()
              },{
                duration: 1000});
            document.getElementById("galleryimages").innerHTML += xmlhttp.responseText;
          }
        }
    }
    xmlhttp.open("POST","services/gallery.php?enduserid="+str+"&counter="+galcount,true);
    xmlhttp.send();
}
function galleryimages(str)
{
  
  document.getElementById('loadinggif1').style.display="block";
  var galcount = document.getElementById('imagecnt').value;

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif1').style.display="none"; 
           document.getElementById("galleryimages").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/gallery.php?enduserid="+str+"&counter="+galcount,true);
    xmlhttp.send();
}
function nextgalleryimage(sno,action)
{
  document.getElementById('loadinggif2').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() 
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
        {
          document.getElementById('loadinggif2').style.display="none"; 
          document.getElementById("popUpDiv").innerHTML = xmlhttp.responseText;
          $("#stretch").removeClass("stretchimg1").addClass("stretchimg");
        $("#prev").removeClass("leftimg1").addClass("leftimg");
        $("#next").removeClass("rightimg1").addClass("rightimg"); 
        }
    }
    xmlhttp.open("POST","services/nextprev.php?prevnext=0&sno="+lastsno,true);
    xmlhttp.send();

}
function prevgalleryimage(sno)
{
  var lastrec = document.getElementById("imagecounter").value;
  var nextrec =parseInt(lastrec)-1;
  var lastsno = document.getElementById("recordsno").value;
  document.getElementById('imagecounter').value=nextrec;
  
  if(nextrec > 0)
  {
    document.getElementById('loadinggif2').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif2').style.display="none"; 
           document.getElementById("popUpDiv").innerHTML = xmlhttp.responseText;
           $("#stretch").removeClass("stretchimg1").addClass("stretchimg");
            $("#prev").removeClass("leftimg1").addClass("leftimg");
            $("#next").removeClass("rightimg1").addClass("rightimg"); 
        }
    }
    xmlhttp.open("POST","services/galleryimg.php?enduserid="+str+"&nextrec="+nextrec+"&lastsno="+lastsno,true);
    xmlhttp.send();
  }
  
}
function heading(str)
{
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif2').style.display="none"; 
           document.getElementById("popUpDiv").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST","services/galheading.php?enduserid="+str+"&type=gallery",true);
    xmlhttp.send();
  
}
function readmorediv(str,str1,str2)
{
  $("#"+str).removeClass("closemoreclass").addClass("readmoreclass").slideDown('slow');
  document.getElementById(str1).style.display="none";
  document.getElementById(str2).style.display="none";
}

function stretchimage(enduserid)
{
  var stretchcheck = document.getElementById('stretchcheck').value;
  
  
  if(stretchcheck=='0')
  {
    $("#"+enduserid).removeClass("galimg").addClass("galimg1");
    $("#stretch").removeClass("stretchimg").addClass("stretchimg1");
    $("#prev").removeClass("leftimg").addClass("leftimg1");
    $("#next").removeClass("rightimg").addClass("rightimg1");
    document.getElementById('stretchcheck').value='1';
  }
  if(stretchcheck=='1')
  {
    $("#"+enduserid).removeClass("galimg1").addClass("galimg");
    $("#stretch").removeClass("stretchimg1").addClass("stretchimg");
    $("#prev").removeClass("leftimg1").addClass("leftimg");
    $("#next").removeClass("rightimg1").addClass("rightimg");
    document.getElementById('stretchcheck').value='0';
  }
}

function stretchimgftr(enduserid)
{

  var width = ($(window).width());
  var height = ($(window).height());
  
  var galimgset="";
  var rightimgfooter="";
  
  if(width>='1300' && width<='1450')
  {
    galimgset = "galimg1";
    rightimgfooter = "rightimgfooter1";
  }
  if(width>='1500' && width<='1800')
  {
    galimgset = "galimg2";
    rightimgfooter = "rightimgfooter1";
  }
  var stretchcheck = document.getElementById('stretchcheck').value;
  
  if(stretchcheck=='0')
  {
    /*strupt = document.getElementById(enduserid).src;*/
   /* strupt = strupt.replace("1129","1366");
    strupt = strupt.replace("635","768");*/
    /*document.getElementById(enduserid).src=strupt;*/
    $("#"+enduserid).removeClass("galimg").addClass(galimgset);
    $("#stretch").removeClass("stretchimg").addClass("stretchimg1");
    $("#prevfooter").removeClass("leftimgfooter").addClass("leftimgfooter1");
    $("#nextfooter").removeClass("rightimgfooter").addClass(rightimgfooter);
    document.getElementById('stretchcheck').value='1';
  }
  if(stretchcheck=='1')
  {
    /*strupt = document.getElementById(enduserid).src;*/
    /*strupt = strupt.replace("1366","1129");
    strupt = strupt.replace("500","635"); 
    */
    /*document.getElementById(enduserid).src=strupt;*/
    $("#"+enduserid).removeClass(galimgset).addClass("galimg");
    $("#stretch").removeClass("stretchimg1").addClass("stretchimg");
    $("#prevfooter").removeClass("leftimgfooter1").addClass("leftimgfooter");
    $("#nextfooter").removeClass(rightimgfooter).addClass("rightimgfooter");
    document.getElementById('stretchcheck').value='0';
  }
}
function stretchimgf(enduserid)
{

  var width = ($(window).width());
  var height = ($(window).height());
  var galimgset="";
  var rightimgfooter="";
  
  if(width>='1300' && width<='1350')
  {
    galimgset = "galimg1";
  }
  if(width>='1500' && width<='1800')
  {
    galimgset = "galimg1";
  }
  var stretchcheck = document.getElementById('stretchcheck').value;
  
  if(stretchcheck=='0')
  {
    $("#"+enduserid).removeClass("galimg").addClass(galimgset);
    document.getElementById('stretchcheck').value='1';
  }
  if(stretchcheck=='1')
  {
    $("#"+enduserid).removeClass(galimgset).addClass("galimg");
    document.getElementById('stretchcheck').value='0';
  }
}
function deletePostData()
{
  var pid  = $("#pid").val();
  var pin  = $("#delete-field").val();
  var ptype  = $("#ptype").val();
  if(pin.length=="4")
  {
    deleteflag(pid,pin,ptype);  
    $("#pinpostError").addClass("hide");
  }
  else
  {
    $("#pinpostError").text("Must be 4 numbers");
    $("#pinpostError").removeClass("hide");
  }
}
function deletefromlist(blockid) {
    $("#DeletePostModal").modal("show");
    $("#pid").val(blockid);
    $("#ptype").val("1");
    /*$("#deleteModal").modal();
    document.getElementById("blockid").value=blockid;*/
    /*$("#dialog-confirm").html("<div style='position:relative;top:20px'><img src='images/question.gif' style='float:left;'><div style='font-size:12px;line-height: 20px;color: #63737F;position:relative;left:5px;top:10px;padding-bottom:30px;'> Are you sure you want to delete this post.</div></div>");

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Confirm",
        height: 200,
        width: 300,
        buttons: {
            "Yes": function () {
                $(this).dialog('close');
                callback(true,blockid);
            },
                "No": function () {
                $(this).dialog('close');
                callback(false,blockid);
            }
        }
    });*/
}


function callback()
{
  var postid = document.getElementById("blockid").value;
  deleteflag(postid);
}
/*function callback(value,postid) {
    if (value) {
        deleteflag(postid);
    } else {
       
    }
}*/

function deleteflag(postid, pin, ptype)
{
  document.getElementById('loadinggif2').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpdel = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttpdel = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttpdel.onreadystatechange = function() {
        if (xmlhttpdel.readyState == 4 && xmlhttpdel.status == 200) {
            var gh = xmlhttpdel.responseText;
          
            if(gh=="1" || gh==1)
            {
              $("#pinpostError").text("Pin is incorrect");
              $("#pinpostError").removeClass("hide");
            }
            else if(gh=='2' || gh==2)
            {
              if(ptype=="1" || ptype==1)
              {
                $("#DeletePostModal").modal("hide");
                $("#delete-field").val("");
                $("#pinpostError").addClass("hide");
                document.getElementById("endetect"+postid).innerHTML = "";
                document.getElementById("endetect"+postid).style.display="none";
                $("#ptype").val("");
              }
              else if(ptype=="2" || ptype==2)
              {
                $("#delete-field").val("");
                $("#DeletePostModal").modal("hide");
                $("#pinpostError").addClass("hide");
                $("#ptype").val("");
                nextgalfooter(postid);

              }
            }
          }
        }
    xmlhttpdel.open("POST","services/deleteflagservice.php?recordid="+postid+"&pin="+pin,true);
    xmlhttpdel.send();
}
function deleteimagefromlist(postid)
{
  $("#DeletePostModal").modal("show");
  $("#pid").val(postid);
  $("#ptype").val("2");
}
function imagewatchlist(postid,action)
{
    document.getElementById('loadinggif2').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpwatch = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttpwatch = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttpwatch.onreadystatechange = function() {
        if (xmlhttpwatch.readyState == 4 && xmlhttpwatch.status == 200) {
          var responsetxt = xmlhttpwatch.responseText;
          document.getElementById('loadinggif2').style.display="none"; 
          
          if(responsetxt=='1')
          {
            if(action=='1')
              {
                document.getElementById("watchlistimage"+postid).innerHTML = "<img onclick='imagewatchlist("+postid+",0)' src='images/watchlistgreen.png' style='height:20px;'>";
              } 
              else 
              {
                document.getElementById("watchlistimage"+postid).innerHTML = "<img onclick='imagewatchlist("+postid+",1)' src='images/watchlist.png' style='height:20px;'>";
              }
          }
          
        }
    }
    xmlhttpwatch.open("POST","services/watchlistservice.php?recordid="+postid+"&action="+action,true);
    xmlhttpwatch.send();
}
function watchlist(postid,action)
{
  document.getElementById('loadinggif2').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpwatch = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttpwatch = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttpwatch.onreadystatechange = function() {
        if (xmlhttpwatch.readyState == 4 && xmlhttpwatch.status == 200) {
          var responsetxt = xmlhttpwatch.responseText;
          document.getElementById('loadinggif2').style.display="none"; 
          
          if(responsetxt=='1')
          {
            if(action=='1')
              {
              document.getElementById("watchlistimg"+postid).innerHTML = '<img src="images/watchlist.png">';
                document.getElementById("addtowatchlist"+postid).innerHTML = '<a role="menuitem" tabindex="-1" onclick="watchlist('+postid+',0)">Remove from watchlist</a>';
              } 
              else 
              {
                document.getElementById("watchlistimg"+postid).innerHTML = '';
                document.getElementById("addtowatchlist"+postid).innerHTML = '<a role="menuitem" tabindex="-1" onclick="watchlist('+postid+',0)">Add to watchlist</a>';
              }
          }
          
        }
    }
    xmlhttpwatch.open("POST","services/watchlistservice.php?recordid="+postid+"&action="+action,true);
    xmlhttpwatch.send();
}
function edituser(enduserid,name,dept,designation,groupid)
{
  $("#editUserModal").modal("show");
  
  $("#edituser_enduserid").val(enduserid);
  $("#edituser_first-name").val(name);
  $("#edituser_dept").val(dept);
  $("#edituser_designation").val(designation);
  
  $("#edituser_group [value='"+groupid+"']").attr("selected","selected");
}
function closepopupedituser()
{
  toggle('edituser_blanket');
  toggle('edituser_popUpDiv');
  
}
function usersubmitpopup()
{
  var name = ($("#edituser_first-name").val());
  var enduserid = ($("#edituser_enduserid").val());
  var group = ($("#edituser_group").val());
  var dept = ($("#edituser_dept").val());
  var designation = ($("#edituser_designation").val());
   $.ajax({
         dataType: 'json',
         url: "services/edituserdet.php?edituser=1&enduserid="+enduserid+"&name="+name+"&group="+group+"&dept="+dept+"&designation="+designation+"&imagepath=",
         success: function (data) {
         if(data.status=='success')
         {
           document.getElementById("edituser_success").style.display="block";
           setTimeout(function(){ $("#editUserModal").modal("hide"); }, 500);
         }
           
      }
    });             
       
    
}
function edituserdetails(name,enduserid,group,dept,designation)
{
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpuser = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttpuser = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttpuser.onreadystatechange = function() {
        if (xmlhttpuser.readyState == 4 && xmlhttpuser.status == 200) {
          var getuserval=xmlhttpuser.responseText;
          
          if(getuserval=='1')
        {
            blanket_size('popUpDiv');
              window_pos('popUpDiv');
            toggle('blanket');
              toggle('popUpDiv');
        }
        }
    }
    xmlhttpuser.open("POST","services/edituserdet.php?edituser=1&enduserid="+enduserid+"&name="+name+"&group="+group+"&dept="+dept+"&designation="+designation+"&imagepath=",true);
    xmlhttpuser.send();
}
function addnote(postid,action)
{ 
  
  
  if(action=='1')
  {
    $("#note_postid").val(postid);
    $("#addNote").modal("show");
    document.getElementById("shownoteheader"+postid).style.display="none";
    toggle('divfirstnote');
    note = document.getElementById("editnote"+postid).value;
      
    document.getElementById("editnote").value=note;
  }
  else
  {
    document.getElementById("note"+postid).innerHTML="";
    document.getElementById("editnote").value="";
    $.ajax({
        dataType: 'json',
        url: "services/notesave.php?noteflag=0&postid="+postid+"&notetext=",
        success: function (data) {
           if(data.status=='success')
           {
             document.getElementById("editnote"+postid).value="";
             document.getElementById("note"+postid).innerHTML="";
             document.getElementById("addtonotelist"+postid).innerHTML = '<a role="menuitem" tabindex="-1" onclick="addnote('+postid+',1)">Add note</a>';
           }
             
        }
      });   
  }
}
function savenote()
{
  var notetext= document.getElementById("editnote").value;
  var postid = document.getElementById("note_postid").value;
  
  $.ajax({
    dataType: 'json',
    url: "services/notesave.php?noteflag=1&postid="+postid+"&notetext="+notetext,
    success: function (data) {
       if(data.status=='success')
       {
         document.getElementById("editnote"+postid).value=notetext;
         document.getElementById("note"+postid).innerHTML="<span class='glyphicon glyphicon-list-alt' onclick='shownote("+postid+")'></span>";
             document.getElementById("addtonotelist"+postid).innerHTML = '<a role="menuitem" tabindex="-1" onclick="addnote('+postid+',0)">Remove note</a>';
         document.getElementById("note_success").style.display="block";
         setTimeout(function(){ closepopupaddnote(); }, 500);
       }
         
    }
  });   
  
}
function shownote(postid)
{
  note = document.getElementById("editnote"+postid).value;
  document.getElementById("shownoteheader"+postid).style.display="block";
  document.getElementById("shownote"+postid).innerHTML=note;
}
function closenote(postid)
{
  
  document.getElementById("shownoteheader"+postid).style.display="none";
  document.getElementById("shownote"+postid).innerHTML="";
}
function closepopupaddnote()
{
  toggle('addnote_blanket');
  toggle('addnote_popUpDiv');
  toggle('divfirstnote');
  document.getElementById("note_success").style.display="none";
}
function latestimages(str)
{
  
  document.getElementById('loadinggif').style.display="block";
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttplatimg = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
      xmlhttplatimg = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttplatimg.onreadystatechange = function() {
        if (xmlhttplatimg.readyState == 4 && xmlhttplatimg.status == 200) {
          document.getElementById('loadinggif').style.display="none";
          
            document.getElementById("latestimages").innerHTML = xmlhttplatimg.responseText;
        }
    }
    xmlhttplatimg.open("POST","services/latestimages.php?enduserid="+str,true);
    xmlhttplatimg.send();
}
function liverec(str,action)
{
  
  document.getElementById('loadinggif').style.display="block";
  document.getElementById('streamuserres').value=action;
  $( "#sidebar-container" ).css("display", "none");
  $( "#show-cont" ).css("display", "block");
  if(action=='1')
  {
  $( "#livehref" ).css("display", "none");
  $( "#exitlivehref" ).css("display", "block");
  $( "#load-content" ).css("display", "block");
  }
  if(action=='0')
  {
  $( "#livehref" ).css("display", "block");
  $( "#exitlivehref" ).css("display", "none");
  $( "#load-content" ).css("display", "none");
  }
  

    
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggif').style.display="none";             
            document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
            var streamtrue = document.getElementById('streamuserres').value;
            
            if(streamtrue=='1')
            {  
        
              setTimeout(function(){ newdatacome(str); }, 3000);
            }
           
        }
    }
    xmlhttp.open("POST","services/userstream.php?enduserid="+str,true);
    xmlhttp.send();
}
function closepopupgallery()
{
  toggle('blanketgallery');
  toggle('popUpDivgallery');
  document.getElementById("popUpDivgallery").innerHTML = "";
  
}
function closepopup()
{
  toggle('blanket');
  toggle('popUpDiv');
  
}

function popuphome(windowname,src,dtype,apptitle) {

  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanket');
  toggle(windowname);
  if(dtype=='12')
  {
    document.getElementById("popUpDiv").innerHTML='<div style="background:#fff;width:300px;height:200px;text-align:center;margin-top:20px;"><div><h2> Add Group </h2></div><br><div> Group Name <input type="text" ng-model="add_group" name="add_group"></div><br><div><input type="button" name="lalith" onclick="addgroup()" value="lalith"></div>';
  }
  if(dtype=='21' || dtype=='20')
  {
    document.getElementById("popUpDiv").innerHTML="<div align='center' style='padding:10px;'>"+apptitle+"</div><div><img src='thumbnail.php?src="+src+"&w=1129&h=635'></div>";
  }
}
function popuphome(windowname,src,dtype,apptitle,enduserid,name) {

  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanket');
  toggle(windowname);
  if(dtype=='12')
  {
    document.getElementById("popUpDiv").innerHTML='<div style="background:#fff;width:300px;height:200px;text-align:center;margin-top:20px;"><div><h2> Add Group </h2></div><br><div> Group Name <input type="text" ng-model="add_group" name="add_group"></div><br><div><input type="button" name="lalith" onclick="addgroup()" value="lalith"></div>';
  }
  if(dtype=='21' || dtype=='20')
  {
    document.getElementById("popUpDiv").innerHTML="<input type='hidden' value='0' name='stretchcheck' id='stretchcheck'><div style='z-index: 99999999;position: fixed;padding: 10px;  top: 50%;left: 50%; transform: translate(-50%, -50%);background-color: #FFF;box-shadow: 4px 4px 80px;border-radius: 8px;border: 5px solid #3A5795;'><div class='image'><span class='roll'  onclick='stretchimgf("+enduserid+")'></span><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div><b>"+name+"</b><br />"+apptitle+"</div></div>";
  }
}
function popup(windowname,src,dtype,sno,countr,apptitle,enduserid) {

  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanket');
  toggle('counterprevnext');
  toggle(windowname);
  document.getElementById("imagelastid").value=countr;
  document.getElementById("recordsno").value=sno;
  if(dtype=='12')
  {
    document.getElementById("popUpDiv").innerHTML='<div style="background:#fff;width:300px;height:200px;text-align:center;margin-top:20px;"><div><h2> Add Group </h2></div><br><div> Group Name <input type="text" ng-model="add_group" name="add_group"></div><br><div><input type="button" name="lalith" onclick="addgroup()" value="lalith"></div>';
  }
  if(dtype=='21' || dtype=='20')
  {
    document.getElementById("popUpDiv").innerHTML="<div align='center' style='padding:10px;'> <img src='images/stretch.png' class='stretchimg' id='stretch' onclick='stretchimage("+enduserid+")'>"+apptitle+"</div><div><img src='thumbnail.php?src="+src+"' id='"+enduserid+"&w=1129&h=635' class='galimg'></div>";
  }
}

/* Prev User gallery Image */
function prevgaluser(sno,enduserid)
{ 
  
  document.getElementById('loadinggifgal').style.display="block";
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('loadinggifgal').style.display="none"; 
           document.getElementById("popUpDivgallery").innerHTML = xmlhttp.responseText;
           
        }
    }
    xmlhttp.open("POST","services/nextprevgal.php?prevnext=1&sno="+sno+"&enduserid="+enduserid,true);
    xmlhttp.send();
  
}
/* Next User gallery Image */
function nextgaluser(sno, enduserid)
{
  
  document.getElementById('loadinggifgal').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() 
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
        {
          
          document.getElementById('loadinggifgal').style.display="none"; 
          document.getElementById("popUpDivgallery").innerHTML = xmlhttp.responseText;
          
        }
    }
    xmlhttp.open("POST","services/nextprevgal.php?prevnext=0&sno="+sno+"&enduserid="+enduserid,true);
    xmlhttp.send();
}
/* Prev Home gallery Image */
function prevgalfooter(sno)
{ 
  
  document.getElementById('loadinggifgal').style.display="block";
  if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          console.log(xmlhttp.responseText);
          console.log((xmlhttp.responseText).length);
           document.getElementById('loadinggifgal').style.display="none"; 
           if(xmlhttp.responseText!="")
           {
            document.getElementById("popUpDivgallery").innerHTML = xmlhttp.responseText;
           }
        }
    }
    xmlhttp.open("POST","services/nextprevgal.php?prevnext=1&sno="+sno,true);
    xmlhttp.send();
  
}
/* Next Home gallery Image */
function nextgalfooter(sno)
{
  
  document.getElementById('loadinggifgal').style.display="block";
  if (window.XMLHttpRequest) 
  {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } 
  else 
  {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() 
    {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
        {
          
          document.getElementById('loadinggifgal').style.display="none"; 
          if(xmlhttp.responseText!="")
           {
            document.getElementById("popUpDivgallery").innerHTML = xmlhttp.responseText;
           }
          
          
        }
    }
    xmlhttp.open("POST","services/nextprevgal.php?prevnext=0&sno="+sno,true);
    xmlhttp.send();
}
/* Popup Load Home gallery Image */
function popupgallery(windowname,watchlist,src,dtype,apptitle,sno,enduserid,name,designation) {
  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanketgallery'); 
  toggle(windowname);
  
    if(designation!='')
    {
      if(watchlist=="1" || watchlist==1)
      {
        document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+" - ("+designation+")</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",0)' src='images/watchlistgreen.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgalfooter("+sno+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgalfooter("+sno+")'></div>";
      }
      else
      {
        document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+" - ("+designation+")</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",1)' src='images/watchlist.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgalfooter("+sno+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgalfooter("+sno+")'></div>";
      }
      
    }
    else
    {
      if(watchlist=="1" || watchlist==1)
      {
        document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+"</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",0)' src='images/watchlistgreen.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgalfooter("+sno+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgalfooter("+sno+")'></div>";    
      }
      else
      {
       document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+"</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",1)' src='images/watchlist.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgalfooter("+sno+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgalfooter("+sno+")'></div>";
      }
    }
}
function webrefresh()
{
  location.reload(); 
}
/* Popup load User gallery Image */
function popupusergallery(windowname,src,dtype,apptitle,sno,enduserid,name,designation) {
  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanketgallery'); 
  toggle(windowname);
  if(designation!='')
  {
    if(watchlist=="1" || watchlist==1)
    {
      document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+" - ("+designation+")</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",0)' src='images/watchlistgreen.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgaluser("+sno+","+enduserid+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgaluser("+sno+","+enduserid+")'></div>";
    }
    else
    {
      document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+" - ("+designation+")</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",1)' src='images/watchlist.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1'  onclick='prevgaluser("+sno+","+enduserid+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgaluser("+sno+","+enduserid+")'></div>";
    }
    
  }
  else
  {
    if(watchlist=="1" || watchlist==1)
    {
      document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+"</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",0)' src='images/watchlistgreen.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1'  onclick='prevgaluser("+sno+","+enduserid+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgaluser("+sno+","+enduserid+")'></div>";    
    }
    else
    {
     document.getElementById(windowname).innerHTML="<input type='hidden' name='usergallerycurrentsno' id='usergallerycurrentsno' value='"+sno+"'><img src='images/close.png' style='display:none;' class='strclose' id='close' onclick='closepopupgallery()'><div class='image'><img src='thumbnail.php?src="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div class='divpopupname'><div class='col-md-12'><div class='col-md-9'><b>"+name+"</b><br />"+decodeURIComponent(apptitle)+"</div><div class='col-md-2 text-right'><a href='javascript:void(0)' id='watchlistimage"+sno+"' style='top:0;position:unset;left:0px;'><img onclick='imagewatchlist("+sno+",1)' src='images/watchlist.png' style='height:20px;'></a>&nbsp;<a href='javascript:void(0)' onclick='deleteimagefromlist("+sno+")' style='top:0;position:unset;left:0px;'><i class='glyphicon glyphicon-trash' style='top:-3px;'></i></a></div></div></div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'> <img src='images/sprite_prev.png' class='leftimgfooter' id='prevfooter1' onclick='prevgaluser("+sno+","+enduserid+")'><img src='images/sprite_next.png' class='rightimgfooter' id='nextfooter1' onclick='nextgalfooter("+sno+","+enduserid+")'></div>";
    }
  }
}
/*function popupgallery(windowname,src,dtype,apptitle,sno,enduserid,name) {
  
  blanket_size(windowname);
  window_pos(windowname);
  toggle('blanketgallery'); 
  toggle(windowname);
  document.getElementById(windowname).innerHTML="<img src='images/close.png' class='strclose' id='close' onclick='closepopupgallery()'><img src='images/expand.png' class='stretchimg' id='stretch' onclick='stretchimgftr("+enduserid+")'> <div><img src='thumbnail.php?file="+src+"&w=1129&h=635' id='"+enduserid+"' class='galimg' style='border:1px solid #000;'></div><div><b>"+name+"</b><br />"+apptitle+"</div><div id='counterprevnext' style='display:block;'><input type='hidden' value='0' name='stretchcheck' id='stretchcheck'></div>";
  
}*/
function showprevnext(sno,enduserid)
{
  document.getElementById("postblockendsno").value=sno;
  document.getElementById("postblockenduserid").value=enduserid;
  document.getElementById("postselblock").submit(); 
  /*
    var newurl = "postselblock.php?sno="+sno+"&enduserid="+enduserid;
    window.open(newurl);
  */
}
function iframpn(sno,enduserid)
{
  
  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("loadinggif").style.display="none";
        document.getElementById("streamuser").innerHTML = xmlhttp.responseText;
        scrolltosno(sno);
      }
  }
  xmlhttp.open("POST","services/searchfiverec.php?sno="+sno+"&enduserid="+enduserid,true);
  xmlhttp.send();
}
function nexttwo(sno,enduserid)
{
  document.getElementById("loadinggif").style.display="block";
  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("loadinggif").style.display="none";
          var resultcheck = xmlhttp.responseText;
          var prevlen = resultcheck.length;
         
          if(prevlen<20)
         {
           document.getElementById("nextrecord").innerHTML="No more result";
         }
        document.getElementById("streamuser").innerHTML = document.getElementById("streamuser").innerHTML+xmlhttp.responseText;
        
      }
  }
  xmlhttp.open("POST","services/nexttworec.php?sno="+sno+"&enduserid="+enduserid,true);
  xmlhttp.send();
}
function prevtwo(sno,enduserid)
{
  document.getElementById("prevrecord").style.display="hidden";
  document.getElementById("loadinggif").style.display="block";
  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("loadinggif").style.display="none";
          var resultcheck = xmlhttp.responseText;
          
          var prevlen = resultcheck.length;
         
        
          
          if(prevlen<20)
           {
            document.getElementById("prevrecord").innerHTML="No more result";
           }
        document.getElementById("streamuser").innerHTML = xmlhttp.responseText+document.getElementById("streamuser").innerHTML;
        document.getElementById("prevrecord").style.display="visible";
      }
  }
  xmlhttp.open("POST","services/prevtworec.php?sno="+sno+"&enduserid="+enduserid,true);
  xmlhttp.send();
}
function openimageuploader(sno)
{
  
  toggle('blanket');
  toggle("popUpDiv");
  document.getElementById("popUpDiv").innerHTML="<iframe src='imgPicker/index.php' style='width:600px;height:350px;'></iframe>";
  
}
function addhovereffect()
{
  
    $('.roll').removeClass("imgover").addClass("imgover1");
  
}
function removehovereffect()
{
  
    $('.roll').removeClass("imgover1").addClass("imgover");
  
}
function notificationbar(str)
{
  $("#notificationLink").click(function()
  {
    if(document.getElementById("notificationContainer").style.display=='none')
    {
      document.getElementById("notificationContainer").style.display="block";
      document.getElementById("notificationcount").style.display="none";
      document.getElementById("notifycnt").value="0";
      notify(str);
    }
    else
    {
      document.getElementById("notificationContainer").style.display="none";
    }
      
    
    $("#notification_count").fadeOut("slow");
    $("#logoutContainer").hide();
    return false;
  });
  $("#notificationClose").click(function()
  {   
  
    $("#notificationContainer").hide();
  });
  
    //Popup Click
  $("#notificationContainer").click(function()
  {
    return false
  });
      
}
function resetdatasearch()
{
  document.getElementById('ddatasearchtag').style.display="none";
  document.getElementById('searchInput').value="";
  
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
  
  
}
function resetcategory()
{
  document.getElementById('categorytag').style.display="none";
  document.getElementById('searchinobj').value="";
  
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
}
function resetmusthave()
{
  document.getElementById('musthavetag').style.display="none";  
  document.getElementById('musthave').value="";
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
}
function resetmustnothave()
{
  document.getElementById('mustnothavetag').style.display="none";
  document.getElementById('mustnothave').value="";
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
}
function resetfilterdate()
{
  document.getElementById('filterdatetag').style.display="none";
  document.getElementById('cutomisedate').checked=false;
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
}
function resetexcludeimages()
{
  document.getElementById('excludeimagestag').style.display="none";
  document.getElementById('notincludeimg').checked=false;
  document.getElementById('ntincludeimg').value="";
  
  enduserid= document.getElementById("getusersearchid").value;
  dtype = document.getElementById("optionsearch").value;
  ddatasearch = document.getElementById("searchInput").value;
  musthave = document.getElementById("musthave").value;
  mustnothave = document.getElementById("mustnothave").value;
  fromda = document.getElementById("example1").value;
  toda = document.getElementById("example2").value;
  ntincludeimg = document.getElementById("ntincludeimg").value;
  searchtypes = document.getElementById("searchtypes").value;
  ddatasearch=ddatasearch.trim();
  searchstream(enduserid,dtype,ddatasearch,musthave,mustnothave,fromda,toda,ntincludeimg,searchtypes);
}
// home auto refresh data
function autorefresh()
{
  document.getElementById("streamuserres").value="1";
  $("#auto-refresh").addClass("hide");
  $("#exit-auto-refresh").removeClass("hide");
  autoStreamData();
}
function autoStreamData()
{
  if(document.getElementById("streamuserres").value=="1")
  {
    homestreamdata();
    setTimeout(function(){ autoStreamData(); }, 3000);
  }
}
function exitautorefresh()
{
  document.getElementById("streamuserres").value="0";
  $("#exit-auto-refresh").addClass("hide");
  $("#auto-refresh").removeClass("hide");
}
function internetusagerefresh()
{
  location.reload();
}
function installedsoftwarerefresh()
{
  location.reload();
}
function galleryrefresh()
{
  location.reload();
}
function webrefresh()
{
  location.reload();
}
function runningappsrefresh()
{
  location.reload();
}
function timesheetrefresh()
{
  location.reload();
}
function lazyminutesrefresh()
{
  location.reload();
}
function osinforefresh()
{
  location.reload();
}


