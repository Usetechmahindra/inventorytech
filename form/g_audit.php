<?php

/* 
 * Grid de auditoria generico por id
 * 
 * 
 */
$caudit = new caudit("aud");
// Cargar filas de auditoria
if (isset($_SESSION['idact'])) {
    $audrow = $caudit->getgridaudit($_SESSION['idact']);
}
// Recuperar auditoría previa
if (isset($_POST['baudit'])){
    $rfila = $caudit->getauditvalues($_POST['id']);
    $_SESSION['idact'] =  get_object_vars($rfila[0])['id'];
    // Refrescar ventana
    echo '<meta http-equiv="refresh" content="0">';
    //header('Location: .');
}
?>

<html>
    <head>
        <style>
            th {
              background-color: <?php echo $_SESSION['color']; ?>;
              color: white;
            }
        </style>
    </head>
    <body>
        <p style="font-weight: bold">Historial de cambios </p>
        <hr style="color:<?php echo $_SESSION['color']; ?>" />
        <div id="dgrid">
        <table id="tgrid">
        <thead>
           <tr>
             <?php
                //Recorrer el array y pintar los nombres de columnas
                // Recorrer el array de columnas
                // auditoria
                echo "<th>Operación</th>";
                echo "<th>Fecha</th>";
                echo "<th>Usuario</th>";
                echo "<th>Restablecer</th>";
             ?>
           </tr>
        </thead>
        <tbody>
            <?php
    //          $result = mysql_query("SELECT idalert,idparametro,idusuario,estado,tipo,operacion,valor,textalert,nbit,horaminbit,horamaxbit,falta from alertserver where idserver=".$_SESSION['idserver']." order by idusuario,idparametro");
    //          while( $row = mysql_fetch_assoc( $result ) ){
                // Recorrer todas las filas y cada columna
                foreach($audrow as $afila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    // Poner el orden establecido
                    $afila = get_object_vars($afila);
                    echo '<form name="faudit" id="faudit" method="post">';
                    echo '<input type="hidden" name="id" value="'.$afila["id"].'">';
                    echo '<input type="hidden" name="idaudit" value="'.$afila["idaudit"].'">';
                   // echo '<tr ondblclick="faudit(\''.$afila["idaudit"].'\')">'; 
                    switch ($afila["typeop"]) {
                            case 1:
                                $afila["typeop"] = "Alta";
                                break;
                            case 3:
                                $afila["typeop"] = "Baja";
                                break;
                            case 4:
                                $afila["typeop"] = "Backup";
                                break;
                            default:
                                $afila["typeop"] = "Modificación";
                                break;
                    }
                    echo "<td>".$afila["typeop"]."</td>";
                    // Auditoria
                    if(!empty($afila["umodif"])) {
                        echo "<td>".date('d-m-Y H:i:s',$afila["fmodif"])."</td>";
                        echo "<td>".$afila["umodif"]."</td>";
                    }else {
                        echo "<td>".date('d-m-Y H:i:s',$afila["fcreate"])."</td>";
                        echo "<td>".$afila["ucreate"]."</td>";
                    }
//                    foreach($afila as $clave =>$valor){
//                        echo "<td>".$valor."</td>";
//                    }
                    echo '<td><input type="submit" class="gdangerboton" name="baudit" id="baudit" value="Restablecer" onclick="return confirm(\'¿Recuperar los datos de este cambio?\');"></td>';
                    // Final de fila
                    echo "</tr>";
                    echo '</form>';
                }
            ?>
            
        </tbody>
        </table>
        </div>  
        </form>
    </body>
</html>


