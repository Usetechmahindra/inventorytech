<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<meta charset="UTF-8">
<?php
    session_start();
    require('../class/cparent.php');
    require('../class/cgroup.php');
    require('../class/cuser.php');
    require('../class/citem.php');
    require('../class/caudit.php');
    require('../class/centity.php');
    require('../class/PHPExcel.php');
    $cimportp = new centity("filesexcel");
    $citemexcelp = new citem("item_excel");
?>
<SCRIPT LANGUAGE="JavaScript">
    function closepopup()
    {
        close();
    }
</SCRIPT>
<head>
    <title>Procesando Excel</title>
    <link rel="icon" href="../upload/images/i_inventario_ico.ico">
    <link rel="stylesheet" type="text/css" href="../css/techinventory.css">
</head>
<body>
<div class="divtitle" style="height:90px">
    <img src="../upload/images/i_inventario.png" alt="i_entity" height="47" align="left">
    <p style="font-weight: bold">Importación de datos </p>
    <hr style="color:<?php echo $_SESSION['color'];?>" />
</div>    
<div class="divprocess" style="height:100px">
<!-- Progress bar holder -->
    <div id="progress" style="width:500px;border:1px solid #e7e7e7"></div>
    <!-- Progress information -->
    <div id="information" style="width"></div>
</div>
<form name="ffindexcel" id="ffind" method="post">
    <?php
    // 1º Sincronizar parámetros excel con parámetros de la entidad:Crear o Actualizar
    $citemexcelp->syncparm($_GET['id']);
//        $error = $_SESSION['textsesion'];
//    }
//    // 2º Grabar los detalles
//    // Total processes
//    $total = 10;
//    // Loop through process
//
//    for($i=1; $i<=$total; $i++){
//        // Calculate the percentation
//        $percent = intval($i/$total * 100)."%";
//
//        // Javascript for updating the progress bar and information
//        echo '<script language="javascript">
//        document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#3e8e41;\">&nbsp;</div>";
//        document.getElementById("information").innerHTML="'.$i.' Fila(s) procesadas.";
//        </script>';
//
//
//    // This is for the buffer achieve the minimum size in order to flush data
//        echo str_repeat(' ',1024*64);
//
//
//    // Send output to browser immediately
//        flush();
//
//
//    // Sleep one second so we can see the delay
//        sleep(1);
//    }
    // Tell user that the process is completed
    echo '<script language="javascript">document.getElementById("information").innerHTML="Proceso finalizado."'.$error.'</script>';
    echo ' <input type="submit" class="boton" name="bclose" id="bclose" value="Cerrar" onclick="javascript:closepopup()">';
    ?>
</form>
</body>
</html>