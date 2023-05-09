<?php
    $request = $_SERVER['REQUEST_URI'];
    $requestclean = strtok($request,"?");
    $uri = explode('/', $requestclean);
    require_once("views/head.php");
    if(!$uri[1]) require_once("views/form.php");
    else if($uri[1] == 'login') require_once("views/login.php");
    else if($uri[1] == 'account') require_once("views/account.php");
    require_once("views/foot.php");
?>
