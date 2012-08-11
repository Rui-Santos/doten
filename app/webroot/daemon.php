<?php

define('TRIGGER_URL', 'http://www.doten.co/daemon.php');
define('PROCESS_URL', 'http://www.doten.co/Orders/check/');
define('SUCCESS_FLAG', 'ok');
define('REFRESH_INTERVAL', 3600); // wait 10 minutes

  /* add a mutex here */

  ignore_user_abort(); // run script in background
  set_time_limit(0); // run script forever
  $interval = REFRESH_INTERVAL;
  date_default_timezone_set('PRC');
  
  $i = 0;
  do
  {
    $i++;
	
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, PROCESS_URL . md5(date('H:i:s')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);

	$fp = fopen('./daemon_log.txt', 'a');  
	if(strpos($output, SUCCESS_FLAG) === false)
	fwrite($fp, date('Y-m-d H:i:s') . ' no ' . $output . " \r\n");  
	else
	fwrite($fp, date('Y-m-d H:i:s') . ' ok ' . $output . " \r\n");  
	fclose($fp);  
	
	if(strpos($output, SUCCESS_FLAG) === false)
	{
	  sleep($interval / 10);
      continue;
	}

    sleep($interval);
  }
  while(true);

?>
