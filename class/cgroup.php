<?php
/* 
 * Group class. Itech interface.
 */
class cgroup extends cparent
{
    public function getgroupentity($pentity)
    {
        // Localizar doc tipo usuario
        try {
            $bucket = $this->connbucket();
            if($bucket == -1)
            {
                return -1;
            }
            $n1ql="select meta(g).id,e.entityname,g.*
                    from techinventory g inner join techinventory e
                    on keys g.fkentity
                    where g.fkentity='".$pentity."'
                    and g.entidad='grupo';";

            $query = CouchbaseN1qlQuery::fromString($n1ql);
            // Gets the properties of the given objec
            $result = $bucket->query($query);

            if($result->metrics['resultCount'] == 0)
            {
                $_SESSION['textsesion']="No existen groupos en la entidad.";
            }
            return $result->rows;
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}