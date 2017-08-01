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

//    public function insert($arow);
//    public function audit($arow);

//    public function update($arow);
//    public function delete($arow);
}