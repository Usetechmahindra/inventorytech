<?php

/* 
 * Grid de auditoria generico por id
 * 
 * 
 */
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
        <div id="dgridaudit">
        <table id="tgridaudit">
        <thead>
           <tr>
             <?php
                //Recorrer el array y pintar los nombres de columnas
                // Recorrer el array de columnas
                // auditoria
                echo "<th>Operación</th>";
                echo "<th>Fecha</th>";
                echo "<th>Usuario</th>";
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
                    echo '<input type="hidden" name="id[]" value="'.$afila["id"].'">';
//                  echo '<tr onclick="fclick(\''.$afila["id"].'\')">';
                    switch ($afila["typeop"]) {
                            case 1:
                                $afila["typeop"] = "Alta";
                                break;
                            case 3:
                                $afila["typeop"] = "Baja";
                                break;
                            default:
                                $afila["typeop"] = "Modificación";
                                break;
                    }
                    echo "<td>".$afila["typeop"]."</td>";
                    // Auditoria
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date('d/m/Y H:i:s',$afila["fmodif"])."</td>";
                        echo "<td>".$afila["umodif"]."</td>";
                    }else {
                        echo "<td>".date('d/m/Y H:i:s',$afila["fcreate"])."</td>";
                        echo "<td>".$afila["ucreate"]."</td>";
                    }
//                    foreach($afila as $clave =>$valor){
//                        echo "<td>".$valor."</td>";
//                    }
                    // Final de fila
                    echo "</tr>";
                }
            ?>
            
        </tbody>
        </table>
        </div>  
        </form>
    </body>
</html>


