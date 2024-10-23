create database pokedexdatabase
    default character set utf8
    collate utf8_unicode_ci;

use productdatabase;

CREATE TABLE pokemon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    weight FLOAT NOT NULL,
    height FLOAT NOT NULL,
    type ENUM('water', 'ground', 'rock') NOT NULL,
    evolution INT DEFAULT NULL
)engine=innodb default charset=utf8 collate=utf8_unicode_ci;


create user pokemonuser@localhost
    identified by 'pokemonpassword';

grant all
    on pokedexdatabase.*
    to pokemonuser@localhost;

flush privileges;