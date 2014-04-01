<?php

include('vendors/SplClassLoader.php');

$loader = new SplClassLoader();
$loader->register();

$config = new Config\MainConfig();
$controller = new Parser\Controller($config);
$controller->run();