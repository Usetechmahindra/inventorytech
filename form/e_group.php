<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<form name="fgroupe" method="post">
     <?php
        // Control de post para pintar valores
        $cgroup = new cgroup("cgroup");
        // Parametro oculto
        echo '<div id="dgride">';
        echo '<input type="hidden" name="id[]" value="">';
        $cgroup->labelinput('groupname','','Nombre grupo','text',20,"required");
        $cgroup->labelinput('description','','Descripción grupo','text',60);
        
        $cgroup->labelinput('emailgroup','','Email Grupo','email',30);
        $cgroup->labelinput('fcreate','','F. creación','date',12,"","disabled");
        $cgroup->labelinput('fmodif','','F. modificación','date',12,"","disabled");
        // Parámetros dinámicos
        echo '</div>';
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo '<input type="submit" class="boton" name="bvalidag" id="bvalidag" value="Aceptar"/>';
     ?>
    
 </form>
 <?php 
 echo $_SESSION['textsesion'];
 echo $_POST['groupname']; ?>