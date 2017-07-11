<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/techtabs.css">
    <?php
        session_start(); 
        require('../class/cparent.php');
        require('../class/cgroup.php');
    ?>
    </head>
    <body>
        <div id="divtitle">
            <img src="../upload/images/i_group.png" alt="i_group" width="70" align="left">
        </div>
        <p style="font-weight: bold">Administración de grupos </p>
        <div class="tabbody">
          <button class="tablinks" onclick="openTab(event, 'Busqueda')">Busqueda</button>
          <button class="tablinks" onclick="openTab(event, 'Parametros')">Parametros</button>
          <button class="tablinks" onclick="openTab(event, 'Edición')">Edición</button>
          <button class="tablinks" onclick="openTab(event, 'Usuarios')">Usuarios</button>
        </div>

        <div id="Busqueda" class="tabbodycontent">
          <?php
            include 'g_group.php';
          ?>
        </div>
        <div id="Parametros" class="tabbodycontent">
          <?php
          ?>
        </div>
        <div id="Edición" class="tabbodycontent">

        </div>

        <div id="Usuarios" class="tabbodycontent">

        </div>
    </body>
</html>
