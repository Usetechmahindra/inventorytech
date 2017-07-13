<?php

/* 
 * Interface application.
 * Standar functions.
 */
interface itech
{
    public function counter($ivalue=1,$vpref='c');
    public function connbucket();
    public function select($ssql);
    public function insert($arow);
    public function audit($arow);
    public function create($arow);
    public function update($arow);
    public function delete($arow);
    public function error($bucket);
}
