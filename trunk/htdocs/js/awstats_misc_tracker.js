var awstatsmisctrackerurl="/js/awstats_misc_tracker.js";
var TRKresult;
var TRKscreen,TRKwinsize,TRKcdi,TRKjava,TRKshk,TRKsvg,TRKfla;
var TRKrp,TRKmov,TRKwma,TRKpdf,TRKpdfver,TRKuserid,TRKsessionid;
var TRKnow,TRKbegin,TRKend;
var TRKnse,TRKn;
function awstats_setCookie(_1,_2,_3){
TRKExpireDate=new Date();
TRKExpireDate.setTime(TRKExpireDate.getTime()+(_3*3600*1000));
document.cookie=_1+"="+escape(_2)+"; path=/"+((_3==null)?"":"; expires="+TRKExpireDate.toGMTString());
}
function awstats_detectIE(_4){
TRKresult=false;
document.write("<SCR"+"IPT LANGUAGE=\"VBScript\">\n on error resume next \n TRKresult = IsObject(CreateObject(\""+_4+"\")) \n </SCR"+"IPT>\n");
if(TRKresult){
return "y";
}else{
return "n";
}
}
function awstats_detectNS(_5){
TRKn="n";
if(TRKnse.indexOf(_5)!=-1){
if(navigator.mimeTypes[_5].enabledPlugin!=null){
TRKn="y";
}
}
return TRKn;
}
function awstats_getCookie(_6){
if(document.cookie.length>0){
TRKbegin=document.cookie.indexOf(_6+"=");
if(TRKbegin!=-1){
TRKbegin+=_6.length+1;
TRKend=document.cookie.indexOf(";",TRKbegin);
if(TRKend==-1){
TRKend=document.cookie.length;
}
return unescape(document.cookie.substring(TRKbegin,TRKend));
}
return null;
}
return null;
}
if(window.location.search==""||window.location.search=="?"){
TRKnow=new Date();
TRKscreen=screen.width+"x"+screen.height;
if(navigator.appName!="Netscape"){
TRKcdi=screen.colorDepth;
}else{
TRKcdi=screen.pixelDepth;
}
TRKjava=navigator.javaEnabled();
TRKuserid=awstats_getCookie("AWSUSER_ID");
TRKsessionid=awstats_getCookie("AWSSESSION_ID");
var TRKrandomnumber=Math.floor(Math.random()*10000);
if(TRKuserid==null||(TRKuserid=="")){
TRKuserid="awsuser_id"+TRKnow.getTime()+"r"+TRKrandomnumber;
}
if(TRKsessionid==null||(TRKsessionid=="")){
TRKsessionid="awssession_id"+TRKnow.getTime()+"r"+TRKrandomnumber;
}
awstats_setCookie("AWSUSER_ID",TRKuserid,10000);
awstats_setCookie("AWSSESSION_ID",TRKsessionid,1);
TRKuserid="";
TRKuserid=awstats_getCookie("AWSUSER_ID");
TRKsessionid="";
TRKsessionid=awstats_getCookie("AWSSESSION_ID");
var TRKnav=navigator.appName.toLowerCase();
var TRKagt=navigator.userAgent.toLowerCase();
var TRKwin=((TRKagt.indexOf("win")!=-1)||(TRKagt.indexOf("32bit")!=-1));
var TRKmac=(TRKagt.indexOf("mac")!=-1);
var TRKns=(TRKnav.indexOf("netscape")!=-1);
var TRKopera=(TRKnav.indexOf("opera")!=-1);
var TRKie=(TRKagt.indexOf("msie")!=-1);
var TRKwinsize;
if(document.documentElement&&document.documentElement.clientWidth){
TRKwinsize=document.documentElement.clientWidth+"x"+document.documentElement.clientHeight;
}else{
if(document.body&&document.body.clientWidth){
TRKwinsize=document.body.clientWidth+"x"+document.body.clientHeight;
}else{
TRKwinsize=window.innerWidth+"x"+window.innerHeight;
}
}
if(TRKie&&TRKwin){
TRKshk=awstats_detectIE("SWCtl.SWCtl.1");
TRKsvg=awstats_detectIE("Adobe.SVGCtl");
TRKfla=awstats_detectIE("ShockwaveFlash.ShockwaveFlash.1");
TRKrp=awstats_detectIE("rmocx.RealPlayer G2 Control.1");
TRKmov=awstats_detectIE("QuickTimeCheckObject.QuickTimeCheck.1");
TRKwma=awstats_detectIE("MediaPlayer.MediaPlayer.1");
TRKpdf="n";
TRKpdfver="";
if(awstats_detectIE("PDF.PdfCtrl.1")=="y"){
TRKpdf="y";
TRKpdfver="4";
}
if(awstats_detectIE("PDF.PdfCtrl.5")=="y"){
TRKpdf="y";
TRKpdfver="5";
}
if(awstats_detectIE("PDF.PdfCtrl.6")=="y"){
TRKpdf="y";
TRKpdfver="6";
}
if(awstats_detectIE("AcroPDF.PDF.1")=="y"){
TRKpdf="y";
TRKpdfver="7";
}
}
if(TRKns||!TRKwin){
TRKnse="";
for(var TRKi=0;TRKi<navigator.mimeTypes.length;TRKi++){
TRKnse+=navigator.mimeTypes[TRKi].type.toLowerCase();
}
TRKshk=awstats_detectNS("application/x-director","");
TRKsvg=awstats_detectNS("image/svg+xml","");
if(document.implementation.hasFeature("org.w3c.dom.svg","")){
TRKsvg="y";
}
TRKfla=awstats_detectNS("application/x-shockwave-flash");
TRKrp=awstats_detectNS("audio/x-pn-realaudio-plugin");
TRKmov=awstats_detectNS("video/quicktime");
TRKwma=awstats_detectNS("application/x-mplayer2");
TRKpdf=awstats_detectNS("application/pdf");
TRKpdfver="";
}
var imgsrc1=awstatsmisctrackerurl+"?screen="+TRKscreen+"&win="+TRKwinsize+"&cdi="+TRKcdi+"&java="+TRKjava;
var imgsrc2="&shk="+TRKshk+"&svg="+TRKsvg+"&fla="+TRKfla+"&rp="+TRKrp+"&mov="+TRKmov+"&wma="+TRKwma+"&pdf="+TRKpdf+"&uid="+TRKuserid+"&sid="+TRKsessionid;
var imgsrc=imgsrc1+imgsrc2;
if(document.createElementNS){
var l=document.createElementNS("http://www.w3.org/1999/xhtml","img");
l.setAttribute("src",imgsrc);
l.setAttribute("height","0");
l.setAttribute("width","0");
l.setAttribute("border","0");
document.getElementsByTagName("body")[0].appendChild(l);
}else{
document.write("<img style=\"display:none;\" src=\""+imgsrc+"\" height=\"0\" width=\"0\" border=\"0\" />");
}
}

