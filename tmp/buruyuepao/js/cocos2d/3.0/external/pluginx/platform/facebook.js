plugin.extend('facebook', {
    name: "",
    version: "",
    loggedIn: false,
    userInfo: null,

    HttpMethod: {
        'Get': 'get',
        'Post': 'post',
        'Delete': 'delete'
    },

    ctor: function(config){
        this.name = "facebook";
        this.version = "1.0";
        this.userInfo = {};

        if (!FB) {
            return;
        }

        var self = this;
        //This configuration will be read from the project.json.
        FB.init(config);
        FB.getLoginStatus(function(response) {
            if (response && response.status === 'connected') {
                //login
                self._isLogined = true;
                //save user info
                self.userInfo = response.authResponse;
            }else{
                self._isLogined = false;
            }
        });

        plugin.FacebookAgent = this;
    },

    /**
     * @returns {FacebookAgent}
     */
    getInstance: function(){
        return this;
    },

    /**
     * @param {Function} callback
     *  callback @param {Number} code
     *  callback @param {String} mag
     */
    login: function(callback){
        var self = this;
        FB.login(function(response) {
            if (response.authResponse) {
                //save user info
                self.userInfo = response.authResponse;
                typeof callback === 'function' && callback(0, "login success");
            } else {
                typeof callback === 'function' && callback(1, "login failed");
            }
        }, { scope: '' });
    },

    isLogedIn: function(callback){
        return this.isLoggedIn(callback);
    },

    /**
     * @param {Function} callback
     * @return {Boolean}
     */
    isLoggedIn: function(callback){
        var self = this;
        FB.getLoginStatus(function(response) {
            if (response && response.status === 'connected') {
                //login - save user info
                self.userInfo = response.authResponse;
                typeof callback === 'function' && callback(0, "logged in");
            }else{
                typeof callback === 'function' && callback(1, "logged out");
            }
        });
    },

    /**
     * @param {Function} callback
     */
    logout: function(callback){
        var self = this;
        FB.logout(function(response) {
            if(response.authResponse){
                // user is now logged out
                self.userInfo = {};
                typeof callback === 'function' && callback(0, "logout success");
            }else{
                typeof callback === 'function' && callback(1, "logout failed");
            }
        });
    },

    /**
     * @param permissions
     * @param callback
     */
    requestPermissions: function(permissions, callback){
        var permissionsStr = permissions.join(',');
        var self = this;
        FB.login(function(response){
            if (response.authResponse) {
                var permissList = response.authResponse['grantedScopes'].split(",");
                //save user info
                self.userInfo = response.authResponse;
                typeof callback === 'function' && callback(0, JSON.stringify(permissList));
            } else {
                typeof callback === 'function' && callback(1, "request failed");
            }
        }, {
            scope: permissionsStr,
            return_scopes: true
        });
    },

    /**
     * @param {Function} callback
     */
    requestAccessToken: function(callback){
        if(typeof callback !== 'function'){
            return;
        }

        if(this.userInfo.accessToken){
            callback(0, this.userInfo.accessToken);
        }else{
            var self = this;
            FB.getLoginStatus(function(response) {
                if (response && response.status === 'connected') {
                    //login - save user info
                    self.userInfo = response.authResponse;
                    callback(0, response.authResponse.accessToken);
                }else{
                    callback(1, undefined);
                }
            });
        }
    },

    /**
     * @param info
     * @param callback
     */
    share: function(info, callback){
        FB.ui({
                method: 'share',
                name: info['title'],
                caption: info['caption'],
                description: info['text'],
                href: info['link'],
                picture: info['imageUrl']
            },
            function(response) {
                if (response) {
                    if(response.post_id)
                        typeof callback === 'function' && callback(0, JSON.stringify(response));
                    else
                        typeof callback === 'function' && callback(3, JSON.stringify(response));
                } else {
                    typeof callback === 'function' && callback(4, JSON.stringify(response));
                }
            });
    },

    /**
     * @param info
     * @param callback
     */
    dialog: function(info, callback){
        if(!info){
            return;
        }

        info['method'] = info['dialog'];
        delete info['dialog'];

        info['name'] = info['site'] || info['name'];
        delete info['site'];

        info['href'] = info['siteUrl'] || info['link'];
        delete info['siteUrl'];
        delete info['link'];

        info['image'] = info['imageUrl'] || info['imagePath'] || info['photo'] || info['picture'] || info['image'];
        delete info['imageUrl'];
        delete info['imagePath'];
        delete info['photo'];


        info['caption'] = info['title'] || info['caption'];
        delete info['title'];

        info['description'] = info['text'] || info['description'];
        delete info['text'];
        delete info['description'];

        if(info['method'] == 'share_open_graph' && info['url']){
            if(info['url']){
                var obj = {};
                if(info["preview_property"])
                    obj[info["preview_property"]] = info["url"];
                else
                    obj["object"] = info["url"];

                for(var p in info){
                    if(p != "method" && p != "action_type" && p != "action_properties"){
                        info[p] && (obj[p] = info[p]);
                        delete info[p];
                    }
                }

                info['action_properties'] = JSON.stringify(obj);
            }else{
                return;
            }
        }else{
            if(!info['href']){
                return;
            }
        }

        if(
            info['method'] != 'share_open_graph' &&
            info['method'] != 'share_link' &&
            info['method'] != 'apprequests'
        ){
            cc.log('web is not supported what this it method');
            return;
        }

        FB.ui(info,
            function(response) {
                if (response) {
                    if(response.post_id)
                        typeof callback === 'function' && callback(0, JSON.stringify(response));
                    else
                        typeof callback === 'function' && callback(response.error_code, response.error_message);
                } else {
                    typeof callback === 'function' && callback(1, "Unknow error");
                }
            });
    },

    /**
     * @param {String} path
     * @param {Number} httpmethod
     * @param {Object} params
     * @param {Function} callback
     */
    request: function(path, httpmethod, params, callback){
        FB.api(path, httpmethod, params, function(response){
            if(response.error){
                callback(response.error.code, JSON.stringify(response))
            }else{
                callback(0, JSON.stringify(response));
            }
        });
    },

    destroyInstance: function(){},

    /**
     * @param {Object} info
     * @param {Function} callback
     */
    pay: function(info, callback){
        /*
         * Reference document
         * https://developers.facebook.com/docs/payments/reference/paydialog
         */

        info.method = 'pay';

        FB.ui(info, function(response) {
            if(response.error_code){
                callback(response.error_code, JSON.stringify(response));
            }else{
                callback(0, JSON.stringify(response));
            }
        })
    }
});