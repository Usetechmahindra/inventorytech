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
    if ($_POST['idform'] == 'fgroupe') {
        $rgrupo = $cgroup->postauto($gentity);
        // Si es busqueda retorna clase, convertir primera coincidencia
        if(!isset($_POST['bsave']) AND !isset($_POST['bnew']))
        {
            $rgrupo = get_object_vars($rgrupo[0]);
        }
    }

?>
<form name="fgroupe" id="fgroupe" method="post">
     <?php
        // Parametro oculto
        echo '<div id="dgride">';
        //<!--Parametro oculto que identificar el form.--> 
        echo '<input type="hidden" name="idform" value="fgroupe">';
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
        // Columnas de auditoría. Los campos ocultos almacenan la fecha en unix format.
        echo '<input type="hidden" name="fcreate" value="'.$rgrupo['fcreate'].'">';
        echo '<input type="hidden" name="ucreate" value="'.$rgrupo['ucreate'].'">';
        echo '<input type="hidden" name="fmodif" value="'.$rgrupo['fmodif'].'">';
        echo '<input type="hidden" name="umodif" value="'.$rgrupo['umodif'].'">'; 
        //Los campos diabled no se envian al POST
        $cgroup->labelinput('fcreate',$rgrupo['fcreate'],'Fecha Alta','date',20,false,false,"disabled");
        $cgroup->labelinput('ucreate',$rgrupo['ucreate'],'U. Alta','text',20,false,false,"disabled");
        $cgroup->labelinput('fmodif',$rgrupo['fmodif'],'Fecha Modif.','date',20,false,false,"disabled");
        $cgroup->labelinput('umodif',$rgrupo['umodif'],'U. Modif','text',20,false,false,"disabled");
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