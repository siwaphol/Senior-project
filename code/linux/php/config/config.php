<?php
  
  $objDOM = new DOMDocument(); 
  $objDOM->load("../config/config.xml"); //make sure path is correct 


  $yearConfig2 = $objDOM->getElementsByTagName("year")->item(0)->nodeValue; 
  
  $semesterConfig2 = $objDOM->getElementsByTagName("semester")->item(0)->nodeValue; 
  
  $rootFolder2 = $objDOM->getElementsByTagName("rootFolder")->item(0)->nodeValue;
  $binFolder = $objDOM->getElementsByTagName("binFolder")->item(0)->nodeValue;

?> 