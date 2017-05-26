var conf_={
    speed:2.5,//单位m
    velocityRatio_hero:0.1,//英雄倍率
    velocityRatio:4,//前景倍率
    velocityRatio_land:2.5,//背景倍率
    velocityRatio_cloud:0.3,//云移动倍率
    velocityRatio_obastacle:5,//障碍物出现的时间间隔原数
    timer:0.042,
    timerCounter_:0,
    distance_:0,
    addObstacle_status:0,
    life:1,
    isStop:true,
    goldScore:0,
    distance:0,
    protcetCounter:0
};


var gameLayer={};
var HomeScene = cc.Scene.extend({
    onEnter:function(){
        this._super();

        var bgLayer = new BgLayer();
        this.addChild(bgLayer,0);
        bgLayer.init();

        gameLayer = new GameLayer();
        this.addChild(gameLayer,1);
        gameLayer.init();
    }
});

