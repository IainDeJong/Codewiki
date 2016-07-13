<?php

class Database
{
    public function __construct()
    {
        if (PDODAO::connect() == true)
        {
        }
        else
        {
                die("NO DATABASE CONNECTION");
        }
    }

//=============================================
    
    public function prepareStatement($sql)
    {
        return PDODAO::prepareStatement($sql);
    }
    
//=============================================
    
    public function doAction($sql, $data)
    {
        return PDODAO::doUpdate($sql, $data);
    }
    
//=============================================
    
    public function executeGetArrays($stmt)
    {
        return PDODAO::getArrays($stmt);
    }
}