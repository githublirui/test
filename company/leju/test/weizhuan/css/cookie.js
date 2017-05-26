function setCookie(a,b){
var d=new Date();
var v=arguments;
var c=arguments.length;
var e=(c>2)?v[2]:null;
var p=(c>3)?v[3]:null;
var m=(c>4)?v[4]:window.location.host;
var r=(c>5)?v[5]:false;
if(e!=null){
   var T=parseFloat(e);
   var U=e.replace(T,"");
   T=(isNaN(T)||T<=0)?1:T;
   U=("snhdwmqy".indexOf(U)==-1||U=="")?'s':U.toLowerCase();
   switch(U){
    case 's':d.setSeconds(d.getSeconds()+T);break;
    case 'n':d.setMinutes(d.getMinutes()+T);break;
    case 'h':d.setHours(d.getHours()+T);break;
    case 'd':d.setDate(d.getDate()+T);break;
    case 'w':d.setDate(d.getDate()+7*T);break;
    case 'm':d.setMonth(d.getMonth()+1+T);break;
    case 'q':d.setMonth(d.getMonth()+1 +3*T);break;
    case 'y':d.setFullYear(d.getFullYear()+ T);break
   }
}
document.cookie=a+"="+escape(b)+((e==null)?"":("; expires="+d.toGMTString()))+((p==null)?("; path=/"):("; path="+p))+("; domain="+m)+((r==true)?"; secure":"")
}
function getCookieVal(a){
var b=document.cookie.indexOf(";",a);
if(b==-1)b=document.cookie.length;
return unescape(document.cookie.substring(a,b))
}
function getCookie(a){
var v=a+"=";
var i=0;
while(i<document.cookie.length){
   var j=i+v.length;
   if(document.cookie.substring(i,j)==v)return getCookieVal(j);
   i=document.cookie.indexOf(" ",i)+1;
   if(i==0)break
}
return null
}
function delCookie(a){
var e=new Date();
e.setTime(e.getTime()-1);
var b=getCookie(a);
document.cookie=a+"="+a+";path=/; domain="+window.location.host+"; expires="+e.toGMTString()
}