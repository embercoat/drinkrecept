<?php
$imageid = $_GET['image'];

if(is_file($imageid.".jpg")){
    header("Content-Type: image/jpeg");
    echo file_get_contents($imageid.".jpg");
} else {
    $db = new mysqli();
    $db->connect("localhost", "root", "#Warcraft1", "drinkrecept");
    $sql = "select data from images where iid=".$imageid;
    $image = $db->query($sql);
    if($image->num_rows > 0){
        $image = $image->fetch_assoc();
        header("Content-Type: image/jpeg");
        file_put_contents($imageid.".jpg", $image['data']);
        echo $image['data'];
    } else {
        header("Content-Type: image/png");
        echo file_get_contents('nopic.png');
    }
}

?>