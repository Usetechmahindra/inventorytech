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
        // Función post
        if ($_POST['idform'] == 'fitem' OR $_POST['idform']=='itemdel') {
            // Recorrer todas las filas. Actualiar 1 a 1.
            $rows = $citem->postauto($gentity);
        } 
        // Parametros de busqueda
        $ritems = $citem->columnitem($nentidad,$gentity);
        ?>
    </head>
    <body>
        <form name="fitem" id="fitem" method="post">
        <div id="dgrid">
        <table id="tgrid">
        <thead>
           <tr>
             <?php
                // Columnas fijas
                echo "<th>Nombre</th>";
                echo "<th>Etiqueta</th>";
                echo "<th>Tipo</th>";
                echo "<th>Tamaño</th>";
                echo "<th>Posición</th>";
                echo "<th>Filtro</th>";
                echo "<th>Grid</th>";
                echo "<th>Requerido</th>";
                echo "<th>Lectura</th>";               
                // auditoria
                echo "<th>Modif.</th>";
                echo "<th>Usuario</th>";
                echo "<th>Baja</th>";
             ?>
           </tr>
        </thead>
        <tbody>
            <?php
                // Recorrer todas las filas y cada columna
                foreach($ritems as $afila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
//                    echo '<tr onclick="Fsubmit(\'fgroup\', \''.$afila["id"].'\')">';
//                    echo '<input type="hidden" name="id[]" value="'.$afila["id"].'">';
                                        // Poner el orden establecido
                    $afila = get_object_vars($afila);
                    echo '<input type="hidden" name="idform" value="fitem">'; 
                    echo '<input type="hidden" name="id[]" value="'.$afila['id'].'">';
                    echo '<input type="hidden" name="docid[]" value='.$afila['docid'].'>';
                    // Valores definidos siempre por la posición.
                    echo '<input type="hidden" name="entidad[]" value="item">';
                    echo '<input type="hidden" name="fkentity[]" value="'.$gentity.'">';
                    echo '<input type="hidden" name="nentidad[]" value="'.$nentidad.'">';

                    echo '<td><input type="text" name="name[]" size=15 required="required" value="'.$afila['name'].'"></td>';
                    echo '<td><input type="text" name="label[]" size=25 required="required" value="'.$afila['label'].'"></td>';
                    echo '<td>';
                        echo '<select name = "type[]" required="required">';
                        $sop ='<option value="text"';
                        if($afila['type']=='text') {
                            $sop.= " SELECTED";    
                        }
                        $sop .='>Texto</option>';
                        echo $sop;
                        $sop ='<option value="textarea"';    
                        if($afila['type']=='textarea') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Textarea</option>';
                        echo $sop;
                        $sop ='<option value="date"';
                        if($afila['type']=='date') {
                            $sop.= " SELECTED";       
                        }
                        $sop.='>Fecha</option>';
                        echo $sop;
                        $sop ='<option value="number"'; 
                        if($afila['type']=='number') {
                            $sop.= " SELECTED";     
                        }
                        $sop.='>Número</option>';
                        echo $sop;
                        $sop ='<option value="checkbox"';
                        if($afila['type']=='checkbox') {
                            $sop.= " SELECTED"; 
                                
                        }
                        $sop.='>Checkbox</option>';
                        echo $sop;
                        echo '</select>';
                    echo '</td>';
                    echo '<td><input type="number" name="size[]" size=4 min="1" max="9999" required="required" value='.$afila['size'].'></td>';
                    echo '<td><input type="number" name="ipos[]" size=3 min="1" max="999" required="required" value='.$afila['ipos'].'></td>';
                    // Combos si/no para que siempre se grabe en bd. El update masivo tiene que tener los array del mismo tamaño
                    echo '<td>';
                    echo '<select name = "bfind[]">';
                    $sop ='<option value=0';
                    if($afila['bfind']==0) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>No</option>';
                    echo $sop;
                    $sop ='<option value=1';
                    if($afila['bfind']==1) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>Si</option>';
                    echo $sop;
                    echo '</td>';
                    echo '<td>';
                    echo '<select name = "bgrid[]">';
                    $sop ='<option value=0';
                    if($afila['bgrid']==0) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>No</option>';
                    echo $sop;
                    $sop ='<option value=1';
                    if($afila['bgrid']==1) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>Si</option>';
                    echo $sop;
                    echo '</td>';
                    echo '<td>';
                    echo '<select name = "brequeried[]">';
                    $sop ='<option value=0';
                    if($afila['brequeried']==0) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>No</option>';
                    echo $sop;
                    $sop ='<option value=1';
                    if($afila['brequeried']==1) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>Si</option>';
                    echo $sop;
                    echo '</td>';
                    echo '<td>';
                    echo '<select name = "breadonly[]">';
                    $sop ='<option value=0';
                    if($afila['breadonly']==0) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>No</option>';
                    echo $sop;
                    $sop ='<option value=1';
                    if($afila['breadonly']==1) {
                        $sop.= " SELECTED";    
                    }
                    $sop .='>Si</option>';
                    echo $sop;
                    echo '</td>';
                    // Auditoria
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date('d/m/Y H:i:s',$afila["fmodif"])."</td>";
                        echo "<td>".$afila["umodif"]."</td>";
                    }else {
                        echo "<td></td>";
                        echo "<td></td>";
                    }
                    echo '<form name="itemdel" id="itemdel[]" method="post">';
                    echo '<input type="hidden" name="idform" value="itemdel">';
                    echo '<input type="hidden" name="iddel" value="'.$afila['id'].'">';
                    echo '<td><input type="submit" name="bdown" id="bdown" value="Baja"></td>';
                    echo '</form>';
                    // Final de fila
                    echo "</tr>";
                }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo ' <input type="submit" class="boton" name="bsave" id="bsaveg" value="Grabar"/>';
            echo ' <input type="submit" class="boton" name="bnew" id="bnewg" value="Nuevo"/>';
            ?>
        </form>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
    </body>
</html>

