<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
$user = $_SESSION['user'];

if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}

try {
    // Conectar a la base de datos
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokedexdatabase',
      'pokemonuser',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: ..');
    exit;
}
if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $url = '.?op=updatepokemon&result=noid';
    header('Location: ' . $url);
    exit;
}


if(isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['weight'])) {
    $price = $_POST['weigth'];
} else {
    header('Location: .');
    exit;
}

if (isset($_POST['height'])) {
    $height = $_POST['height'];
} else {
    header('Location: .');
    exit;
}

if (isset($_POST['type'])) {
    $type = $_POST['type'];
} else {
    header('Location: .');
    exit;
}

$ok = true;
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
if (!is_numeric($weight) || $weight <= 0) {
    $ok = false;
}
if (!is_numeric($height) || $height <= 0) { 
    $ok = false;
}
if (!in_array($type, ['water', 'ground', 'rock'])) { 
    $ok = false;
}

$resultado = 0;

if($ok) {
    $sql = 'UPDATE pokemon SET name = :name, weight = :weight, height = :height, type = :type WHERE id = :id';    $sentence = $connection->prepare($sql);
    $parameters = ['name' => $name, 'weight' => $weight, 'height' => $height, 'type' => $type, 'id' => $id];
    foreach($parameters as $nombreParametro => $valorParametro) {
        $sentence->bindValue($nombreParametro, $valorParametro);
    }
    try {
        $sentence->execute();
        $resultado = $sentence->rowCount();
        $url = '.?op=editpokemon&result=' . $resultado;
    } catch(PDOException $e) {
    }
}

if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['weight'] = $weight; 
    $_SESSION['old']['height'] = $height; 
    $_SESSION['old']['type'] = $type; 
    $url = 'edit.php?op=editproduct&result=' . $resultado . '&id=' . $id;
}
header('Location: ' . $url);