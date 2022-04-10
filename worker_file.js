async function wGetPackges(args) {
    var packageID = await args.contract.methods.packageID().call();
    var mypackages = [];
    for (var i = 1; i <= packageID; i++) {
        var package = await args.contract.methods.packages(i).call();
        if (package.wallet.toUpperCase() == args.account.toUpperCase()) {
            mypackages.push({...package, id: i });
        }
    }
    postMessage(mypackages);
}
onmessage = function(event) {
    wGetPackges(event.data);
}