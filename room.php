<?php
require_once("db.php");

$table = (object)[
    "no" => "Číslo",
    "name" => "Název",
    "phone" => "Telefon",
    "people" => "Lidé",
    "avg_salary" => "Průměrná mzda",
    "keys" => "Klíče"
];



$roomId = filter_input(INPUT_GET, "roomId", FILTER_VALIDATE_INT);

$roomQuery = "select * FROM room WHERE room_id=?";
$keyQuery = "select CONCAT(e.surname, ' ', e.name) 'name', e.employee_id 'id' FROM employee e INNER JOIN `key` k ON e.employee_id = k.employee WHERE k.room=? ORDER BY e.surname";
$peopleQuery = "select CONCAT(e.surname, ' ', e.name) 'name', e.employee_id 'id', e.wage FROM employee e INNER JOIN room ON e.room = room.room_id WHERE room.room_id=? ORDER BY e.surname";

$pdo = DB::connect();

$st = $pdo->prepare($roomQuery);
$st->execute([$roomId]);

$stt = $pdo->prepare($peopleQuery);
$stt->execute([$roomId]);

$sttt = $pdo->prepare($keyQuery);
$sttt->execute([$roomId]);


if (!$roomId || $st->rowCount() == 0){
    http_response_code(404);
    include('404.php'); // provide your own HTML for the error page
    die();
}else {
    $room = $st->fetch();
    $room->people = [];
    $room->avg_salary = 0;
    $room->keys = [];

    while ($row = $stt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->people, $row);
        $room->avg_salary += $row->wage;
    }

    while ($row = $sttt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->keys, $row);

    }

    $room->avg_salary = ($room->avg_salary !== 0) ? ($room->avg_salary / count($room->people)) : "—";
    empty($room->people) ?? "-";
//var_dump(count($room->keys));
//    var_dump($room);
}
?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <title></title>
</head>
<body>
<section class="container">

    <h1>Místnost č. <?= $room->no ?></h1>
    <dl>
        <?php     foreach ($table as $key => $value):?>
            <dt><?= $value ?></dt>
            <?php
            if(is_array($room->$key)){
                foreach ($room->$key as $row){
                    echo "<dd><a href='./person.php?personId=$row->id'>$row->name</a></dd>";
                }
            }else{
                echo "<dd>". $room->$key . "</dd>";
            }
            ?>
        <?php endforeach; ?>

    </dl>
    <a href="./people.php" class="back">Zpět na seznam zaměstnanců</a>
</section>
</body>
</html>
