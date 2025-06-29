<?php
    $q = mysqli_query($conn, "SELECT COUNT(id) AS Songs FROM songs");

    $result = mysqli_fetch_assoc($q);

    echo $result["Songs"];
?>
