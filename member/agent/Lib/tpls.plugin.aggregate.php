<?php

/*
********************************************************
TinyButStrong plug-in: Aggregate
Version 1.00, on 2006-07-21, by Skrol29
********************************************************
*/

// Name of the class is a keyword used for Plug-In authentication. So i'ts better to save it into a constant.
define('TBS_AGGREGATE','clsTbsAggregate');

$GLOBALS['_TBS_AutoInstallPlugIns'][] = TBS_AGGREGATE;

class clsTbsAggregate {

	function OnInstall() {
		$this->Disabled = true;
		$this->TBS->Aggregate = array();
		return array('OnData','BeforeMergeBlock','AfterMergeBlock');
	}

	function BeforeMergeBlock(&$TplSource,&$BlockBeg,&$BlockEnd,$PrmLst,&$Src,&$LocR) {

		if (!isset($PrmLst['aggregate'])) return;

  	$this->Disabled = false;
		$this->Src =& $Src;
		$this->OpeLst = array();
		$this->OpeNbr = 0;

		$Lst = $PrmLst['aggregate'];
		$Lst = str_replace(chr(10),' ',$Lst);
		$Lst = str_replace(chr(13),' ',$Lst);
		$Lst = explode(',',$Lst);
		foreach ($Lst as $item) {
			
			// Prepare info
			$item = trim($item);
			$p = strpos($item,':');
			if ($p===false) {
				$this->TBS->meth_Misc_Alert('Aggregate plug-in','\''.$item.'\' is an invalide name for a computed column.');
				continue;
			}
			$field = substr($item,0,$p);
			$ope_type = strtolower(substr($item,$p+1));
			if (!in_array($ope_type,array('sum','min','max','avg','count','acc','chg'))) {
				$this->TBS->meth_Misc_Alert('Aggregate plug-in','Type \''.$ope_type.'\' is an invalide type of operation.');
				continue;
			}
			
			// Create object
			$Ope = (object) null;
			$Ope->Type = $ope_type;
			if (($ope_type=='sum') or ($ope_type=='acc')) { 
				$Ope->Value = 0;
			} else {
				$Ope->Value = null;
			}
			$Ope->OrigCol = $field;
			$Ope->Name = $field.':'.$ope_type; // $ope_type is lowercase
			$Ope->Nbr = 0;
			$Ope->Fct = array(&$this,'f_Ope_'.$ope_type);
			
			// Save and clean
			$this->OpeNbr++;
			$this->OpeLst[$this->OpeNbr] =& $Ope;
			unset($Ope);
			
		}

	}

	function OnData($BlockName,&$CurrRec,$RecNum,&$TBS) {

		if ($this->Disabled) return;

		// Calculations
		for ($i=1;$i<=$this->OpeNbr;$i++) {
			$Ope =& $this->OpeLst[$i];
			call_user_func_array($Ope->Fct,array(&$Ope,&$CurrRec));
		}

	}

	function AfterMergeBlock(&$Buffer,&$DataSrc,&$LocR) {

		if ($this->Disabled) return;

		// Save info in last record for fields outside the block
		$LastRec =& $this->Src->CurrRec;
		if (!is_array($LastRec)) $LastRec = array();
		for ($i=1;$i<=$this->OpeNbr;$i++) {
			$Ope =& $this->OpeLst[$i];
			if (($Ope->Type==='avg') and ($Ope->Nbr>0)) $Ope->Value = ($Ope->Value / $Ope->Nbr);
			$LastRec[$Ope->Name] = $Ope->Value;
		}

		// Clear all prepared variables;
		unset($this->Src);
		unset($this->OpeLst);
		$this->Disabled = true;

		// Save data
		$this->TBS->Aggregate = $LastRec;

	}

	function f_Ope_Sum(&$Ope,&$CurrRec) {
		$Ope->Value += $CurrRec[$Ope->OrigCol];
	}

	function f_Ope_Min(&$Ope,&$CurrRec) {
		// Don't use PHP function min(), it has a bad behavior with NULL.
		$x =& $CurrRec[$Ope->OrigCol];
		if (is_null($Ope->Value)) {
			$Ope->Value = $x;
		} elseif (!is_null($x)) {
			if ($x<$Ope->Value) $Ope->Value = $x;
		}
	}

	function f_Ope_Max(&$Ope,&$CurrRec) {
		$Ope->Value = max($Ope->Value,$CurrRec[$Ope->OrigCol]);
	}

	function f_Ope_Avg(&$Ope,&$CurrRec) {
		$x =& $CurrRec[$Ope->OrigCol];
		if (!is_null($x) and ($x!=='')) {
			$Ope->Value += $x;
			$Ope->Nbr++;
		}
	}

	function f_Ope_Count(&$Ope,&$CurrRec) {
		$x =& $CurrRec[$Ope->OrigCol];
		if (!is_null($x) and ($x!=='')) $Ope->Value++;
	}

	function f_Ope_Acc(&$Ope,&$CurrRec) {
		// Same as Sum but same intermediary values
		$Ope->Value += $CurrRec[$Ope->OrigCol];
		$CurrRec[$Ope->Name] = $Ope->Value;
	}

	function f_Ope_Chg(&$Ope,&$CurrRec) {
		$x =& $CurrRec[$Ope->OrigCol];
		if ($Ope->Value==$x) {
			$CurrRec[$Ope->Name] = '';
		} else {
			$CurrRec[$Ope->Name] = $x;
			$Ope->Value = $x;
		}
	}

}



?>