<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
$user = $_SESSION['user'];

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
    header('Location:..');
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}



$sql = 'select * from pokemon where id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

try {
    $sentence->execute();
    $row = $sentence->fetch();
} catch(PDOException $e) {
    header('Location:.');
    exit;
}

if($row == null) {
    header('Location: .');
    exit;
}


$name = '';
$weight = '';
$height = '';
$type = '';
$evolution = '';

if(isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if(isset($_SESSION['old']['weight'])) {
    $weight = $_SESSION['old']['weight'];
    unset($_SESSION['old']['weight']);
}
if(isset($_SESSION['old']['height'])) {
    $height = $_SESSION['old']['height'];
    unset($_SESSION['old']['height']);
}
if(isset($_SESSION['old']['type'])) {
    $type = $_SESSION['old']['type'];
    unset($_SESSION['old']['type']);
}
if(isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}

$id = $row['id'];
if($name == '') {
    $name = $row['name'];
}
if($weight == '') {
    $weight = $row['weight'];
}
if($height == '') {
    $height = $row['height'];
}
if($type == '') {
    $type = $row['type'];
}
if($evolution == 'evolution') {
    $evolution = $row['evolution'];
}

$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokedex</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">Pokedex</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Editar Pokemon</h4>
                </div>
            </div>
            <div class="container">
            <?php
                if(isset($_GET['op']) && isset($_GET['result'])) {
                    if($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                        }
                }
                ?>
                <div>
                    <form action="update.php" method="post">
                        <div class="form-group">
                            <label for="name">Pokémon Name</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="product name">
                        </div>
                        <div class="form-group">
                            <label for="price">Weight (kg)</label>
                            <input value="<?= $weight ?>" required type="number" step="0.001" class="form-control" id="price" name="price" placeholder="product price">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select required class="form-control" id="type" name="type">
                                <option value="">Select Type</option>
                                <option value="water" <?= $type == 'water' ? 'selected' : '' ?>>Water</option>
                                <option value="ground" <?= $type == 'ground' ? 'selected' : '' ?>>Ground</option>
                                <option value="rock" <?= $type == 'rock' ? 'selected' : '' ?>>Rock</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="evolution">Evolution ID (Optional)</label>
                            <input value="<?= $evolution ?>" type="number" class="form-control" id="evolution" name="evolution" placeholder="Evolution ID">
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <button type="submit" class="btn btn-primary">edit</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; Pokedex 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>