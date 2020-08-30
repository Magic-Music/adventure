var waitTimer;
var timeToWaitBeforeBlankTurn = 60;

$(function(){
    $('#command').focus();
    $('#command').keypress(function(e) {
        if (e.key == 'Enter') {
            sendCommand();
        }
        restartWaitTimer();
    });
    
    $('#output').click(function(e) {
        $('#command').focus();        
    });

    setInterval(wait, 1000);
    
    sendCommand('look');
});

function restartWaitTimer()
{
    waitTimer = timeToWaitBeforeBlankTurn;
}

function wait()
{
    if(--waitTimer == 0) {
        sendCommand('nothing');
    }
}

function sendCommand(command)
{
    if (!command) {
        command = $('#command').val();
        $('#command').val('');
    }
    
    $.post('/command' , {command:command}, displayResult);
}

function displayResult(data)
{
    var output = $('#output');
    output.append(data.response + "<br><br>");
    output.animate({ scrollTop: output.prop("scrollHeight")}, 1000);
   
    if (data.gameover || null) {
        $('#input').slideUp();
    }
    
    restartWaitTimer();
}