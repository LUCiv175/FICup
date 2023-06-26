<?php
    include("conn.php");
    $sql = "UPDATE squadre SET punti = 0, goal_fatti = 0, goal_subiti = 0";
    $conn->query($sql);
    $sql = "UPDATE giocatori SET goal = 0";
    $conn->query($sql);


    $sql = "SELECT * from squadre inner join giocatori on squadre.id = giocatori.id_squadra order by squadre.punti desc, squadre.goal_fatti desc, squadre.goal_subiti asc, squadre.nome asc";
    $result = $conn->query($sql);
        $giocatori = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $giocatori[] = $row;
            }
        }

        function generateMatchups($teams) {
            shuffle($teams);
            $half = count($teams) / 2;
            $homeTeams = array_slice($teams, 0, $half);
            $awayTeams = array_slice($teams, $half);
        
            $totalDays = count($teams) * 2 - 2;
            $allMatchups = array();
        
            for ($day = 0; $day < $totalDays; $day++) {
                $matchups = array();
                for ($i = 0; $i < $half; $i++) {
                    $matchup = array($homeTeams[$i], $awayTeams[$i]);
                    $matchups[] = $matchup;
                }
        
                $allMatchups[] = $matchups;
        
                // Ruota le squadre in modo che ogni squadra abbia una partita "fuori casa" con tutte le altre
                if ($day % 2 == 0) {
                    array_push($homeTeams, array_pop($awayTeams));
                    array_unshift($awayTeams, array_shift($homeTeams));
                } else {
                    array_unshift($homeTeams, array_shift($awayTeams));
                    array_push($awayTeams, array_pop($homeTeams));
                }
            }
        
            return $allMatchups;
        }        

    
    $response = "";
    $squadre = array();
    foreach ($giocatori as $giocatore) {
        if(array_search($giocatore['nome'], $squadre) === false){
            $squadre[] = $giocatore['nome'];
        }
    }

    $scontri = generateMatchups($squadre);
    foreach ($scontri as $day => $matchups) {
        $response .= "<div class='giornata'><h3>Giornata " . ($day + 1) . "</h3>";
        foreach ($matchups as $matchup) {
            $goalHome = rand(0, 5);
            $goalAway = rand(0, 5);
            if($goalHome > $goalAway){
                $sql = "UPDATE squadre SET punti = punti + 3, goal_fatti = goal_fatti + $goalHome, goal_subiti = goal_subiti + $goalAway WHERE nome = '$matchup[0]'";
                $conn->query($sql);
                $sql = "UPDATE squadre SET goal_fatti = goal_fatti + $goalAway, goal_subiti = goal_subiti + $goalHome WHERE nome = '$matchup[1]'";
                $conn->query($sql);
            } else if($goalHome < $goalAway){
                $sql = "UPDATE squadre SET punti = punti + 3, goal_fatti = goal_fatti + $goalAway, goal_subiti = goal_subiti + $goalHome WHERE nome = '$matchup[1]'";
                $conn->query($sql);
                $sql = "UPDATE squadre SET goal_fatti = goal_fatti + $goalHome, goal_subiti = goal_subiti + $goalAway WHERE nome = '$matchup[0]'";
                $conn->query($sql);
            } else {
                $sql = "UPDATE squadre SET punti = punti + 1, goal_fatti = goal_fatti + $goalHome, goal_subiti = goal_subiti + $goalAway WHERE nome = '$matchup[0]'";
                $conn->query($sql);
                $sql = "UPDATE squadre SET punti = punti + 1, goal_fatti = goal_fatti + $goalAway, goal_subiti = goal_subiti + $goalHome WHERE nome = '$matchup[1]'";
                $conn->query($sql);
            }
            $response .= "<div class='matchup'>";
            $response .= "<h5>" . $matchup[0] . "  " . $goalHome . " - " . $goalAway . "  " . $matchup[1] . "</h5>";

            $response .= "<div class='homeGoals'>";

            $squad = $matchup[0];
            $goal = $goalHome;
            $minute = 1;
            $counterAttaccanti=3;
            for($i = 0; $i < $goal; $i++){
                $whoDidTheGoal = rand(1, 100);
                if($counterAttaccanti==2) $limite = 30;
                else if($counterAttaccanti==1) $limite = 30;
                else $limite = 50;
                
                if($whoDidTheGoal <= $limite){
                    $counterAttaccanti = 0;
                    $attaccanti = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "attaccante"){
                            $counterAttaccanti++;
                            $attaccanti[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterAttaccanti);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $attaccanti[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $attaccanti[$whoScored - 1]['nome_cognome'] . "</p>";
                    
                }
                else if($whoDidTheGoal <= 80){
                    $counterCentrocampisti = 0;
                    $centrocampisti = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "centrocampista"){
                            $counterCentrocampisti++;
                            $centrocampisti[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterCentrocampisti);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $centrocampisti[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $centrocampisti[$whoScored - 1]['nome_cognome'] . "</p>";
                    
                }
                else if($whoDidTheGoal <= 99){
                    $counterDifensori = 0;
                    $difensori = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "difensore"){
                            $counterDifensori++;
                            $difensori[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterDifensori);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $difensori[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $difensori[$whoScored - 1]['nome_cognome'] . "</p>";
                }
                else {
                    $portieri = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "portiere"){
                            $portieri = $giocatore;
                        }
                    }
                    $sql = "UPDATE giocatori SET goal_subiti = goal_subiti + 1 WHERE id = " . $portieri['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $portieri['nome_cognome'] . "</p>";
                }
            }
            $response .= "</div>";
            $response .= "<div class='awayGoals'>";
            $minute = 1;
            $squad=$matchup[1];
            $goal=$goalAway;
            $counterAttaccanti=3;
            for($i = 0; $i < $goal; $i++){
                $whoDidTheGoal = rand(1, 100);
                if($counterAttaccanti==2) $limite = 30;
                else if($counterAttaccanti==1) $limite = 30;
                else $limite = 50;
                
                if($whoDidTheGoal <= 50){
                    $counterAttaccanti = 0;
                    $attaccanti = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "attaccante"){
                            $counterAttaccanti++;
                            $attaccanti[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterAttaccanti);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $attaccanti[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $attaccanti[$whoScored - 1]['nome_cognome'] . "</p>";
                }
                else if($whoDidTheGoal <= 80){
                    $counterCentrocampisti = 0;
                    $centrocampisti = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "centrocampista"){
                            $counterCentrocampisti++;
                            $centrocampisti[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterCentrocampisti);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $centrocampisti[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $centrocampisti[$whoScored - 1]['nome_cognome'] . "</p>";
                }
                else if($whoDidTheGoal <= 99){
                    $counterDifensori = 0;
                    $difensori = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "difensore"){
                            $counterDifensori++;
                            $difensori[] = $giocatore;
                        }
                    }
                    $whoScored = rand(1, $counterDifensori);
                    $sql = "UPDATE giocatori SET goal = goal + 1 WHERE id = " . $difensori[$whoScored - 1]['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $difensori[$whoScored - 1]['nome_cognome'] . "</p>";
                }
                else {
                    $portieri = array();
                    foreach ($giocatori as $giocatore) {
                        if($giocatore['nome'] == $squad && $giocatore['ruolo'] == "portiere"){
                            $portieri = $giocatore;
                        }
                    }
                    $sql = "UPDATE giocatori SET goal_subiti = goal_subiti + 1 WHERE id = " . $portieri['id'];
                    $conn->query($sql);
                    $time = rand($minute, 95);
                    $minute = $time;
                    if($time > 90) $time = "90 + ". ($time - 90);
                    $response .= "<p>" . $time . "' - " . $portieri['nome_cognome'] . "</p>";
                }
            }
            $response .= "</div>";
            $response .= "</div>";
            }
    }$response .= "</div>";

    //classifca
    $content = $response;
    $response = "<h2>Classifica</h2>";
    $sql = "SELECT * FROM squadre ORDER BY punti DESC, goal_fatti DESC, goal_subiti ASC";
    $result = $conn->query($sql);
    $squadre = array();
    while($row = $result->fetch_assoc()) {
        $squadre[] = $row;
    }
    
    $response .= "<table><tr id='int_table'><th >Posizione</th><th>Squadra</th><th>Punti</th><th class='goal_fatti'>Goal Fatti</th><th class='goal_subiti'>Goal Subiti</th><th class='dif_reti'>Differenza Reti</th></tr>";
    
    $posizione = 1;
    foreach ($squadre as $row) {
        $response .= "<tr class='hid' id='".$row['id']."'><td>" . $posizione . "</td><td>". $row['nome'] . "</td><td>" . $row['punti'] . "</td><td class='goal_fatti'>" . $row['goal_fatti'] . "</td><td class='goal_subiti'>" . $row['goal_subiti'] . "</td><td class='dif_reti'>" . ($row['goal_fatti'] - $row['goal_subiti']) . "</td></tr>";
        $posizione++;
    }
    $response .= "</table>";
    //classifica marcatori
    $response .= "<h2>Classifica marcatori</h2>";
    $response .= "<div class='thead'>";
    $response .= "<table id='intestazione'><tr><th id='text_pos' class='pos'>Pos</th><th>Nome</th><th class='nome_squadra'>Squadra</th><th class='gol'>Goal</th></tr></table></div><div class='tbody'><table id='contenuto'>";
    $sql = "SELECT * FROM giocatori inner join squadre on giocatori.id_squadra = squadre.id ORDER BY goal DESC";
    $result = $conn->query($sql);
    $posizione = 1;
    while($row = $result->fetch_assoc()) {
        $response .= "<tr><td class='pos'>" . $posizione . "</td><td>" . $row['nome_cognome'] . "</td><td class='nome_squadra'>" . $row['nome'] . "</td><td class='gol'>" . $row['goal'] . "</td></tr>";
        $posizione++;
    }
    $response .= "</table>";
    $response .= "</div>";
    $response .= "<br>";
    $response .= $content;
    echo $response;
    $conn->close();
?>