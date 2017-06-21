<?php

/* 
 * Interface application.
 * Standar functions.
 */
interface itech
{
    public function counter($ivalue=1);
    public function connbucket($ivalue=1);
    public function newclass($arow);
    public function insert($arow);
    public function audit($arow);
    public function create($arow);
    public function update($arow);
    public function delete($arow);
    public function error();
}
