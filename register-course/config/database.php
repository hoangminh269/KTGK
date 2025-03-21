<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "test1";
    private static $username = "root";
    private static $password = "";
    private static $conn;

    // Kết nối CSDL
    public static function connect() {
        if (!self::$conn) {
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$dbname);
            if (self::$conn->connect_error) {
                die("Kết nối thất bại: " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>
