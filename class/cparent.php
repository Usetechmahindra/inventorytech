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
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución: '.$ex->getMessage();
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
    public function newclass($fkentity=null)
    {
        // En array añadir el contador
        try {
            $arrow = array();
            $arow['entidad'] = $this->nclase;
            $arow['docid'] = $this->counter();
            if(!is_null($fkentity)) {
                $arow['fkentity'] = $fkentity;
            }
            // Las ordenaciones se hacen por docid, no es necesario formatear con 0.
            $arow['id'] = $this->nclase.'_'.$arow['docid'];
            // Campo name varchar obligatorio
            $arow['pkname'] = "";
            $arow['fcreate']=time();
            $arow['ucreate']=$_SESSION['user'];

            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función newclass: '.$ex->getMessage();
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
            $_SESSION['textsesion']='Error en ejecución: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function insert($arow)
    {
    }
    public function audit($id,$arow,$ioper=1)
    {
        // Crea el registro de auditoria. 1 Alta, 2 Modif, 3 Baja
        try {
            $rowaudit = get_object_vars($arow[0]);
            $rowaudit['docid'] = $this->counter(1, 'aud');
            $rowaudit['idaudit'] = $id; // Id de la fila que se esta auditando.
            unset($rowaudit['id']);
            $rowaudit['typeop'] = $ioper;
            $rowaudit['fmodif'] = time();
            $rowaudit['entidad'] = 'aud_'.$this->nclase;
            // Recorrer todas las filas pasadas
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            $idaud = 'aud_'.$this->nclase.'_'.$rowaudit['docid'];
            $rowaudit = $bucket->upsert($idaud,$rowaudit);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error al inicializar datos '.$ex->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            $this->error();
        }
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
            $_SESSION['textsesion']='Error al inicializar datos '.$ex->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            $this->error();
            return -1;
        }
    }
    public function update($arow,$iop=2)
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
                $findname=$this->getbysearch('pkname', $arow['pkname'], FALSE);
                // Si la ha encontrado por nombre
                if (count($findname) > 0)
                {
                    $_SESSION['textsesion'] ="Se ha localizado un registro previo.";
                    return $findname;
                }else{
                    // Añadir los campos obligatorios a los del post
                    $iop = 1;
                    $pkname = $arow['pkname'];
                    $anew = $this->newclass($afila);
                    foreach ($anew as $key => $value) {
                        $arow[$key] = $value;
                    }
                    $arow['pkname'] =$pkname;
                    $_SESSION['textsesion'] ="Nueva creación realizada.";
                }
            }else{
                $arow['fmodif']=time(); 
                $arow['umodif']=$_SESSION['user'];
            }
            // Lanzar el UPSERT en BD
            $id = $arow['id'];
            $arow = $this->avalues($arow);
            $arow = $bucket->upsert($id,$arow);
            // Correcto
            $arow = $this->getdocid($id);
            $_SESSION['textsesion'] ="Grabación realizada.";
            // Retornar siempre array de clases.
            $afinal = array();
            array_push($afinal,$arow);
            // Auditoria
            $this->audit($id, $afinal,$iop);
            return $afinal;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error al inicializar datos '.$ex->getMessage();
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
            // Auditoria. 3 Baja.
            $this->audit($arow['id'], $arow,3);
            $arow = $bucket->remove($arow['id']);

            $_SESSION['textsesion']='Fila borrada.';
            return null;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en borrado: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    // Permite configurar los campos en el grid al estilo de labelinput
    public function rowgrid($svalue,$stype) {
        try {
            // Depeniendo del tipo pintar el objeto en cuestión
            $etipo = substr($stype,0,8);
            switch ($etipo) {
                case 'checkbox':
                    if (is_null($svalue)) {
                       $svalue = "NO"; 
                    }else {
                        $svalue = "SI";
                    }
                    break;
                case 'date':
                    $svalue = date('d-m-Y',$svalue);
                case 'usuario':
                case 'grupo':
                case 'grupo_en':
                case 'entidad_':
                    // Obtener valor id y ejecutar default
                    $sdocval = $this->fknamedocid($svalue);
                    if ($sdocval <> "") {
                        $svalue = $sdocval;
                    }
                case 'timezone':    
                default:
                    break;
            } 
            return $svalue;            
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error crear valor row: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    private function fknamedocid($docid)
    {
        // Retorna el campo fkname del docid
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select pkname
                    from techinventory u 
                    where meta(u).id='".$docid."'";           
            // Control de entidad padre
            // Traer filas de entidad
            $doc=$this->select($n1ql);
            $sval = "";
            if (count($doc) > 0) {
                $doc = get_object_vars($doc[0]);
                $sval = $doc['pkname'];
            }
            return $sval;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error al obtener valor por docid: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function labelinput($skey,$svalue,$slabel,$stype,$isize=10,$brequired=false,$bfind=false,$breadonly=false)
    {
        // A la función se le pasan los parametros para que pinte en bloque el input con el label y su tipo
        //$cgroup->labelinput($acol['name'],$rgrupo[$acol['name']],$acol['label'],c,$acol['size'],$acol['brequeried'],$acol['bfind'],false);
        try {
            // Si tiene busqueda es requerido.
            if($brequired) {
                $srequired = "required";
            }
            //$svalue ="NUEVO";
            echo '<div class="labelinput">';
            // Si tiene label añadirla
            echo '<label for="'.$skey.'">'.$slabel.'</label> <br />';
            // Dependiendo del tipo de caja.
            $sclass = "";
            $this->configlavel($svalue,$stype,$isize,$sclass);
            // Depeniendo del tipo pintar el objeto en cuestión
            $etipo = substr($stype,0,8);
            switch ($etipo) {
                case 'timezone':
                    $simput = '<select name="'.$skey.'" '.$srequired.' '.$sreadonly.' '.$sclass.'>';
                    $simput .= '<option value="0">Seleccionar zona horaria</option>';
                    foreach($this->tz_list() as $t) {
                        $simput .='<option value="'.$t['zone'].'"';
                        // Si es el valor actual
                        if ($t['zone'] == $svalue) {
                            $simput .=" SELECTED ";
                        }
                        $simput .='>'.$t['diff_from_GMT'] . ' - ' . $t['zone'];
                        $simput .='</option>';
                    }
                    $simput .='</select>';
                    break;
                case 'monitor':
                case 'usuario':
                case 'grupo':
                case 'grupo_en':
                case 'entidad_':
                    // Cargar los combos de tipo entidad
                    $simput= '<select name = "'.$skey.'">';
                    $simput .= '<option value="'.$svalue.'">Seleccionar '.$slabel.'</option>';
                    $aentidades = $this->comboentidad($stype);
                    foreach($aentidades as $fila) {
                        $simput .='<option value="'.$fila['id'].'"';    
                        if($svalue==$fila['id']) {
                            $simput.= " SELECTED"; 
                        }
                        $simput.= '>'.$fila['pkname'].'</option>';
                    }
                    $simput .='</select>';
                    break;
                case 'textarea':
                    $simput = '<'.$stype.' name="'.$skey.'"';
                    if($breadonly) {
                        $sreadonly = "readonly";
                        $sclass = "";
                    }
                    $simput .= ' rows=4 cols="'.$isize.'" '.$srequired.' '.$sreadonly.' '.$sclass.'>';
                    $simput .= $svalue;
                    $simput .= '</textarea>';
                    break;
                // password coge el resto de propiedades de default, por eso no hace break
                case 'password':
                    // Pasar el decodec y aplicar el default
                    $svalue = base64_decode($svalue);
                    $sclass .= " autocomplete=off ";
                default:
                    $simput = '<input type="'.$stype.'" name="'.$skey.'"';
                    if($breadonly) {
                        $sreadonly = "readonly";
                        $sclass = "";
                    }
                    $simput .= ' value="'.$svalue.'" size="'.$isize.'" maxlength="'.$isize.'" '.$srequired.' '.$sreadonly.' '.$sclass.'" />';
                    break;
            } 
            echo $simput;
            // Si es tipo fecha poner su clase formato jquery
            // Si hay que pintar la busqueda
            echo '</div>  ';
            if($bfind)
            {
                echo ' <input type="submit" class="bfind" name="f'.$skey.'" id="f'.$skey.'" value=""/> ';
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error crear input: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    
    
    public function configlavel(&$svalue,&$stype,&$isize,&$sclass)
    {
        // Permite recotar los parametros
        
        switch ($stype) {
            case 'checkbox':
                if(!is_null($svalue)){
                    $sclass ='checked="checked"';
                }
                break;
            // El formato europeo de php de fechas es - para diferenciar del americano /.
            case 'date':
                $stype = "text";
                if ($svalue <> null)
                {
                    $svalue = date('d-m-Y',$svalue);
                    $isize = 10;
                }
                $sclass =  'class="cdate"';
                break;
            case 'datetime':
                $stype = "text";
                if ($svalue <> null)
                {
                    $svalue = date('d-m-Y H:i:s',$svalue);
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
        // Controlar que el nivel que tiene el usuario es correcto
        if (isset($_SESSION['$gentity']))
        {
            if ($_SESSION['fkentity'] > $_SESSION['$gentity'])
            {
                $_SESSION['textsesion'] = "El usuario con el que se ha logado no tiene permiso para acceder a ese nivel.";
                return -1;
            }  
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
        // botones
        unset($avalues['bsave']);
        unset($avalues['bnew']);
        unset($avalues['bdown']);
        return $avalues;
    }
    // Obtener los datos de una entidad por un valor de busqueda. OP. fkentity
    public function getbysearch($item,$value,$blike=TRUE)
    {
        try {
             $_SESSION['textsesion'] = "";
             $n1ql="";
             // Controla filtro
             if (!empty($item)) {
                 if ($blike) {
                    $n1ql.=" and u.".$item." like '%".$value."%'";
                 }else {
                    $n1ql.=" and u.".$item." = '".$value."'"; 
                 }
             }                
             // Traer filas de entidad
             return $n1ql;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getbysearch: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    // La función convierte la fecha a Nº y realiza la busqueda desde/hasta si esta en post.
    private function getbydate($item)
    {
        try {
            // Controlar el nombre del item
            
            $_SESSION['textsesion'] = "";
            $n1ql="";
            // Filtro desde edición
            if (!empty($_POST[$item->name])) {
                if (strtotime($_POST[$item->name])==FALSE)
                {
                    return $n1ql;
                }
                // Convertir a int
                $n1ql.= " and u.".$item->name." = ".(strtotime($_POST[$item->name]));
            }
            // Controla filtro desde
            if (!empty($_POST['D_'.$item->name])) {
                // Convertir a int
                $n1ql.= " and u.".$item->name." >= ".strtotime($_POST['D_'.$item->name]);
            }
            // Controla filtro hasta. Añadir 1 día
            if (!empty($_POST['H_'.$item->name])) {
                $fecha = new DateTime($_POST['H_'.$item->name]);
                $fecha->add(new DateInterval('P1D'));
                $n1ql.= " and u.".$item->name." < ".$fecha->getTimestamp();
            }              

             // Traer filas de entidad
             return $n1ql;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getbydate: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function itementity($gentity,$itype=0) {
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select u.* from techinventory u where entidad='item_".$this->nclase."' and fkentity ='".$gentity."'";
            // Dependiendo del tipo: 0 Todos, 1 grid, 2 campos de filtro
            switch ($itype) {
               case 1:
                   $n1ql.=" and bgrid=true";
                   break;
               case 2:
                   $n1ql.=" and bfind=true";
                   break;
               default:
                   break;
            }
                
            $n1ql.=" order by ipos";
            return $this->select($n1ql);
            // Añadir 
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función itementity: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function getdocid($pid,$bcheckentity=FALSE) {
        try {
            // Obtener por id
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                $_SESSION['textsesion']='Error en función getdocid: Sin conexión a base de datos.';
                return -1;
            }
            $result = $bucket->get($pid);
            // Tanto la busqueda como por id retornan el valor del id
            $result->value->id = $pid;
            // Controlar si se valida nombre de entidad
            if($bcheckentity) {
                if ($result->value->entidad <> $this->nclase) {
                    return null;
                }
            }
            return $result->value;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getdocid: '.$ex->getMessage();
//            $this->error(); // Al ser una select por id puede ya no exister de momeno no audit.
            return -1;
        }
        
    }
    // Check post auto. Dependiendo. Botones new, update, busquedas.....
    public function postauto($pentity)
    {
        try {
            // Check new
            // $_POST siempre son string reconfigurar a valores correctos
            // Crea el registro de auditoria. 1 Alta, 2 Modif, 3 Baja
            $rfilas = $this->postdatatype($_POST);
          
            if (isset($_POST['bnew']) and $_SESSION['bread']==0) {
                $rfilas = $this->newclass($pentity);
                
                $rfilas = $this->update($rfilas,1); 
                $_SESSION['textsesion'] = "Nueva fila creada.";
                // Retornar array
                return $rfilas;
            }         
            // Check update
            if (isset($_POST['bsave']) and $_SESSION['bread']==0) {
                $rfilas = $this->update($rfilas,2); 
                return $rfilas;
            }
            // Baja
            if (isset($_POST['bdown']) and $_SESSION['bread']==0) {
                // Control de baja
                return $this->delete($rfilas);
            }
            // Check find buttons
            if (!is_null($rfilas)) {
                $rfilas = $this->findbutton($pentity);
                // Retorno
                return $rfilas;
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función postauto: '.$ex->getMessage();
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
            // Si el filtro es de tipo fecha llamar a función de fecha
            $n1ql="select meta(u).id,e.entityname,u.*
            from techinventory u inner join techinventory e
            on keys u.fkentity 
            where u.entidad='".$this->nclase."'"
            . " and u.docid > 0 ";
            // Control de entidad padre
            if (!empty($pentity)) {
                $n1ql.=" and u.fkentity='".$pentity."'";
            }
            $rfilas=$_POST;
            foreach($afilter as $filtro)
            {
                $acol = get_object_vars($filtro);
                // Controlar si existe valor en POST
                if($rfilas[$filtro->name]<>"")
                {
                switch ($filtro->type)
                    {
                        case 'date':
                            $n1ql.=$this->getbydate($filtro);
                            break;
                        default:
                            $n1ql.=$this->getbysearch($acol['name'],$rfilas[$acol['name']]);
                    }
                }
            }
            // Retornar las filas
            return $this->select($n1ql);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función findbutton: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    public function my_arrayclass()
    {
        // Completar con la llamada de las hijas
        $acolclass = array(); 
        // Coger los datetypes de los custom parameters
        $bucket = $this->connbucket();
        if($bucket == -1)
        {
            return -1;
        }
        $n1ql="select name,type from techinventory where entidad='item_".$this->nclase."' and fkentity='".$_SESSION['$gentity']."'";
        $ccustom=$this->select($n1ql);
        // Recorrer el array de clases
        foreach ($ccustom as $cfila) {
            $afila = get_object_vars($cfila);
            array_push($acolclass, array("name" => $afila['name'], "type" => $afila['type'],"default" => null)); 
        }
        return $acolclass;
    }
    // Función que trata post para configurar los tipos de dato correctos.
    public function postdatatype($arow)
    {
        try {
            // Recorrer el post
            //Control datos genericos
            $arow['docid'] = (int)$arow['docid'];
            // Cargar array de campos default de cada clase
            $adefault = $this->my_arrayclass();
            // Recorrer array
            foreach ($adefault as $valor) {               
                switch ($valor['type']) {
                    case 'number':
                        $arow[$valor['name']] = (int)$arow[$valor['name']];
                        break; 
                    case 'date':
                        $arow[$valor['name']] = (int)strtotime($arow[$valor['name']]);
                        break;
                    case 'datetype':
                        $arow[$valor['name']] = (int)strtotime($arow[$valor['name']]);
//                        $arow[$valor['name']] = (int)$arow[$valor['name']];
                        break;
                    case 'checkbox':
                        if(!is_null($arow[$valor['name']])) {
                            $arow[$valor['name']] = (bool)TRUE;
                        }
                        break; 
                    case 'password':
                        $arow[$valor['name']] = (string)base64_encode($arow[$valor['name']]);
                        break;
                    default:
                        $arow[$valor['name']] = (string)$arow[$valor['name']];
                        break;
                }
                if (is_null($arow[$valor['name']])){
                    $arow[$valor['name']] = $valor['default'];
                }
            }

            //Control de auditoria
            $arow['fcreate'] = (int)$arow['fcreate'];
            $arow['fmodif'] = (int)$arow['fmodif'];
            return $arow;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución: '.$ex->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }        
    }
    // Función para mostrar los timezones
    public function tz_list() {
        $zones_array = array();
        $timestamp = time();
        foreach(timezone_identifiers_list() as $key => $zone) {
          date_default_timezone_set($zone);
          $zones_array[$key]['zone'] = $zone;
          $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
    }
    // Retorna combo entidad,grupo,usuario
    public function comboentidad($fkentity)
    {
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select meta(e).id,e.pkname
                   from techinventory e";
            
            // Tipo de combo
            switch ($fkentity){
                case 'usuario':
                    $n1ql.=" where e.entidad='usuario'";
                    // Entidad en uso
                    $n1ql.=" and e.fkentity = '".$_SESSION['$gentity']."'";
                    break;
                case 'grupo':
                    // Entidades globales
                    $n1ql.=" where e.entidad='grupo'";
                    $n1ql.=" and e.fkentity = 'entidad_0'"; 
                    //$n1ql.=" and e.fkentity = '".$_SESSION['$gentity']."'"; // Se Permiten los grupos de todos los niveles
                    // Entidad en uso
                    break;
                case 'grupo_en':
                    $n1ql.=" where e.entidad='grupo'";
                    $n1ql.=" and e.fkentity = '".$_SESSION['$gentity']."'"; // Se Permiten los grupos de todos los niveles
                    // Entidad en uso
                    break;
                case 'monitor':
                    $n1ql.=" where e.entidad='monitor'";
                    $n1ql.=" and e.fkentity = '".$_SESSION['$gentity']."'";
                    // Entidad en uso
                    break;
                default:
                    $n1ql.=" where e.entidad = 'entidad' and e.fkentity='".$fkentity."'";
                    break;
            }   
            $n1ql.=" and e.docid > 0"; // El docid es pkname que es obligatorio y no puede modificarse.
            // Traer filas de entidad
            $cfilas=$this->select($n1ql);
            // Recorrer las clases y meter en array
            $afilas = array();
            foreach($cfilas as $fila) {
                array_push($afilas,get_object_vars($fila));
            }
            return $afilas;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución comboentidad: '.$ex->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
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
            $aid = 'e_'.$this->nclase.'_'.$icount;
            
            $aerr = array("docid" => $icount,
            "entidad" =>'e_'.$this->nclase, 
            "fcreate" => time(),
            "user" => $_SESSION['user'],
            "error" => $_SESSION['textsesion']);
            
            $result = $bucket->upsert($aid,$aerr);
            // Bien
            return 1;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en ejecución: '.$ex->getMessage();
            return -1;
        }
    }
}
