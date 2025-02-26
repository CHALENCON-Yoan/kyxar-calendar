<?php
$dayNames = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

if (isset($_POST["firstDayDisplayMonth"])) {
    $previousDate = $_POST["firstDayDisplayMonth"];
    if (isset($_POST["previous"])) {
        $firstDateOfMonth = (new DateTime($previousDate))->modify("-1 month");
    } elseif (isset($_POST["next"])) {
        $firstDateOfMonth = (new DateTime($previousDate))->modify("+1 month");
    }
    $month = $firstDateOfMonth->format("m");
    $year = $firstDateOfMonth->format("Y");
} else {
    if (isset($_GET["month"]) && (intval($_GET["month"]) >= 1 && intval($_GET["month"]) <= 12)) {
        $month = intval($_GET["month"]);
    } else {
        $month = date("m");
    }

    if (isset($_GET["year"]) && (intval($_GET["year"]) > 0)) {
        $year = intval($_GET["year"]);
    } else {
        $year = date("Y");
    }
    $firstDateOfMonth = new DateTime($year . "-" . $month . "-01");
}

$firstDayOfMonth = intval($firstDateOfMonth->format("w"));
if ($firstDayOfMonth == 0)
    $firstDayOfMonth = 7;

$dayCount = intval($firstDateOfMonth->format("t"));

$necessaryRowsCount = ceil(($dayCount + $firstDayOfMonth - 1) / 7);

$today = new DateTime();

$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
$formatter->setPattern('MMMM yyyy');
$monthDisplay = $formatter->format($firstDateOfMonth);
$monthDisplay = mb_convert_case($monthDisplay, MB_CASE_TITLE, "UTF-8");

// Exemple de requête pour récupérer des événements depuis une base de données
//$pdo = new PDO("mysql:host=hostname;dbname=database;charset=utf8", "user", "pwd");
//$stmt = $pdo->prepare("SELECT event_id, event_name, event_day FROM events WHERE month = ? AND year = ?;");
//$stmt->execute([$month, $year]);
//$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Calendrier</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header>
            <form action="" method="post">
                <input type="hidden" name="firstDayDisplayMonth"
                       value="<?php echo $firstDateOfMonth->format("Y-m-d"); ?>"/>
                <button type="submit" name="previous"><</button>
            </form>
            <h1><?php echo $monthDisplay ?></h1>
            <form action="" method="post">
                <input type="hidden" name="firstDayDisplayMonth" id="firstDayDisplayMonthInput"
                       value="<?php echo $firstDateOfMonth->format("Y-m-d"); ?>"/>
                <button type="submit" name="next">></button>
            </form>
        </header>
        <main>
            <?php
            $dayNumber = 1;

            for ($row = 0; $row < $necessaryRowsCount + 1; $row++) {
                echo '<div class="calendar-row">';
                for ($day = 0; $day < count($dayNames); $day++) {
                    if ($row == 0) {
                        $id = "";
                        $classNames = "calendar-header-cell";
                        $content = $dayNames[$day];
                    } elseif (($row != 1 || $day >= $firstDayOfMonth - 1) && $dayNumber <= $dayCount) {
                        $id = "day" . $dayNumber;
                        $classNames = "calendar-cell";
                        if ($today->format("Y-m-d") == (new DateTime($year . "-" . $month . "-" . $dayNumber))->format("Y-m-d")) {
                            $classNames .= " current-day";
                        }
                        $content = $dayNumber;
                        $dayNumber++;
                    } else {
                        $id = "";
                        $classNames = "calendar-cell";
                        $content = "";
                    }
                    echo '<div ' . ($id != "" ? 'id="' . $id . '" ' : '') . 'class="' . $classNames . '">' . ($content != "" ? $content : "") . '</div>';
                }
                echo '</div>';
            }
            ?>
        </main>
        <footer><p id="message"></p></footer>
        <script src="calendar.js"></script>
    </body>
</html>
