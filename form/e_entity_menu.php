<?php
// Control menñu
if (isset($_POST['bsavel'])){
    $rfila=$centity->updatelogo();
    if (count($rfila) > 0) {
        $rfila = get_object_vars($rfila[0]);
        $_SESSION['idact'] = $rfila['id'];
//            // Refrescar ventana
//            echo '<meta http-equiv="refresh" content="0">';
    }else
        {
          unset($_SESSION['idact']); 
    }
}
if (isset($_POST['bresetl'])){
    // Poner a nulo el logo
    unset($rfila['logo']);
    $rfila=$centity->updatelogo();
    if (count($rfila) > 0) {
        $rfila = get_object_vars($rfila[0]);
        $_SESSION['idact'] = $rfila['id'];
//            // Refrescar ventana
//            echo '<meta http-equiv="refresh" content="0">';
    }else
        {
          unset($_SESSION['idact']); 
    }
}

// Formulario de imagen
echo '<form name="fentitylogoe" action="" method="POST" enctype="multipart/form-data">';
if (isset($rfila['logo'])){
    echo '<img src="'.$rfila['logo'].'" alt="IMGLOGO" style="width:75px;" align="middle"/>';
}else{
    echo '<img src="../upload/images/i_menudef.png" alt="IMGLOGO" style="width:75px;" align="middle"/>';
}
echo '<input type="hidden" name="idform" value="fentitylogoe">';
echo '<input type="hidden" name="id" value="'.$rfila['id'].'">';
echo '<p></p>';
echo '<input type="file" name="image" />';
// Botonera
echo '<hr style="color:'.$_SESSION['color'].';" />';
echo ' <input type="submit" class="boton" name="bsavel" id="bsavel" value="Grabar"/>';
echo ' <input type="submit" class="dangerboton" name="bresetl" id="bresetl"  value="Reset" onclick="return confirm(\'¿Resetear logo?\');"/>';
echo '</form>';