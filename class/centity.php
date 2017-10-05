<?php
/* 
 * Entity class. Itech interface.
 */
class centity extends cparent
{
     public function my_arrayclass()
    {
        // Retorna el array de campos obligatorios de la clase
        $acolclass = parent::my_arrayclass();
        // Cada clase tendra sus array default.  
        array_push($acolclass, array("name" => "buser", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bgroup", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "btools", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "timezone", "type" => "text","default" => $_SESSION['timezone']));
        array_push($acolclass, array("name" => "color", "type" => "color","default" => "#e31732"));
        array_push($acolclass, array("name" => "colorinvert", "type" => "color","default" => "#636161"));
        return $acolclass;
    }
     public function update($arow,$iop=2)       
    {
        // Llamada a la función padre
        // Control de timezone
        if ($arow['timezone'] == '0') {
            $arow['timezone']=$_SESSION['timezone'];
        }
        $arow=parent::update($arow,$iop);
        // Llamada a función de creación de item auto
        $this->itemdef($arow);
        // Retornar la fila final
        return $arow;
    }
    
    private function itemdef($arow)
    {
        try {
            // Recorrer todas las filas pasadas
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            // Item default entidad
            $aitem = array();
            //// pkname
            $key = 'item_'.$arow[0]->id;
            $aitem['docid'] = 0;
            $aitem['entidad'] = "item_entidad";
            $aitem['fkentity'] = $arow[0]->id;
            $aitem['name'] = "pkname";
            $aitem['label'] = "Nombre";
            $aitem['type'] = "text";
            $aitem['size'] = 20;
            $aitem['ipos'] = -1;
            $aitem['bfind'] = true;
            $aitem['bgrid'] = true;
            $aitem['brequeried'] = true;
            $aitem['breadonly'] = false;
            $aitem['fcreate'] = time();
            $aitem['ucreate'] = $_SESSION['user'];
            $aresult = $bucket->upsert($key,$aitem);
            // Item de usuarios de entidad
            if($arow[0]->buser) {
                $key = 'item_usuario_'.$arow[0]->id;
                $aitem['entidad'] = "item_usuario";
                $aresult = $bucket->upsert($key,$aitem);
            }
            // Item de grupos de entidad
            if($arow[0]->bgroup) {
                $key = 'item_grupo_'.$arow[0]->id;
                $aitem['entidad'] = "item_grupo";
                $aresult = $bucket->upsert($key,$aitem);
            } 
            
        } catch (Exception $ex) {
            $_SESSION['textsesion']="Error en creación de item de entidad.".$ex->getMessage();
            $this->error();
            return -1; 
        }
    }
    
    public function updatelogo()
    {
        try {
            $target_dir =  "../upload/images/entidad/";
            //$target_file = $_POST['id'];
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
    //      
            //echo $target_file;
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            //Control de nombre path+id+type
            $target_file = $target_dir.$_POST['id'].'.'.$imageFileType;
            // Check if image file is a actual image or fake image
            if(isset($_POST["update_imagen"])) {
                $check = getimagesize($_FILES['image']["tmp_name"]);
                if($check !== false) {
                    $_SESSION['textsesion']="El fichero es del tipo imagen - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    $_SESSION['textsesion']="El fichero no se ha detectado como imagen, selecione otro fichero.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                $_SESSION['textsesion']="Se procederá a sustituir la imagen actual.";
            //    $uploadOk = 0;
            }
            // Check file size. "2MB"
            if ($_FILES["image"]["size"] > 2097152) {
                $_SESSION['textsesion']= "Sólo se permite subir imagenes de hasta 2MB.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "bmp") {
                $_SESSION['textsesion']="Formatos de imagen válidos JPG, JPEG, PNG, GIF y BMP.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['textsesion']=$_SESSION['textsesion']." Lo sentimos, el fichero no puede subirse al servidor.";
                return 0;
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Actualizar el documento entidad
                    $arow = get_object_vars($this->getdocid($_POST['id']));
                    $arow['logo'] = $target_file;
                    return $this->update($arow);
                } else {
                    $_SESSION['textsesion']="Lo sentimos, se produjo un error en la subida del fichero. Vuelva a intentarlo.";
                    return 0;
                }
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion'].=$ex->getMessage();
            $this->error();
            return -1; 
        }

    }
    public function findsexcel($gentity) {
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select meta(u).id,u.* from techinventory u where entidad='filesexcel' and fkentity ='".$gentity."'"; 
            // Control post ( Ver filtros de g_import form).
            if (!empty($_POST['docid'])) {
               $n1ql.= " and docid =".$_POST['docid'];
            }
            if (!empty($_POST['nfile'])) {
               $n1ql.= " and pkname ='".strtoupper($_POST['nfile']);
            }
            if (!empty($_POST['ddfile'])) {    
               $n1ql.= " and fcreate >= ".strtotime($_POST['ddfile']);
            }
            if (!empty($_POST['hdfile'])) {
               // Sumar 1 día en segundos 60*60*24 
               $n1ql.= " and fcreate < ".(strtotime($_POST['hdfile'])+86400);
            }
            if (isset($_POST['bproc'])) {
               $n1ql.= " and bproc=TRUE";
            }else{
                $n1ql.= " and bproc is null";     
            }
            $n1ql.=" order by docid";
            return $this->select($n1ql);
            // Añadir 
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función filesexcel: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    public function gridexcel($rows,$id)
    {
        echo '<table id="tgrid">';
        echo '<thead>';
        if(is_null($id))
        {
            $this->gridcabexcel($rows);
        }else {
          $this->griddetexcel($id);  
        }
        echo '</tbody>';
        echo '</table>';
    }
    
    private function gridcabexcel($rows)
    {
        try {
            echo '<tr>';
                    // Recorrer el array de columnas
                    // auditoria
                    echo "<th>ID Fichero</th>";
                    echo "<th>Nombre Fichero</th>";
                    echo "<th>Procesado</th>";
                    echo "<th>Alta</th>";
                    echo "<th>U. Alta</th>";
                    echo "<th>Modificación</th>";
                    echo "<th>U. Modif.</th>";
                    // Botón de edición
                    echo "<th>Editar</th>"; 
                    echo "<th>Borrar</th>";  
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
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

                    $afila['bproc'] = $this->rowgrid($afila['bproc'], 'checkbox');
                    echo "<td>".$afila['bproc']."</td>";      

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
                    // Control de botones
                    if ($afila['bproc'] =='NO')
                    {
                        echo '<td><input type="submit" class="gboton" name="bedit" id="bedit" value="Editar"></td>';
                        echo '<td><input type="submit" class="gdangerboton" name="bbaja" id="bbaja" value="Borrar" onclick="return confirm(\'¿Borrar fila?\');"></td>';
                    }else {
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    // Final de fila
                    echo '</form>';
                    echo "</tr>";
                }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función gridcabexcel: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    private function griddetexcel($id)
    {
        try {
            // Cargar los detalles de la fila por el id
            $n1ql="select meta(u).id,u.* from techinventory u where entidad='item_excel' and fkentity ='".$id."' order by ipos,docid";
            $rows=$this->select($n1ql);
            echo '<tr>';
                    // Recorrer el array de columnas
                    // auditoria
                    echo "<th>ID Fichero</th>";
                    echo "<th>Posición</th>";
                    echo "<th>Nombre Parámetro</th>";
                    echo "<th>Procesar</th>";
                    echo "<th>Tipo</th>";
                    echo "<th>Tamaño</th>";
                    echo "<th>Modificación</th>";
                    echo "<th>U. Modif.</th>";
                    // Botón de edición
                    echo "<th>Editar</th>"; 
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                // Recorrer todas las filas y cada columna
                foreach($rows as $afila){
                    //echo '<tr onclick="openTab(event, \'Edición\')">';
                    echo '<tr>';
                    echo '<form name="fimportdet" id="fimportdet" method="post">';
                    ///////echo '<input type="hidden" name="idform" value="fimportdet">';  // En esta ocasión no es necesario
                    // Poner el orden establecido
                    $afila = get_object_vars($afila);

                    echo '<input type="hidden" name="id" value="'.$afila["id"].'">';
                    //echo '<tr ondblclick="fclick(\''.$afila["id"].'\')">';
                    echo "<td>".$afila["fkentity"]."</td>";
                    
                    
                    
                    echo '<td><input type="number" name="ipos" size=2 min="1" max="999" value='.$afila['ipos'].'></td>';
                    echo '<td><input type="text" name="label" size=25 value="'.$afila['label'].'"></td>';
                    $col='<td><input type="checkbox" name="bproc" size=10 value="'.$afila['bproc'].'"';
                    if(!is_null($afila['bproc'])){
                        $col.=' checked';
                    }
                    $col.='></td>';
                    echo $col;
                    
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
                        $sop ='<option value="usuario"';    
                        if($afila['type']=='usuario') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Combo Usuarios</option>';
                        echo $sop;
                        $sop ='<option value="grupo"';    
                        if($afila['type']=='grupo') {
                            $sop.= " SELECTED"; 
                        }
                        $sop .= '>Combo Grupo</option>';
                        echo $sop;
                        
                        $sop ='<option value="grupo_en"';    
                        if($afila['type']=='grupo_en') {
                            $sop.= " SELECTED"; 
                        }
                        // Rellenar automáticamente las entidades de menú
                        $aentidades = $this->comboentidad('entidad_0');
                        foreach($aentidades as $fila) {
                            $sop ='<option value="'.$fila['id'].'"';    
                            if($afila['type']==$fila['id']) {
                                $sop.= " SELECTED"; 
                            }
                            $sop .= '>'.$fila['pkname'].'</option>';
                            echo $sop; 
                        }    
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
                    
                    echo '<td><input type="number" name="size" size=2 min="1" max="999" value='.$afila['size'].'></td>';  
                    // Auditoria
                    if(!empty($afila["fmodif"])) {
                        echo "<td>".date('d-m-Y H:i:s',$afila["fmodif"])."</td>";
                        echo "<td>".$afila["umodif"]."</td>";
                    }else {
                        echo "<td></td>";
                        echo "<td></td>";
                    }
                    // Control de botones
                    echo '<td><input type="submit" class="gboton" name="beditdet" id="beditdet" value="Grabar"></td>';
                    // Final de fila
                    echo '</form>';
                    echo "</tr>";
            }            
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función griddetexcel: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    public function newexcel($gentity)
    {
        try {
            $target_dir = "../upload/excel/";
            //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $_FILES["fileToUpload"]["name"];
            $uploadOk = 1;
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // Check if image file is a actual image or fake image
            if(isset($_POST["bnew"])) {
                $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $_SESSION['textsesion'] = "El fichero no se ha detectado, selecione otro fichero.";
                    $uploadOk = 0;
                }
            }
            // Check if file already exists. Sustituir
            if (file_exists($target_file)) {
            //    echo "Se procederá a sustituir la imagen actual.";
                //$uploadOk = 0;
            }
            // Check file size. "2MB"
            if ($_FILES["fileToUpload"]["size"] > 2097152) {
                $_SESSION['textsesion']="Sólo se permite subir ficheros de hasta 2MB.";
                $uploadOk = 0;
            }
            // Allow certain file formats
//            if($FileType != "xlsx" && FileType != "xls") {
//                $_SESSION['textsesion']="El formato compatible es xlsx o xls.Formato de fichero:".$FileType;
//                $uploadOk = 0;
//            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
               return $uploadOk;     
            // if everything is ok, try to upload file
            } else {
                // Crear entidad tipo fichero excel en BD
                $rfilas = $this->newclass($gentity);
                $rfilas['pkname'] = $rfilas['docid'].'_'. $_FILES["fileToUpload"]["name"];
                $rfilas['bproc'] = NULL;
                $rfilas = $this->update($rfilas,1); 
                $_SESSION['idact'] = $rfilas[0]->id;
                $_SESSION['textsesion'] = "Excel cargado.";
                $target_file = $target_dir .$rfilas[0]->pkname;
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                } else {
                    $_SESSION['textsesion']="Lo sentimos, se produjo un error en la subida del fichero. Vuelva a intentarlo.";
                    return 0;
                }
                return $rfilas;
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función newexcel: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function deleteexcel($docid)
    {
        try {
            // Cargar fila del ID
            $_SESSION['textsesion'] = "";
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                $_SESSION['textsesion']='Error en función deleteexcel: Sin conexión a base de datos.';
                return -1;
            }
            $doc = $bucket->get($docid);
            if (count($doc) > 0) {
                $doc = get_object_vars($doc->value);
            }else
            {
                $_SESSION['textsesion']= "Error al localizar el documento ".$docid;
                return -1;
            }
            // Borrar fichero
            $_SESSION['textsesion']="Borrando fichero excel.";
            $target_dir = "../upload/excel/";
            $target_file = $target_dir .$doc['pkname'];
            unlink($target_file);
            $_SESSION['textsesion']="Fichero excel borrado.Borrando detalles del fichero.";
            // Borrar detalles de fichero item_excel
            $n1ql = "delete from techinventory where fkentity='".$docid."'";
            $query = CouchbaseN1qlQuery::fromString($n1ql);
            // Gets the properties of the given objec
            $result = $bucket->query($query);

            if($result->metrics['resultCount'] == 0)
            {
                $_SESSION['textsesion']="No existían filas de detalles de fichero excel.";
            }
            // Borrar cabecera de fichero
            $bucket->remove($docid);
            // Borrado correctamente
            $_SESSION['textsesion']="Fichero excel borrado correctamente.";
            return 0;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función deleteexcel: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow); 
}
