<?xml version="1.0" encoding="UTF-8"?>
<krpano onstart="action(start);" >
    <skin_settings uishowpic="" />
    <skin_settings openautonext="0" />
    <autorotate enabled="false" waittime="1" accel="1.5" speed="5" horizon="0"/>
    <action name="start">
        delayedcall(autonexttimer,15, autonextscene(););
        loadscene(scene1, null, MERGE);
    </action>
    <textstyle name="DEFAULT" effect="" bordercolor="0xF6F6F6" backgroundcolor="0xFEFEFE" background="True" edge="bottom" blendmode="NORMAL" alpha="0.8" fadeintime="0" fadetime="0" showtime="0.1" noclip="False" yoffset="-3" xoffset="0" textalign="none" origin="cursor" textcolor="0x000000" border="True" italic="False" bold="False" fontsize="12" font="Arial, Helvetica, sans-serif"/>
    <layer name="introimage" url="%SWFPATH%introimage.png" alpha="0.8" align="center" onclick="hideintroimage();" keep="true" visible="true" />
    <action name="hideintroimage">if(layer[introimage].enabled,set(layer[introimage].enabled,false);tween(layer[introimage].alpha, 0.0, 0.5, default, removelayer(introimage)););</action>
    <plugin zorder="20000" visible="false" name="closeautonext" keep="true" url="%SWFPATH%/autonextclose.png" align="top" x="0" y="10" alpha="1" scale="1" onclick="autonextchange();" />
    <scene name="scene1" scenetitle="一楼厨房" thumburl="%SWFPATH%/images/scene1/thumb.jpg" nowpic="" onstart="thumb_start(1);action(startscene);set(control.mousetype, moveto);" >
        <events onviewchange="if(view.vlookat GT 90,set(view.vlookat,90););if(view.vlookat LT -90,set(view.vlookat,-90););" />
        <view fovtype="MFOV" fov="100" hlookat="0" fovmin="30" fovmax="130" limitview="range" hlookatmin="-180" hlookatmax="180" vlookatmin="-90" vlookatmax="90" />
        <preview url="%SWFPATH%/images/scene1/preview.jpg" />
        <image type="CUBE"  progressive="false">
        <left url="%SWFPATH%/images/scene1/pano_left.jpg" />
        <front url="%SWFPATH%/images/scene1/pano_front.jpg" />
        <right url="%SWFPATH%/images/scene1/pano_right.jpg" />
        <back url="%SWFPATH%/images/scene1/pano_back.jpg" />
        <up url="%SWFPATH%/images/scene1/pano_up.jpg" />
        <down url="%SWFPATH%/images/scene1/pano_down.jpg" />
        <mobile>
            <left url="%SWFPATH%/images/scene1/mb_left.jpg" />
            <front url="%SWFPATH%/images/scene1/mb_front.jpg" />
            <right url="%SWFPATH%/images/scene1/mb_right.jpg" />
            <back url="%SWFPATH%/images/scene1/mb_back.jpg" />
            <up url="%SWFPATH%/images/scene1/mb_up.jpg" />
            <down url="%SWFPATH%/images/scene1/mb_down.jpg" />
        </mobile>
        <tablet>
            <left url="%SWFPATH%/images/scene1/tb_left.jpg" />
            <front url="%SWFPATH%/images/scene1/tb_front.jpg" />
            <right url="%SWFPATH%/images/scene1/tb_right.jpg" />
            <back url="%SWFPATH%/images/scene1/tb_back.jpg" />
            <up url="%SWFPATH%/images/scene1/tb_up.jpg" />
            <down url="%SWFPATH%/images/scene1/tb_down.jpg" />
        </tablet>
        </image>
        <hotspot name="spot1" devices="all" url="%SWFPATH%/spot/1446037371Bew4cW.jpg" capture="False" enabled="True" visible="True" rotatewithview="False" distorted="True" smoothing="True" scale="0.1499999999999997" atv="" ath="0" rz="4" ry="-1" rx="6" onclick="openurl(http://baidu.com,_blank);" onhover="showtext('红包测试');" onover="" onout="" />
        <action name="startscene">
            action(activatespot,scene1,get(plugin[scene1].rote));
        </action>
    </scene>
    <contextmenu fullscreen="true" enterfs="全屏" exitfs="退出全屏">
        <item caption="旋转开启/暂停" onclick="action(autogochange);" separator="true" />
        <item caption="自动切换开启/暂停" onclick="action(autonextchange);"/>
    </contextmenu>
    <plugin name="map" url="%SWFPATH%/map/" keep="true" align="lefttop" x="0"  y="0" handcursor="false" scalechildren="true" width="" height="" alpha="1" />
    <plugin name="scene1" url="%SWFPATH%/map/mappoint.png" keep="true" parent="map" align="lefttop" edge="center" rote="0" zorder="2" onclick="loadscene(scene1, null, MERGE, BLEND(1));" x="-500"  y="-500" onhover="showtext('一楼厨房');" />
    <plugin name="activespot" url="%SWFPATH%/map/mappointactive.png" keep="true" align="center" edge="center" visible="false" zorder="3" />
    <plugin name="radar" url="%SWFPATH%/plugins/radar.swf" alturl="%SWFPATH%/plugins/radar.js" zorder="1" keep="true" heading="0" parent="map" align="lefttop" edge="center" x="0" y="0" linecolor="0" fillcolor="0xFF0000" scale="0.5" visible="false" />
    <action name="activatespot">
        set(plugin[activespot].parent, plugin[%1]);
        set(plugin[activespot].visible, true);
        copy(plugin[radar].x, plugin[%1].x);
        copy(plugin[radar].y, plugin[%1].y);
        set(plugin[radar].visible, true);
        set(plugin[radar].heading, %2);
    </action>
    <action name="prevscene">
        copy(sceneindex, scene[get(xml.scene)].index);
        sub(lastindex, scene.count, 1);
        dec(sceneindex, 1, 0, get(lastindex));
        loadscene(get(scene[get(sceneindex)].name), null, MERGE, BLEND(0.5));
    </action>

    <action name="nextscene">
        copy(sceneindex, scene[get(xml.scene)].index);
        sub(lastindex, scene.count, 1);
        inc(sceneindex, 1, get(lastindex), 0);
        loadscene(get(scene[get(sceneindex)].name), null, MERGE, BLEND(0.5));
    </action>
    <action name="autonextscene">
        if(skin_settings.openautonext == 1,
        nextscene();
        delayedcall(autonexttimer,15, autonextscene();
        );
        );
    </action>
    <action name="autonextchange">
        if(skin_settings.openautonext == 0,
        set(skin_settings.openautonext,1);
        set(autorotate.enabled,true);
        delayedcall(autonexttimer,15, autonextscene(););
        ,
        set(skin_settings.openautonext,0);
        stopdelayedcall(autonexttimer);
        set(plugin[closeautonext].visible,false);
        );
    </action>
    <action name="autogochange">
        if(autorotate.enabled == true,
        set(autorotate.enabled,false);,set(autorotate.enabled,true);
        );
    </action>

    <action name="flyout">
        copy(backup_rx,rx);copy(backup_ry,ry);copy(backup_rz,rz);copy(backup_scale,scale);copy(backup_directionalsound,directionalsound);copy(backup_zorder,zorder);
        tween(rx, 0);tween(ry, 0);tween(rz, 0);tween(scale, 1.3);tween(flying, 1.0);set(directionalsound,false);set(zorder,100)
    </action>
    <action name="flyback">
        tween(rx, get(backup_rx));tween(ry, get(backup_ry));tween(rz, get(backup_rz));
        tween(scale, get(backup_scale));tween(flying, 0.0);set(directionalsound,get(backup_directionalsound));set(zorder,get(backup_zorder));
    </action>
</krpano>
