var defaultParam = {
    UserPhoneAuth: {
        method:'GetCode'
        , userId:'5000123'
        , phone:'13282987627'
    },
    GetWebSearchCount: {
        cityScriptIndex:0
        , keywords:'机'
        , categoryId:'14'
    },
    VotePost: {
        categoryId: '14'
        , majorCategoryScriptIndex: '0'
        , postId: '29791423'
        , agent: 0
        , cityScriptIndex: 0
        , reasonId: 1
        , content: '帖子信息不正确'
    }
};

InterfaceConfig = function() {};
InterfaceConfig.getDefaultParam = function(interfaceName) {
    var jsonStr = '';
    var interfaceParam = defaultParam[interfaceName];
    for (var item in interfaceParam) {
        jsonStr += ',"' + item + '" : "' + interfaceParam[item] + '"\n';
    }
    jsonStr = jsonStr.substr(1, jsonStr.length);
    return jsonStr;
};
