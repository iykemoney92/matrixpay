const MatrixPay = artifacts.require("MatrixPay");

module.exports = function(deployer) {
    deployer.deploy(MatrixPay, '0x7782711FA94c6991f63cE5f6AA86B622Dfbf8bcF');
};