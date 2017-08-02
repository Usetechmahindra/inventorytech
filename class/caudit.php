<?php
/* 
 * Group class. Itech interface.
 */
class caudit extends cparent
{
    public function my_arrayclass()
    {
        // Retorna el array de campos obligatorios de la clase
        // Cada clase tendra sus array default.
        $acolclass = array();
//        array_push($acolclass, array("name" => "ipos", "type" => "number","default" => 0));     
//        array_push($acolclass, array("name" => "bfind", "type" => "bool","default" => 0));
//        ......      
        return $acolclass;
    }
    
    public function getgridaudit($id)
    {
        try {
             $_SESSION['textsesion'] = "";
             $n1ql="select meta(u).id,u.*
                    from techinventory u
                    where u.idaudit='".$id."' order by docid desc";

             // Traer filas de entidad
             return $this->select($n1ql);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getgridaudit: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
    
    public function getauditvalues($idaud)
    {
        try {
             $_SESSION['textsesion'] = "";
             // Obtener el array de auditoria a tratar
             $aaudit = get_object_vars($this->getdocid($idaud));          
             $afilaact = get_object_vars($this->getdocid($aaudit['idaudit']));
             // Recorrer la fila actual sustituyendo
             foreach ($afilaact as $key => $value) {
                 switch($key){
                     case 'entidad':
                     case 'id':
                         break;
                     default:
                         $afilaact[$key] = $aaudit[$key];
                 }
             }  
             // Act. fila actual.
             return $this->update($afilaact,4);
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función getgridaudit: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }   

//    public function insert($arow);
//    public function audit($arow);

//    public function update($arow);
//    public function delete($arow);
}