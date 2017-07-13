<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $cgroup = new cgroup("cgroup");
    // Valores de cookies
    
    // Control de post para pintar valores
    
    // El evento de búsqueda siempre es el último
    if (isset($_POST['fgroupname'])) {
        $rgrupo=$cgroup->getgroupbyname($gentity, $_POST['groupname']); 
    }
?>
<form name="fgroupe" method="post">
     <?php
        // Parametro oculto
        echo '<div id="dgride">';
        echo '<input type="hidden" name="id" value="'.$rgrupo[0]->id.'">';
            $cgroup->labelinput('groupname',$rgrupo[0]->groupname,'Nombre grupo','text',20,true,"required");
            $cgroup->labelinput('description',$rgrupo[0]->description,'Descripción grupo','text',60,false);
            $cgroup->labelinput('emailgroup',$rgrupo[0]->emailgroup,'Email Grupo','email',30,false);
            $cgroup->labelinput('fcreate',$rgrupo[0]->fcreate,'F. creación','date',12,false,"","disabled");
            $cgroup->labelinput('fmodif',$rgrupo[0]->fmodif,'F. modificación','date',12,false,"","disabled");
        // Parámetros dinámicos
        echo '</div>';
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo '<input type="submit" class="boton" name="bvalidag" id="bvalidag" value="Aceptar"/>';
     ?>
    
 </form>
 <?php 
 echo $_SESSION['textsesion'];
 ?>