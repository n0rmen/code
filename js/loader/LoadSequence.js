/**
 * @file loadSequence.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2015 Cyril Vermande
 *
 * LoadSequence
*/
var LoadSequence = {
	loaded: false,
	items: [],
	add: function(func){
		this.items.push(func);
	},
	init: function(){
		if(LoadSequence.loaded) throw "Page is already loaded";
		LoadSequence.items.forEach(function(item){
			item.call();
		});
		LoadSequence.loaded = true;
	}
};

window.addEventListener('load', LoadSequence.init);
