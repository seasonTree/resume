const user = require('./user');

let mockArr = [user],
    mock = {};

for(var i = 0; i < mockArr.length; i++){
    var item = mockArr[i];

    mock = Object.assign(mock, item);
}

module.exports = mock;


