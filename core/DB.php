<?php 

namespace Core;

use Config\Trait\SingletonTrait;
use Database\Migrations\GuestMigrations;
use PDO;
use PDOException;

class DB
{
    use SingletonTrait;

    private PDO $conn;

    public function connect(): void
    {
        try {
            $this->conn = new PDO("mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_NAME"]}",
                $_ENV["DB_USER"], $_ENV["DB_PASSWORD"],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );

            if($_ENV["DB_MIGRATE"]){
                GuestMigrations::up();
            }

        } catch (PDOException $e) {
            echo exception_page($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param string $name
     */
    public static function table(string $name): DB_Query
    {
        return new DB_Query($name);
    }

    /**
     * @param string $sql
     * @param object|array|string $data
     */
    public function query(string $sql, object|array|string $data = ""): false|\PDOStatement
    {
        $smtp = $this->conn->prepare($sql);
        if (is_array($data) && !empty($data)) {
            $smtp->execute($data);
        } else {
            $smtp->execute();
        }
        return $smtp;
    }
}


class DB_Query
{
    private string $name;
    private string $select = "*";
    private array $where = [
        "transaction" => "",
        "data" => []
    ];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function select(...$data): static
    {
        $this->select = $data ? implode(", ", $data) : "*" ;
        return $this;
    }

    public function where($data, $separator = "AND"): static
    {
        $sql = array_map(function($key){
            return "$key" . " = :$key";
        }, array_keys($data));

        $this->where = [
            "transaction" => " WHERE " . implode(" $separator ", $sql),
            "data" => $data
        ];

        return $this;
    }

    public function get()
    {
        $sql = "SELECT $this->select FROM $this->name" . $this->where["transaction"];

        if(isset($this->where["data"])){
            return DB::getInstance()->query($sql, $this->where["data"])->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return DB::getInstance()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function insert($data): false|\PDOStatement
    {
        $keys = implode(", ", array_keys($data));
        $keys_for_values = implode(", :", array_keys($data));
        $query = "INSERT INTO $this->name ($keys) VALUES (:$keys_for_values)";
        return DB::getInstance()->query($query, $data);
    }

    public function update($data, $id)
    {
        $data["id"] = $id;

        $info = implode(", ", array_map(function($key, $value) {
            return "$key = :$key";
        }, array_keys($data), $data));

        $query = "UPDATE $this->name SET $info WHERE id = :id";

        return DB::getInstance()->query($query, $data);
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->name . $this->where["transaction"];
        return DB::getInstance()->query($query, $this->where["data"]);
    }
}