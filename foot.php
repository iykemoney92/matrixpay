<!-- JavaScript Bundle with Popper -->
<script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/toastr.min.js"></script>
<script src="js/sweetalert2.min.js"></script>
<script src="js/web3.min.js"></script>
<script src="js/easytimer.min.js"></script>
<script>
var accounts;
var account;
var matrixPayContract;
const PACKAGE_PRICE = [0.01, 0.1, 1, 10, 100];
var abi = [
    {
      "inputs": [
        {
          "internalType": "address",
          "name": "_feeWallet",
          "type": "address"
        }
      ],
      "payable": false,
      "stateMutability": "nonpayable",
      "type": "constructor"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "_user",
          "type": "address"
        },
        {
          "indexed": false,
          "internalType": "uint256",
          "name": "_level",
          "type": "uint256"
        },
        {
          "indexed": false,
          "internalType": "uint256",
          "name": "_time",
          "type": "uint256"
        }
      ],
      "name": "buyPackageEvent",
      "type": "event"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "_sender",
          "type": "address"
        },
        {
          "indexed": false,
          "internalType": "uint256",
          "name": "_amount",
          "type": "uint256"
        }
      ],
      "name": "receivedEtherEvent",
      "type": "event"
    },
    {
      "anonymous": false,
      "inputs": [
        {
          "indexed": true,
          "internalType": "address",
          "name": "_user",
          "type": "address"
        },
        {
          "indexed": true,
          "internalType": "address",
          "name": "_referrer",
          "type": "address"
        },
        {
          "indexed": false,
          "internalType": "uint256",
          "name": "_time",
          "type": "uint256"
        }
      ],
      "name": "regEvent",
      "type": "event"
    },
    {
      "payable": true,
      "stateMutability": "payable",
      "type": "fallback"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "FEE",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "PACKAGE_PRICE",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "PAYOUT",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "REF_FEE",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [],
      "name": "currUserID",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [],
      "name": "feeWallet",
      "outputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [],
      "name": "increment",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [],
      "name": "ownerWallet",
      "outputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [],
      "name": "packageID",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "packages",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "level",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "amount",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "userID",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "remSlot",
          "type": "uint256"
        },
        {
          "internalType": "bool",
          "name": "isFulfiled",
          "type": "bool"
        },
        {
          "internalType": "address",
          "name": "wallet",
          "type": "address"
        },
        {
          "internalType": "bool",
          "name": "swirled",
          "type": "bool"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "name": "userList",
      "outputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "address",
          "name": "",
          "type": "address"
        }
      ],
      "name": "users",
      "outputs": [
        {
          "internalType": "address",
          "name": "wallet",
          "type": "address"
        },
        {
          "internalType": "bool",
          "name": "isExist",
          "type": "bool"
        },
        {
          "internalType": "uint256",
          "name": "id",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "referrerID",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "totEarned",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "bonusEarned",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "balance",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": false,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "_referrerID",
          "type": "uint256"
        }
      ],
      "name": "regUser",
      "outputs": [],
      "payable": true,
      "stateMutability": "payable",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "address",
          "name": "_user",
          "type": "address"
        }
      ],
      "name": "isUserExist",
      "outputs": [
        {
          "internalType": "bool",
          "name": "",
          "type": "bool"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": false,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "_level",
          "type": "uint256"
        }
      ],
      "name": "buyNewPackage",
      "outputs": [],
      "payable": true,
      "stateMutability": "payable",
      "type": "function"
    },
    {
      "constant": true,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "_level",
          "type": "uint256"
        },
        {
          "internalType": "address",
          "name": "_caller",
          "type": "address"
        },
        {
          "internalType": "bool",
          "name": "_swirled",
          "type": "bool"
        }
      ],
      "name": "findNextEarner",
      "outputs": [
        {
          "internalType": "uint256",
          "name": "",
          "type": "uint256"
        }
      ],
      "payable": false,
      "stateMutability": "view",
      "type": "function"
    },
    {
      "constant": false,
      "inputs": [
        {
          "internalType": "uint256",
          "name": "_userID",
          "type": "uint256"
        },
        {
          "internalType": "uint256",
          "name": "_level",
          "type": "uint256"
        }
      ],
      "name": "swirl",
      "outputs": [],
      "payable": true,
      "stateMutability": "payable",
      "type": "function"
    }
  ];
