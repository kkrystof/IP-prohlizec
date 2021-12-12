<?php
require_once("db.php");




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
    $room->keys = [];
    $room->avg_salary = 0;

    while ($row = $stt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->people, $row);
        $room->avg_salary += $row->wage;
        // FORMATOVAT ROVNOU TADY - mam tady optom zbytecne nekolik loopu

    }

    while ($row = $sttt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->keys, $row);
        // FORMATOVAT ROVNOU TADY - mam tady optom zbytecne nekolik loopu

    }

    $room->avg_salary = $room->avg_salary / count($room->people);
//var_dump(count($room->keys));
//    var_dump($room);
}
?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Karta místnosti č. <?= $room->no ?></title>
</head>
<body>
<section class="container">

    <h1>Místnost č. <?= $room->no ?></h1>
    <dl>
        <?php     foreach ($room as $key => $value):?>
            <dt><?= $key ?></dt>
            <?php
            if(is_array($value)){
                foreach ($value as $data){
                    echo "<dd><a href='./person.php?personId=$data->id'>$data->name</a></dd>";
                }
            }else{
                echo "<dd>". $value . "</dd>";
            }
            ?>
        <?php endforeach; ?>

    </dl>
    <a href="./people.php" class="back">Zpět na seznam zaměstnanců</a>
    <!--    ◄-->
</section>
</body>
</html>
