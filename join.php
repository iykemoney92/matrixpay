<?php $title = 'MatrixPay'; ?>
<html>
<head>
<?php include_once('head.php') ?>
<link href="css/signin.css" rel="stylesheet"/>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<main class="form-signin">
<h1 class="h1 mb-5"><?php echo $title ?></h1>
<h1 class="h3 mb-3 fw-normal">Join the club</h1>

  <form>
    <img class="mb-4"  src="img/auth-icons.png" alt=""  >
    <hr/>
    
  Already a member? <a href="login.php">Login</a>
  <br/><br/> <div class="alert alert-warning hidden" role="alert" id="warn1" style="display:none">
      Metamask is required to interact with contract. <a href="https://metamask.io/download">Get Metamask</a> 
    </div>
    <div class="form-floating">
      <input type="text" class="form-control" id="referralID" value="<?php echo $_GET['referralID'] != null ? $_GET['referralID'] : 1?>" placeholder="Referral ID">
      <label for="referralID" class="text-dark">Referral ID</label>
    </div>
    <p class="m-3"></p>
    <button class="w-100 btn btn-lg btn-primary" type="button"  onclick="register()">Join</button>
    <hr/>
  <p><a class="w-100 btn btn-lg btn-warning" href="https://link.trustwallet.com/open_url?coin_id=60&url=" id="trustBtn"><i class="bi bi-currency-bitcoin"></i>Open in TrustWallet</a>
    </p> 
    <p class="mt-5 mb-3 text-muted">© <?php echo date('Y') ?></p>
  </form>
  
</main>
</body>
<?php include('foot.php') ?>
<script>
 window.addEventListener('load',()=>{
   if(!checkWeb3){
     $('#warn1').show();
     loadWallet();
   }
 })
</script>
</html>