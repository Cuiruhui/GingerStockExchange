<?php

// TbsSql Engine
// Version 2.0, 2007-03-01, Skrol29
class MYSQL {
	var $xltm;
	function MYSQL($srv='',$uid='',$pwd='',$db='',$drv='',&$xltm) {
		$this->xltm = &$xltm;
		$this->Id = false;
		$this->Mode = 0; // 0=silent, 1=normal, 2=debug, 3=trace
		// Try to connect when instance is created
		if ($srv!=='') $this->Connect($srv,$uid,$pwd,$db,$drv);
		$this->_Dbs_Prepare();
		// Hook for the TinyButStrong Template Engine
		$GLOBALS['_TBS_UserFctLst']['k:tbssql'] = array('type'=>4,'open'=>array(&$this,'_Dbs_RsOpen'),'fetch'=>array(&$this,'_Dbs_RsFetch'),'close'=>array(&$this,'_Dbs_RsClose'));
	}

	// Public methods

	function Connect($srv,$uid='',$pwd='',$db='',$drv='') {
		$auto = (($srv!='') and ($uid.$pwd.$db.$drv==''));
		if ($auto) {
			// Connection by a global variable
			$var = $srv;
			if (!isset($GLOBALS[$var])) return $this->_Message('Automatic Connection: global variable \''.$var.'\' is not found.');
			if (!$this->_TakeVar($var,'srv',$srv)) return false;
			if (!$this->_TakeVar($var,'uid',$uid)) return false;
			if (!$this->_TakeVar($var,'pwd',$pwd)) return false;
			if (!$this->_TakeVar($var,'db' ,$db)) return false;
			$this->_TakeVar($var,'drv',$drv,false);
			unset($GLOBALS[$var]);
		}
		$this->_Dbs_Connect($srv,$uid,$pwd,$db,$drv);
		if (is_null($this->Id)) $this->Id = false;
		if ($this->Id===false) return $this->_SqlError(false);
		return true;
	}

	function Close() {
		if ($this->Id!==false) $this->_Dbs_Close();
	}

	function Execute($Sql) {
		$ArgLst = func_get_args();
		$Sql = $this->_SqlProtect($ArgLst);
		$RsId = $this->_Dbs_RsOpen(null,$Sql);
		if ($RsId===false) return $this->_SqlError($this->Id);
		$this->_Dbs_RsClose($RsId);
		return true;
	}

	function GetVal($Sql) {
		$ArgLst = func_get_args();
		$Sql = $this->_SqlProtect($ArgLst);
		$RsId = $this->_Dbs_RsOpen(null,$Sql);
		if ($RsId===false) return $this->_SqlError($this->Id);
		$x = false;
		$Row = $this->_Dbs_RsFetch($RsId);
		if ($Row!==false) $x = reset($Row);
		$this->_Dbs_RsClose($RsId);
		return $x;
	}

	function GetRow($Sql) {
		$ArgLst = func_get_args();
		$Sql = $this->_SqlProtect($ArgLst);
		$RsId = $this->_Dbs_RsOpen(null,$Sql);
		if ($RsId===false) return $this->_SqlError($this->Id);
		$x = $this->_Dbs_RsFetch($RsId);
		$this->_Dbs_RsClose($RsId);
		return $x;
	}

	function GetRows($Sql) {
		$ArgLst = func_get_args();
		$Sql = $this->_SqlProtect($ArgLst);
		$RsId = $this->_Dbs_RsOpen(null,$Sql);
		if ($RsId===false) return $this->_SqlError($this->Id);
		$x = array();
		while ($r = $this->_Dbs_RsFetch($RsId)) {
			$x[] = $r;
		}
		$this->_Dbs_RsClose($RsId);
		return $x;
	}

	function GetList($Sql) {
		$ArgLst = func_get_args();
		$Sql = $this->_SqlProtect($ArgLst);
		$RsId = $this->_Dbs_RsOpen(null,$Sql);
		if ($RsId===false) return $this->_SqlError($this->Id);
		$x = array();
		$first = true;
		while ($r = $this->_Dbs_RsFetch($RsId)) {
			if ($first) {
				$cols = array_keys($r);
				$col1 = $cols[0];
				$col2 = isset($cols[1]) ? $cols[1] : false;
				unset($cols);
				$first = false;
			}
			if ($col2===false) {
				$x[] = $r[$col1];
			} else {
				$x[$r[$col1]] = $r[$col2];
			}
		}
		$this->_Dbs_RsClose($RsId);
		return $x;
	}

	function GetSql($Sql) {
		$ArgLst = func_get_args();
		return $this->_SqlProtect($ArgLst);
	}

	function AffectedRows() {
		return $this->_Dbs_AffectedRows();
	}

	function LastRowId() {
		return $this->_Dbs_LastRowId();
	}

	// Private methods

	function _SqlError($ObjId) {
		if ($this->Mode==0) return;
		$x =  'Database error message: '.$this->_Dbs_Error($ObjId);
		if ($this->Mode==2) $x .= "\r\nSQL = ".$this->LastSql;
		$this->_Message($x);
		return false;
	}

	function _Message($Txt,$Color='#FF0000') {
		if ($this->Mode!=0) {
			echo '<div style="color: '.$Color.';">[查询] '.nl2br(htmlentities($Txt)).'</div>'."\r\n";
			flush();
		}
		return false;
	}

