<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link rel="stylesheet" href="../css/jquery-ui_tech.css">

  <script src="../java/jquery.js"></script>
  <script src="../java/jquery-ui.js"></script>
  <script>
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