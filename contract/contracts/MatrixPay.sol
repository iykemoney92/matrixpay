pragma solidity >=0.4.23 <0.6.0;

contract MatrixPay {
    address public ownerWallet;
    address public feeWallet;
    struct PackageStruct {
        uint level;
        uint amount;
        uint userID;
        uint remSlot;
        bool isFulfiled;
        address wallet;
        bool swirled;
    }

    struct UserStruct {
        address wallet;
        bool isExist;
        uint id;
        uint referrerID;
        uint totEarned;
        uint bonusEarned;
        uint balance;
    }

    mapping(uint => uint) public PACKAGE_PRICE;
    mapping(uint => uint) public PAYOUT;
    mapping(address => UserStruct) public users;
    mapping(uint => PackageStruct) public packages;
    mapping(uint => address) public userList;
    uint public currUserID = 0;
    uint public packageID = 0;
    uint[] public FEE = [0.0005 ether, 0.005 ether, 0.05 ether, 0.5 ether, 5 ether];
    uint[] public REF_FEE = [0.0003 ether, 0.003 ether, 0.03 ether, 0.3 ether, 3 ether];
    uint public increment;

    event regEvent(address indexed _user, address indexed _referrer, uint _time);
    event buyPackageEvent(address indexed _user, uint _level, uint _time);
    event receivedEtherEvent(address indexed _sender, uint _amount);

    constructor(address _feeWallet) public {
        ownerWallet = msg.sender;
        feeWallet = _feeWallet;

        PACKAGE_PRICE[1] = 0.01 ether;
        PACKAGE_PRICE[2] = 0.1 ether;
        PACKAGE_PRICE[3] = 1 ether;
        PACKAGE_PRICE[4] = 10 ether;
        PACKAGE_PRICE[5] = 100 ether;

        PAYOUT[1] = 0.009 ether;
        PAYOUT[2] =  0.09 ether;
        PAYOUT[3] = 0.9 ether;
        PAYOUT[4] =  9 ether;
        PAYOUT[5] =  90 ether;
        //PackageStruct[] memory emptyPackageList;
        UserStruct memory userStruct;
        currUserID++;
        userStruct = UserStruct({
            wallet: msg.sender,
            isExist: true,
            id: currUserID,
            referrerID: 0,
            totEarned: 0,
            bonusEarned: 0,
            balance: 0
        });
        users[ownerWallet] = userStruct;
        userList[currUserID] = ownerWallet;

        for(uint i = 1; i <= 5; i++) {
            PackageStruct memory new_package;
            new_package  = PackageStruct({
                    level: i,
                    amount: PACKAGE_PRICE[i],
                    userID: currUserID,
                    isFulfiled: false,
                    remSlot: 2,
                    wallet: msg.sender,
                    swirled: true
            });
            packageID++;
            packages[packageID] = new_package;
            users[ownerWallet].balance = users[ownerWallet].balance + (PACKAGE_PRICE[i] * 2);
        }
    }

    function () external payable {
        emit receivedEtherEvent(msg.sender, msg.value);
    }

    function regUser(uint _referrerID) public payable {
        require(!users[msg.sender].isExist, 'User exist');
        require(_referrerID > 0 && _referrerID <= currUserID, 'Incorrect referrer Id');
        require(msg.value == PACKAGE_PRICE[1], 'Incorrect Value');

        UserStruct memory _referrer = users[userList[_referrerID]];
        if(_referrer.isExist){
            //give referral a bonus package
            PackageStruct memory new_package;
            new_package = PackageStruct({
                    level: 1,
                    amount: PACKAGE_PRICE[1],
                    userID: _referrerID,
                    isFulfiled: false,
                    remSlot: 2,
                    wallet: userList[_referrerID],
                    swirled: false
            });
            packageID++;
            packages[packageID] = new_package;
        }
        
        UserStruct memory userStruct;
        currUserID++;
        userStruct = UserStruct({
            wallet: msg.sender,
            isExist: true,
            id: currUserID,
            referrerID: _referrerID,
            totEarned: 0,
            bonusEarned: 0,
            balance: (PACKAGE_PRICE[1] * 2)
        });

        users[msg.sender] = userStruct;
        userList[currUserID] = msg.sender;

        //give slot to user
        PackageStruct memory new_package;
        new_package = PackageStruct({
                level: 1,
                amount: PACKAGE_PRICE[1],
                userID: currUserID,
                isFulfiled: false,
                remSlot: 2,
                wallet: msg.sender,
                swirled: false
        });
        packageID++;
        packages[packageID] = new_package;

        payForPackage(1, msg.sender);
        emit regEvent(msg.sender, userList[_referrerID], now);
    }

    function isUserExist(address _user) public view returns(bool)
    {
        return users[_user].isExist ==  true ? true: false;
    }
    
    function buyNewPackage(uint _level) public payable {
        require(users[msg.sender].isExist, 'User not exist'); 
        require(_level > 0 && _level <= 5, 'Incorrect level');
        require(msg.value == PACKAGE_PRICE[_level], 'Incorrect Value');
        payForPackage(_level, msg.sender);
        packageID++;
        packages[packageID] = PackageStruct({
                    level: _level,
                    amount: PACKAGE_PRICE[_level],
                    userID: users[msg.sender].id,
                    isFulfiled: false,
                    remSlot: 2,
                    wallet: msg.sender,
                    swirled: false
        });
        users[msg.sender].balance = users[msg.sender].balance + (PACKAGE_PRICE[_level] * 2);
        emit buyPackageEvent(msg.sender, _level, now);
    }

    function payForPackage(uint _level, address _user) internal {
        UserStruct memory referer;
        referer = users[userList[users[_user].referrerID]];
 
        if(referer.isExist){
            users[referer.wallet].bonusEarned = users[referer.wallet].bonusEarned + REF_FEE[_level];
            //require(address(uint160(referer.wallet)).transfer(REF_FEE[_level]),'Unable to pay referral');
        }
        //transfer fee
        //require(address(uint160(feeWallet)).send(FEE[_level]),'Unable to pay fee');
        //find a user with free package
        uint index = findNextEarner(_level,msg.sender,true);
        if(index == 0){
            index = findNextEarner(_level,msg.sender,false);
        }
        PackageStruct memory thePackage = packages[index];
        UserStruct memory nextEarner = users[thePackage.wallet];
        uint remSlot = packages[index].remSlot;
        packages[index].remSlot = remSlot - 1;
        if((remSlot - 1) == 0 ){
            packages[index].isFulfiled = true;
        } 
        users[nextEarner.wallet].totEarned = users[nextEarner.wallet].totEarned + PAYOUT[_level]; 
        users[nextEarner.wallet].balance = users[nextEarner.wallet].balance - PACKAGE_PRICE[_level]; 
        /*(bool sent, bytes memory transactionBytes) = address(uint160(users[nextEarner.wallet].wallet)).call{value:PAYOUT[_level]}('');
        require(sent,'Transfer failed');*/
        //bool sent = address(uint160(users[nextEarner.wallet].wallet)).send(PAYOUT[_level]);
        /*if(sent != true){
            address(uint160(users[nextEarner.wallet].wallet)).transfer(PYOUT[_level]);
        }*/
        address(uint160(users[nextEarner.wallet].wallet)).transfer(PAYOUT[_level]);
        return;
    }

    function findNextEarner(uint _level, address _caller, bool _swirled) public view  returns(uint) {
        uint nextEarner; 
        bool userFound = false;
        for(uint i = 1; i<= packageID; i++){
            PackageStruct memory currPackage = packages[i];
            if(currPackage.level == _level){
                if(!currPackage.isFulfiled){
                    if(currPackage.wallet != _caller){
                        if(_swirled == true)
                        {
                            if(currPackage.swirled == true)
                            {
                                userFound = true;
                                nextEarner = i;
                                break;
                            }
                        }else{
                            userFound = true;
                            nextEarner = i;
                            break;
                        }
                            
                        
                    }
                }
            }
        }
        //require(userFound != false, 'No Earner Found');
        return nextEarner;
    }

    function swirl(uint _userID, uint _level ) public payable {
        require(msg.sender == ownerWallet, 'Only owner can call function');
        require(_userID > 0 && _userID <= currUserID, 'No user found');
        PackageStruct memory new_package;
            new_package  = PackageStruct({
                    level: _level,
                    amount: PACKAGE_PRICE[_level],
                    userID: _userID,
                    isFulfiled: false,
                    remSlot: 2,
                    wallet: userList[_userID],
                    swirled: true
            });
        packageID++;
        packages[packageID] = new_package;
        users[userList[_userID]].balance = users[userList[_userID]].balance + (PACKAGE_PRICE[_level] * 2);
        }
    
}