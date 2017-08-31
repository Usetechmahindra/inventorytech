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
        $cimport = new centity("filesexcel");
        if($_POST['idform'] == 'ffindexcel') {
            $rows = $cimport->findsexcel($gentity);
        }
        if($_POST['idform'] == 'importexcel') {
            // Controlar Nuevo o busqueda
            if(!empty($_FILES["fileToUpload"]["tmp_name"])) {
               $_POST['docid'] = $cimport->newexcel($gentity); 
            }
            $rows = $cimport->findsexcel($gentity);
        }
        
        ?>
    </head>
    <body>
        <form name="ffindexcel" id="ffind" method="post">
           <!--Parametro oculto que identificar el form.--> 
           <input type="hidden" name="idform" value="ffindexcel">
            <div id="dfind">
                <?php
                    // Recorrer los parámetros dinámicos de la entidad
                    $cimport->labelinput('docid',"",'Id Fichero','number',5,false,false,false);
                    $cimport->labelinput('pkname',"",'Nombre fichero','text',30,false,false,false);
                    $cimport->labelinput('ddfile',"",'Desde','date',10,false,false,false);
                    $cimport->labelinput('hdfile',"",'Hasta','date',10,false,false,false);
                    $cimport->labelinput('bproc',null,'Procesado','checkbox',15,false,true,false);
                    echo '<hr style="color:'.$_SESSION['color'].';" />';
                ?>    
            </div>
        </form>
        <div id="dgrid">
        <form name="importexcel" id="impexcel" method="post" enctype="multipart/form-data">
        <?php
            echo '<input type="hidden" name="idform" value="importexcel">';
            echo '<input type="file" name="fileToUpload" id="fileToUpload">';
            echo '<input type="submit" class="boton" name="bnew" id="bnewi" value="Cargar Excel" onclick="return confirm(\'¿Cargar excel?\');"/>';
        ?>
        </form>    
        <table id="tgrid">
        <thead>
           <tr>
             <?php
                // Recorrer el array de columnas
                // auditoria
                echo "<th>ID Fichero</th>";
                echo "<th>Nombre Fichero</th>";
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
                    echo '<form name="fimport" id="fimport" method="post">';
                    echo '<input type="hidden" name="idform" value="fimport">';
                    // Poner el orden establecido
                    $afila = get_object_vars($afila);
                    
                    echo '<input type="hidden" name="id" value="'.$afila["id"].'">';
                    //echo '<tr ondblclick="fclick(\''.$afila["id"].'\')">';
                    echo "<td>".$afila["docid"]."</td>";
                    echo "<td>".$afila["pkname"]."</td>";
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
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
        </form>
    </body>
</html>

