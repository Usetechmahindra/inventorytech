<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/techtabs.css">
        <script>
            $( function() {
                // Coger la cookie
                var activetab = getCookie("ctabname");
                //alert(activetab);
                openTab(event, activetab);
            } );
        </script>
    </head>
    <body>
        <div class="divtitle">
            <img src="../upload/images/i_inventario.png" alt="i_inventario" height="47" align="left">
            <p style="font-weight: bold">Administración de importaciones </p>
<!--            <button class="tablinks" onclick="openTab(event, 'Busqueda')">Busqueda</button>
            <button class="tablinks" onclick="openTab(event, 'Edición')">Edición</button>
            <button class="tablinks" onclick="openTab(event, 'Parámetros')">Parámetros</button>-->
            <hr style="color:<?php echo $_SESSION['color'];?>" />
        </div>
        <div class="tabbody">
            
        </div>
    </body>
</html>