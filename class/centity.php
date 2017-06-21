<?php
/* 
 * Entity class. Itech interface.
 */
class centity extends cparent
{
    public function newclass($arow)
    {
        // Recorre el array identificado y crea/actualiza
        try {

        } catch (Exception $e) {
            $_SESSION['textsesion']='Error en ejecución: '.$e->getMessage();
        }
    }
//    public function insert($arow);
//    public function audit($arow);
//    public function create($arow);
//    public function update($arow);
//    public function delete($arow);
}
