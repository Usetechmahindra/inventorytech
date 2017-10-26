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
    // Crear clases
    $cimportp = new centity("entidad");
    $citemexcelp = new citem("item_entidad");
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
    if ($citemexcelp->syncparm($_GET['id']) <> -1)
    {
        if($cimportp->procexcel($_GET['id'])<> -1) 
        {
            // Actualizar el ficehro a procesado
            $cimportp->changefilestatus($_GET['id'],true);
        }
    }
    // Tell user that the process is completed
    echo '<script language="javascript">document.getElementById("information").innerHTML="Proceso finalizado."'.$error.'</script>';
    echo ' <input type="submit" class="boton" name="bclose" id="bclose" value="Cerrar" onclick="javascript:closepopup()">';
    ?>
</form>
</body>
</html>