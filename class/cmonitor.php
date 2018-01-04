<?php
/**
 * Description of cmonitor
 *
 * @author USEWEENERS
 */

class cmonitor extends cparent{
    
    // La función genera con los datos post la gráfica deseada
    public function createchart()
    {
        try {
            // Create the chart - Column 2D Chart with data given in constructor parameter 
            // Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
            // Comprobar que se ha seleccionado 1 monitor. Coger la configuración
            if (empty($_POST['monitor1'])) {
                $_SESSION['textsesion']="Debe de seleccionar un monitor del desplegable";
                return $_SESSION['textsesion'];
            }else {
                $rcfg = $this->cfgmonitor($_POST['monitor1']);
            }         
            // Coger los datos con los filtros seleccionados
            $rows = $this->rowsmonitor($_POST['monitor1'],$rcfg[0]->p->pkname);
            if (count($rows) <= 0) {
                return $_SESSION['textsesion'];
            }
            // Tipo de gráfica
            switch ($_POST['grafica'])
            {
                case 'area':
                    return $this->areachart($rcfg,$rows);
                case 'pie':
                case 'column':
                default:
                    $_SESSION['textsesion']="Sin código para mostrar:".$_POST['grafica'];
                    return $_SESSION['textsesion'];
            }
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función createchart: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    private function rowsmonitor($monitor,$psort)
    {
        try {
            // Se tiene que haber selecionando alguna entidad para ver sus datos
            if(empty($_SESSION['idact'])) {
                $_SESSION['textsesion']="Debe de selecionar alguna entidad para mostrar sus datos.";
                return -1;
            }
            $_SESSION['textsesion'] = "";
            // Se le pasa el nivel de entidad y el tipo de nombre de entidad (entidad,usuario,grupo..).
            $n1ql="select meta(u).id,u.*
                    from techinventory u
                    where u.entidad='".$this->nclase."'
                    and u.fkentity='".$_SESSION['idact']."'
                    and u.fkmonitor='".$monitor."' and u.".$psort." is not null"; 
            // Ordenar serie y luego fecha
            $n1ql.=" order by u.".$psort.",u.fcreate";
            // Filtro de fechas fcreate fechas numéricas
            
            // Retornar filas
            return $this->select($n1ql);
                
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función rowsmonitor: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    private function cfgmonitor($monitor)
    {
        try {
            $_SESSION['textsesion'] = "";
            $n1ql="select *
                from techinventory p inner join techinventory m
                on keys p.fkentity
                where p.entidad='param_monitor'
                and m.entidad='monitor'
                and meta(m).id='".$monitor."'
                order by p.ipos";

            // Filtro de fechas fcreate fechas numéricas

            // Retornar filas
            return $this->select($n1ql);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función cfgmonitor: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    private function areachart($rcfg,$rows)
    {
        try {
            // Con la configuración y las filas pintar en formato area.
            $categoryArray=array();
            $dataseries=array();
          
            $arrData = array(
                "chart" => array(
                    "caption"=> $rcfg[0]->m->descripcion,
                    "xAxisname"=> "Fecha lectura",
                    "yAxisName"=> "(".$rcfg[0]->m->unidad.")",
                    "formatNumberScale"=> "0",
                    "inDecimalSeparator"=> ",",
                    "inThousandSeparator"=> ".",
//                    "numberScaleValue"=> "1024,1024,1024",
//                    "numberScaleUnit"=> " MB, GB, TB",
//                    "defaultNumberScale"=> $rcfg[0]->m->unidad
                    //"numberPrefix"=> $rcfg[0]->m->unidad,
//                    "legendItemFontColor"=> "#666666",
//                    "theme"=> "zune"
                    )
                );
            // Inicializar elemeno dataset
            $arrData["dataset"]=array();
            // Crear categoría unica con todos los valores
            $categoryArray=$this->createcategory($rows,$categoryArray);
            if ( $categoryArray < 0) {
                return -1;
            } 
            $dataseries=$this->createseries($rows,$rcfg);
            if ($dataseries < 0) {
                return -1;
            }
            $arrData["categories"]=array(array("category"=>$categoryArray));
            // Serie final
            foreach($dataseries as $key => $values) {
                $adata = array();
                // En vez de recorrer el array de valores de la serie. Recorrer siempre el array de categorías y pintar hueco o valor
                foreach($categoryArray as $fcreate) {
                    if (array_key_exists($fcreate['label'], $values)) {
                        array_push($adata, array("value" => $values[$fcreate['label']])); 
                    }else {
                        array_push($adata, array("value" => 0)); 
                    }
                }
//                foreach($values as $row) {
//                    array_push($adata, array("value" => $row)); 
//                }
                array_push($arrData["dataset"], array("seriesName"=> $key, "renderAs"=>"area", "data"=>$adata));
            }
            

            
            // creating dataset object
           // array_push($arrData["dataset"], array("seriesName"=> "Usado", "renderAs"=>"area", "data"=>$dataseries2));
            //$arrData["dataset"] = array(array("seriesName"=> "Asignado", "renderAs"=>"area", "data"=>$dataseries1), array("seriesName"=> "Usado",  "renderAs"=>"area", "data"=>$dataseries2),array("seriesName"=> "Profit",  "renderAs"=>"area", "data"=>$dataseries3));


            /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */
            $jsonEncodedData = json_encode($arrData);

            // chart object
            $msChart = new FusionCharts("mscombi2d", "ex1" , "95%", "55%", "dgraf", "json", $jsonEncodedData);

            //$columnChart = new FusionCharts("column2d", "ex1", "95%", "55%", "dgraf", "json", $achar.$adata);
            
            ///// $jsonEncodedData = json_encode($arrData);
            ///// $columnChart = new FusionCharts("stackedarea2d", "Grafica / Hora" , 700, 300, "graf_hora", "json", $jsonEncodedData);
            // Render the chart
            $msChart->render();
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función areachart: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    // Función crea categoría. Se pretende tener una categoría ordenada de todos los valores no repetidos.
    private function createcategory($rows,$categoryArray)
    {
        try {
            // Recorrer $rows
            $categoryArray = array();
            foreach($rows as $fila) {
                $afila = get_object_vars($fila);
                if (in_array( array("label" => $afila['fcreate']), $categoryArray) == false) {
                    array_push($categoryArray, array("label"=> $afila['fcreate']));
                }
            }
            // Ordenar categoría
            array_multisort($categoryArray);
            return $categoryArray;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función createcategory: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    private function createseries($rows,$rcfg)
    {
        try {
            $series = array();
            foreach($rows as $fila) {
                $afila = get_object_vars($fila);
                // Por cada serie 
                $nserie=$rcfg[0]->p->pkname;   
                for($i = 1; $i < count($rcfg); $i++)
                {
                    $col=$rcfg[$i]->p->pkname;
                    $series[$afila[$nserie]."_".$col][$afila['fcreate']]=$afila[$col];
                   // $series[$afila[$nserie]][$afila['fcreate']][$afila[$nserie]."_".$col]=$afila[$col];
                }
            }
            // Retornar el array de series
            return $series;
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función createseries: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
}