	function _TakeVar($VarName,$Key,&$Target,$MustBe=true) {
		if (isset($GLOBALS[$VarName][$Key])) {
			$Target = $GLOBALS[$VarName][$Key];
		} elseif ($MustBe) {
			$this->_Message('Automatic Connection: item \''.$Key.'\' is not found in the global variable \''.$VarName.'\'.');
			return false;
		}
		return true;
	}

	function _SqlDate($Date,$Mode) {
		// Return the date formated for the current Database
		if (is_string($Date)) {
			$x = strtotime($Date);
			if (($x===-1) or ($x===false)) {
				// display error message
				$this->_Message('Date value not recognized: '.$Date);
				$Mode = 0; // Try with the string mode
				$x = $Date;
			}
		} else {
			$x = $Date;
		}
		return $this->_Dbs_Date($x,$Mode);
	}

	function _SqlProtect($ArgLst,$Normal=true) {
		// Replace all %i% and @i@ figures by corresponding protected values
		$Sql = $ArgLst[0];
		$IdxMax = count($ArgLst) - 1;
		for ($i=1;$i<=$IdxMax;$i++) {
			// Simple value
			$tag = '%'.$i.'%';
			if (strpos($Sql,$tag)!==false) {
				$x = $this->_Dbs_ProtectStr(''.$ArgLst[$i]);
				$Sql = str_replace($tag,$x,$Sql) ;
			}
			// String value
			$tag = '@'.$i.'@';
			if (strpos($Sql,$tag)!==false) {
				$x = '\''.$this->_Dbs_ProtectStr(''.$ArgLst[$i]).'\'';
				$Sql = str_replace($tag,$x,$Sql) ;
			}
			// Date value
			$tag = '#'.$i.'#';
			if (strpos($Sql,$tag)!==false) {
				$x = $this->_SqlDate($ArgLst[$i],1);
				$Sql = str_replace($tag,$x,$Sql) ;
			}
			// Date and time value
			$tag = '~'.$i.'~';
			if (strpos($Sql,$tag)!==false) {
				$x = $this->_SqlDate($ArgLst[$i],2);
				$Sql = str_replace($tag,$x,$Sql) ;
			}
		}
		if ($Normal) {
			if ($this->Mode==2) {
				$this->LastSql[] = $Sql;
			} elseif ($this->Mode==3) {
				$this->_Message('Trace SQL: '.$Sql,'#663399');
			}
		}
		return $Sql;
	}

	// Deprecated

	function Value($DefVal,$Sql) {
		$ArgLst = func_get_args();
		array_shift($ArgLst);
		$Sql = $this->_SqlProtect($ArgLst,false);
		$x = $this->GetVal($Sql);
		if ($x===false) $x = $DefVal;
		return $x;
	}

	function Row1($Sql) {
		$Sql = $this->_SqlProtect(func_get_args(),false);
		return $this->GetRow($Sql);
	}

	function Rows($Sql) {
		$Sql = $this->_SqlProtect(func_get_args(),false);
		return $this->GetRows($Sql);
	}

	// -------------------------------
	// Specific to the Database System
	// -------------------------------

	// Database Engine: MySQL
	// Version 1.02, 2006-10-12, Skrol29

	function _Dbs_Prepare() {
		if (@mysql_ping()) { // Check if a MySQL connection already exist
			$this->Id = true;
		}
	}

	function _Dbs_Connect($srv,$uid,$pwd,$db,$drv) {
		// Sould set $this->Id, value false means connection failed.
		$Id = @mysql_connect($srv,$uid,$pwd);
		mysql_query("SET NAMES 'utf8'",$Id);
		if (($Id!==false) and ($db!=='')) {
			if (!@mysql_select_db($db)) $this->_SqlError(false);
		}
		$this->Id = $Id;
	}

	function _Dbs_Close() {
		if (is_resource($this->Id)) {
			return @mysql_close($this->Id);
		} else {
			return @mysql_close();
		}
	}

	function _Dbs_Error($ObjId) {
		if (is_resource($this->Id)) {
			return @mysql_error($ObjId);
		} else {
			return @mysql_error();
		}
	}

	function _Dbs_RsOpen($Src,$Sql) {
		// $Src is only for compatibility with TinyButStrong
		if (is_resource($this->Id)) {
			return @mysql_query($Sql,$this->Id);
		} else {
			return @mysql_query($Sql);
		}
	}

	function _Dbs_RsFetch(&$RsId) {
		return mysql_fetch_assoc($RsId);
	}

	function _Dbs_RsClose(&$RsId) {
		if ($RsId===true)  return true;
		return @mysql_free_result($RsId);
	}

	function _Dbs_ProtectStr($Txt) {
		return mysql_escape_string($Txt);
	}

	function _Dbs_Date($Timestamp,$Mode) {
		switch ($Mode) {
			case 1:
				// Date only
				return '\''.date('Y-m-d',$Timestamp).'\'';
			case 2:
				// Date and time
				return '\''.date('Y-m-d H:i:s',$Timestamp).'\'';
			case 0:
				// Value is a string
				return '\''.$this->_Dbs_ProtectStr($Timestamp).'\'';
			default:
				// Error in date recognization
				return '\'0000-00-00\'';
		}
	}

	function _Dbs_LastRowId() {
		return $this->GetVal('SELECT LAST_INSERT_ID()');
	}

	function _Dbs_AffectedRows() {
		if (is_resource($this->Id)) {
			return mysql_affected_rows($this->Id);
		} else {
			return mysql_affected_rows();
		}
	}

}



?>
