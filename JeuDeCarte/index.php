<?php
require_once "carte.php";
require_once "function.php";
$cartes = array();
$cartesJoueur = array();
$cartesAdversaire = array();
$cartesJouerJoueur;
$cartesJouerAdversaire;
session_start();
if (isset($_GET['relance'])) {
    $_SESSION['EnPartie'] = false;
}
if (!$_SESSION['EnPartie'] || $_SESSION['EnPartie'] == null) {
    $cartes = importData();
    $cartesJoueur = array_merge($cartesJoueur, Draw($cartes, 4));
    $cartesAdversaire = array_merge($cartesAdversaire, Draw($cartes, 4));
    $_SESSION['EnPartie'] = true;
} else {
    $cartes = $_SESSION['cartes'];
    $cartesJoueur = $_SESSION['cartesJ'];
    $cartesAdversaire = $_SESSION['cartesA'];
}
if (isset($_GET['attack'])) {
    $cartesJouerJoueur = Play($cartesJoueur, $_GET['name']);
    $cartesJouerAdversaire = Play($cartesAdversaire, BestDefense($cartesAdversaire, $_GET['type'])->getName());

    $cartesProchainTour = BestCard($cartesAdversaire)->getName();
    $typeProchainTour = BestCard($cartesAdversaire)->bestAttack();
}
if (isset($_GET['defense'])) {
    $cartesJouerJoueur = Play($cartesJoueur, $_GET['name']);
    $cartesJouerAdversaire = Play($cartesAdversaire, $_GET['card']);
}
if (!isset($_SESSION['Win'])) {
    $_SESSION['Win'] = 0;
}
if (!isset($_SESSION['Lose'])) {
    $_SESSION['Lose'] = 0;
}
if (!isset($_SESSION['Draw'])) {
    $_SESSION['Draw'] = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de carte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        /* Bloque le scroll et fixe la hauteur à 100% de la fenêtre */
        body,
        html {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        /* --- STYLE DE BASE DE TOUTES LES CARTES --- */
        .card {
            margin-bottom: 0 !important;
            border: 2px solid rgba(0, 0, 0, 0.125);
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 100%;
            object-fit: cover;
            object-position: top center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-body {
            padding: 0.5rem !important;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .card-title {
            text-align: center;
            font-weight: bold;
        }

        .card-text {
            margin-bottom: 0.15rem !important;
        }

        input[type="radio"] {
            margin-left: 6px;
            transform: scale(0.95);
        }

        /* --- 1. VOS CARTES (EN BAS) : GRANDES --- */
        .bottom-cards .card {
            width: 14.5rem !important;
            min-height: 300px;
            font-size: 0.9rem;
        }

        .bottom-cards .card-img-top {
            height: 175px;
        }

        .bottom-cards .card-title {
            font-size: 1.15rem !important;
            margin-bottom: 0.25rem !important;
        }

        .bottom-cards .btn-success {
            padding: 0.3rem 0.5rem !important;
            font-size: 0.85rem !important;
            margin-top: 0.4rem;
            width: 100%;
            font-weight: bold;
        }

        /* --- 2. CARTES ADVERSAIRE & MILIEU : PLUS PETITES --- */
        /* Cela libère de l'espace sur l'écran pour éviter les superpositions invisibles */
        .top-cards .card,
        .middle-cards .card {
            width: 11.5rem !important;
            min-height: 230px;
            font-size: 0.8rem;
        }

        .top-cards .card-img-top,
        .middle-cards .card-img-top {
            height: 120px;
        }

        .top-cards .card-title,
        .middle-cards .card-title {
            font-size: 0.95rem !important;
            margin-bottom: 0.15rem !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100 d-flex flex-column p-0">
        <div class="row m-0 flex-grow-1 h-100">

            <!-- Zone de Jeu -->
            <div class="col-md-9 d-flex flex-column justify-content-evenly h-100 pt-2 pb-2">

                <!-- 1. Cartes de l'Adversaire -->
                <div class="d-flex justify-content-center gap-3 top-cards">
                    <?php
                    foreach ($cartesAdversaire as $c) {
                        echo $c->hiden("text-bg-danger");
                    }
                    ?>
                </div>

                <!-- 2. Plateau de combat / Cartes jouées -->
                <div class="d-flex justify-content-center align-items-center gap-4 middle-cards">
                    <?php
                    if (isset($_GET['attack'])) {
                        if (isset($cartesJouerAdversaire))
                            echo $cartesJouerAdversaire->showDefense($_GET['type'], "text-bg-success text-white");

                        echo "<div class='text-center mx-3'>";
                        if (isset($cartesJouerJoueur)) {
                            echo "<h3 class='m-0 fw-bold'>" . $cartesJouerJoueur->attack($_GET['type'], $cartesJouerAdversaire) . "</h3>";
                        }
                        if (isset($typeProchainTour))
                            echo "<div class='badge bg-warning text-dark mt-2 p-2 fs-6'>Adversaire choisit : " . strtoupper($typeProchainTour) . "</div>";
                        echo "</div>";

                        if (isset($cartesJouerJoueur)) {
                            echo $cartesJouerJoueur->showAttack($_GET['type'], "text-bg-success text-white");
                        }
                    }
                    if (isset($_GET['defense'])) {
                        if (isset($cartesJouerAdversaire))
                            echo $cartesJouerAdversaire->showAttack($_GET['defense'], "text-bg-success text-white");

                        echo "<div class='text-center mx-3'>";
                        if (isset($cartesJouerJoueur)) {
                            echo "<h3 class='m-0 fw-bold'>" . $cartesJouerJoueur->Defense($_GET['defense'], $cartesJouerAdversaire) . "</h3>";
                        }
                        echo "</div>";

                        if (isset($cartesJouerJoueur)) {
                            echo $cartesJouerJoueur->showDefense($_GET['defense'], "text-bg-success text-white");
                        }
                    }
                    if (isset($_GET['reset'])) {
                        $_SESSION['Win'] = 0;
                        $_SESSION['Lose'] = 0;
                        $_SESSION['Draw'] = 0;
                    }
                    ?>
                </div>

                <!-- 3. Cartes du Joueur -->
                <!-- Ajout de position-relative et z-index: 9999 pour GARANTIR que les boutons sont cliquables -->
                <div class="d-flex justify-content-center gap-3 mb-2 bottom-cards position-relative"
                    style="z-index: 9999;">
                    <?php
                    foreach ($cartesJoueur as $c) {
                        if (isset($_GET['attack'])) {
                            echo $c->showToDefend($typeProchainTour, $cartesProchainTour, "text-bg-primary text-white");
                        } else {
                            echo $c->showToAttack("text-bg-primary text-white");
                        }
                    }

                    $_SESSION['cartes'] = $cartes;
                    $_SESSION['cartesJ'] = $cartesJoueur;
                    $_SESSION['cartesA'] = $cartesAdversaire;
                    ?>
                </div>
            </div>

            <!-- Panneau Latéral : Instructions et Score -->
            <div class="col-md-3 bg-dark text-white p-4 d-flex flex-column h-100 shadow-lg position-relative"
                style="z-index: 10000;">
                <h3 class="mb-4 border-bottom pb-2">Tableau de bord</h3>

                <div class="mb-4">
                    <h5 class="text-uppercase text-muted mb-3">Scores</h5>
                    <div class="fs-4 fw-bold bg-black p-3 rounded text-center">
                        <span class="text-success mx-2">W: <?php echo $_SESSION['Win'] ?? 0; ?></span> |
                        <span class="text-danger mx-2">L: <?php echo $_SESSION['Lose'] ?? 0; ?></span> |
                        <span class="text-warning mx-2">D: <?php echo $_SESSION['Draw'] ?? 0; ?></span>
                    </div>
                    <form action="index.php" method="get" class="mt-auto border-top pt-4">
                        <button type="submit" name="reset" class="btn btn-danger w-100 fw-bold py-3 fs-5">Reinitialiser
                            les statistique</button>
                    </form>
                </div>

                <div class="mb-4 flex-grow-1">
                    <h5 class="text-uppercase text-muted mb-3">Instructions</h5>
                    <ul class="list-group list-group-flush rounded text-sm" style="font-size: 1rem;">
                        <li class="list-group-item text-bg-secondary py-3">Power vs Defense</li>
                        <li class="list-group-item text-bg-secondary py-3">Scary vs Bravery</li>
                        <li class="list-group-item text-bg-secondary py-3">Speed vs Speed</li>
                    </ul>
                </div>

                <form action="index.php" method="get" class="mt-auto border-top pt-4">
                    <button type="submit" name="relance" class="btn btn-danger w-100 fw-bold py-3 fs-5">Relancer une
                        partie</button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>