<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style>
      .ui-state-active {
          background: <?php echo $_SESSION['color']; ?>;
      }
  </style>
  <script>
    //  Script de menú acorderon    
        $( function() {
          $( "#dinmenu" ).accordion({heightStyle: "content"});
          // opcion activa asignar otra opcion de menu
          // ///////////////////////////////////////////////////////////////////
          var activemenu = getCookie("cactivemenu");
          //alert(typeof parseInt(activemenu));
          $( "#dinmenu" ).accordion( "option", "active", parseInt(activemenu) );
        } );
  </script>
</head>
<body>
 
<div id="dinmenu">
<?php 
// Pintar dinamicamente estructura de abajo.
$cuser = new cuser("cuser");
$cuser->usermenudim();
//echo $_COOKIE['cactivemenu'];
?>
</div>
</body>
</html>