<?php
    include "conn.php";
    $id_squadra = $_POST['id_squadra'];
    $sql = "SELECT * FROM giocatori inner join squadre on giocatori.id_squadra = squadre.id where id_squadra = $id_squadra order by ruolo desc";
    $result = $conn->query($sql);
    $giocatori = array();
    foreach($result as $row) {
        $giocatori[] = $row;
    }
    $response = "<div class='nomEtorna'>";
    $response .= "<h1 class='nomeTeam'>".$giocatori[0]['nome']."</h1>";
    $id_squad = $giocatori[0]['id_squadra'];
    switch($id_squad){
        case 1:
            $response .= "<img src='images/image1.png' alt=''>";
            break;
        case 2:
            $response .= "<img src='images/image2.png' alt=''>";
            break;
        case 3:
            $response .= "<img src='images/image3.png' alt=''>";
            break;
        case 4:
            $response .= "<img src='images/image4.png' alt=''>";
            break;
        case 5:
            $response .= "<img src='images/image5.png' alt=''>";
            break;
        case 6:
            $response .= "<img src='images/image6.png' alt=''>";
            break;
        case 7:
            $response .= "<img src='images/image7.png' alt=''>";
            break;
        case 8:
            $response .= "<img src='images/image8.png' alt=''>";
            break;
        case 9:
            $response .= "<img src='images/image9.png' alt=''>";
            break;
        case 10:
            $response .= "<img src='images/image10.png' alt=''>";
            break;
        case 11:
            $response .= "<img src='images/image11.png' alt=''>";
            break;
        case 12:
            $response .= "<img src='images/image12.png' alt=''>";
            break;
        case 13:
            $response .= "<img src='images/image13.png' alt=''>";
            break;
        case 14:
            $response .= "<img src='images/image14.png' alt=''>";
            break;
        case 15:
            $response .= "<img src='images/image15.png' alt=''>";
            break;
        case 16:
            $response .= "<img src='images/image16.png' alt=''>";
            break;
        case 17:
            $response .= "<img src='images/image17.png' alt=''>";
            break;
        case 18:
            $response .= "<img src='images/image18.png' alt=''>";
            break;
        case 19:
            $response .= "<img src='images/image19.png' alt=''>";
            break;
        case 20:
            $response .= "<img src='images/image20.png' alt=''>";
            break;
        case 21:
            $response .= "<img src='images/image21.png' alt=''>";
            break;
        default:
            $response .= "<img src='images/image22.png' alt=''>";
            break;
    }
    $response .= "<input type='button' value='Torna alla lista' onclick='torna()'>";
    $response .= "</div>";
    $response .= "<div class='giocatori'>";
        $response .= "<div class='giocatore'>";
        $response .= "<table>";
        $response .= "<tr><th>Nome</th><th>Ruolo</th><th>Goal</th></tr>";
        foreach($giocatori as $giocatore) {
            $response .= "<tr><td>".$giocatore['nome_cognome']."</td><td>".$giocatore['ruolo']."</td><td>".$giocatore['goal']."</td></tr>";
        }
        $response .= "</table>";
        $response .= "</div>";
    $response .= "</div>";

    echo $response;
    $conn->close();
?>
