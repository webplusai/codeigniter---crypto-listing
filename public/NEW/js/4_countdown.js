var timeinterval;
var nextendtime;

function getTimeRemaining(id,endtime,currentDate) {

  var t = Date.parse(endtime) - currentDate;
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
    
  t = {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
  

  
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector('.days');
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');
  
  daysSpan.innerHTML = t.days;
  hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
  minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
  secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

  if (t.total <= 0) {
    clearInterval(timeinterval);
    
    if (id == 'startclock')
    {
      daysSpan.innerHTML = 'L';
      hoursSpan.innerHTML = 'I';
      minutesSpan.innerHTML = 'V';
      secondsSpan.innerHTML = 'E';
      initializeClock('endclock', nextendtime);  
    }
    else
    {
      daysSpan.innerHTML = '- -';
      hoursSpan.innerHTML = '- -';
      minutesSpan.innerHTML = '- -';
      secondsSpan.innerHTML = '- -';  
      
      id = 'startclock';
      var clock = document.getElementById(id);
      var daysSpan = clock.querySelector('.days');
      var hoursSpan = clock.querySelector('.hours');
      var minutesSpan = clock.querySelector('.minutes');
      var secondsSpan = clock.querySelector('.seconds'); 
      
      daysSpan.innerHTML = '- -';
      hoursSpan.innerHTML = '- -';
      minutesSpan.innerHTML = '- -';
      secondsSpan.innerHTML = '- -';  
    }
  }  
  
                                             
}

function initializeClock(id, endtime, endtime2) {     
  nextendtime = endtime2;
  
  timeinterval = setInterval(updateClock, 1000);
  
  function updateClock() 
  {
    var currentDate = Date.parse(ServerDate());
	  getTimeRemaining(id,endtime,currentDate)
  }  
}
