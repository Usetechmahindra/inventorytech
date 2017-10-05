<?php
/* 
 * Group class. Itech interface.
 */
class citem extends cparent
{
    public function my_arrayclass()
    {
        // Retorna el array de campos obligatorios de la clase
        $acolclass = parent::my_arrayclass();
        // Cada clase tendra sus array default.
        array_push($acolclass, array("name" => "ipos", "type" => "number","default" => 0));     
        array_push($acolclass, array("name" => "bfind", "type" => "checkbox","default" => FALSE));
        array_push($acolclass, array("name" => "bgrid", "type" => "checkbox","default" => FALSE));
        array_push($acolclass, array("name" => "brequeried", "type" => "checkbox","default" => FALSE));
        array_push($acolclass, array("name" => "breadonly", "type" => "checkbox","default" => FALSE));
        
        return $acolclass;
    }
    public function columnitem($fkentity)
    {
        try {
            $_SESSION['textsesion'] = "";
            // Se le pasa el nivel de entidad y el tipo de nombre de entidad (entidad,usuario,grupo..).
            $n1ql="select meta(u).id,e.entityname,u.*
                   from techinventory u inner join techinventory e
                   on keys u.fkentity 
                   where u.entidad='".$this->nclase."'"
                    . " and u.docid > 0 ";   // El docid es pkname que es obligatorio y no puede modificarse.
            // Control de entidad padre
            if (!empty($fkentity)) {
                $n1ql.=" and u.fkentity='".$fkentity."'";
            }
            $n1ql.=" order by u.ipos";
            // Traer filas de entidad
            return $this->select($n1ql);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función columnitem: '.$e->getMessage();
            $this->error();
            return -1;
        }
    }
    public function intemexcelnew($fkentity,$pkname)
    {
        try {
            // Leer excel
            $objPHPExcel = new PHPExcel();
            $target_dir = "../upload/excel/";
            // Solapa de lectura y 11 columnas
            $inputFileName = $target_dir.$pkname;
            /**  Identificar tipo de fichero **/
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            /**  Crear el lector del fichero con el tipo identifcado **/
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($inputFileName);
            $objWorksheet =  $objPHPExcel->setActiveSheetIndex(0); 
            // Cargado $objPHPExcel y con solapa 0.
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $acolumn = $sheetData[1];
            // Detalles de filas
            //array_shift($sheetData);
            // Recorrer todas las filas de la hoja
            $ipos = 1;
            foreach($acolumn as $key=>$val){
                // Crear campos por cada fila
                $rfilas = $this->newclass($fkentity);
                // Borrar columna por defecto de newclass
                unset($rfilas['pkname']);
                // La primera fila siempre será pkname
                $rfilas['ipos']=$ipos;
                if($ipos==1) {
                    $rfilas['name'] = 'pkname';
                }else {
                    $rfilas['name'] = $val;
                }
                $rfilas['label'] = $val;
                $rfilas['type'] = "text";
                $rfilas['size'] = 20;
                $rfilas['bproc'] = true;
                // Actualizar fila
                $rfilas = $this->update($rfilas,1); 
                // Retornar array
                $ipos++;
                //echo $val;
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función intemexcelnew: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    public function intemexcelupdate()
    {
        try {
            // Cargar por ID
            $arow=$this->getdocid($_POST['id']);
            // Igualar al post menos el id de formulario
            foreach ($_POST as $key => $value) {
                $arow->$key = $value;
            }
            // Control de procesado nulo
            $arow->bproc = $_POST['bproc'];
            $arow->fmodif=time(); 
            $arow->umodif=$_SESSION['user'];
            // Actualizar
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            $id= $arow->fkentity;
            $arow = $bucket->upsert($arow->id,$arow);
            // Retornar las filas del fichero griddetexcel
            $_POST['id'] = $id;
            
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función intemexcelupdate: '.$ex->getMessage();
            $this->error();
            return -1;  
        }
    }
}