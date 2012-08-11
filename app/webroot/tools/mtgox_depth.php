<?php
	header( "Content-type: image/png");

    if(!empty($_GET['s']))
      $PRICE_SCALE = $_GET['s'];
    else
      $PRICE_SCALE = 0.1;
    
    if(!empty($_GET['r']))
      $PRICE_RANGE = $_GET['r'];
    else
      $PRICE_RANGE = 10;
    

    include "./libchart/classes/libchart.php";

    $url = "https://mtgox.com/code/data/getDepth.php";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:5.0) Gecko/20100101 Firefox/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    //curl_setopt ($ch, CURLOPT_CAPATH, "/home/doten/app/webroot/");
    //curl_setopt ($ch, CURLOPT_CAINFO, "mtgox.crt");
    // Get the response and close the channel.
    $content = curl_exec($ch);
    //$response = curl_getinfo($ch);

    curl_close($ch);
    
    $len = strlen($content);
    $find = strstr($content, "{");

    $content = substr($find,0);
    $content = json_decode($content, 1);
    
    $asks = $content['asks'];
    $bids = $content['bids'];
     
    $ask_highest_price = $asks[count($asks) - 1][0];
    $bid_lowest_price = $bids[0][0];
    $lowest_price = $bid_lowest_price;
    $highest_price = $ask_highest_price;
    $range = ($ask_highest_price - $bid_lowest_price) / $PRICE_SCALE;
    $middle = ($asks[0][0] - $bid_lowest_price) / $PRICE_SCALE;
    $range = (int)$range;
    $middle = (int)$middle;
    

    $ask_count = 0;
    $ask_group = array(0 => array('price' => 0, 'amount' => 0));
    $bid_count = 0;
    $bid_group = array(0 => array('price' => 0, 'amount' => 0));
    
    for($i = 0; $i < $range + 5; $i++)
    {
      $ask_group[$i]['price'] = $lowest_price + $PRICE_SCALE * $i;
      $ask_group[$i]['amount'] = 0;
      $bid_group[$i]['price'] = $lowest_price + $PRICE_SCALE * $i;
      $bid_group[$i]['amount'] = 0;
    }
    foreach($asks as $ask)
    {
      while($ask[0] > $ask_group[$ask_count + 1]['price'] && $ask_count < $range)
        $ask_count++;
      $ask_group[$ask_count]['amount'] += $ask[1];
    }
    
    foreach($bids as $bid)
    {
      while($bid[0] > $bid_group[$bid_count + 1]['price']&& $bid_count < $range)
        $bid_count++;
      $bid_group[$bid_count]['amount'] += $bid[1];
    }


    $chart = new VerticalBarChart(1100, 600);
    $serie_ask = new XYDataSet();
    $serie_bid = new XYDataSet();
    
    for($i = (int)($middle - $PRICE_RANGE); $i < $middle + $PRICE_RANGE && $i >= 0; $i++)
    {
      if($i % 1)
      {
        $serie_bid -> addPoint(new Point('', $bid_group[$i]['amount']));
        $serie_ask -> addPoint(new Point('', $ask_group[$i]['amount']));
      }
      else
      {
        $serie_bid -> addPoint(new Point((string)$bid_group[$i]['price'], $bid_group[$i]['amount']));
        $serie_ask -> addPoint(new Point((string)$ask_group[$i]['price'], $ask_group[$i]['amount']));
      }
    }


    $dataSet = new XYSeriesDataSet();
    $dataSet->addSerie("Bid", $serie_bid);
    $dataSet->addSerie("Ask", $serie_ask);
    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphCaptionRatio(0.88);

    $chart->setTitle("Mt.Gox Realtime Depth\n Welcome to https://doten.co\n" . "     *SCALE " . $PRICE_SCALE . "\n      " . "*MIDDLE " . $asks[0][0]);
    $chart->render();
	/*
    $image_path= "./mtgox_depth.png"; 
    $sTmpVar   =   fread(fopen($image_path,   'r '),   filesize($image_path)); 

    echo   $sTmpVar ; 
	*/
?>