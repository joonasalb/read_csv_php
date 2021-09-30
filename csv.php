<?php

$handle = fopen("/home/jonas/Downloads/matrixrates (13).csv", "r");
$row = 0;
$matrix = array();

// $con = new PDO("mysql:host=localhost;dbname=teste", "root", "root1234");

while ($line = fgetcsv($handle, 1000, ",")) {
    if ($row++ == 0) {
        continue;
    }

    $method = $line[8];

    $deadline = explode('Prazo de entrega para a sua região em até ', $method);
    $deadline = explode(' dias úteis, após coleta da transportadora', $deadline[1])[0];

    $prompt_delivery = explode('Pronta Entrega: Coleta em até ', $method);
    $prompt_delivery = explode(' dias úteis. Pré-Venda: Coleta em até ', $prompt_delivery[1])[0];

    $pre_sale = explode(' dias úteis. Pré-Venda: Coleta em até ', $method);
    $pre_sale = explode(' dias úteis após data informada nos produtos', $pre_sale[1])[0];

    $matrix[] = [
        'zip_start'       => $line[3],
        'zip_end'         => $line[4],
        'deadline'        => $deadline,
        'prompt_delivery' => $prompt_delivery,
        'pre_sale'        => $pre_sale
    ];

    $sql = "INSERT INTO matrix_rates (zip_start, zip_end, deadline, prompt_delivery, pre_sale) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$line[3], $$line[4], $deadline, $prompt_delivery, $pre_sale]);
}
print_r($matrix);
fclose($handle);
