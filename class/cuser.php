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
                $_SESSION['cwidth'] = '900';
                $_SESSION['cheight'] = '550';
                break;
            case 'M':
                $_SESSION['cwidth'] = '1280';
                $_SESSION['cheight'] = '570';
                break;
            case 'F':
                $_SESSION['cwidth'] = '1680';
                $_SESSION['cheight'] = '900';
                break;
            default:
                $_SESSION['cwidth'] = '1280';
                $_SESSION['cheight'] = '570';  
            }
        return 1;
    }
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}
