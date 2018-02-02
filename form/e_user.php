<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $cuser = new cuser("usuario");
    $cols = $cuser->itementity($gentity,0);
    // Valores de cookies
    if (isset($_SESSION['idact'])) {
        // Meter en varaible de sesion
        $rfila = $cuser->getdocid($_SESSION['idact'],true);
        if (count($rfila) > 0) {
           $rfila = get_object_vars($rfila);
           $_SESSION['idact'] = $rfila['id'];
        } else {
            unset($_SESSION['idact']); 
        }
        $_SESSION['textsesion'] = "";
    }
    // El eventos post
    if ($_POST['idform'] == 'fusere'){
        // No grabar el id de formulario
        $rfila = $cuser->postauto($gentity);
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
<form name="fusere" id="fusere" method="post">
     <?php
        // Asignar y borrar.
        // Parametro oculto
        echo '<div id="dgride">';
        //<!--Parametro oculto que identificar el form.--> 
        echo '<input type="hidden" name="idform" value="fusere">';
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
            $cuser->labelinput($acol['name'],$rfila[$acol['name']],$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],$acol['breadonly']);
        }
        // Campos especificos formulario usuario
        ///////////////////////////////////////
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        $cuser->labelinput('password',$rfila['password'],'Password','password',20,false,false,false);
//        echo '<input type="hidden" name="password" value="'.$rfila['password'].'">';
        $cuser->labelinput('email',$rfila['email'],'Email','email',30,false,false,false);
        $cuser->labelinput('description',$rfila['description'],'Descripción','text',40,false,false,false);
        $cuser->labelinput('bloginapp',$rfila['bloginapp'],'Login','checkbox',10,false,false,false);
        
        $cuser->labelinput('bread',$rfila['bread'],'Readonly','checkbox',10,false,false,false);
        $cuser->labelinput('bshowuser',$rfila['bshowuser'],'Adm.Usuarios','checkbox',10,false,false,false);
        $cuser->labelinput('bshowgroup',$rfila['bshowgroup'],'Adm. Grupos','checkbox',10,false,false,false);
        $cuser->labelinput('bshowentidad',$rfila['bshowentidad'],'Adm. Entidades','checkbox',10,false,false,false);
        $cuser->labelinput('bshowtools',$rfila['bshowtools'],'Herramientas','checkbox',10,false,false,false);

        echo '<hr style="color:'.$_SESSION['color'].';" />';
        //Los campos diabled no se envian al POST
        $cuser->labelinput('fcreate',$rfila['fcreate'],'Fecha Alta','datetime',20,false,false,true);
        $cuser->labelinput('ucreate',$rfila['ucreate'],'U. Alta','text',20,false,false,true);
        $cuser->labelinput('fmodif',$rfila['fmodif'],'Fecha Modif.','datetime',20,false,false,true);
        $cuser->labelinput('umodif',$rfila['umodif'],'U. Modif','text',20,false,false,true);
        // Columnas de auditoría. Los campos ocultos almacenan la fecha en unix format.
        echo '<input type="hidden" name="fcreate" value="'.$rfila['fcreate'].'">';
        echo '<input type="hidden" name="ucreate" value="'.$rfila['ucreate'].'">';
        echo '<input type="hidden" name="fmodif" value="'.$rfila['fmodif'].'">';
        echo '<input type="hidden" name="umodif" value="'.$rfila['umodif'].'">'; 
        // Botonera
        echo '<hr style="color:'.$_SESSION['color'].';" />';
        echo ' <input type="submit" class="boton" name="bsave" id="bsaveu" value="Grabar"/>';
        echo ' <input type="submit" class="boton" name="bnew" id="bnewu" value="Nuevo"/>';
        echo ' <input type="submit" class="dangerboton" name="bdown" id="bdowu" value="Baja" onclick="return confirm(\'¿Borrar fila?\');">';
        echo '</div>';
     ?>
    
 </form>
 <?php
    echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
 ?>