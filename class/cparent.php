<?php
/* 
 * Parent class. Itech interface.
 */
class cparent implements itech
{
    // Connectar al bucket configurado en el init
    public function connbucket()
    {
        // Al conectar a la apps leer 1 vez y guardar en sesión el array.
        try {
            // Connectar al bucket
            $cluster = new CouchbaseCluster($_SESSION['couchserver']);
            $bucket = $cluster->openBucket($_SESSION['$bucketName'],$_SESSION['$passbucket']);
            // Bien
            return $bucket;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            echo $_SESSION['textsesion'];
            return -1;
        }
    }
    // Funciones de interfaz
    private function counter($ivalue=1) {
        // Permite incrementar o disminuir por defecto 1
        $icount = $bucket->counter('c_'.$this->getentity(), $ivalue, array('initial' => 1));
        return $icount;
    }
    public function newclass($arow);
    public function insert($arow);
    public function audit($arow);
    public function create($arow);
    public function update($arow);
    public function delete($arow);
    // Log error generico
    public function error() {
        try {
            // Contador de error
            $icount = $bucket->counter('e_'.$this->$nentity, $ivalue, array('initial' => 1));
            // Grabar doc error
            $result = $_SESSION['bucket']->upsert($entidad.'_'.$acont['scont'], array(
            "docid" => $acont['icont'],
            "entidad" =>$entidad,
            "email" => $entidad."@techmahindra.com",
            "interests" => array("Queens")
        ));
            // Bien
            return 1;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            echo $_SESSION['textsesion'];
            return -1;
        }
    }
}
