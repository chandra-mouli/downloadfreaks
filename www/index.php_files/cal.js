var pres = new Date();
var month=pres.getMonth();
var yr = pres.getFullYear();
var date = pres.getDate();
months1 = new Array("Jan","Feb", "Mar","Apr", "May", "June", "July", "Aug","Sep", "Oct","Nov", "Dec");
document.write("<b  style=\"color:#999;font-size:15px;padding-left:60px;line-height:40px;\">" + yr + " " + months1[month] + " " + "</b><br/>");
if (yr % 4 == 0){months = new Array(31,29,31,30,31,30,31,31,30,31,30,31);}
else{months = new Array(31,28,31,30,31,30,31,31,30,31,30,31);}
document.write("<table><thead><tr><td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td></tr></thead>");
var init = pres.getDay() - date%7 + 8;
document.write("<tr>");
for(var it=0;it<42;it++){
	if(it && it%7==0){ document.write("</tr><tr>"); }
	var x=it-init +1;
	if(x<10) x = "0"+x ;
	if(it >= init && it < init+months[month]){
		if(x==date) document.write("<td class=\"pres\"> "+ x + " </td>");
		else document.write("<td> "+ x + " </td>"); }
	else document.write("<td>  </td>");
	}
document.write("</tr>");
document.write("</table>");

