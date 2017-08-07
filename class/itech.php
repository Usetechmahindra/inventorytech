<?php

/* 
 * Interface application.
 * Standar functions.
 */
interface itech
{
    public function my_arrayclass();
    public function counter($ivalue=1,$vpref='c');
    public function connbucket();
    public function select($ssql);
    public function insert($arow);
    public function audit($id,$arow,$ioper=1);
    public function create($arow);
    public function update($arow,$iop=2);
    public function delete($arow);
    public function getbysearch($item,$value,$fkentity,$blike=TRUE);
    public function itementity($gentity,$itype=0);
    public function getdocid($pid,$bcheckentity=FALSE);
    public function postdatatype($arow);
    public function error();
}
