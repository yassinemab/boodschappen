<?php
    function getBrandById($id, $conn) {
        $query = "SELECT * FROM brands WHERE id = $id";
        $result = $conn->query($query);
        if($result->num_rows == 0) {
            return -1;
        }
        return $result->fetch_assoc();
    }

    function getBrandByName($name, $conn) {
        $name = strtolower($conn->real_escape_string($name));
        $query = "SELECT * FROM brands WHERE name = $name";
        $result = $conn->query($query);
        if($result->num_rows == 0) {
            return -1;
        }
        return $result->fetch_assoc();
    }
?>