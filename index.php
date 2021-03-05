<?php
require_once('php/connect.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exercice A table !</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.3.1.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<section class="hero" id="hero">
    <h2 class="hero_header">Osaka <span class="light">Okonomiyaki</span></h2>
    <p class="tagline">Panel Admin</p>
</section>
<div class="container-fluid">
    <div class="row text-center">
        <div class="col-lg-4">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#allVisits">Voir toutes les visites
            </button>
            <!-- Tableau > Toutes les infos sur les visites-->
            <div class="collapse" id="allVisits">
                <?php
                echo "<table style='border: solid 1px black;'>";
                echo "<tr><th>Date de la visite</th><th>ID client</th><th>Code QR de la table</th></tr>";

                class TableRows extends RecursiveIteratorIterator
                {
                    function __construct($it) {
                        parent::__construct($it, self::LEAVES_ONLY);
                    }

                    function current() {
                        return "<td style='width: 150px; border: 1px solid black;'>" . parent::current() . "</td>";
                    }

                    function beginChildren() {
                        echo "<tr>";
                    }

                    function endChildren() {
                        echo "</tr>" . "\n";
                    }
                }
                try {
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $bdd->prepare("SELECT date, table_qr, client_id FROM visits");
                    $stmt->execute();

                    // set the resulting array to associative
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                    foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                        echo $v;
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $conn = null;
                echo "</table>";
                ?>
            </div>
            <!--Fin tableau > Toutes les visites-->
        </div>
        <div class="col-lg-4">
            <!--Dropdown Sélectionner le row de la table Tables à visionner-->
            <form>
                <select name="users" onchange="showTables(this.value)">
                    <option value="">Choisissez une table:</option>
                    <option value="1">Table 1 Alice</option>
                    <option value="2">Table 2 Corinne</option>
                    <option value="3">Table 3 Manon</option>
                    <option value="4">Table 4 Norah</option>
                    <option value="5">Table 5 Audrey</option>
                    <option value="6">Table 6 Jaina</option>
                </select>
            </form>
            <div id="txtHint"><b>Les infos des tables PHP+AJAX utilisé qu'une fois parce que mysqli au lieu de PDO</b>
            </div>
            <!--Fin dropdown-->
        </div>
        <div class="col-lg-4">
            <button class="btn btn-primary" data-toggle="collapse" data-target="#allClients">Voir tous les clients
            </button>
            <!-- Tableau > Tous les clients-->
            <div class="collapse" id="allClients">
                <?php
                echo "<table style='border: solid 1px black;'>";
                echo "<tr><th>ID client</th><th>Langue du client</th><th>Marque de téléphone du client</th></tr>";

                class ClientRows extends RecursiveIteratorIterator
                {
                    function __construct($it) {
                        parent::__construct($it, self::LEAVES_ONLY);
                    }

                    function current() {
                        return "<td style='width: 150px; border: 1px solid black;'>" . parent::current() . "</td>";
                    }
                    function beginChildren() {
                        echo "<tr>";
                    }
                    function endChildren() {
                        echo "</tr>" . "\n";
                    }
                }
                try {
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $bdd->prepare("SELECT client_id, client_lang, phone_brand FROM clients");
                    $stmt->execute();

                    // set the resulting array to associative
                    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

                    foreach (new ClientRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                        echo $v;
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $conn = null;
                echo "</table>";
                ?>
            </div>
            <!--Fin tableau > Tous les clients-->
        </div>
    </div>
</div>
<!-- Stats Gallery Section -->
<?php
//query table la plus visitée
$mostVisitedTable = $bdd->query('SELECT COUNT(DISTINCT date) AS \'total_flash\', qr_nom 
    FROM qr_codes 
    JOIN visits 
    ON table_qr = qr_id  
    GROUP BY table_qr 
    ORDER BY total_flash DESC
    ');
$donnees = $mostVisitedTable->fetch();
//fin query table la p^lus visitée

//query langue la plus représentée
$langMostSeen = $bdd->query('SELECT COUNT(client_lang), client_lang 
    FROM clients 
    GROUP BY client_lang 
    ORDER BY `COUNT(client_lang)` DESC LIMIT 1
    ');
$donnees2 = $langMostSeen->fetch();

//fin query

//query pour le mois le plus visited
$bestMonth = $bdd->query('SELECT CASE MONTH(date) 
    WHEN 1 THEN \'janvier\' 
    WHEN 2 THEN \'Février\' 
    WHEN 3 THEN \'mars\' 
    WHEN 4 THEN \'avril\' 
    WHEN 5 THEN \'mai\' 
    WHEN 6 THEN \'juin\' 
    WHEN 7 THEN \'juillet\' 
    WHEN 8 THEN \'août\' 
    WHEN 9 THEN \'septembre\' 
    WHEN 10 THEN \'octobre\' 
    WHEN 11 THEN \'novembre\' 
    ELSE \'décembre\' 
    END 
    AS \'mois\', 
    year(date),month(date),
    COUNT(date)
    AS \'totalVisites\' 
    FROM visits 
    GROUP BY year(date),month(date) 
    ORDER BY COUNT(date) DESC 
    LIMIT 1
    ');
$donnees3 = $bestMonth->fetch();
//fin query

//query pour le jour de la semaine le plus visité
$bestDayOfWeek = $bdd->query('SELECT CASE DAYOFWEEK(date) 
    WHEN 1 THEN \'Lundi\' 
    WHEN 2 THEN \'Mardi\' 
    WHEN 3 THEN \'Mercredi\' 
    WHEN 4 THEN \'Jeudi\' 
    WHEN 5 THEN \'Vendredi\' 
    WHEN 6 THEN \'Samedi\' 
    WHEN 7 THEN \'Dimanche\' 
    END AS \'jourSemaine\', 
    COUNT(date), date, DAYOFWEEK(date) 
    FROM visits 
    GROUP BY DAYOFWEEK(date) 
    ORDER BY COUNT(date) DESC LIMIT 1
    ');
$donnees4 = $bestDayOfWeek->fetch();
?>
<div class="gallery">
    <div class="thumbnail">
        <h1 class="stats"><?php echo $donnees['qr_nom']; ?></h1>
        <h4>La table la plus prisée</h4>
        <p>Avec un nombre de flashs de: <?php echo $donnees['total_flash'];
            $mostVisitedTable->closeCursor(); ?>
        </p>
    </div>
    <div class="thumbnail">
        <h1 class="stats"><?php echo $donnees2['client_lang'];
            $langMostSeen->closeCursor(); ?>
        </h1>
        <h4>La langue la plus parlée</h4>
        <p>Par les clients du restaurant</p>
    </div>
    <div class="thumbnail">
        <h1 class="stats"><?php echo $donnees3['mois'];?></h1>
        <h4>Le mois le plus visité</h4>
        <p>Avec un total de <?php echo $donnees3['totalVisites'];
            $bestMonth->closeCursor(); ?> visites</p>
    </div>
    <div class="thumbnail">
        <h1 class="stats"><?php echo $donnees4['jourSemaine'];
            $bestDayOfWeek->closeCursor(); ?></h1>
        <h4>Le jour préféré</h4>
        <p>De nos clients, depuis l'ouverture</p>
    </div>
</div>

<!-- Copyrights Section -->
<div class="copyright">&copy;2019- <strong>Light Theme</strong></div>
<script src="js/script.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-3.3.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/popper.min.js"></script>
<script src="js/bootstrap-4.3.1.js"></script>
</body>
</html>