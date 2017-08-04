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
        $cgroup = new cgroup("grupo");
        // Parametros de busqueda
        $cols = $cgroup->itementity($gentity,1);
        // Campos de filtro
        $afilter = $cgroup->itementity($gentity,2);
        // Función busquedagrid
        if ($_POST['idform'] == 'ffindgroup') {
            $rows = $cgroup->postauto($gentity);
        }else {
            // Controlar formulario grid
            if ($_POST['idform'] == 'fgroup') {
                $_SESSION['idact'] = $_POST['id'];
            }else {
                // Lanzar la busqueda
                $rows = $cgroup->getbysearch("", "", $gentity);
            }
        } 
        ?>
    </head>
    <body>
        <form name="ffindgroup" id="ffind" method="post">
           <!--Parametro oculto que identificar el form.--> 
           <input type="hidden" name="idform" value="ffindgroup">
            <div id="dfind">
                <?php
                    // Recorrer los parámetros dinámicos de la entidad
                    foreach($afilter as $filtro)
                    {
                        $acol = get_object_vars($filtro);
                        // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
                        // Requerido al falso
                        $acol['brequeried'] = FALSE;
                        $cgroup->labelinput($acol['name'],"",$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],false);
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
                
                // Realizar la asignación manual para evitar el orden con lo que lo retorna el motor de bd
                // Utilizar esta lógica para los parámetros variables
//                foreach($afila as $clave =>$valor){
//                    echo "<th>".$clave."</th>";
//                } 
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
    //          $result = mysql_query("SELECT idalert,idparametro,idusuario,estado,tipo,operacion,valor,textalert,nbit,horaminbit,horamaxbit,falta from alertserver where idserver=".$_SESSION['idserver']." order by idusuario,idparametro");
    //          while( $row = mysql_fetch_assoc( $result ) ){
                // Recorrer todas las filas y cada columna
                foreach($rows as $afila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    echo '<tr>';
                    echo '<form name="fgroup" id="fgroup" method="post">';
                    echo '<input type="hidden" name="idform" value="fgroup">';
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
                    echo "<td>".date('d/m/Y H:i:s',$afila["fcreate"])."</td>";
                    echo "<td>".$afila["ucreate"]."</td>";
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date('d/m/Y H:i:s',$afila["fmodif"])."</td>";
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
<!--        <input type="submit" name="update_alert" value="Actualizar">
        <input type="submit" name="insert_alert" value="Insertar">
        <input type="submit" name="check_alert" value="Comprobar">-->
<!--        <input type="submit" name="check_email" value="Check Email">-->
        
        </form>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
    </body>
</html>

