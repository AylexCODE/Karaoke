<?php
    $q = $conn->prepare("SELECT COUNT(title) AS Songs FROM songs");

    $q->execute();

    $result = $q->fetch(PDO::FETCH_ASSOC);

    echo $result["Songs"];
?>