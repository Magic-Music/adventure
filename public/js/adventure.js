var waitTimer;
var timeToWaitBeforeBlankTurn = 30;

$(function(){
    $('#command').focus();
    $('#command').keypress(function(e) {
        if (e.key == 'Enter') {
            sendCommand();
        }
        restartWaitTimer();
    });
    
    restartWaitTimer();
    setInterval(wait, 1000);
});

function restartWaitTimer()
{
    waitTimer = timeToWaitBeforeBlankTurn;
}

function wait()
{
    if(--waitTimer == 0) {
        sendCommand(true);
    }
}

function sendCommand(wait = false)
{
    var command;

    if (!wait) {
        command = $('#command').val();
        $('#command').val('');
    }
    
    $.post('/api/command' , {command:command}, displayResult);
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