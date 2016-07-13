<?php
################################################################################
# Author	: M@nKind - Geert Weggemans 
# Date 		: 12-01-2012
# Desc		: Base Data Acces Object
################################################################################

// basic PDO DATA ACCESS OBJECT class. best practice is to require once in index.php

abstract class PDODAO
{
    public 	static $tableprefix = "";
    public 	static $m_db = "";
    protected 	static $db;
//==============================================================================
    public static function createDB()
    {
        try
        {
            //Debug::_echo("CONNECTING TO DATABASE SERVER".PDOhost);
            self::$db = new PDO(PDOdriver.":host=".PDOhost,PDOuser,PDOpass);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Debug::_echo("CONNECTED TO ".PDOhost);
            self::exec("CREATE DATABASE `".PDOdatabase."`;");
            //Debug::_echo("CONNECTED TO CREATE DATABASE".PDOdatabase);
        }    
        catch(PDOException $e)
        {
            //Debug::_error($e);
        }
    }        
    
    public static function connect($tableprefix="")
    {
        try
        {
            self::$tableprefix = $tableprefix;
            self::$db = new PDO(PDOdriver.":host=".PDOhost.";dbname=".PDOdatabase,PDOuser,PDOpass);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$m_db = PDOdatabase;
            return true;
        }
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }
//==============================================================================	
    public static function isPDO()
    {
        return true;
    }
//==============================================================================	
    public static function connected()
    {
        return (self::$m_db != "");
    }
//==============================================================================	
    public static function getDB($dbid=1)
    {
        return self::$m_db;
    }
//==============================================================================	
    public static function prepareStatement($sql)
    {
        try
        {
            $stmt = self::$db->prepare($sql);
            return $stmt;						
	}	
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }
//==============================================================================	
    public static function getAssoc($stmt)
    {
	return self::fetchAll($stmt, PDO::FETCH_ASSOC);
    }	
//==============================================================================	
    public static function getSet($stmt)
    {
	return self::fetchAll($stmt, PDO::FETCH_OBJ);
    }				
//==============================================================================	
    public static function getArrays($stmt)
    {
	return self::fetchAll($stmt, PDO::FETCH_BOTH);
    }				 
//==============================================================================	
    public static function  getArray($stmt)
    {
	return self::fetchOne($stmt, PDO::FETCH_BOTH);
    }				 
//==============================================================================	
    public static function getObject($stmt)
    {
	return self::fetchOne($stmt, PDO::FETCH_OBJ);
    }	
//==============================================================================	
    public static function beginTransaction()
    {
        self::$db->beginTransaction();
    }
//==============================================================================	
    public static function rollBack()
    {
        self::$db->rollBack();
    }
//==============================================================================	
    public static function commit()
    {
        self::$db->commit();
    }
//==============================================================================	
    public static function execute($sql)
    {
        try
        {
            self::$db->exec($sql);
            return true;
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }	
//==============================================================================	
    public static function quote($string)
    {
        return str_replace("'","''",$string);	
    }
//==============================================================================	
    public static function unquote($string)
    {
        return str_replace("''","'",$string);	
    }
//==============================================================================	
    public static function doDeleteQuery($sql)
    {
        try
        {
            $affected_rows = self::$db->exec($sql);
            return $affected_rows;
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }
//==============================================================================	
    public static function doUpdateQuery($sql)
    {
        try
        {
            $affected_rows = self::$db->exec($sql);
            return $affected_rows;
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }	
//==============================================================================	
    public static function doUpdate($sql, $data)
    {
        try
        {
            $stmt = self::$db->prepare($sql);
            $stmt->execute($data);
            return true;
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }	
//==============================================================================	
    public static function doInsertQuery($sql)
    {
        try
        {
            $results = self::$db->exec($sql);
            return self::$db->lastInsertId();
        }
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }	
	
//==============================================================================

	public static function lastId()
    {
         return self::$db->lastInsertId();
    }
	
//==============================================================================	
    public static function doInsert($sql, $data)
    {
        try
        {
            $stmt = self::$db->prepare($sql);
            $stmt->execute($data);
            return self::$db->lastInsertId();
        }
        catch(PDOException $e)
        {
            self::logError($e);
            return false;
        }
    }	
//==============================================================================
    public static function getNamesString($table, $idstr)
    {
        $ret = "";
        $data = self::$db->query("SELECT name FROM ".$table." WHERE ID in (".$idstr.")");
        foreach($data as $d)
        {
            if ($ret)$ret.=", ";
            $ret.= $d->name;
         }
        return $ret;
    }
//==============================================================================
    public static function getDataObject($qry)
    {
        try
        {
            $stmt = self::$db->prepare($qry);
            return self::getObject($stmt);
        }    
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    public static function getDataArray($qry)
    {
        try
        {
            $stmt = self::$db->prepare($qry);
            return self::getArray($stmt);
        }    
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    public static function getDataArrays($qry)
    {
        try
        {
            $stmt = self::$db->prepare($qry);
            return self::getArrays($stmt);
        }    
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    public static function getDataSet($qry)
    {
        try
        {
            $stmt = self::$db->prepare($qry);
            return self::getSet($stmt);
        }    
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    public static function getDataAssoc($qry)
    {
        try
        {
            $stmt = self::$db->prepare($qry);
            return self::getAssoc($stmt);
        }    
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    public static function getSingleValFromQuery($qry, $default=-1)	
    {
        $ret = $default;
        try
        {
            $stmt = self::$db->prepare($qry);
            $stmt->execute();
            $valrec = $stmt->fetch(PDO::FETCH_NUM);
            if ($valrec && isset($valrec[0])) 	
            {
                    $ret = $valrec[0];
            }
        }
        catch(PDOException $e)
        {
            self::logError($e);
        }	
        return $ret;
    }
//==============================================================================
    public static function getAutoCompleteIDValuePairs($table, $idfield, $valfield, $wheretest)
    {
        return self::getDataSet("SELECT ".$idfield.", ".$valfield." FROM ".$table." WHERE ".$wheretest."' ORDER BY ".$valfield." ASC");
    }
//==============================================================================	
    public function createVarValueByfunction($valuefunction)
    {
        $value = $valuefunction;
        if ($valuefunction) 
        switch ($valuefunction)
        {
            case "[*NOWDATE*]" : 	$value = DateUtils::FormatDate(DateUtils::DF_YMD); break;
            case "[*NOWWEEK*]" : 	$value = DateUtils::FormatDate(DateUtils::DF_WEEKYEAR);	break; //getNowDate();		
            case "[*NOWMONTH*]" : 	$value = DateUtils::FormatDate(DateUtils::DF_WEEKYEAR);	break;
            case "[*NOWYEAR*]" : 	$value = DateUtils::FormatDate(DateUtils::DF_YEAR);		break;
            case "[*NOWDATETIME*]" : 	$value = DateUtils::FormatDate(DateUtils::DF_DATETIME);	break;
            case "[*NOWTIMESTAMP*]" : 	$value = Utils::getNowTimeStamp();	break;
            case "[*NEWPASS*]" : 	$value = Utils::generatePassWord();	break;
            default : 
                //Debug::_echo("DAO Unknown valuefunction : [".$valuefunction."]");
                break;
        }		
        return $value;
    }
//==============================================================================	
    public static function replaceVars($vResult)
    {
        return str_replace("[*NOWYEAR*]", DateUtils::FormatDate(DateUtils::DF_YEAR), $vResult); 
    }	
//==============================================================================	
    public static function getSetting($settingname,$default=0)
    {
        return self::getSingleValFromQuery("SELECT val FROM ".self::$tableprefix."settings WHERE name='".$settingname."' LIMIT 1", $default);
    }
//==============================================================================	
    public static function realEscapeString($str)
    {
        return $str;
    }
//==============================================================================	
// PROTECTED PARTS
//==============================================================================	
    protected static function logError($e)
    {
        //Debug::_echo('Database Error: '.$e->getCode());
        //Debug::_echo('Regelnummer: '.$e->getLine());
        //Debug::_echo('Bestand: '.$e->getFile());
        //Debug::_echo('Foutmelding: '.$e->getMessage());
    }
//==============================================================================
    protected static function fetchOne($stmt, $fetchtype)
    {
        try
        {
            $stmt->execute();
            return $stmt->fetch($fetchtype);
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
//==============================================================================
    protected static function fetchAll($stmt, $fetchtype)
    {
        try
        {
            $result = $stmt->execute(); //i did this
            return $stmt->fetchAll($fetchtype);
        }	
        catch(PDOException $e)
        {
            self::logError($e);
            return null;
        }
    }	
}
