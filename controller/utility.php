<?php
	function exception_error_handler($severity, $message, $file, $line) {
	    if (!(error_reporting() & $severity)) {
	        // This error code is not included in error_reporting
	        return;
	    }
	    throw new Exception($message."\n".$severity."\n".$file."\n".$line);
	}
	set_error_handler("exception_error_handler");
