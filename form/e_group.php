<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $cgroup = new cgroup("grupo");
    $cols = $cgroup->itementity();
    // Valores de cookies
    
    // El eventos post
    if (count($_POST)>0) {
        $rgrupo = $cgroup->postauto($gentity);
    }

?>
<form name="fgroupe" method="post">
     <?php
        // Parametro oculto
        echo '<div id="dgride">';
        
        echo '<input type="hidden" name="id" value="'.$rgrupo['id'].'">';
        echo '<input type="hidden" name="docid" value="'.$rgrupo['docid'].'">';
        echo '<input type="hidden" name="entidad" value="'.$rgrupo['entidad'].'">';
        // Forzar a la entidad que esta en la variable del menú elegido
        echo '<input type="hidden" name="fkentity" value="'.$gentity.'">';
        // Recorrer los parámetros dinámicos de la entidad
        foreach($cols as $col)
        {
            $acol = get_object_vars($col);
            // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
            $cgroup->labelinput($acol['name'],$rgrupo[$acol['name']],$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],false);
        }
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        // Columnas de auditoría.       
        $cgroup->labelinput('fcreate',$rgrupo['fcreate'],'F. creación','date',12,false,false,"readonly");
        $cgroup->labelinput('ucreate',$rgrupo['ucreate'],'U. Alta','text',20,false,false,"readonly");
        $cgroup->labelinput('fmodif',$rgrupo['fmodif'],'F. modificación','date',12,false,false,"readonly");
        $cgroup->labelinput('umodif',$rgrupo['umodif'],'U. Modif','text',20,false,false,"readonly");
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo ' <input type="submit" class="boton" name="bsave" id="bsaveg" value="Grabar"/>';
        echo ' <input type="submit" class="boton" name="bnew" id="bnewg" value="Nuevo"/>';
        echo '</div>';
     ?>
    
 </form>
 <?php
 echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
 ?>