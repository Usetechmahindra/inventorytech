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
                    $rfilas['ipos'] = -1;
                }else {
                    $rfilas['name'] = strtolower(trim($val));
                }
                $rfilas['label'] = trim($val);
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
                // Control de valores int
                 switch ($key) {
                   case 'ipos':
                   case 'size':
                       $value = (int)$value;
                 }
                $arow->$key = $value;
            }
            // Control de procesado nulo
            
            $arow->bproc = isset($_POST['bproc']);
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
    
    public function syncparm($pkentity)
    {
        try {
            $n1ql="select meta(u).id,u.* from techinventory u where entidad='item_excel' and fkentity ='".$pkentity."'"
            . " and bproc = true and ipos > 0 "  // El primero siempre es pkname y se deja el de por defecto de intem_entidad.
            . " order by ipos,docid";
            $rows=$this->select($n1ql);

            // Total processes
            $total = count($rows);
            // Loop through process
            echo '<p>Sincronizando parámetros de entidad: '.$total.' </p>';
            
            for($i=0; $i<$total; $i++){
                // Calculate the percentation
                $percent = intval(($i+1)/$total * 100)."%";

                // Javascript for updating the progress bar and information
                echo '<script language="javascript">
                document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#3e8e41;\">&nbsp;</div>";
                document.getElementById("information").innerHTML="'.$i.' Fila(s) procesadas.";
                </script>';


            // This is for the buffer achieve the minimum size in order to flush data
                echo str_repeat(' ',1024*64);


            // Send output to browser immediately
                flush();

                $this->updateparmexcel($rows[$i]);
            // Sleep one second so we can see the delay
            //    sleep(1);
            }
            return $total;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función syncparm: '.$ex->getMessage();
            $this->error();
            return -1;  
        }
    }
    // Actualiza / Crea parametro
    public function updateparmexcel($crow)
    {
        try {
            // Cargar el parametro
            $this->nclase = 'item_entidad';
            $findname=$this->getbysearch('name', $crow->name, $_SESSION['$gentity'],FALSE);
            // Tanto si encuentra como no, actualizar el parametro
            $findname = get_object_vars($findname[0]);
            $cols = get_object_vars($crow);
            $findname['bfind']=$findname['bfind'];
            $findname['bgrid']=$findname['bgrid'];
            $findname['breadonly']=$findname['breadonly'];
            $findname['brequeried']=$findname['brequeried'];
            $findname['entidad']=$findname['entidad'];
            $findname['fkentity']=$_SESSION['$gentity'];
            // Datos de item_fileexel
            $findname['ipos'] = $cols['ipos'];
            $findname['label']= $cols['label'];
            $findname['name'] = $cols['name'];
            $findname['size'] = $cols['size'];
            $findname['type'] = $cols['type'];
            // UPDATE
            $this->update($findname, 2);
            
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función updateparmexcel: '.$ex->getMessage();
            $this->error();
            return -1;  
        }
    }
}