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
            $_SESSION['textsesion'] = "";
            $n1ql="select meta(g).id,e.entityname,g.*
                    from techinventory g inner join techinventory e
                    on keys g.fkentity
                    where g.fkentity='".$pentity."'
                    and g.entidad='grupo';";
            // Llamar a la búsqueda genérica
            return $this->select($n1ql);
        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
    // Obtener grupo por entidad y nombre grupo
    public function getgroupbyname($pentity,$pgroupname) {
        try {
             $_SESSION['textsesion'] = "";
             $n1ql="select meta(g).id,e.entityname,g.*
                    from techinventory g inner join techinventory e
                    on keys g.fkentity 
                    where g.fkentity='".$pentity."'
                    and g.entidad='grupo'
                    and g.groupname like '%$pgroupname%'";
             return $this->select($n1ql);
        } catch (Exception $ex) {
             $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
            // Si no se ha podido conectar al bucket, no se puede grabar el error.
            //echo $_SESSION['textsesion'];
            return -1;
        }
    }
//    public function insert($arow);
//    public function audit($arow);

//    public function update($arow);
//    public function delete($arow);
}