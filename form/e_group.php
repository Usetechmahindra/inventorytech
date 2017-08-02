<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $cgroup = new cgroup("grupo");
    $cols = $cgroup->itementity($gentity,0);
    // Valores de cookies
    if (isset($_COOKIE['cid'])) {
        // Meter en varaible de sesion
        $rfila = $cgroup->getdocid($_COOKIE['cid']);
        if (count($rfila) > 0) {
           $rfila = get_object_vars($rfila); 
           $_SESSION['idact'] = $_COOKIE['cid'];
        }
        $_SESSION['textsesion'] = "";
//        // Borrar la cookie
        unset($_COOKIE['cid']);
    }
    // El eventos post
    if ($_POST['idform'] == 'fgroupe'){
        // No grabar el id de formulario
        $rfila = $cgroup->postauto($gentity);
        // Coger la primera coincidencia
        if (count($rfila) > 0) {
            $rfila = get_object_vars($rfila[0]);
            $_SESSION['idact'] = $rfila['id'];
            // Refrescar ventana
            //echo '<meta http-equiv="refresh" content="0">';
        }
    }
    header('Location: .');


    

?>
<form name="fgroupe" id="fgroupe" method="post">
     <?php
        // Asignar y borrar.
        // Parametro oculto
        echo '<div id="dgride">';
        //<!--Parametro oculto que identificar el form.--> 
        echo '<input type="hidden" name="idform" value="fgroupe">';
        echo '<input type="hidden" name="id" value="'.$rfila['id'].'">';
        echo '<input type="hidden" name="docid" value="'.$rfila['docid'].'">';
        echo '<input type="hidden" name="entidad" value="'.$rfila['entidad'].'">';
        // Forzar a la entidad que esta en la variable del menú elegido
        echo '<input type="hidden" name="fkentity" value="'.$gentity.'">';
        // Recorrer los parámetros dinámicos de la entidad
        foreach($cols as $col)
        {
            $acol = get_object_vars($col);
            // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
            $cgroup->labelinput($acol['name'],$rfila[$acol['name']],$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],$acol['breadonly']);
        }
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        //Los campos diabled no se envian al POST
        $cgroup->labelinput('fcreate',$rfila['fcreate'],'Fecha Alta','date',20,false,false,true);
        $cgroup->labelinput('ucreate',$rfila['ucreate'],'U. Alta','text',20,false,false,true);
        $cgroup->labelinput('fmodif',$rfila['fmodif'],'Fecha Modif.','date',20,false,false,true);
        $cgroup->labelinput('umodif',$rfila['umodif'],'U. Modif','text',20,false,false,true);
        // Columnas de auditoría. Los campos ocultos almacenan la fecha en unix format.
        echo '<input type="hidden" name="fcreate" value="'.$rfila['fcreate'].'">';
        echo '<input type="hidden" name="ucreate" value="'.$rfila['ucreate'].'">';
        echo '<input type="hidden" name="fmodif" value="'.$rfila['fmodif'].'">';
        echo '<input type="hidden" name="umodif" value="'.$rfila['umodif'].'">'; 
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo ' <input type="submit" class="boton" name="bsave" id="bsaveg" value="Grabar"/>';
        echo ' <input type="submit" class="boton" name="bnew" id="bnewg" value="Nuevo"/>';
        echo ' <input type="submit" class="dangerboton" name="bdown" id="bdowg" value="Baja" onclick="return confirm(\'¿Borrar fila?\');">';
        echo '</div>';
     ?>
    
 </form>
 <?php
 echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
 ?>