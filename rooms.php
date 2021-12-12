<?php
require_once("db.php");


$data = [];
$table = (object)[
    "name" => "Název",
    "no" => "Číslo",
    "phone" => "Telefon",
];

$active = (object) [
    "key" => "name",
    "up" => true
];


$query = "SELECT room.*, room.room_id 'id' FROM room ORDER BY room.name";
$order = $_GET["order"] ?? false;

$pdo = DB::connect();
$arr = explode("_", $order);

if($order && count($arr) == 2){

    if(array_key_exists($arr[0], $table) && $arr[1] == "up" || $arr[1] == "down"){
        $active->key = $arr[0];
        $active->up = ($arr[1] === "up") ? true : false;
        $query = "SELECT room.*, room.room_id 'id' FROM room ORDER BY room." . $arr[0] . " " . (($arr[1] === "up") ? "ASC" : "DESC");
    }

}

$st = $pdo->prepare($query);
$st->execute();


if ($st->rowCount() == 0){
    http_response_code(404);
}else {
    while ($row = $st->fetch(PDO::FETCH_OBJ)) {
        array_push($data, $row);
    }
}

?>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <title>Seznam místností</title>
</head>
<body>
<section class="container">

    <h1>Seznam místností</h1>
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($table as $key => $column): ?>
            <th>
                <?= $column ?>
                <a href="./rooms.php?order=<?= $key ?>_up" class="arrow <?= ($active->key == $key & $active->up) ? "active" : "" ?>">🔽</a>
                <a href="./rooms.php?order=<?= $key ?>_down" class="arrow <?= ($active->key == $key & !$active->up) ? "active" : "" ?>">🔼</a>
            </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row): ?>
            <tr>
                <?php foreach ($table as $key => $column): ?>
                    <td><?= ($key === "name") ? "<a href='./room.php?roomId=$row->id'>".$row->$key."</a>" : $row->$key ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</section>
</body>
</html>
