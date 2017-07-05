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
        $bucket = $this->connbucket();
        if($bucket == -1)
        {
            return -1;
        }
        $n1ql="select meta(u).id,* from techinventory u where u.entidad='usuario' and u.username='".$_POST['user']."' and bloginapp=TRUE";

        $query = CouchbaseN1qlQuery::fromString($n1ql);
        // Gets the properties of the given objec
        $result = $bucket->query($query);

        if($result->metrics['resultCount'] == 0)
        {
            $_SESSION['textsesion']="No existe ningún usuario con los datos introducidos.";
            return 0;
        }
        
        $codecpass = base64_encode($_POST['password']);

        //echo $codecpass;
        
        if ($result->rows[0]->u->password <> $codecpass)
        {
            $_SESSION['textsesion']="No existe ningún usuario con la contraseña introducida.";
            return 0; 
        }
        $_SESSION['textsesion']="Bienvenido:".$result->rows[0]->u->description;
        $_SESSION['user']=$result->rows[0]->u->username;
        $_SESSION['fkentity']=$result->rows[0]->u->fkentity;
        $_SESSION['minsesion'] = 10;
        $_SESSION['tlogon'] = time();
        // Configuración tamaño sesion
        $this->setformsize($result->rows[0]->u->appsize);
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
        return 1;
    }
    // Dependiendo del tamaño configurado en el usuario configurar el contenedor principal
    private function setformsize($csize)
    {
        switch ($csize) {
            case 'S':
                $_SESSION['cwidth'] = '870';
                $_SESSION['cheight'] = '550';
                break;
            case 'M':
                $_SESSION['cwidth'] = '1250';
                $_SESSION['cheight'] = '570';
                break;
            case 'F':
                $_SESSION['cwidth'] = '1650';
                $_SESSION['cheight'] = '900';
                break;
            default:
                $_SESSION['cwidth'] = '1250';
                $_SESSION['cheight'] = '570';  
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
            $query = CouchbaseN1qlQuery::fromString($n1ql);
            $result = $bucket->query($query);

            if($result->metrics['resultCount'] == 0)
            {
                $_SESSION['textsesion']="El usuario no tiene un menú dinámico disponible.";
                return 0;
            }
            // Recorrer la filas he ir insertando menu
            foreach($result->rows as $row) {
                echo "<h3>".$row->e->entityname."</h3>";
                echo "<div>";
                // Control de formularios
//                $vmenu ="<p onClick=\"location.href='".$vphp."'\" onMouseover=\"\" style=\" cursor: pointer;\">".$vdescripcion."</p>";
//                "buser":TRUE,"bgroup":TRUE,"bparameter":TRUE,"bexcel":FALSE,
                // Pintar siempre administración (detalles de entidad).
                echo '<button class="mboton" value="'.$row->id.'" onclick="showOpcion(this.value)" style="">Entidades</button>';
                echo '<br>';
                if($row->e->buser){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="showOpcion(U,this.value)" style="">Usuarios</button>';
                    echo '<br>';
                }
                if($row->e->bgroup){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="showOpcion(G,this.value)" style="">Grupos</button>';
                    echo '<br>';
                }
                if($row->e->bparameter){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="showOpcion(P,this.value)" style="">Parametros</button>';
                    echo '<br>';
                }
                if($row->e->bexcel){
                    echo '<button class="mboton" value="'.$row->id.'" onclick="showOpcion(I,this.value)" style="">Importación Excel</button>';
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
