<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $cgroup = new cgroup("grupo");
    // Valores de cookies
    
    // Control de post para pintar valores
    
    // El evento de búsqueda
    if (isset($_POST['fgroupname'])) {
        $rgrupo=$cgroup->getgroupbyname($gentity, $_POST['groupname']);
        $rgrupo=get_object_vars($rgrupo[0]);
        if (is_null($rgrupo)){
            $rgrupo=$_POST;
        }
    }
    // Nuevo. Resetea el form.
    if (isset($_POST['bnew'])) {
        $rgrupo = $_POST;
        $rgrupo = $cgroup->create($rgrupo); 
    }
    // Grabar. (Crea/actualiza)
    if (isset($_POST['bsave'])) {
        $rgrupo = $_POST;
        $rgrupo = $cgroup->update($rgrupo); 
        // Control de errores
        if ($rgrupo == -1) {
            // Asignar el post original, en la var de sesión mostrará el error.
            $rgrupo = $_POST;
        }
        $_POST=$rgrupo;
    }
?>
<form name="fgroupe" method="post">
     <?php
        // Parametro oculto
        echo '<div id="dgride">';
        echo '<input type="hidden" name="id" value="'.$rgrupo['id'].'">';
        echo '<input type="hidden" name="docid" value="'.$rgrupo['docid'].'">';
        echo '<input type="hidden" name="entidad" value="'.$rgrupo['entidad'].'">';
        echo '<input type="hidden" name="fkentity" value="'.$rgrupo['fkentity'].'">';
        
        // Forzar a la entidad que esta en la variable del menú elegido
        echo '<input type="hidden" name="fkentity" value="'.$gentity.'">';
            $cgroup->labelinput('groupname',$rgrupo['groupname'],'Nombre grupo','text',20,true,"required");
            $cgroup->labelinput('description',$rgrupo['description'],'Descripción grupo','text',60,false);
            $cgroup->labelinput('emailgroup',$rgrupo['emailgroup'],'Email Grupo','email',30,false);
            $cgroup->labelinput('fcreate',$rgrupo['fcreate'],'F. creación','date',12,false,"","readonly");
            $cgroup->labelinput('fmodif',$rgrupo['fmodif'],'F. modificación','date',12,false,"","readonly");
        // Parámetros dinámicos
        echo '</div>';
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo ' <input type="submit" class="boton" name="bsave" id="bsaveg" value="Grabar"/>';
        echo ' <input type="submit" class="boton" name="bnew" id="bnewg" value="Nuevo"/>';
     ?>
    
 </form>
 <?php
 echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
 ?>