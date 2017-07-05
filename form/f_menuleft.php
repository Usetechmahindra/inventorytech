<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <script>
  function showOpcion(ventity) {
    if (ventity=="") {
      return;
    } 
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("dcuerpo").innerHTML=this.responseText;
      }
    }
    // Controlar el tipo de form pasado
    
    xmlhttp.open("GET","f_getgroup.php?q="+ventity,true);
    xmlhttp.send();
  }    
//  Script de menú acorderon    
  $( function() {
    $( "#dinmenu" ).accordion({
      heightStyle: "content"
    });
  } );
  </script>
</head>
<body>
 
<div id="dinmenu">
<?php 
// Pintar dinamicamente estructura de abajo.
require('../class/cparent.php');
require('../class/cuser.php');
$cuser = new cuser("cuser");
$cuser->usermenudim();
//echo $_SESSION['fkentity'];
?>
</div>

</body>
</html>