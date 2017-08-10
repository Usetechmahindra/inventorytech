<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $centity = new centity("entidad");
    $cols = $centity->itementity($gentity,0);
    // Valores de cookies
    if (isset($_SESSION['idact'])) {
        // Meter en varaible de sesion
        $rfila = $centity->getdocid($_SESSION['idact'],true);
        if (count($rfila) > 0) {
           $rfila = get_object_vars($rfila);
           $_SESSION['idact'] = $rfila['id'];
        } else {
            unset($_SESSION['idact']); 
        }
        $_SESSION['textsesion'] = "";
    }
    // El eventos post
    if ($_POST['idform'] == 'fentitye'){
        // No grabar el id de formulario
        $rfila = $centity->postauto($gentity);
        // Coger la primera coincidencia
        if (count($rfila) > 0) {
            $rfila = get_object_vars($rfila[0]);
            $_SESSION['idact'] = $rfila['id'];
//            // Refrescar ventana
//            echo '<meta http-equiv="refresh" content="0">';
        }else
            {
              unset($_SESSION['idact']); 
        }
        header('Location: .');
    }
?>
<form name="fentitye" id="fentitye" method="post" enctype="multipart/form-data">
     <?php
        // Asignar y borrar.
        // Parametro oculto
        echo '<div id="dgride">';
        //<!--Parametro oculto que identificar el form.--> 
        echo '<input type="hidden" name="idform" value="fentitye">';
        echo '<input type="hidden" name="id" value="'.$rfila['id'].'">';
        echo '<input type="hidden" name="docid" value="'.$rfila['docid'].'">';
        echo '<input type="hidden" name="entidad" value="'.$rfila['entidad'].'">';
        // Forzar a la entidad que esta en la variable del menú elegido
        echo '<input type="hidden" name="fkentity" value="'.$gentity.'">';
        echo '<input type="hidden" name="logo" value="'.$rfila['logo'].'">';
        // Recorrer los parámetros dinámicos de la entidad
        foreach($cols as $col)
        {
            $acol = get_object_vars($col);
            // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
            $centity->labelinput($acol['name'],$rfila[$acol['name']],$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],$acol['breadonly']);
        }
        if ($gentity == 'entidad_0') {
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            /* 
            * Campos especificos cuando la entidad está configurando las entidades menú.
            */
            $centity->labelinput('color',$rfila['color'],'Color','color',15,false,false,false);
            $centity->labelinput('colorinvert',$rfila['colorinvert'],'C.Inverso','color',15,false,false,false);
            $centity->labelinput('buser',$rfila['buser'],'Usuarios','checkbox',15,false,false,false);
            $centity->labelinput('bgroup',$rfila['bgroup'],'Grupos','checkbox',15,false,false,false);
            $centity->labelinput('bexcel',$rfila['bexcel'],'Importación','checkbox',15,false,false,false);
            // timezone
            $centity->labelinput('timezone',$rfila['timezone'],'Zona horaria','timezone',20,false,false,false);
        }
        //Los campos diabled no se envian al POST
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        $centity->labelinput('fcreate',$rfila['fcreate'],'Fecha Alta','datetime',20,false,false,true);
        $centity->labelinput('ucreate',$rfila['ucreate'],'U. Alta','text',20,false,false,true);
        $centity->labelinput('fmodif',$rfila['fmodif'],'Fecha Modif.','datetime',20,false,false,true);
        $centity->labelinput('umodif',$rfila['umodif'],'U. Modif','text',20,false,false,true);
        // Columnas de auditoría. Los campos ocultos almacenan la fecha en unix format.
        echo '<input type="hidden" name="fcreate" value="'.$rfila['fcreate'].'">';
        echo '<input type="hidden" name="ucreate" value="'.$rfila['ucreate'].'">';
        echo '<input type="hidden" name="fmodif" value="'.$rfila['fmodif'].'">';
        echo '<input type="hidden" name="umodif" value="'.$rfila['umodif'].'">'; 
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo ' <input type="submit" class="boton" name="bsave" id="bsavee" value="Grabar"/>';
        echo ' <input type="submit" class="boton" name="bnew" id="bnewe" value="Nuevo"/>';
        echo ' <input type="submit" class="dangerboton" name="bdown" id="bdowne" value="Baja" onclick="return confirm(\'¿Borrar fila?\');">';
        
        echo '</div>';
     ?>
    
 </form>
 <?php
 echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
 ?>