<?php
require_once 'Database.php';

$db = new Database();



if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if(isset($_GET['limit'])){
    echo(json_encode($db->showData($page, $_GET['limit']),JSON_UNESCAPED_UNICODE));
}
