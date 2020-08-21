<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Adventure</title>

        <script src="/js/jquery.js"></script>
        <script>
            $(function(){
                $('#command').focus();
            });
        </script>

        <link href="/css/styles.css" rel="stylesheet" type="text/css" />
        
    </head>
    <body>
        <div class='output'>
            <span style='font-size:2em; color:cyan'>College Adventure<br><br></span>
            You are a student at Wibbleforce College, and you are in trouble!<br><br><br>
            You're late with your homework, your teachers are after your blood, and to make things worse,<br>
            you offered to help out with the concert tonight, and you haven't even started getting the equipment to the hall.<br><br><br>
            As if that wasn't enough, other students and staff keep bothering you to help them too...<br><br><br>
            Who will you be?<br><br><br>
            <span class='inverse'>1</span>: Adam<br><br>
            <span class='inverse'>2</span>: Mark<br><br>
            <span class='inverse'>3</span>: Steve<br><br>
            <span class='inverse'>4</span>: Matthew<br><br>
            Enter a number (1-4) below to choose your character!
        </div>
        <div class='input'>
            <span class='command_label'>&gt;</span>
            <input class='command' type='text' id='command' name='command'>
        </div>
    </body>


</html>
