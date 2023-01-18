<?php
require_once('cfg.php');

function show_page($id) {
    global $conn;
    if (!$id)
        $id = "1";
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if (empty($row['id'])) {
        $web = '<center><h1>Podstrona nie istnieje</h1></center>';
    }
    else {
        $web = $row['page_content'];

    return $web;
}
}
?>