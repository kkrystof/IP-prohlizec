<?php
require_once("db.php");

$table = (object)[
    "name" => "Jméno",
    "surname" => "Příjmení",
    "job" => "Pozice",
    "wage" => "Mzda",
    "room" => "Místost",
    "keys" => "Klíče"
];

$personId = filter_input(INPUT_GET, "personId", FILTER_VALIDATE_INT);

$emplQuery = "select e.*, room.name 'room' from employee e INNER JOIN room ON e.room = room.room_id WHERE e.employee_id=?;";
$keyQuery = "select room.room_id, room.name FROM `key` INNER JOIN room ON room.room_id = key.room WHERE key.employee=? ORDER BY room.name";

$pdo = DB::connect();

$st = $pdo->prepare($emplQuery);
$st->execute([$personId]);

$stt = $pdo->prepare($keyQuery);
$stt->execute([$personId]);


if (!$personId || $st->rowCount() == 0){
    http_response_code(404);
    include('404.php');
    die();
}else {
    $room = $st->fetch();
    $room->keys = [];

    while ($row = $stt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->keys, $row);

    }

}
?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <title>Karta osoby <?= "$room->surname {$room->name[0]}." ?></title>
</head>
<body>
<section class="container">

<h1>Karta osoby - <?= "$room->surname {$room->name[0]}." ?></h1>
<dl>
    <?php     foreach ($table as $key => $value):?>
    <dt><?= $value ?></dt>
        <?php
            if(is_array($room->$key)){
                foreach ($room->$key as $data){
                    echo "<dd><a href='./room.php?roomId=$data->room_id'>$data->name</a></dd>";
                }
            }else{
                echo "<dd>". $room->$key. "</dd>";
            }
        ?>
    <?php endforeach; ?>

</dl>
<a href="./people.php" class="back">Zpět na seznam zaměstnanců</a>
</section>
</body>
</html>
