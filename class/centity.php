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
        array_push($acolclass, array("name" => "bexcel", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "timezone", "type" => "text","default" => "Europe/Madrid"));
        array_push($acolclass, array("name" => "color", "type" => "color","default" => "#e31732"));
        array_push($acolclass, array("name" => "colorinvert", "type" => "color","default" => "#636161"));
        
        return $acolclass;
    }
     public function update($arow,$iop=2)       
    {
        // Llamada a la función padre
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
            $aitem['ucreate'] = "root";
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
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow); 
}
