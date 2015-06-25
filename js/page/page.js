/**
 * @file page.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2014 Cyril Vermande
 *
 * Page management
*/
var Page = {
	mainTitle: "",
	title: function(title){
		if(title) document.title = title + " | " + Page.mainTitle;
		else document.title = Page.mainTitle;
		return Page;
	},
	url: function(url, title){
		url = url || "";
		title = title || "Le Son du coin";
		if(window.history.pushState) window.history.pushState(null, title , url);
		else window.location.hash = "#" + url;
		return Page;
	},
	content: function(html){
		if(html) document.getElementById('page-content').innerHTML = html;
		return Page;
	},
	message: function(message){
		return Page;
	},
	error: function(message){
		return Page;
	},
	showLoading: function(){
		document.getElementById('loading').style.display = "block";
		return Page;
	},
	hideLoading: function(){
		document.getElementById('loading').style.display = "none";
		return Page;
	},
	scrollTo: function(pos){
		window.scrollTo(0, pos);
		return Page;
	}
}