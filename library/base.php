<?php
Namespace App;

class base
{
	public function log($arMsg, $arMsgType)  
	{
		//define empty string                                 
		$stEntry = "";
		//get the event occur date time,when it will happened  
		$arLogData['event_datetime'] = '[' . date('D Y-m-d h:i:s A') . '] [client ' . $_SERVER['REMOTE_ADDR'] . '] [' . $arMsgType . ']';  

		//if message is array type  
		if (is_array($arMsg)) {  
			//concatenate msg with datetime  
			foreach($arMsg as $msg)  
				$stEntry .= $arLogData['event_datetime'] . " " . $msg . "\r\n";  
		}  
		else {   //concatenate msg with datetime  
			$stEntry .= $arLogData['event_datetime'] . " " . $arMsg . "\r\n";  
		}  
		//create file with current date name  
		// $stCurLogFileName = 'gtlog' . date('Ymd') . '.log';
		$stCurLogFileName = 'gtlog' . '.log';
		//open the file append mode,dats the log file will create day wise  
		$fHandler = fopen(LOG . $stCurLogFileName, 'a+');  
		//write the info into the file  
		fwrite($fHandler, $stEntry);  
		//close handler  
		fclose($fHandler);
	}

	public function create($objectPath) 
	{
		$anObject = new $objectPath;
        return $anObject;
	}
}
?>