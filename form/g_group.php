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
        $cgroup = new cgroup("cgroup");
        // Retornar array de grupos de entidad
        $rows = $cgroup->getgroupentity($gentity);
        ?>
    </head>
    <body>
        <form name="fgroup" method="post">
        <div id="dgrid">
        <table id="tgrid">
        <thead>
           <tr>
             <?php
                //Recorrer el array y pintar los nombres de columnas
                $afila = get_object_vars($rows[0]);
                // Realizar la asignación manual para evitar el orden con lo que lo retorna el motor de bd
                // Utilizar esta lógica para los parámetros variables
//                foreach($afila as $clave =>$valor){
//                    echo "<th>".$clave."</th>";
//                } 
                echo "<th>Entidad</th>";
                echo "<th>Nombre grupo</th>";
                echo "<th>Descripción</th>";
                echo "<th>Email</th>";
                echo "<th>Alta</th>";
                echo "<th>Modificación</th>";
             ?>
           </tr>
        </thead>
        <tbody>
            <?php
    //          $result = mysql_query("SELECT idalert,idparametro,idusuario,estado,tipo,operacion,valor,textalert,nbit,horaminbit,horamaxbit,falta from alertserver where idserver=".$_SESSION['idserver']." order by idusuario,idparametro");
    //          while( $row = mysql_fetch_assoc( $result ) ){
                // Recorrer todas las filas y cada columna
                foreach($rows as $cfila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    $afila = get_object_vars($cfila);
                    echo '<tr onclick="Fsubmit(\'fgroup\', \''.$afila["id"].'\')">';
                    echo '<input type="hidden" name="id[]" value="'.$afila["id"].'">';
                    // Poner el orden establecido
                    echo "<td>".$afila["entityname"]."</td>";
                    echo "<td>".$afila["groupname"]."</td>";
                    echo "<td>".$afila["description"]."</td>";
                    echo "<td>".$afila["emailgroup"]."</td>";
                    echo "<td>".date("d/m/Y",strtotime($afila["fcreate"]))."</td>";
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date("d/m/Y",strtotime($afila["fmodif"]))."</td>";
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
    </body>
</html>

