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
    $sql = "SELECT id, word FROM dictionary";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $url = "http://".$row["word"];
            $img_name = $row["word"].'.jpg';
            $isUrlValid = urlExists($url);
            if($isUrlValid){
                echo $row['id'].':'.$url. PHP_EOL;
                $img_output = get_screenshot($url, $img_name);
                if ($img_output) {
                    $sql = "UPDATE dictionary SET active=1, preview='$img_name' where id=" . $row['id'];
                    $conn->query($sql);
                }
            }else{
                $sql = "UPDATE dictionary SET active=0, preview=NULL where id=".$row['id'];
                $conn->query($sql);
            }

        }
    }

function urlExists($url=NULL)
{
    if($url == NULL) return false;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode>=200 && $httpcode<=301){
        return true;
    } else {
        return false;
    }
}
function get_screenshot($url, $img_name){
    $rn = rand();
    $googlePagespeedData = @file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");
    if($googlePagespeedData) {
        $googlePagespeedData = json_decode($googlePagespeedData, true);
        //screenshot data
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_', '-'), array('/', '+'), $screenshot);
        //display screenshot image
        $data = "data:image/jpeg;base64," . $screenshot;
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        file_put_contents('img/' . $img_name, $data);
        return $googlePagespeedData;
    }
}
$conn->close();
?>
