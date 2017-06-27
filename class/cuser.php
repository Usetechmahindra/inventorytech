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
        if($this->connbucket() == -1)
        {
            return -1;
        }
        $n1ql="select * from techinventory u where u.entidad='usuario' and u.username='".$_POST['user']."'";
        
        $query = CouchbaseN1qlQuery::fromString($n1ql);
        // Gets the properties of the given objec
        $arows = (array)$_SESSION['bucket']->query($query);
        
        if($arows['metrics']['resultCount'] == 0)
        {
            $_SESSION['textsesion']="No existe ningún usuario con los datos introducidos.";
            return 0;
        }
        $a = (array)$arows['rows'];
        var_dump($a);


        // Control de password en code64
        //var_dump($arows);

        
        $codecpass = base64_encode($_POST['password']);

        
        //echo $codecpass;
        
        if ($arows['results']['u']['password'] <> $codecpass)
        {
            $_SESSION['textsesion']="No existe ningún usuario con la contraseña introducida.";
            return 0; 
        }
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
    
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}