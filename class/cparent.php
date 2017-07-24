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
        date_default_timezone_set($_SESSION['timezone']); 
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
            $arow['entidad'] = $this->nclase;
            $arow['docid'] = $this->counter();
//            $arow['id'] = $this->nclase.'_'.str_pad($arow['docid'], 4, "0", STR_PAD_LEFT);
            // Las ordenaciones se hacen por docid, no es necesario formatear con 0.
            $arow['id'] = $this->nclase.'_'.$arow['docid'];
            $arow['fcreate']=time();
            $arow['ucreate']=$_SESSION['user'];
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función newclass: '.$e->getMessage();
            $this->error();
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
            $this->error();
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
            $this->error();
            return -1;
        }
    }
    public function update($arow)
    {
        try {
            // Recorrer todas las filas pasadas
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            // Solo actualizar la fila que se ha indicado.
            // Controlar si es nuevo, llamar a la función create 
            if(empty($arow['id']))
            {
                // Realizar la busqueda por nombre. Siempre es el campo principal
                $findname=$this->getbysearch('name', $arow['name'], $arow['fkentity'],FALSE);
                // Si la ha encontrado por nombre
                if (count($findname) > 0)
                {
                    $arow = get_object_vars($findname[0]);
                    $_SESSION['textsesion'] ="Se ha localizado un registro previo.";
                    return $arow;
                }else{
                    $arow = $this->newclass($afila);
                    $_SESSION['textsesion'] ="Nueva creación realizada.";
                }
            }else{
                $arow['fmodif']=time(); 
                $arow['umodif']=$_SESSION['user'];
            }
            // Lanzar el UPSERT en BD
            $arow = $bucket->upsert($arow['id'],$this->avalues($arow));
            // Correcto
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error al inicializar datos '.$e->getMessage();
            $this->error();
            return -1; 
        }
    }
    public function delete($arow)
    {
        try {
            // Borrado por id
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            $arow = $bucket->remove($arow['iddel']);
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en borrado: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    public function labelinput($skey,$svalue,$slabel,$stype,$isize=10,$brequired=false,$bfind=false,$disabledread="")
    {
        // A la función se le pasan los parametros para que pinte en bloque el input con el label y su tipo
        try {
            // Si tiene busqueda es requerido.
            if($brequired) {
                $srequired = "required";
            }
            echo '<div class="labelinput">';
                echo '<label for="'.$skey.'">'.$slabel.'</label> <br />';
                // Dependiendo del tipo de caja.
                $this->configlavel($svalue,$stype,$isize);
                echo '<input type="'.$stype.'" name="'.$skey.'" size="'.$isize.'" maxlength="'.$isize.'" '.$srequired.' '.$disabledread.' value="'.$svalue.'" />';
                // Si es tipo fecha poner su clase formato jquery
                // Si hay que pintar la busqueda
            echo '</div>  ';
            if($bfind)
            {
                echo ' <input type="submit" class="bfind" name="f'.$skey.'" id="f'.$skey.'" value=""/> ';
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error crear input: '.$e->getMessage();
            $this->error();
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
                    $svalue = date('d/m/Y H:i:s',$svalue);
                    $isize = 15;
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
    ////////////////////////////////////////////////////////////////////////////
    // Funciones auxiliares
    private function avalues($arow)
    {
        // Retorna el el id del arow
        $avalues = $arow;
        unset($avalues['id']);
        unset($avalues['idform']);
        unset($avalues['iddel']);
        // botones
        unset($avalues['bsave']);
        return $avalues;
    }
    // Obtener los datos de una entidad por un valor de busqueda. OP. fkentity
    public function getbysearch($item,$value,$fkentity,$blike=TRUE)
    {
        try {
             $_SESSION['textsesion'] = "";
             $n1ql="select meta(u).id,e.entityname,u.*
                    from techinventory u inner join techinventory e
                    on keys u.fkentity 
                    where u.entidad='".$this->nclase."'";
             // Controla filtro
             if (!empty($item)) {
                 if ($blike) {
                    $n1ql.=" and u.".$item." like '%".$value."%'";
                 }else {
                    $n1ql.=" and u.".$item." = '".$value."'"; 
                 }
             }                
             // Control de entidad padre
             if (!empty($fkentity)) {
                 $n1ql.="and u.fkentity='".$fkentity."'";
             }
             // Traer filas de entidad
             return $this->select($n1ql);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getbysearch: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    public function itementity($gentity,$itype=0) {
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select u.* from techinventory u where entidad='item' and fkentity ='".$gentity."' and nentidad='".$this->nclase."'";
            // Dependiendo del tipo: 0 Todos, 1 grid, 2 campos de filtro
            switch ($itype) {
               case 1:
                   $n1ql.=" and bgrid='1'";
                   break;
               case 2:
                   $n1ql.=" and bfind='1'";
                   break;
               default:
                   break;
            }
                
            $n1ql.=" order by ipos";
            return $this->select($n1ql);
            // Añadir 
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función itementity: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    // Obtiene el nombre de la entidad pasando el key del doc.
    public function getfkname($key)
    {
        try {
            $n1ql = "SELECT name FROM techinventory tech where META(tech).id = '".$key."'";
            $docid = $this->select($n1ql);
            if(!empty($docid)) {
                $key = $docid[0]->name;
            }
            return $key;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getentityname: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    public function getdocid($pid) {
        try {
            // Obtener por id
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                $_SESSION['textsesion']='Error en función getdocid: Sin conexión a base de datos.';
                return -1;
            }
            $result = $bucket->get($pid);
            $result->value->id = $pid;
            return $result->value;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getdocid: '.$e->getMessage();
            $this->error();
            return -1;
        }
        
    }
    // Check post auto. Dependiendo. Botones new, update, busquedas.....
    public function postauto($pentity)
    {
        try {
            // Check new
            $rfilas = $_POST;
            if (isset($_POST['bnew'])) {
                $rfilas = $this->create($rfilas); 
                return $rfilas;
            }         
            // Check update
            if (isset($_POST['bsave'])) {
                // Controlar si existe grupo por nombre
                // Recorrer todas las filas
                $result = $this->update($rfilas); 
                // Control de errores
                if ($result == -1) {
                    // Asignar el post original, en la var de sesión mostrará el error.
                    $rfilas = array();
                    array_push($rfilas,$_POST);
                } 
                return $rfilas;
            }
            // Baja
            if (isset($_POST['bdown'])) {
                // Control de baja
                return $this->delete($rfilas);
            }
            // Check find buttons
            if (!is_null($rfilas)) {
                $rfilas = $this->findbutton($pentity);
            }
            // Retorno
            return $rfilas;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función postauto: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    // La función hace localiza los campos de busqueda y hace la llamada a la busqueda
    // En $_POST sólo aparecera el boton de búsqueda pinchado
    private function findbutton($pentity)
    {
        try {
            // Localizar y recorrer los campos de busqueda para identificar el boton q lanzo el post
            $afilter = $this->itementity($pentity,TRUE);
            foreach($afilter as $filtro)
            {
                $rfilas=$_POST;
                $acol = get_object_vars($filtro);
                // Localizar por boton: f+valor de campo
                $clave = array_search('f'.$acol['name'], array_keys($_POST));
                // Si es distinto de false ha encontrado la columna en el post
                if($clave <> FALSE) {
                    // Realizar la busqueda.
                    $rfilas=$this->getbysearch($acol['name'],$_POST[$acol['name']],$pentity);
                }
                return $rfilas;
            }

        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función findbutton: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    // Log error generico
    public function error() {
        try {
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            // Contador de error
            $icount = $this->counter(1,'e');
            // Grabar doc error
            $result = $bucket->upsert('e_'.$this->nclase.'_'.$icount, array(
            "docid" => $icount,
            "entidad" =>'e_'.$this->$cname,
            "fcreate" => date('d/m/Y H:m:s'),
            "user" => $_SESSION['user'],
            "error" => $_SESSION['textsesion']));
            // Bien
            return 1;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            return -1;
        }
    }
}
