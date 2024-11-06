<?php

namespace Database\Migrations;

use Core\DB;

class GuestMigrations{
    public static function up(){
        $db = DB::getInstance();

        $db->query("
            CREATE TABLE IF NOT EXISTS guests (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                country VARCHAR(255) NOT NULL,
                PRIMARY KEY (id)
            );
        ");

        return true;
    }
}