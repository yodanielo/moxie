<?php
class sessionm {
    var $lifeTime;
    var $dbHandle;
    function __construct(){
    	 session_set_save_handler(array(&$this,"open"),
                         array(&$this,"close"),
                         array(&$this,"read"),
                         array(&$this,"write"),
                         array(&$this,"destroy"),
                         array(&$this,"gc"));
     	$this->lifeTime = 21600;
   	    $this->conectar();
    }
    function conectar()
    {
		$dbcon=new MantixOaD();
		$this->dbHandle =$dbcon->con;
    }
    function open($savePath, $sessName) {
        $this->lifeTime = 21600;
		 return true;
    }
    function close() {
        $this->gc(21600);
        return @mysql_close($this->dbHandle);
    }
    function read($sessID) {
    	$res = mysql_query("SELECT session_data AS d FROM mox_sessiones  WHERE session_id = '".$sessID."'  AND session_expires > ".time(),$this->dbHandle);
        if($row = mysql_fetch_array($res))
            return $row['d'];
        return "";
    }
    function write($sessID,$sessData) {
       $newExp = time() + $this->lifeTime;
       $this->conectar();
       $res = mysql_query("SELECT * FROM mox_sessiones  WHERE session_id = '".$sessID."'",$this->dbHandle);
        if(mysql_num_rows($res)) {
            mysql_query("UPDATE mox_sessiones  SET session_expires = '".$newExp."', session_data = '".$sessData."'  WHERE session_id = '".$sessID."'",$this->dbHandle);
            if(mysql_affected_rows($this->dbHandle))
                return true;
        }
        else {
            mysql_query("INSERT INTO mox_sessiones ( session_id, session_expires, session_data)  VALUES( '$sessID', '$newExp', '$sessData')",$this->dbHandle);
            if(mysql_affected_rows($this->dbHandle))
                return true;
         }
    	return false;
    }
    function destroy($sessID) {
        mysql_query("DELETE FROM mox_sessiones WHERE session_id = '$sessID'",$this->dbHandle);
        if(mysql_affected_rows($this->dbHandle))
            return true;
         return false;
    }
    function gc($sessMaxLifeTime) {
        mysql_query("DELETE FROM mox_sessiones WHERE session_expires < ".time(),$this->dbHandle);
        return mysql_affected_rows($this->dbHandle);
    }
}
ini_set('session.gc_maxlifetime',21600);
ini_set('session.gc_probability',1);
ini_set('session.gc_divisor',1);
$sessionma = new sessionm();
session_start();
?>