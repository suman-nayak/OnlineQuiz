
var timer = document.getElementById("timer");

var quizForm = document.getElementById("quizForm");

function startTimer()
{
    var minutes = Math.floor(remainingSeconds / 60);

    var seconds = remainingSeconds % 60;

    if (minutes < 10)
    {
        minutes = "0" + minutes;
    }

    if (seconds < 10)
    {
        seconds = "0" + seconds;
    }

    timer.innerHTML = minutes + ":" + seconds;

    if (remainingSeconds <= 0)
    {
        timer.innerHTML = "Time Up!";

        alert("Your quiz time is over. Your answers will be submitted automatically.");

        quizForm.submit();

        return;
    }

    remainingSeconds--;

    setTimeout(startTimer, 1000);
}

startTimer();
