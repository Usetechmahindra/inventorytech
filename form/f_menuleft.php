<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <script>
  function showOpcion(vop,ventity) {
    if (vop=="" || ventity=="") {
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
    switch (vop) {
        case 'E':
            xmlhttp.open("GET","f_getentity.php?gentity="+ventity,true);
            break; 
        case 'U':
            xmlhttp.open("GET","f_getuser.php?gentity="+ventity,true);
            break; 
        case 'G':
            xmlhttp.open("GET","f_getgroup.php?gentity="+ventity,true);
            break;
         case 'P':
            xmlhttp.open("GET","f_getparameter.php?gentity="+ventity,true);
            break;
         case 'I':
            xmlhttp.open("GET","f_getimport.php?gentity="+ventity,true); 
            break; 
        default: 
            // E
            xmlhttp.open("GET","f_getentity.php?gentity="+ventity,true);
    }
    // Ejecutar tabs
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