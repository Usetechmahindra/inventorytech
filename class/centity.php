<?php
/* 
 * Entity class. Itech interface.
 */
class centity implements itech
{
    public function newclass($arow);
    public function insert($arow);
    public function counter();
    public function audit($arow);
    public function create($arow);
    public function update($arow);
    public function delete($arow);
}
?>
