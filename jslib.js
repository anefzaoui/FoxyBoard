function extract_url_string(str){

var str = str;
var csd1 = "http://marketplace.firefox.com/app/";
var csd2 = "https://marketplace.firefox.com/app/";
var msg="";
var first_c = str.lastIndexOf('app/')+4;
var last_c = str.lastIndexOf('?');
if
( (str.substring(0,first_c)!=csd1) && (str.substring(0,first_c)!=csd2) ){
msg=" <font color=red><b>Oops, this is not a link for the marketplace. Please make sure you copied the correct Link</b></font> ";
return ["",msg];
}
else{
msg="";
}

if(last_c==-1){
res = str.substring(first_c);
}
else{
res = str.substring(first_c,last_c);
}
return ["[firefox-app id=&quot;"+res+"&quot;]",msg];

}

function results(){
parent.tinyMCE.activeEditor.selection.setContent(extract_url_string(document.getElementById("fxb-package-url").value)[0]);
document.getElementById("error-msg-fx-board-widget").innerHTML=extract_url_string(document.getElementById("fxb-package-url").value)[1];

}