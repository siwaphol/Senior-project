<?php
    include('xcrud/xcrud.php');
    $xcrud = Xcrud::get_instance();
    $xcrud->table('Users');


    $xcrud2 = Xcrud::get_instance();
    $xcrud2->table('Programs');

    $xcrud3 = Xcrud::get_instance();
    $xcrud3->table('Roles');

    $xcrud4 = Xcrud::get_instance();
    $xcrud4->table('PROGRAMS_ROLES');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Some page title</title>
</head>
 
<body>
 
<?php
    echo $xcrud->render();
    echo $xcrud2->render();
    echo $xcrud3->render();
    echo $xcrud4->render();
?>
 
</body>
</html>