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
            if ($_POST['idform'] == 'fitem') {
                // Recorrer todas las filas. Actualiar 1 a 1.
                $rfilas = $citem->postauto($gentity,$nentidad);
                // Refrescar ventana
                echo '<meta http-equiv="refresh" content="0">';
            }
            // Parametros de busqueda
            $ritems = $citem->columnitem($nentidad,$gentity);
        ?>
    </head>
    <body>
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
                echo "<th>Grabar</th>";
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
                    ////////////////////////////////////////////////////
                    //////////////// Formulario por fila ///////////////
                    ////////////////////////////////////////////////////
                    echo '<form name="fitem" id="fitem" method="post">';
                    
                    echo '<input type="hidden" name="idform" value="fitem">'; 
                    echo '<input type="hidden" name="id" value="'.$afila['id'].'">';
                    echo '<input type="hidden" name="docid" value='.$afila['docid'].'>';
                    // Valores definidos siempre por la posición.
                    echo '<input type="hidden" name="entidad" value="item">';
                    echo '<input type="hidden" name="fkentity" value="'.$gentity.'">';
                    echo '<input type="hidden" name="nentidad" value="'.$nentidad.'">';

                    echo '<td><input type="text" name="name" size=15 value="'.$afila['name'].'"></td>';
                    echo '<td><input type="text" name="label" size=25 value="'.$afila['label'].'"></td>';
                    echo '<td>';
                        echo '<select name = "type" required="required">';
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
                        $sop ='<option value="combouser"';    
                        if($afila['type']=='combouser') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Combo Usuarios</option>';
                        echo $sop;
                        $sop ='<option value="combogroup"';    
                        if($afila['type']=='combogroup') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Combo Grupo</option>';
                        echo $sop;
                        $sop ='<option value="comboentity"';    
                        if($afila['type']=='comboentity') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Combo Entidad</option>';
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
                        
                        $sop ='<option value="email"';
                        if($afila['type']=='email') {
                            $sop.= " SELECTED";      
                        }
                        $sop.='>Email</option>';
                        echo $sop;
                        
                        $sop ='<option value="password"';
                        if($afila['type']=='password') {
                            $sop.= " SELECTED";       
                        }
                        $sop.='>Password</option>';
                        echo $sop;
                        
                        $sop ='<option value="checkbox"';
                        if($afila['type']=='checkbox') {
                            $sop.= " SELECTED";      
                        }
                        $sop.='>Checkbox</option>';
                        echo $sop;
                        echo '</select>';
                    echo '</td>';
                    echo '<td><input type="number" name="size" size=2 min="1" max="9999" value='.$afila['size'].'></td>';
                    echo '<td><input type="number" name="ipos" size=2 min="1" max="999" value='.$afila['ipos'].'></td>';
                    // Combos si/no para que siempre se grabe en bd. El update masivo tiene que tener los array del mismo tamaño
                    echo '<td>';
                    echo '<select name = "bfind">';
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
                    echo '<select name = "bgrid">';
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
                    echo '<select name = "brequeried">';
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
                    echo '<select name = "breadonly">';
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
//                  echo '<form name="itemdel" id="itemdel" method="post">';
//                  echo '<input type="hidden" name="idform" value="itemdel">';
//                  echo '<input type="hidden" name="iddel" value="'.$afila['id'].'">';
                    echo '<td><input type="submit" class="gboton" name="bsave" id="bsaveg" value="Grabar"/></td>';
                    echo '<td><input type="submit" class="gdangerboton" name="bdown" id="bdown" value="Baja" onclick="return confirm(\'¿Borrar fila?\');"></td>';
                    // Final de fila
                    echo "</tr>";
                    echo '</form>';
                }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '<form name="fitem" id="fitem" method="post">';
            echo '<input type="hidden" name="idform" value="fitem">'; 
            echo ' <input type="submit" class="boton" name="bnew" id="bnewg" value="Nuevo"/>';
            echo '</form>';
            ?>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>        
    </body>
</html>

