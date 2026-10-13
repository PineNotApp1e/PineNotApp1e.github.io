<?php
require_once "carte.php";
function importData()
{
    $jsonData = file_get_contents('carte.json');
    $data = json_decode($jsonData, true);
    $dataReturn = array();
    foreach ($data["carte"] as $cart) {
        $carte;
        if ($cart['name'] == "Gambler") {
            $carte = creerCarteAleatoire($cart['name'], $cart["image"]);
        } else {
            $carte = new carte($cart['name'], $cart["image"], $cart["power"], $cart["scary"], $cart["speed"], $cart["defense"], $cart["bravery"]);

        }
        array_push($dataReturn, $carte);
    }

    shuffle($dataReturn);
    return $dataReturn;
}
function Draw(&$carte, $nombre)
{
    $carteDraw = array();
    for ($i = 0; $i < $nombre; $i++) {
        $carteDraw[] = array_pop($carte);
    }
    return $carteDraw;
}
function Play(&$carte, $name)
{
    foreach ($carte as $index => $c) {
        if ($c->getName() === $name) {
            $removed = array_splice($carte, $index, 1);
            return $removed[0];
        }
    }
    return null;
}
function BestCard($cards): carte
{
    $attack = 0;
    $play = $cards[0];
    foreach ($cards as $card) {
        if ($card->getAttack($card->bestAttack()) > $attack) {
            $attack = $card->getAttack($card->bestAttack());
            $play = $card;
        }
    }
    return $play;
}
function BestDefense($cards, $type): carte
{
    $attack = 0;
    $play = $cards[0];
    foreach ($cards as $card) {
        if ($card->getDefense($type) > $attack) {
            $attack = $card->getAttack($card->bestAttack());
            $play = $card;
        }
    }
    return $play;
}
function creerCarteAleatoire($nom, $imagePath) : carte
{
    $totalCible = 415;

    $points = [0, $totalCible];
    while (count($points) < 6) {
        $p = rand(0, $totalCible);
        if (!in_array($p, $points)) {
            $points[] = $p;
        }
    }
    sort($points);

    $power = $points[1] - $points[0];
    $scary = $points[2] - $points[1];
    $speed = $points[3] - $points[2];
    $defense = $points[4] - $points[3];
    $bravery = $points[5] - $points[4];

    $stats = [$power, $scary, $speed, $defense, $bravery];
    shuffle($stats);


    return new carte($nom,$imagePath,$stats[0],$stats[1],$stats[2],$stats[3],$stats[4]);
}
