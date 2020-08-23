$(function(){
    $('#command').focus();
    $('#command').keypress(function(e) {
        if (e.key == 'Enter') {
            sendCommand();
        }
    });
});

function sendCommand()
{
    var command = $('#command').val();
    $('#command').val('');
    $.post('/api/command' , {command:command}, displayResult);
}

function displayResult(data)
{
    $('#output').append(data.response + "<br><br>");
    
    if (data.gameover || null) {
        $('#input').slideUp();
    }
}