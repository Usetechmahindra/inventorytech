<?php
/* 
 * User class. Itech interface.
 */
class cuser extends cparent
{
    public function login()
    {
        // Localizar doc tipo usuario
        try {
            $n1ql="select meta(u).id,* from techinventory u inner join techinventory e on keys u.fkentity
                where u.entidad='usuario' 
                and u.username='".$_POST['user']."' 
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
            $_SESSION['user']=$result[0]->u->username;
            $_SESSION['fkentity']=$result[0]->u->fkentity;
            $_SESSION['color'] = $result[0]->e->color;
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
            // Conectado a couch. cargar las entidades >= a la fk del usuario (Sesión).
            $result = $bucket->get($_SESSION['fkentity']);
            // Coger el valor de doc ID
            $n1ql="select meta(e).id,* from techinventory e where e.entidad='entidad' and docid>=".$result->value->docid." order by docid";
            
            $result = $this->select($n1ql);

            if(count($result) == 0)
            {
                $_SESSION['textsesion']="El usuario no tiene un menú dinámico disponible.";
                return 0;
            }
            // Recorrer la filas he ir insertando menu
            foreach($result as $row) {
                echo "<h3>".$row->e->entityname."</h3>";
                echo "<div>";
                // Control de formularios
//                $vmenu ="<p onClick=\"location.href='".$vphp."'\" onMouseover=\"\" style=\" cursor: pointer;\">".$vdescripcion."</p>";
//                "buser":TRUE,"bgroup":TRUE,"bparameter":TRUE,"bexcel":FALSE,
                // Pintar siempre administración (detalles de entidad).  onclick="return a1_onclick('a1')"
                echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getentity\')" style="">Entidades</button>';
                echo '<br>';
                if($row->e->buser){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getuser\')" style="">Usuarios</button>';
                    echo '<br>';
                }
                if($row->e->bgroup){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getgroup\')" style="">Grupos</button>';
                    echo '<br>';
                }
                if($row->e->bparameter){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getparameter\')" style="">Parametros</button>';
                    echo '<br>';
                }
                if($row->e->bexcel){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="javascript:openbody(\''.$row->id.'\' , \'f_getimport\')" style="">Importación Excel</button>';
                    echo '<br>';
                }
                echo "</div>";
            }
            
        } catch (Exception $ex) {
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
