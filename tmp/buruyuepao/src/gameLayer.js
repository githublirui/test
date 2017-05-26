/**
 * Created by Administrator on 2014/9/24 0024.
 */
var GameLayer=cc.Layer.extend({
    winsize:null,
    isdead:false,
    obastacleImageArray:[//障碍物
        s_obstacle_banana,
        s_obstacle_coke,
        s_obstacle_dog,
        s_obstacle_shit],
    obastacleList:[],//障碍物存储
    targetGoldList:[],
    baodarenSprite:null,
    init:function(){
        this._super();
        this.width=cc.director.getWinSize().width;
        this.height=cc.director.getWinSize().height;
        // 获得游戏屏幕的尺寸
        this.winsize = cc.director.getWinSize();
        if(this.baodarenSprite==null){
        	this.addHero();
        }
        this.currentSpeed_obastacle=conf_.velocityRatio_obastacle/conf_.speed;

        this.schedule(this.timerCounter,conf_.timer);
        this.schedule(this.obastacleMove,conf_.timer);
       /* this.schedule(this.targetGoldMove,conf_.timer);*/
        this.scheduleUpdate();
         self=this;
        /*事件注入*/
        cc.eventManager.addListener({
            event: cc.EventListener.TOUCH_ONE_BY_ONE,
            swallowTouches: true,
            onTouchBegan: this.onTouchBegan,
            onTouchMoved: this.onTouchMoved,
            onTouchEnded: this.onTouchEnded
        }, this);
    },reStartGame:function(isCountinue){
        if(!isCountinue){
        	
            conf_.speed=2.5;
         /*   conf_.goldScore=0;*/
            conf_.distance=0;
            conf_.timerCounter_=0;
            conf_.currentSpeed = conf_.speed * conf_.velocityRatio;
           /* gameMain.setGold(conf_.goldScore);*/
            gameMain.setDistance(conf_.distance);
            for(var index in this.obastacleList){
                this.deleteSprite(this.obastacleList[index]);
            }
          /*  for(var index in this.targetGoldList){
                this.deleteSprite(this.targetGoldList[index]);
            }*/
            
        }
        this.baodarenSprite.runAction(cc.rotateTo(.1,0));
        this.scheduleOnce(function(){conf_.isStop = true;gameMain.openlose();},0.3);
        this.unscheduleAllCallbacks();
        this.init();

    },
    openProtcet:function(){
        conf_.protcetCounter=100;
        var delay = cc.delayTime(0.05);
        var action = cc.fadeIn(0.05);

        var action_back =action.reverse();
        fade = cc.sequence(action, delay.clone(), action_back).repeatForever();
        this.baodarenSprite.flashAction = this.baodarenSprite.runAction( fade);
    },
    onTouchMoved:function(touch,event){
    },
    update:function(){
        if(!conf_.isStop){
        	/* console.log(this.baodarenSprite.opacity);*/
        	if(conf_.distance_>5000){
                conf_.speed=5;
            }else if(conf_.distance_>4000){
                conf_.speed=4.5;
            }else if(conf_.distance_>3000){
                conf_.speed=4;
            }else if(conf_.distance_>2000){
                conf_.speed=3.5;
            }else if(conf_.distance_>1000){
                conf_.speed=3;
            }
            else{
            	conf_.speed=2.5;
            }
            conf_.distance_ = ~~(conf_.timerCounter_ * conf_.speed);
            if(conf_.protcetCounter==0){
                this.baodarenSprite._actionManager.removeAction(this.baodarenSprite.flashAction );
                this.baodarenSprite.opacity=255;
            }else{
                conf_.protcetCounter--;
            }
            if(this.obastacleList.length==0||(this.obastacleList.length<2&&this.obastacleList[0].x<this.winsize.width/2)){
                //添加障碍物和金币
                this.addObstacle();
            }
            //碰撞检测
            //吃金币
     /*       for(var index in this.targetGoldList){
                var targetGold=this.targetGoldList[index].getBoundingBox();
                var baodaren=this.baodarenSprite.getBoundingBox();
                if(cc.rectIntersectsRect(baodaren,targetGold)){

                    if(!this.targetGoldList[index].isfirst){
                        this.targetGoldList[index].isfirst=true;
                        conf_.goldScore++;
                        var actionFly= cc.MoveTo.create(.5,cc.p(self.winsize.width,self.winsize.height));
                        var actionMoveDone = cc.CallFunc.create(this.deleteSprite,this);
                        gameMain.setGold(conf_.goldScore);
                        this.targetGoldList[index].runAction(cc.Sequence.create(actionFly,actionMoveDone));
                    }
                }
            }*/

            if(conf_.protcetCounter==0) {//保护过程中不会被绊倒
                //被绊倒
                for (var index in this.obastacleList) {
                    var obastacle = this.obastacleList[index].getBoundingBox();
                    var obastacleRect = cc.rect(obastacle.x + obastacle.width * 0.1, obastacle.y - obastacle.height * 0.1, obastacle.width * 0.3, obastacle.height * 0.3);


                    var baodaren = this.baodarenSprite.getBoundingBox();
                    var baodarenRect = cc.rect(baodaren.x, baodaren.y, baodaren.width * 0.5, baodaren.height * 0.8);

                    if (cc.rectIntersectsRect(baodarenRect, obastacleRect)) {
                        this.baodarenSprite.runAction(cc.Sequence.create(cc.DelayTime.create(0.2),cc.rotateTo(.1,-270)));
                        this.scheduleOnce(function () {
                            conf_.isStop = true;
                        },.1);
                        this.scheduleOnce(function () {
                        	//被绊倒了
                            gameMain.openlose();//被注释的代码里包含倒计时和重新开始自己测试放开就好

                        },1);
                    }
                }
            }

        }
    },timerCounter: function () {
        if(!conf_.isStop) {
            conf_.timerCounter_++;
            conf_.distance = conf_.timerCounter_;
            gameMain.setDistance(conf_.distance);
        }
    },
    onTouchEnded:function(touch, event){

    },onTouchBegan:function(touch,event){
        if(self.baodarenPosY.toFixed(2)==self.baodarenSprite.y.toFixed(2)){
            var actionMoveUp= cc.MoveTo.create(1/conf_.speed<0.2?0.2:1/conf_.speed,cc.p(self.baodarenSprite.x,self.baodarenSprite.y+self.baodarenSprite.getBoundingBox().height));
            var actionMoveDown= cc.MoveTo.create(1/conf_.speed<0.1?0.1:1/conf_.speed,cc.p(self.baodarenSprite.x,self.baodarenPosY));
            self.baodarenSprite.runAction(cc.Sequence.create(actionMoveUp,actionMoveDown));
        }
        return true;
    },addHero:function(){
        //添加英雄到屏幕
        cc.spriteFrameCache.addSpriteFrames(s_sprite_baodaren_list);
        var animFrames = [];
        for (var i = 0; i <= 7; i++) {
            var str =  "people_"+i + ".png";
            var frame = cc.spriteFrameCache.getSpriteFrame(str);
            animFrames.push(frame);
        }
        this.currentSpeed_hero=conf_.velocityRatio_hero/conf_.speed;

        var animation = cc.Animation.create(animFrames, this.currentSpeed_hero);
        var runningAction = cc.RepeatForever.create(cc.Animate.create(animation));
        this.baodarenSprite= cc.Sprite.create("#people_0.png");
        this.baodarenSprite.setScale(0.5);
        this.baodarenPosY=conf_.groundHeight*0.8;
        this.baodarenSprite.attr({x:this.winsize.width/2, y:this.baodarenPosY});
        this.baodarenSprite.runAction(runningAction);
        this.baodarenSprite.setTag(9);

        this.addChild(this.baodarenSprite,6);


    },addObstacle:function(){

        //随机产生一个障碍物
        var randomIndex = Math.floor(Math.random() * this.obastacleImageArray.length);
        var obastacle = cc.Sprite.create(this.obastacleImageArray[randomIndex]);
        obastacle.setAnchorPoint(1,0);
        obastacle.setScale(0.4);
        obastacle.setTag(7);
        this.obastaclePosY=conf_.groundHeight*0.5 ;
        obastacle.setPosition(this.winsize.width+obastacle.getBoundingBox().width, this.obastaclePosY );
        this.addChild(obastacle,5);
        this.obastacleList.push(obastacle);

   },obastacleMove: function () {
        if(!conf_.isStop) {
            //障碍物的移动
            for (var index in this.obastacleList) {
                this.obastacleList[index].x -= conf_.currentSpeed;
                if (~~this.obastacleList[index].x < -this.obastacleList[index].getBoundingBox().width) {
                    //如果障碍物离开屏幕则删除
                    this.deleteSprite(this.obastacleList[index]);
                }
            }
        }
    },deleteSprite:function(sprite){
        this.removeChild(sprite,true);
        if(sprite.getTag()==7){
            var index = this.obastacleList.indexOf(sprite);
            if(index > -1 ){
                this.obastacleList.splice(index, 1);
            }
        }else if(sprite.getTag()==8){
            var index = this.targetGoldList.indexOf(sprite);
            if(index > -1 ){
                this.targetGoldList.splice(index, 1);
            }
        }else if(sprite.getTag()==9){
            /*var index = this.targetGoldList.indexOf(sprite);
            if(index > -1 ){
                this.targetGoldList.splice(index, 1);
            }*/
        }


    }
});