<?php $title = 'RubyCash'; ?>
<html>
<head>
<?php include_once('head.php') ?>
<link href="css/cover.css" rel="stylesheet"/>
</head>
<body class=" text-center text-white bg-dark">
<main class="cover-container  w-100 p-3 mx-auto ">
<h1 class="h1 mb-5"><?php echo $title ?></h1>
<h1 class="h3 mb-3 fw-normal">🚀 Champ!!!</h1>
<p><a href="login.php">Logout</a></p>


<div class="container mt-5 mb-5 border border-primary pt-2 pb-4">
<div class="" id="home">
    <div class="container mt-3">
      <div class="row">
      <div class="col-md-4">
      <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">ID</h5>
    <p class="card-text"><span id="id"></span></p>
  </div>
</div></div><div class="col-md-4">
      <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Address</h5>
    <p class="card-text"><span id="address"></span></p>
  </div>
</div></div><div class="col-md-4">
      <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Earned</h5>
    <p class="card-text"><span id="totEarned"></span> ETH</p> 
  </div>
</div></div>
      </div>
    </div>
  </div>
  <div class="mt-2 mb-2 border border-secondary">
   <div class="row">
     <div class="col-md-4">
       <p>ReferredBy</p>
       <span id="referredBy"></span>
     </div>
     <div class="col-md-8">
       <p>Affiliate Link</p>
       https://rubycash.life/join.php?referralID=<span id="myRefID"></span>
     </div>
   </div>
  </div>
  <div class="mt-2 mb-2 border border-warning">
    <div class="row">
      <div class="col-md-6">
        <p>Balance</p>
        <span id="balance"></span>ETH
      </div>
      <div class="col-md-6">
        <p>Bonus Earned</p>
        <span id="bonusEarned"></span>ETH
      </div>  
    </div>
</div>
  <div id="packages">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#purchaseModal"><i class="bi bi-bag-plus"></i>Purchase new package</button>
</div>
</div><div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title " id="exampleModalLabel">Purchase new package</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     <select class="form-select" id="level" aria-label="Default select example">
  <option selected="" value="0">Select Package</option>
  <option value="4">100 ETH</option>
  <option value="3">10 ETH</option>
  <option value="2">1 ETH</option><option value="1">0.1 ETH</option>
<option value="0">0.01 ETH</option>
</select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="buyNewPackage(parseInt($('#level').val()))">Make Purchase</button>
      </div>
    </div>
  </div>
</div><div class="">
    <table class="table table-responsive text-white">
        <thead>
            <tr><th>ID</th>
<th>Package Amount</th>
<th>Slot Remaining</th>
<th>IsFulfiled</th></tr>
        </thead>
<tbody id="mypackages">
    
</tbody>
    </table>
</div>
</main>
<?php include('foot.php') ?>
<script>
 window.addEventListener('load',async()=>{
  try { await loadWallet() }
  catch(e){ toastr.error('Account not found');window.location.replace('login.php') };
  await loadContract();
  var result = await rubyCashContract.methods.isUserExist(account).call();
  if(result){
  await loadHome(); 
  }else{ 
    window.location.replace('join.php');
  }
 })
</script>
</body>
</html>