<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
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
            <p style="font-weight: bold">Herramientas entidad</p>
            <button class="tablinks" onclick="openTab(event, 'Importación')">Importación</button>
            <button class="tablinks" onclick="openTab(event, 'Monitores')">Monitores</button>
            <hr style="color:<?php echo $_SESSION['color'];?>" />
        </div>
        <div class="tabbody">
            <div id="Importación" class="tabbodycontent">
              <?php
                include 'g_import.php';
              ?>
            </div>
            <div id="Monitores" class="tabbodycontent">
              <?php
                //include 'g_group.php';
              ?>
            </div>
        </div>
    </body>
</html>