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
        array_push($acolclass, array("name" => "bfind", "type" => "bool","default" => 0));
        array_push($acolclass, array("name" => "bgrid", "type" => "bool","default" => 0));
        array_push($acolclass, array("name" => "brequeried", "type" => "bool","default" => 0));
        array_push($acolclass, array("name" => "breadonly", "type" => "bool","default" => 0));
        
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

//    public function insert($arow);
//    public function audit($arow);

//    public function update($arow);
//    public function delete($arow);
}