var contractAddress = '0x84475f151EB47F3052F0F6434a8C97C550495587';
var checkWeb3 = () => {
    if (typeof window.ethereum !== 'undefined') {
    return true;
}else{
    return false;
}
}
var loadWallet = async () => {
if (typeof web3 !== 'undefined') {
        web3 = new Web3(web3.currentProvider);
    } else {
        // set the provider you want from Web3.providers
        web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
}
accounts = await ethereum.request({ method: 'eth_requestAccounts' });
account = accounts[0];
window.ethereum.on("accountsChanged", function (accounts) {
    accounts = accounts;
    account = accounts[0];
    window.location.reload();
  });
}

var loadContract = async () => {
    matrixPayContract = new web3.eth.Contract(abi,contractAddress);
}

var pollAccountChanged = () => {
    setTimeout(function () {
  if (!window.ethereum || typeof window.ethereum.on != "function") {
    return false;
  } 
  window.ethereum.on("accountsChanged", function (accounts) {
    accounts = accounts;
    account = accounts[0];
    window.location.reload();
  });
},1500); 
}

var login = async() => {
  await loadWallet();
  await loadContract();
  var result = await matrixPayContract.methods.isUserExist(account).call();
  if(result){
    //set cookie
    window.location.replace('home.php');
  }else{
    toastr.error('user not found');
  }
}

var register = async() => {
  await loadWallet();
  await loadContract();
  var userExist = await matrixPayContract.methods.isUserExist(account).call();
  if(userExist){
    toastr.error('Account already exists');
  }else{
  var refID = $('#referralID').val() != null ? $('#referralID').val() : 1;
  var result = await matrixPayContract.methods.regUser(refID.toString()).send({from: account, value: web3.utils.toWei(PACKAGE_PRICE[0].toString())});
  window.location.replace('home.php'); }
}

var getAllPackages = async() => {
  var packageID = await matrixPayContract.methods.packageID().call();
  var mypackages = [];
  for(var i = 1; i<= packageID; i++){
    var package = await matrixPayContract.methods.packages(i).call();
    if(package.wallet.toUpperCase() == account.toUpperCase()){
      mypackages.push({...package, id:i});
    }
  }
  return mypackages;
}

var wGetAllPackages = async () => {
  let worker = new Worker('worker_file.js');
  worker.onmessage = function(event){
    console.log(event);
  }
  worker.postMessage({args:{contract:matrixPayContract,account:account}});
}

var testWorker = (data) => {
  let worker = new Worker('test_worker.js');
  worker.onmessage = function(event){
    console.log(event);
  }
  worker.postMessage(data);
}

var getUser = async() => {
  var user = await matrixPayContract.methods.users(account).call();
  return user;
}

var buyNewPackage = async(level) => {
  var result = await matrixPayContract.methods.buyNewPackage(level + 1).send({from: account, value: web3.utils.toWei(PACKAGE_PRICE[level].toString())});
  $('#purchaseModal').modal('hide');
  await loadHome();
  return result;
}

var decorateHome = (totEarned,address,id,packages,referredBy,myRefID,balance,bonusEarned) => {
  $('#totEarned').text(totEarned);
  $('#address').text(address);
  $('#id').text(id);
  $("#referredBy").text(referredBy);
  $('#myRefID').text(myRefID);
  $('#balance').text(balance);
  $('#bonusEarned').text(bonusEarned);
  var packageUI = '';
  packages.map((package)=>{
    packageUI += '<tr><td>'+package.id+'</td><td>'+web3.utils.fromWei(package.amount.toString())+' eth</td><td>'+package.remSlot+'</td><td><span class="badge '+(package.isFulfiled ? 'bg-success': 'bg-info')+'">'+(package.isFulfiled ? 'completed': 'pending')+'</span></td></tr>';
  })
  $('#mypackages').html(packageUI);
}

var loadHome = async () => {
  var user = await getUser();
  var _account = account.substr(0,6)+'...'+account.substr((account.length-6),account.length);
  var referralAddr = await matrixPayContract.methods.userList(user.referrerID).call();
  referralAddr = referralAddr.substr(0,6)+'...'+referralAddr.substr((referralAddr.length-6),referralAddr.length)
  
  var packages = [];
  getAllPackages().then(async (e)=>{
    packages = e; 
    decorateHome(web3.utils.fromWei(user.totEarned),_account,user.id,packages,referralAddr,user.id,web3.utils.fromWei(user.balance),web3.utils.fromWei(user.bonusEarned));
  });
  decorateHome(web3.utils.fromWei(user.totEarned),_account,user.id,packages,referralAddr,user.id,web3.utils.fromWei(user.balance),web3.utils.fromWei(user.bonusEarned));
  toastr.success('🚀Updated!!!');
}
</script>