array( 
    data=>array( 
    weather=>array( 
    info=>array( 
    weather=>array(//最近三天的天气 
    0=>array( 
    desc=>//天气描叙 
    lowTemp=>//最低温度 
    highTemp=>//最高温度 
    date=>时间 
) 
... 
) 
pm=>array( 
    num=>//pm2.5数值 
    desc=>//污染情况 
) 
), 
version=>天气数据版本号 
), 

cateory=>array( 
    info=>array( 
         //之前此处数据与之前数据结构一致 
    ), 
         version=>//天气数据版本号 
    ), 
) 
    status => //数据返回状态 
    errMessage => //出错提示 
    errDetail => //出错详细原因 
)