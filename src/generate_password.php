<?php
require_once 'utils/patterns/builder/PasswordBuilder.php';
require_once 'utils/patterns/builder/PasswordDirector.php';

header('Content-Type: application/json');

$length = isset($_POST['length']) ? (int)$_POST['length'] : 12;
$useUpper = isset($_POST['uppercase']);
$useLower = isset($_POST['lowercase']);
$useNumbers = isset($_POST['numbers']);
$useSymbols = isset($_POST['symbols']);

$builder = new PasswordBuilder();
$director = new PasswordDirector($builder);
$password = $director->build($length, $useUpper, $useLower, $useNumbers, $useSymbols);

echo json_encode(['password' => $password]);
