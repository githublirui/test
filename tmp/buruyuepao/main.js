cc.game.onStart = function(){
var screenSize = cc.view.getFrameSize();
    var designSize = cc.size(screenSize.width,screenSize.height);
    

    cc.loader.resPath = "res";
    cc.view.setDesignResolutionSize(designSize.width, designSize.height, cc.ResolutionPolicy.SHOW_ALL);
    cc.view.setResolutionPolicy(cc.ResolutionPolicy.NO_BORDER);
    cc.view.adjustViewPort(true);

    cc.view.resizeWithBrowserSize(true);
    cc.director.setProjection(cc.Director.PROJECTION_2D);

    document.getElementById("Cocos2dGameContainer").className=document.getElementById("Cocos2dGameContainer").className+"hidden";
    //load resources
    cc.LoaderScene.preload(g_resources, function () {
        cc.director.runScene(new HomeScene());
        gameMain.loaderEnd();
    }, this);


};

cc.game.run();
