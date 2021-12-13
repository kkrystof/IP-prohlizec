<?php
require_once("db.php");

$data = [];
$table = (object)[
    "name" => "Jméno",
    "room" => "Místnost",
    "phone" => "Telefon",
    "job" => "Pozice"
];

$active = (object) [
    "key" => "name",
    "up" => true
];




$query = "SELECT e.employee_id 'id', CONCAT(e.surname, ' ', e.name) 'name', room.name 'room', room.phone, e.job FROM employee e INNER JOIN room ON e.room = room.room_id ORDER BY e.surname";

$order = $_GET["order"] ?? false;

$pdo = DB::connect();
$arr = explode("_", $order);

if($order && count($arr) == 2){

    if(array_key_exists($arr[0], $table) && $arr[1] == "up" || $arr[1] == "down"){
        $active->key = $arr[0];
        $active->up = ($arr[1] === "up") ? true : false;
        $query = "SELECT e.employee_id 'id', CONCAT(e.surname, ' ', e.name) 'name', room.name 'room', room.phone, e.job FROM employee e INNER JOIN room ON e.room = room.room_id ORDER BY" . ($arr[0] == 'phone') ? 'room.' : 'e.' . $arr[0] . " " . (($arr[1] === "up") ? "ASC" : "DESC");

    }

}

$st = $pdo->prepare($query);
$st->execute();

if ($st->rowCount() == 0){
    http_response_code(404);
}else{
    while ($row = $st->fetch(PDO::FETCH_OBJ)){
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
    <title>Seznam zaměstnanců</title>
</head>
<body>
<section class="container">
    <h1>Seznam zaměstnanců</h1>
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($table as $key => $column): ?>
            <th>
                <?= $column ?>
                <a href="./people.php?order=<?= $key ?>_up" class="arrow <?= ($active->key == $key & $active->up) ? "active" : "" ?>">🔽</a>
                <a href="./people.php?order=<?= $key ?>_down" class="arrow <?= ($active->key == $key & !$active->up) ? "active" : "" ?>">🔼</a>
            </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($table as $key => $column): ?>
                        <td><?= ($key === "name") ? "<a href='./person.php?personId=$row->id'>".$row->$key."</a>" : $row->$key ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>
</body>
</html>
