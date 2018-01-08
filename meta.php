#!/bin/php
<?php
echo "Started: ";
echo date('i:s');
echo " | ";
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "searchengine";
$conn = new mysqli($servername, $username, $password, $dbname);
    $word=$word.$domain;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT id, word FROM dictionary where meta_tags IS NULL";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $metas = @get_meta($row["word"]);


            if (count($metas)) {
                $meta = serialize($metas);
                echo $row['id'].':'. $row['word'] . PHP_EOL;
            }

            $sql = "UPDATE dictionary SET meta_tags='$meta' where id=".$row['id'];
            $conn->query($sql);
        }
    }


function get_meta($url){
    $url = 'http://'.$url;
    if (!$url){
        return false;
    }
    return get_meta_tags($url);
}
$conn->close();
?>
