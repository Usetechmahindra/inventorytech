<html>
    <head>
        <style>
            th {
              background-color: <?php echo $_SESSION['color']; ?>;
              color: white;
            }
        </style>
        <!Funciones post>
        <?php
        // Crear clase de para llamada a funciones genericas
        // Control POST
        $cuser = new cuser("usuario");
        // Parametros de busqueda
        $cols = $cuser->itementity($gentity,1);
        // Campos de filtro
        $afilter = $cuser->itementity($gentity,2);
        // Función busquedagrid
        if ($_POST['idform'] == 'ffinduser') {
            $rows = $cuser->postauto($gentity);
        }else {
            // Controlar formulario grid
            if ($_POST['idform'] == 'fuser') {
                $_SESSION['idact'] = $_POST['id'];
            }else {
                // Lanzar la busqueda
                $rows = $cuser->getbysearch("", "", $gentity);
            }
        } 
        ?>
    </head>
    <body>
        <form name="ffinduser" id="ffind" method="post">
           <!--Parametro oculto que identificar el form.--> 
           <input type="hidden" name="idform" value="ffinduser">
            <div id="dfind">
                <?php
                    // Recorrer los parámetros dinámicos de la entidad
                    foreach($afilter as $filtro)
                    {
                        $acol = get_object_vars($filtro);
                        // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
                        // Requerido al falso
                        $acol['brequeried'] = FALSE;
                        $cuser->labelinput($acol['name'],"",$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],false);
                    }
        echo '<hr style="color:'.$_SESSION['color'].';" />';
                ?>    
            </div>
        </form>
        <div id="dgrid">
        <table id="tgrid">
        <thead>
           <tr>
             <?php
                //Recorrer el array y pintar los nombres de columnas
                // Recorrer el array de columnas
                foreach($cols as $col)
                {
                    $acol = get_object_vars($col);
                    echo "<th>".$acol['label']."</th>";
                }
                // auditoria
                echo "<th>Alta</th>";
                echo "<th>U. Alta</th>";
                echo "<th>Modificación</th>";
                echo "<th>U. Modif.</th>";
                // Botón de edición
                echo "<th>Editar</th>";  
             ?>
           </tr>
        </thead>
        <tbody>
            <?php
                // Recorrer todas las filas y cada columna
                foreach($rows as $afila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    echo '<tr>';
                    echo '<form name="fuser" id="fuser" method="post">';
                    echo '<input type="hidden" name="idform" value="fuser">';
                    // Poner el orden establecido
                    $afila = get_object_vars($afila);
                    
                    echo '<input type="hidden" name="id" value="'.$afila["id"].'">';
                    //echo '<tr ondblclick="fclick(\''.$afila["id"].'\')">';
                    foreach($cols as $col)
                    {
                        $acol = get_object_vars($col);
                        echo "<td>".$afila[$acol['name']]."</td>";                   
                    }
                    // Auditoria
                    echo "<td>".date('d-m-Y H:i:s',$afila["fcreate"])."</td>";
                    echo "<td>".$afila["ucreate"]."</td>";
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date('d-m-Y H:i:s',$afila["fmodif"])."</td>";
                        echo "<td>".$afila["umodif"]."</td>";
                    }else {
                        echo "<td></td>";
                        echo "<td></td>";
                    }
                    echo '<td><input type="submit" class="gboton" name="bedit" id="bedit" value="Editar" onclick="openTab(event, \'Edición\')"></td>';
                    // Final de fila
                    echo '</form>';
                    echo "</tr>";
                }
            ?>
            
        </tbody>
        </table>
        </div> 
        </form>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
    </body>
</html>

