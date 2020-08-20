<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Adventure</title>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script>
            $(function(){
                $('#command').focus();
            });
        </script>

        <!-- Fonts -->
        <link href="https://allfont.net/allfont.css?fonts=zx-spectrum" rel="stylesheet" type="text/css" />
        
        <!-- Styles -->
        <style>
            html, body {
                background-color: black;
                color: white;
                font-family: 'ZX Spectrum';
                font-size: 1em;
                font-weight: 200;
                height: 98vh;
                margin: 0;
                overflow:hidden;
            }

            .output::-webkit-scrollbar {
              width: 10px;
            }

            .output::-webkit-scrollbar-track {
              background: darkblue;
            }
            
            .output::-webkit-scrollbar-thumb {
              background-color: blue;
              border-radius: 4px;
              border: 3px solid black;
            }

            .input, .output {
                margin:10px;
                border:6px double;
                border-radius: 6px;
            }

            .output {
                scrollbar-width: thin;
                scrollbar-color: black darkblue;
                height: calc(100vh - 130px);
                width: calc(100vw - 82px);
                border-color: darkblue;
                color: #bbb;
                overflow-y: scroll;
                padding: 10px;
            }

            .input, .command, .command_label {
                width: calc(100vw - 62px);
                color: green;
                font-family: 'ZX Spectrum';
                display: inline-block;
            }

            .input {
                border-color: darkcyan;
                position: absolute;
                bottom: 0;
                height: 50px;
            }

            .command {
                height: calc(100% - 4px);
                width: calc(100% - 40px);
                padding:2px;
                border: none;
                background-color: black;
                font-size: 1em;
            }

            .command:focus {
                outline: none;
            }

            .command_label {
                width:0;
                padding:0;
                margin:0;
                margin-left: 4px;
                text-align: right;
            }

            .inverse{
                background-color: blue;
                padding: 2px 0 2px 4px;
            }

        </style>
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
