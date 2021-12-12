<?php

$empl = [];
$table = (object)[
    "name" => "Název",
    "no" => "Číslo",
    "phone" => "Telefon",

];


$active = (object) [
    "key" => "name",
    "up" => true
];

require_once("db.php");


//$order = filter_input(INPUT_GET, "order", FILTER_VALIDATE_);

//$query = "select * from employee";
//$query = "
//SELECT , Customers.CustomerName, Orders.OrderDate
//FROM employee
//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
//";

function query($by, $direction){
    return "SELECT room.*, room.room_id 'id' FROM room ORDE R BY room." . $by . " " . $direction;

}

$pdo = DB::connect();

$order = $_GET["order"] ?? false;

if($order){
    $arr = explode("_", $order);



    if(array_key_exists($arr[0], $table) && $arr[1] == "up" || $arr[1] == "down"){
        $active->key = $arr[0];
        $active->up = ($arr[1] === "up") ? true : false;
        $st = $pdo->prepare(query($arr[0], ($arr[1] === "up") ? "ASC" : "DESC"));
    }

}else{
    $st = $pdo->prepare("SELECT room.*, room.room_id 'id' FROM room ORDER BY room.name");
}

$st->execute();





if ($st->rowCount() == 0){
    http_response_code(404);
}else{
    while ($row = $st->fetch(PDO::FETCH_OBJ)){
            array_push($empl, $row);
    }

//    var_dump(count($empl));

}
//var_dump($empl);

?>

<!doctype html>
<html lang="en">
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



    <div class="scroll-view">
    <table class="table">
        <thead>
        <tr>
            <?php foreach ($table as $key => $column): ?>
            <th>
                <?= $column ?>
<!--                <img src="https://unpkg.com/lucide-static@latest/icons/arrow-down-circle.svg">-->
<!--                🡹⮟-->
                <a href="./rooms?order=<?= $key ?>_up" class="arrow <?= ($active->key == $key & $active->up) ? "active" : "" ?>">🔽</a>
                <a href="./rooms?order=<?= $key ?>_down" class="arrow <?= ($active->key == $key & !$active->up) ? "active" : "" ?>">🔼</a>
<!--                <img style="border-color: red" src="https://unpkg.com/lucide-static@latest/icons/arrow-up-circle.svg">-->
            </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($empl as $row): ?>
            <tr>
                <?php foreach ($table as $key => $column): ?>

                    <td><?= ($key === "name") ? "<a href='./room.php?roomId=$row->id'>".$row->$key."</a>" : $row->$key ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</section>

</body>
</html>
