<?php
/* 
 * Parent class. Itech interface.
 */

include('itech.php');

class cparent implements itech
{
    public $nclase;
    
    function __construct($nclase)
    {
        $this->nclase = $nclase;
    }
    // Leer init
    public function readcfg() {
      // Analizar sin secciones
      $arraycfg = parse_ini_file("cfg/techinventory.ini");
      //print_r($arraycfg);
      $_SESSION['couchserver'] = $arraycfg['couchserver'];
      $_SESSION['bucketName'] = $arraycfg['bucketName'];
      $_SESSION['passbucket'] = $arraycfg['passbucket'];
      return 1;
    }
    // Connectar al bucket configurado en el init
    public function connbucket()
    {
        // Al conectar a la apps leer 1 vez y guardar en sesión el array.
        try {
            // Connectar al bucket
            $cluster = new CouchbaseCluster($_SESSION['couchserver']);
            $bucket = $cluster->openBucket($_SESSION['bucketName'],$_SESSION['passbucket']);
            // Bien
            //echo "Bucket conectado:".var_dump($_SESSION['bucket']);
            return $bucket;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
    // Funciones de interfaz
    public function counter($ivalue=1,$vpref='c') {
        // Permite incrementar o disminuir por defecto 1
        $bucket=$this->connbucket();
        if($bucket == -1)
        {
            return -1;
        }
        //echo $this->nclase;
        $icount = $bucket->counter($vpref.'_'.$this->nclase, $ivalue, array('initial' => 1));
        //$icount = $_SESSION['bucket']->counter($vpref.'_pruebas', $ivalue, array('initial' => 1));
        return $icount->value;
    }
    public function newclass($arow)
    {
        // En array añadir el contador
        try { 
            $arow['docid'] = $this->counter();
            $arow['id'] = $this->nclase.'_'.str_pad($arow['docid'], 4, "0", STR_PAD_LEFT);
            $arow['fcreate']=date("d/m/Y H:m:s");
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            return -1;
        }
    }
    public function select($n1ql)
    {
        try {
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            $query = CouchbaseN1qlQuery::fromString($n1ql);
            // Gets the properties of the given objec
            $result = $bucket->query($query);

            if($result->metrics['resultCount'] == 0)
            {
                $_SESSION['textsesion']="No existen filas.";
            }
            return $result->rows;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            return -1;
        }
    }
    public function insert($arow)
    {
    }
    public function audit($arow)
    {
    }
    public function create($arow)
    {
        // La función limpia el formulario $arow en el post resetear todo el array
        try {
            foreach ($arow as &$valor) {
                $valor = NULL;
            }
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error al inicializar datos '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
    public function update($arow)
    {
        try {
            // Controlar si es nuevo, llamar a la función create
            if(empty($arow['id']))
            {
                $arow = $this->newclass($arow);
            }else{
                $arow['fmodif']=date("d/m/Y H:m:s");    
            }
            return $arrow;
        } catch (Exception $ex) {
            // Llamar a función de error..........................................................................
            $_SESSION['textsesion']='Error al inicializar datos '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1; 
        }
    }
    public function delete($arow)
    {
    }
    public function labelinput($skey,$svalue,$slabel,$stype,$isize=10,$bfind=false,$brequired="",$bisabled="enabled")
    {
        // A la función se le pasan los parametros para que pinte en bloque el input con el label y su tipo
        try {
            echo '<div class="labelinput">';
                echo '<label for="'.$skey.'">'.$slabel.'</label> <br />';
                // Dependiendo del tipo de caja.
                $this->configlavel($svalue,$stype,$isize);
                echo '<input type="'.$stype.'" name="'.$skey.'" size="'.$isize.'" maxlength="'.$isize.'" '.$brequired.' '.$bisabled.' value="'.$svalue.'" />';
                // Si es tipo fecha poner su clase formato jquery
                // Si hay que pintar la busqueda
            echo '</div>  ';
            if($bfind)
            {
                echo ' <input type="submit" class="bfind" name="f'.$skey.'" id="f'.$skey.'" value=""/> ';
            }
            $_SESSION['textsesion']="";
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error crear input: '.$e->getMessage();
            return -1;
        }
    }
    
    private function configlavel(&$svalue,&$stype,&$isize)
    {
        // Permite recotar los parametros
        switch ($stype) {
            case 'date':
                $stype = "text";
                if ($svalue <> null)
                {
                    $isize = 15;
                    $svalue = date('d/m/Y H:m:s',strtotime($svalue));
                }
                break;
            default:
                break;
        }
    }
    
    public function CheckLogin()
    {
        if(empty($_SESSION['user']))
        {
           $_SESSION['textsesion'] = "Sesión no iniciada.";
            return -1;
        }
        // máximo tiempo de sesión.
        if ($_SESSION['tlogon'] + $_SESSION['minsesion'] * 60 < time()) {
             $_SESSION['textsesion'] = "Por razones de seguridad su sesión ha esperiado, vuelva a ingresar sus datos en el sistema.";
             return -1;
             // session timed out
         }
         // Añadimos tiempo a la sesion
         $_SESSION['tlogon'] = time();
        return 1;
    }
    // Log error generico
    public function error($bucket) {
        try {
            // Contador de error
            $icount = $this->counter(1,'e');
            // Grabar doc error
            $result = $bucket->upsert('e_'.$this->nclase.'_'.$icount, array(
            "docid" => $icount,
            "entidad" =>'e_'.$this->$cname,
            "fcreate" => date(),
            "error" => $_SESSION['textsesion']));
            // Bien
            return 1;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            echo $_SESSION['textsesion'];
            return -1;
        }
    }
}
