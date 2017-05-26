
var BgLayer=cc.Layer.extend({
        velocityRatio:3,//倍率
        velocityRatio_land:.3,//倍率
        velocityRatio_cloud:0.2,//倍率
        cloudsList:[],
        landspacesList:[],
        bulidsList:[],
        showBulidList:[],
        groundsList:[],
        currentIndex:0,
        init:function(){
            this._super();
            this.width=cc.director.getWinSize().width;
            this.height=cc.director.getWinSize().height;
             // 获得游戏屏幕的尺寸
            this.winsize = cc.director.getWinSize();

            // 获得游戏屏幕的尺寸
            this.winsize = cc.director.getWinSize();
            conf_.currentSpeed=conf_.speed*conf_.velocityRatio;
            //添加地面
            this.addGrounds();
            //添加房屋
            this.addBuilds();
            //添加小山陆地远景
            this.addlands();
            //添加云
            this.addclouds();
            this.moveobj=1;
            var gmaebg = cc.Sprite.create(s_css_bg);
            gmaebg.setAnchorPoint(0,0);
            /*
            gmaebg.width=this.winsize.width;
            gmaebg.height=this.winsize.height;
            */
            gmaebg.setScale(this.winsize.width/gmaebg.width);
            gmaebg.setPosition(0, 0);
            this.addChild(gmaebg,0);

        },
    bulidsMove:function(){
            if(!conf_.isStop){
                conf_.currentSpeed = conf_.speed * conf_.velocityRatio;
                //建筑运动
                for(var index in this.bulidsList) {

                    for(var i in this.bulidsList[index]) {
                        //如果是第一个的最后一个数组则检测是否已经快运行结束
                        var lastI=this.bulidsList[index].length-1;

                        this.bulidsList[index][i].x -= conf_.currentSpeed;
                    }
                }

                var lastIndex = this.bulidsList.length-1;
                var lasti = this.bulidsList[0].length-1;

                if(~~this.bulidsList[1][0].x<0){

                    this.bulidsList[0] =
                        this.bulidsList[0].sort(function(){ return 0.5 - Math.random() });
                    for(var v in this.bulidsList[0]){
                        this.bulidsList[0][v].x=
                        Number(v) * this.bulidsList[0][v].getBoundingBox().width+this.bulidsList[lastIndex][lasti].x+this.bulidsList[lastIndex][lasti].getBoundingBox().width;
                    }
                    this.bulidsList.push(this.bulidsList.shift());
                }




            }
        },
        addBuilds:function(){
            // 设置建筑物
            this.bulidsList[0]=[];
            this.bulidsList[1]=[];
            this.bulidsList[0].push(cc.Sprite.create(s_layer3_building_2));
            this.bulidsList[0] = this.bulidsList[0].sort(function(){ return 0.5 - Math.random() });
            this.bulidsList[0][0].setScale(0.8);
            this.buildPosY=this.groundHeight+this.bulidsList[0][0].getBoundingBox().height*0.78;

            for(var index in this.bulidsList[0]){
                this.bulidsList[0][index].setAnchorPoint(0,1);
                this.bulidsList[0][index].setScale(0.8);
                this.bulidsList[0][index].setPosition(-conf_.currentSpeed+~~index*this.bulidsList[0][index].getBoundingBox().width, this.buildPosY);
                this.addChild(this.bulidsList[0][index],3);


            }
            this.bulidsList[1].push(cc.Sprite.create(s_layer3_building_2));
            this.bulidsList[1] = this.bulidsList[1].sort(function(){ return 0.5 - Math.random() });

            for(var index in this.bulidsList[0]){
                this.bulidsList[1][index].setAnchorPoint(0,1);
                this.bulidsList[1][index].setScale(0.8);
                this.bulidsList[1][index].setPosition((~~index+this.bulidsList[0].length)*this.bulidsList[1][index].getBoundingBox().width, this.buildPosY);
                this.addChild(this.bulidsList[1][index],3);

            }
            this.schedule(this.bulidsMove,conf_.timer);
        },
        groundsMove:function(){
            //地面运动
            if(!conf_.isStop){

                    for(var index in this.groundsList) {
                        this.groundsList[index].x -= conf_.currentSpeed;

                    }
                if(this.groundsList[1].x  <0 ){
                    this.groundsList[0].x =this.groundsList[1].getBoundingBox().x + this.groundsList[1].getBoundingBox().width;
                    this.groundsList.push(this.groundsList.shift());
                }


            }
        },
        addGrounds: function () {
            // 设置地面
            this.groundsList.push(cc.Sprite.create(s_layer4_ground));
            this.groundsList.push(cc.Sprite.create(s_layer4_ground));

            for(var index in this.groundsList){
                this.groundsList[index].setAnchorPoint(0,1);
                this.groundsList[index].setScale(0.8);
                this.groundsList[index].setPosition(~~index*this.groundsList[index].getBoundingBox().width,this.groundsList[index].getBoundingBox().height);
                this.addChild(this.groundsList[index],3);
            }
            this.groundHeight=this.groundsList[0].getBoundingBox().height;
            conf_.groundHeight=this.groundHeight;
            this.schedule(this.groundsMove,conf_.timer);
        },
        addlands:function(){
            // 设置小山的背景
            this.landspacesList.push(cc.Sprite.create(s_layer2_landspace));
            this.landspacesList.push(cc.Sprite.create(s_layer2_landspace));
            this.landspacesList[0].setScale(1.1);
            this.landposY=this.groundHeight+this.landspacesList[0].height*1.02;

            for(var index in this.landspacesList){
                this.landspacesList[index].setAnchorPoint(0,1);
                this.landspacesList[index].setScale(1.1);
                this.landspacesList[index].setPosition(~~index*this.landspacesList[index].getBoundingBox().width, this.landposY );
                this.addChild(this.landspacesList[index],2);
            }

            this.schedule(this.landspacesMove,conf_.timer);
            this.moveobj=1;
        },
        landspacesMove:function(){
            if(!conf_.isStop){
                this.currentSpeed_land=conf_.speed*conf_.velocityRatio_land;
                for(var index in this.landspacesList){
                    this.landspacesList[index].x-=this.currentSpeed_land;
                }

                if(this.landspacesList[0].x<-this.currentSpeed_land){

                    this.landspacesList[this.landspacesList.length-1].x = this.winsize.width+this.landspacesList[this.landspacesList.length-1].getBoundingBox().width;
                    this.landspacesList.push(this.landspacesList.shift());
                }
            }
        },addclouds:function(){
        // 设置天空云朵元素
        this.cloudsList.push(cc.Sprite.create(s_layer1_clouds));
        this.cloudsList.push(cc.Sprite.create(s_layer1_clouds));
        this.cloudsList[0].setAnchorPoint(0,1);
        this.cloudsList[0].setScale(0.4);
        this.cloudPosY=this.buildPosY+this.cloudsList[0].getBoundingBox().height;
        for(var index in this.cloudsList){
            this.cloudsList[index].setAnchorPoint(0,1);
            this.cloudsList[index].setScale(0.4);
            this.cloudsList[index].setPosition(~~index*this.winsize.width*1.5, this.cloudPosY );
            this.addChild(this.cloudsList[index],1);
        }

        this.currentSpeed_cloud=conf_.speed*conf_.velocityRatio_cloud;
        this.schedule(this.cloudsMove,conf_.timer);
        this.moveobj=1;

    },
    cloudsMove:function(){
        if(!conf_.isStop){
            for(var index in this.cloudsList){
                this.cloudsList[index].x-=this.currentSpeed_cloud;
            }

            if(this.cloudsList[0].x<-this.currentSpeed_cloud){
                this.cloudsList[this.cloudsList.length-1].x = this.winsize.width*1.5;
                this.cloudsList.push(this.cloudsList.shift());
            }
        }
    }
});


