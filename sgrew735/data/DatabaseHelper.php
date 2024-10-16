<?php

class DatabaseHelper
{
    public static function createConnection($values = [])
    {
        try {
            $connString = $values[0];
            $user = isset($values[1]) ? $values[1] : null;
            $password = isset($values[2]) ? $values[2] : null;

            $pdo = new PDO($connString, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            die("Error connecting to the database: " . $e->getMessage());
        }
    }

    public static function runQuery($connection, $sql, $parameters = [])
    {
        try {
            $statement = null;

            if (isset($parameters) && is_array($parameters) && !empty($parameters)) {
                $statement = $connection->prepare($sql);
                $statement->execute($parameters);
            } else {
                $statement = $connection->query($sql);
            }

            return $statement;
        } catch (PDOException $e) {
            die("Query error: " . $e->getMessage());
        }
    }

    public static function fetchAll($connection, $sql, $parameters = [])
    {
        $statement = self::runQuery($connection, $sql, $parameters);
        return $statement->fetchAll();
    }

    public static function fetchOne($connection, $sql, $parameters = [])
    {
        $statement = self::runQuery($connection, $sql, $parameters);
        return $statement->fetch();
    }
}
