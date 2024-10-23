<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
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
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}
 
$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

if (isset($_POST['name'], $_POST['weight'], $_POST['height'], $_POST['type'])) {
    $name = $_POST['name'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $type = $_POST['type'];
    $evolution = isset($_POST['evolution']) ? $_POST['evolution'] : null;
    $ok = true;
    $name = trim($name);

    if(strlen($name) < 2 || strlen($name) > 100) {
        $ok = false;
    }
    if (!(is_numeric($weight) && $weight > 0)) {
        $ok = false;
    }
    if (!(is_numeric($height) && $height > 0)) {
        $ok = false;
    }
    if (!in_array($type, ['water', 'ground', 'rock'])) {
        $ok = false;
    }
    if ($evolution !== null && !is_numeric($evolution)) {
        $ok = false;
    }

    if($ok) {
        $sql = 'insert into pokemon (name, weight, height, type, evolution) 
                VALUES (:name, :weight, :height, :type, :evolution)';
        $sentence = $connection->prepare($sql);

        $parameters = [
            'name' => $name,
            'weight' => $weight,
            'height' => $height,
            'type' => $type,
            'evolution' => $evolution
        ];
        foreach($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }

        try {
            $sentence->execute();
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado;
        } catch(PDOException $e) {
        }
    }
}
if ($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['evolution'] = $evolution;
}


header('Location: ' . $url);