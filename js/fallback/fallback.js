/**
 * @file fallback.js
 * @author Cyril Vermande - madislak [at] yahoo.fr
 * @copyright All right reserved 2014 Cyril Vermande
 *
 * Fallback
*/
var Modernizr = Modernizr || false;

if(Modernizr && !Modernizr.input.placeholder){
	$('input[type!=password]').each(function(){
		if(this.value.length == 0) this.value = this.getAttribute('placeholder');
	}).focus(function(){
		var placeholder = this.getAttribute('placeholder');
		if(placeholder && this.value == placeholder) this.value = "";
	}).blur(function(){
		var placeholder = this.getAttribute('placeholder');
		if(placeholder && this.value == "") this.value = placeholder;
	});
	$('input[type=password]').each(function(){
		if(this.value.length == 0) $(this).hide().before("<input type=\"text\" id=\"" + this.id + "-placeholder\" value=\"Mot de passe...\" onfocus=\"$('#" + this.id + "-placeholder').hide();$('#" + this.id + "').show().focus();\" />");
	}).blur(function(){
		var placeholder = this.getAttribute('placeholder');
		if(placeholder && this.value == ""){
			$('#' + this.id + '-placeholder').show();
			$('#' + this.id).hide();
		}
	});
	$('textarea').each(function(){
		if(this.innerHTML.length == 0) this.innerHTML = this.getAttribute('placeholder');
	}).focus(function(){
		var placeholder = this.getAttribute('placeholder');
		if(placeholder && this.innerHTML == placeholder) this.innerHTML = "";
	}).blur(function(){
		var placeholder = this.getAttribute('placeholder');
		if(placeholder && this.innerHTML == '') this.innerHTML = placeholder;
	});
}

if(Modernizr && !Modernizr.inputtypes.date && jQuery.datepicker){
	$('input[type=date]').attr('type', "input").attr('data-type', "date");
	$('input[data-type="date"]').datepicker({dateFormat:'dd/mm/yy', firstDay:1, dayNamesMin:['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa']});
}

if(!Array.prototype.forEach){
	Array.prototype.forEach = function(func){
		for(var i=0,l=this.length; i<l; i++){
			func(this[i]);
		}
	}
}

if(!Array.prototype.some){
	Array.prototype.some = function(func){
		for(var i=0,l=this.length; i<l; i++){
			if(func(this[i]) === true) return true;
		}
		return false;
	}
}
