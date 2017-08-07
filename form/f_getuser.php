<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    // Variable que identifica la entidad de parametros
    $nentidad = "usuario";
?>
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
            <img src="../upload/images/i_user.png" alt="i_user" height="47" align="left">
            <p style="font-weight: bold">Administración de usuarios </p>
            <button class="tablinks" onclick="openTab(event, 'Busqueda')">Busqueda</button>
            <button class="tablinks" onclick="openTab(event, 'Edición')">Edición</button>
            <button class="tablinks" onclick="openTab(event, 'Histórico')">Histórico</button>
            <button class="tablinks" onclick="openTab(event, 'Parámetros')">Parámetros</button>
            <hr style="color:<?php echo $_SESSION['color'];?>" />
        </div>
        <div class="tabbody">
            <div id="Busqueda" class="tabbodycontent">
              <?php
                include 'g_user.php';
              ?>
            </div>
            <div id="Edición" class="tabbodycontent">
                <?php
                    include 'e_user.php';
                ?>
            </div>
            <div id="Histórico" class="tabbodycontent">
                <?php
                    include 'g_audit.php';
                ?>
            </div>
            <div id="Parámetros" class="tabbodycontent">
                <?php
                    include 'g_item.php';
                ?>
            </div>  
        </div>      
    </body>
</html>