<?php
/* 
 * User class. Itech interface.
 */
class cuser extends cparent
{
    public function my_arrayclass()
    {
        // Retorna el array de campos obligatorios de la clase
        $acolclass = parent::my_arrayclass();
        // Cada clase tendra sus array default. 
        array_push($acolclass, array("name" => "password", "type" => "password","default" => NULL));
        array_push($acolclass, array("name" => "bloginapp", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bread", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bshowuser", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bshowgroup", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bshowentidad", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bshowtools", "type" => "checkbox","default" => NULL));
        return $acolclass;
    }
    public function login()
    {
        // Localizar doc tipo usuario
        try {
            $n1ql="select meta(u).id,* from techinventory u inner join techinventory e on keys u.fkentity
                where u.entidad='usuario' 
                and u.pkname='".$_POST['user']."' 
                and u.bloginapp=TRUE";
            // Gets the properties of the given objec
            $result = $this->select($n1ql);

            if(count($result) == 0 || $result == -1)
            {
                $_SESSION['textsesion']="No existe ningún usuario con los datos introducidos.";
                return 0;
            }

            $codecpass = base64_encode($_POST['password']);

            //echo $codecpass;
        
            if ($result[0]->u->password <> $codecpass)
            {
                $_SESSION['textsesion']="No existe ningún usuario con la contraseña introducida.";
                return 0; 
            }
            $_SESSION['textsesion']="Bienvenido:".$result[0]->u->description;
            $_SESSION['user']=$result[0]->u->pkname;
            $_SESSION['fkentity']=$result[0]->u->fkentity;
            
            $_SESSION['bread']=(bool)$result[0]->u->bread;
            $_SESSION['bshowuser']=(bool)$result[0]->u->bshowuser;
            $_SESSION['bshowgroup']=(bool)$result[0]->u->bshowgroup;
            $_SESSION['bshowentidad']=(bool)$result[0]->u->bshowentidad;
            $_SESSION['bshowtools']=(bool)$result[0]->u->bshowtools;
            
            $_SESSION['color'] = $result[0]->e->color;
            $_SESSION['colorinvert'] = $result[0]->e->colorinvert;
            $_SESSION['minsesion'] = 10;
            $_SESSION['timezone'] = $result[0]->e->timezone;
            $_SESSION['tlogon'] = time();
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
        return 1;
    }
    public function usermenudim()
    {
        try
        {
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            //
            // Conectado a couch. cargar las entidades >= a la fk del usuario (Sesión).
            $result = $bucket->get($_SESSION['fkentity']);
            // Coger el valor de doc ID
            $n1ql="select meta(e).id,* from techinventory e where e.entidad='entidad' and fkentity='entidad_0'  and docid>=".$result->value->docid." order by docid";
            
            $result = $this->select($n1ql);

            if(count($result) == 0)
            {
                $_SESSION['textsesion']="El usuario no tiene un menú dinámico disponible.";
                return 0;
            }
            // Recorrer la filas he ir insertando menu
            foreach($result as $row) {
                $ssection = "<h3>";
                if ($row->e->logo <> "") {
                    $ssection.='<img src="'.$row->e->logo.'" alt="" style="width:20px;"/> ';
                }else {
                    $ssection.='<img src="../upload/images/i_menudef.png" alt="" style="width:20px;"/> ';
                }
                $ssection .= $row->e->pkname."</h3>";
                echo $ssection;
                echo "<div>";
                // Control de formularios
//                $vmenu ="<p onClick=\"location.href='".$vphp."'\" onMouseover=\"\" style=\" cursor: pointer;\">".$vdescripcion."</p>";
//                "buser":TRUE,"bgroup":TRUE,"bparameter":TRUE,"bexcel":FALSE,
                // Pintar siempre administración (detalles de entidad).  onclick="return a1_onclick('a1')"
                if($_SESSION['bshowentidad']) {
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getentity\')" style="">Entidades</button>';
                    echo '<br>';
                }
                if($row->e->buser && $_SESSION['bshowuser']){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getuser\')" style="">Usuarios</button>';
                    echo '<br>';
                }
                if($row->e->bgroup && $_SESSION['bshowgroup']){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getgroup\')" style="">Grupos</button>';
                    echo '<br>';
                }
                if($row->e->btools && $_SESSION['bshowtools']){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_gettools\')" style="">Herramientas</button>';
                    echo '<br>';
                }
                echo "</div>";
            }
            
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Grabar auditoria de error.
            $this->error($bucket);
            return -1;
        }
    }
    
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}
