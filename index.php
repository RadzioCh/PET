<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

$petController = new \App\Pet\Controller\PetController();
$petController->setGet($_GET);
$petController->setPost($_POST);
echo $petController->fire();

