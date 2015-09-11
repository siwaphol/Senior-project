<?php
$path_parts = pathinfo('https://www3.reg.cmu.ac.th/regist157/public/stdtotal.php?var=maxregist&COURSENO=204100&SECLEC=001&SECLAB=000&border=1&mime=xls&ctype=&');

echo $path_parts['dirname'], "\n";
echo $path_parts['basename'], "\n";
echo $path_parts['extension'], "\n";
echo $path_parts['filename'], "\n"; // since PHP 5.2.0
?>

