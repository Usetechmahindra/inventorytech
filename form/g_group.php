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
        
        
        // Retornar array de grupos de entidad
        if (!empty($_POST)){
            $rows = $cgroup->postauto();
        }
        $rows = $cgroup->getbysearch("", "", $gentity);
        $afila = get_object_vars($rows[0]);
        $cols = $cgroup->itementity();
        $afilter = $cgroup->itementity(TRUE);
        ?>
    </head>
    <body>
        <form name="ffind" method="post">
            <div id="dfind">
                <?php

                    // Recorrer los parámetros dinámicos de la entidad
                    foreach($afilter as $filtro)
                    {
                        $acol = get_object_vars($filtro);
                        // $skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$readonly=""
                        $cgroup->labelinput($acol['name'],"",$acol['label'],$acol['type'],$acol['size'],$acol['brequeried'],$acol['bfind'],false);
                    }
        echo '<hr style="color:'.$_SESSION['color'].';" />';
                ?>    
            </div>
        </form>
        <form name="fgroup" method="post">
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
                foreach($rows as $cfila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    $afila = get_object_vars($cfila);
                    echo '<tr onclick="Fsubmit(\'fgroup\', \''.$afila["id"].'\')">';
                    echo '<input type="hidden" name="id[]" value="'.$afila["id"].'">';
                    // Poner el orden establecido
                    foreach($cols as $col)
                    {
                        $acol = get_object_vars($col);
                        // Control tipo select
                        if ($acol['type'] == 'select') {                          
                            $afila[$acol['name']] = $cgroup->getfkname($afila[$acol['name']]);
                        }
                        echo "<td>".$afila[$acol['name']]."</td>";
                        
                    }
                    // Auditoria
                    echo "<td>".$afila["fcreate"]."</td>";
                    echo "<td>".$afila["ucreate"]."</td>";
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".$afila["fmodif"]."</td>";
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
    </body>
</html>

