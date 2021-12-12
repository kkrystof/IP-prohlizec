<?php
require_once("db.php");




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
    include('404.php'); // provide your own HTML for the error page
    die();
}else {
    $room = $st->fetch();
    $room->keys = [];

    while ($row = $stt->fetch(PDO::FETCH_OBJ)) {
        array_push($room->keys, $row);
        // FORMATOVAT ROVNOU TADY - mam tady optom zbytecne nekolik loopu

    }

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
    <title>Document</title>
</head>
<body>
<section class="container">

<h1>Karta osoby - <?= "$room->surname {$room->name[0]}." ?></h1>
<dl>
    <?php     foreach ($room as $key => $value):?>
    <dt><?= $key ?></dt>
        <?php
            if(is_array($value)){
                foreach ($value as $data){
                    echo "<dd><a href='./room?roomId=$data->room_id'>$data->name</a></dd>";
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
