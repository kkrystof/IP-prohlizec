<?php

require_once("db.php");

//$roomId = $_GET["room_id"];
//$roomId = filter_input(INPUT_GET, "room_id", FILTER_VALIDATE_INT);

//$query = "select * from employee";
//$query = "
//SELECT , Customers.CustomerName, Orders.OrderDate
//FROM employee
//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
//";

$query = "SELECT e.employee_id 'id', CONCAT(e.surname, ' ', e.name) 'name', room.name 'room', room.phone, e.job FROM employee e INNER JOIN room ON e.room = room.room_id ORDER BY e.surname";




$pdo = DB::connect();
$st = $pdo->prepare($query);
$st->execute();


$empl = [];
$table = (object)[
        "name" => "Jméno",
        "room" => "Místnost",
        "phone" => "Telefon",
        "job" => "Pozice"
];


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
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
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
                <a href="./lide?poradi=<?= $key ?>_up" class="arrow active">🔼</a>
                <a href="./lide?poradi=<?= $key ?>_down" class="arrow">🔽</a>
<!--                <img style="border-color: red" src="https://unpkg.com/lucide-static@latest/icons/arrow-up-circle.svg">-->
            </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($empl as $row): ?>
            <tr>
                <?php foreach ($table as $key => $column): ?>

                    <td><?= ($key === "name") ? "<a href='./person.php?personId=$row->id'>".$row->$key."</a>" : $row->$key ?></td>
                <?php endforeach; ?>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</section>

</body>
</html>
