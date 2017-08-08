<?php
/* 
 * Entity class. Itech interface.
 */
class centity extends cparent
{
     public function my_arrayclass()
    {
        // Retorna el array de campos obligatorios de la clase
        $acolclass = parent::my_arrayclass();
        // Cada clase tendra sus array default.  
        array_push($acolclass, array("name" => "buser", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bgroup", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "bexcel", "type" => "checkbox","default" => NULL));
        array_push($acolclass, array("name" => "timezone", "type" => "text","default" => "Europe/Madrid"));
        array_push($acolclass, array("name" => "color", "type" => "color","default" => "#e31732"));
        array_push($acolclass, array("name" => "colorinvert", "type" => "color","default" => "#636161"));
        array_push($acolclass, array("name" => "logo", "type" => "image","default" => NULL));
        
        return $acolclass;
    }
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}
