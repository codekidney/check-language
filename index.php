<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language detector</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <form id="form">
        <h2>Language detector</h2>
        <p><small>Type any text to field below to detect language. Can detect text written in <?php echo implode(', ', $languagesLong); ?>.</small></p>
        <textarea id="unknown" name="text" rows="6"></textarea>
        <p><button>Click to detect language</button></p>
        <p id="result" style="display:none">This is written in <span id="language"></span>. (time: <span id="time"></span>sec.)</p>
    </form>
    <script src="assets/js/code.jquery.com_jquery-3.7.1.min.js"></script>
    <script>
        $("#form").submit( function () {    
            $.ajax({   
                type: "POST",
                data : $(this).serialize(),
                url: "ajax.php",   
                success: function(result){
                    const {language, time} = JSON.parse(result);
                    $('#language')
                    console.log({language, time});
                    $("#language").text(language);                   
                    $("#time").text(time);  
                    $("#result").show();                 
                }   
            });   
            return false;   
        });
    </script>
</body>
</html>