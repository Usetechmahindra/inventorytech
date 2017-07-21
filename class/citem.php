<?php
/* 
 * Group class. Itech interface.
 */
class citem extends cparent
{
    public function columnitem($pnentidad,$fkentity)
    {
        try {
            $_SESSION['textsesion'] = "";
            // Se le pasa el nivel de entidad y el tipo de nombre de entidad (entidad,usuario,grupo..).
            $n1ql="select meta(u).id,e.entityname,u.*
                   from techinventory u inner join techinventory e
                   on keys u.fkentity 
                   where u.entidad='".$this->nclase."'"
                    . " and u.nentidad ='".$pnentidad."'";
            // Control de entidad padre
            if (!empty($fkentity)) {
                $n1ql.=" and u.fkentity='".$fkentity."'";
            }
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