<?php {

    $formbuilding = (isset($_GET['building']) ? $_GET['building'] : "");

    $formday = (isset($_GET['day']) ? $_GET['day'] : "");

    $formhour = (isset($_GET['hour']) ? $_GET['hour'] : "0");
    $formmin = (isset($_GET['min']) ? $_GET['min'] : "0");
    $formtime = "$formhour:$formmin";

    $formcapacity = (isset($_GET['capacity']) ? (int)$_GET['capacity'] : 0);




} ?>