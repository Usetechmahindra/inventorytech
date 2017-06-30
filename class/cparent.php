<?php
/* 
 * Parent class. Itech interface.
 */

include('./interfaces/itech.php');

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
        return $icount;
    }
    public function newclass($arow)
    {
    }
    public function insert($arow)
    {
    }
    public function audit($arow)
    {
    }
    public function create($arow)
    {
    }
    public function update($arow)
    {
    }
    public function delete($arow)
    {
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
