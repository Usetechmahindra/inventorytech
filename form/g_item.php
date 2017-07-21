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
        $citem = new citem("item");
        // Parametros de busqueda
        $cols = $citem->columnitem($nentidad,$gentity);
        // Función busquedagrid
        if ($_POST['idform'] == 'ffinditem') {
//            $rows = $citem->postauto();
//        }else {
//                $rows = $citem->getbysearch("", "", $gentity);
        } 
        ?>
    </head>
    <body>
        <form name="ffind" id="ffind" method="post">
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
//                    echo '<tr onclick="Fsubmit(\'fgroup\', \''.$afila["id"].'\')">';
//                    echo '<input type="hidden" name="id[]" value="'.$afila["id"].'">';
                    // Poner el orden establecido
                    $afila = get_object_vars($afila);
                    foreach($cols as $col)
                    {
                        $acol = get_object_vars($col);
                        // Control tipo select
                        if ($acol['type'] == 'select') {                          
                            $fkname = $cgroup->getfkname($afila[$acol['name']]);
                            $afila[$acol['name']] = $fkname;
                        }
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

