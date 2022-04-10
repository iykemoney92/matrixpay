<?php $title = 'MatrixPay'; ?>
<html>
<head>
<?php include_once('head.php') ?>
<link href="css/cover.css" rel="stylesheet"/>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0"><?php echo $title ?></h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
        <a class="nav-link active" aria-current="page" href="/">Home</a>
        <a class="nav-link" href="login.php">Login</a>
        <a class="nav-link" href="#">Contract:<small>-</small></a>
      </nav>
    </div>
  </header>
  <section class="border border-danger m-2 p-3">
  <h5>We are launching soon</h5>
   <div id="timer">
   <div class="row">
    <div class="days border border-danger border-4 rounded-3 p-1 col m-3"></div>
    <div class="hours border border-danger border-4 rounded-3 p-1 col m-3"></div>
    <div class="minutes border border-danger border-4 rounded-3 p-1 col m-3"></div>
    <div class="seconds border border-danger border-4 rounded-3 p-1 col m-3"></div>
    </div>
   </div>
  </section>
  <main class="px-3">

    <h1>Join the billion dollar club</h1>
    <p class="lead">No referrals required, no hidden charges. Download our litepaper to know what its all about</p>
    <p class="lead">
      <a href="" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Download Litepaper</a>
      <a href="join.php" class="btn btn-lg btn-success fw-bold border-success bg-success">Join the Club</a>
    </p>
  </main>

  <footer class="mt-auto text-white-50">
    <p><?php echo $title.' '.date('Y'); ?></p>
  </footer>
</div>  
</body>
<?php include('foot.php') ?>
<script>
window.addEventListener('load', ()=>{
var timer = new easytimer.Timer();
timer.start({countdown: true, startValues:{}, target: {days:10, hours:24, minutes:60, seconds: 60}});

timer.addEventListener('secondsUpdated', function (e) {
  
  $('#timer .days').html(timer.getTimeValues().days +' <br/>days');
    $('#timer .hours').html(timer.getTimeValues().hours  +' <br/>hours');
    $('#timer .minutes').html(timer.getTimeValues().minutes  +' <br/>minutes' );
    $('#timer .seconds').html(timer.getTimeValues().seconds  +' <br/>seconds');
});

timer.addEventListener('targetAchieved', function (e) {
    $('#timer').html('KABOOM!!');
});
});
</script>
</html>