<?php
    include("conn.php");
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>FI CUP</title>
        <link rel="icon" href="images/FI_Cup_Logo.png" type="image"/>
        
    </head>
    <body>
        <div class='inizio'>
        <h1>FI CUP</h1>
        <form id='myForm'>
        <input type="submit" value="GENERA" name='start'>
        </form></div>
        

        <input type="hidden" name="squad" value=0>
        <div id="risultati">
        </div>
        <div class="squadra">
        </div>



        <script>
            //$( "#loading" ).hide();
            $(document).ready(function() {
            $('#myForm').submit(function(event) {
                event.preventDefault(); // Evita il comportamento di default del form
                // Ottieni i dati dal form
                //$( "#loading" ).show();
                $(".inizio").hide();
                $("#risultati").hide();
                var formData = $(this).serialize();
                // Esegui la richiesta POST AJAX
                $.ajax({
                type: 'POST',
                url: 'process.php', // URL al file PHP che elabora la richiesta
                data: formData,
                success: function(response) {
                    // Gestisci la risposta del server
                    //$( "#loading" ).hide();
                    $(".inizio").show();
                    $("#risultati").show();
                    $('#risultati').html(response);
                    
                    document.getElementsByClassName('inizio')[0].style = 'margin-bottom: 43vh;';
                    
                    $('html, body').animate({
                        scrollTop: $("#risultati").offset().top
                    }, 1000);
                    
                    
                },
                error: function() {
                    // Gestisci eventuali errori
                    alert('Si è verificato un errore durante l\'invio della richiesta.');
                }
                });
            });
            });
            $(document).ready(function() {
                $(document).on('click', '.hid', function() {
                    formData = $(this).attr('id');
                    $.ajax({
                    type: 'POST',
                    url: 'doc.php', // URL al file PHP che elabora la richiesta
                    data: {id_squadra: formData},
                    success: function(response) {
                        $('#risultati').hide();
                        $('.inizio').hide();
                        $('.squadra').html(response);
                        //return on the top of the page
                        $('html, body').animate({
                            scrollTop: $("head").offset().top
                        }, 500);

                    },
                    error: function() {
                        // Gestisci eventuali errori
                        alert('Si è verificato un errore durante l\'invio della richiesta.');
                    }
                    });
                });
            });
            function torna(){
                $('.squadra').html('');
                $('#risultati').show();
                $('.inizio').show();
                $('html, body').animate({
                    scrollTop: $("head").offset().top
                }, 0);
                $('html, body').animate({
                        scrollTop: $("#risultati").offset().top
                    }, 1000);

            }
            
            
        </script>
        
    </body>
</html>
<?php
    $conn->close();
?>
