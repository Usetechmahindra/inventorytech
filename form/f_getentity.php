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
        <div id="divtitle">
            <img src="../upload/images/i_entidad.png" alt="i_entity" height="52" align="left">
        </div>
        <p style="font-weight: bold">Administración de entidades </p>
        <div class="tabbody">
          <button class="tablinks" onclick="openTab(event, 'Busqueda')">Busqueda</button>
          <button class="tablinks" onclick="openTab(event, 'Edición')">Edición</button>
        </div>

        <div id="Busqueda" class="tabbodycontent">
          <?php

          ?>
        </div>
        <div id="Edición" class="tabbodycontent">
            <?php

            ?>
        </div>
    </body>
</html>


