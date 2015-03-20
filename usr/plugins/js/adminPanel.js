function Yetii() {

	this.defaults = {
		
		id: null,
		active: 1,
		interval: null,
		wait: null,
		persist: null,
		tabclass: 'tab',
		activeclass: 'active',
		callback: null
	
	};
	
	for (var n in arguments[0]) { this.defaults[n]=arguments[0][n]; };	
	
	this.getTabs = function() {
        	
        var retnode = [];
        var elem = document.getElementById(this.defaults.id).getElementsByTagName('*');
		
		var regexp = new RegExp("(^|\\s)" + this.defaults.tabclass.replace(/\-/g, "\\-") + "(\\s|$)");
	
        for (var i = 0; i < elem.length; i++) {
        if (regexp.test(elem[i].className)) retnode.push(elem[i]);
        }
    
        return retnode;
    
    };
	
	this.links = document.getElementById(this.defaults.id + '-nav').getElementsByTagName('a');
	this.lis = document.getElementById(this.defaults.id + '-nav').getElementsByTagName('li');
	
	this.show = function(number){
        
        for (var i = 0; i < this.tabs.length; i++) {
        this.tabs[i].style.display = ((i+1)==number) ? 'block' : 'none';
        this.lis[i].className = ((i+1)==number) ? this.defaults.activeclass : '';
		}
		
		this.defaults.active = number;
		if (this.defaults.callback) this.defaults.callback(number);
    
    };
	
	this.rotate = function(interval){
    
        this.show(this.defaults.active);
        this.defaults.active++;
    
        if(this.defaults.active > this.tabs.length) this.defaults.active = 1;
    
	
        var self = this;
		
		if (this.defaults.wait) clearTimeout(this.timer2);
		 
        this.timer1 = setTimeout(function(){self.rotate(interval);}, interval*1000);
    
    };
	
	this.next = function() {
		
		this.defaults.active++;
    	if(this.defaults.active > this.tabs.length) this.defaults.active = 1;
		this.show(this.defaults.active);
	
	};
	
	this.previous = function() {
		
		this.defaults.active--;
    	if(!this.defaults.active) this.defaults.active = this.tabs.length;
		this.show(this.defaults.active);
	
	};
	
	this.parseurl = function(tabinterfaceid){
		var result=window.location.search.match(new RegExp(tabinterfaceid+"=(\\d+)", "i")); 
		return (result==null)? null : parseInt(RegExp.$1);
	};

	this.createCookie = function(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	};
	
	this.readCookie = function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	};


	
	this.tabs = this.getTabs();
	
	this.defaults.active = (this.parseurl(this.defaults.id)) ? this.parseurl(this.defaults.id) : this.defaults.active;
	if (this.defaults.persist && this.readCookie(this.defaults.id)) this.defaults.active = this.readCookie(this.defaults.id);  
	this.show(this.defaults.active);
	
	var self = this;
	for (var i = 0; i < this.links.length; i++) {
	this.links[i].customindex = i+1;
	this.links[i].onclick = function(){ 
		
		if (self.timer1) clearTimeout(self.timer1);
		if (self.timer2) clearTimeout(self.timer2); 
		
		self.show(this.customindex);
		if (self.defaults.persist) self.createCookie(self.defaults.id, this.customindex, 0);
		
		if (self.defaults.wait) self.timer2 = setTimeout(function(){self.rotate(self.defaults.interval);}, self.defaults.wait*1000);
		
		return false;
	};
    }
	
	if (this.defaults.interval) this.rotate(this.defaults.interval);
	
};


var tooltip = {
	options: {
		attr_name: "tooltip",
		blank_text: "(откроется в новом окне)",
		newline_entity: "  ",
		max_width: 0, 
		delay: 50,
		skip_tags: ["link", "style"]
	},

	t: document.createElement("DIV"),
	c: null,
	g: false,
	canvas: null,

	m: function(e){
		if (tooltip.g){
			var x = window.event ? event.clientX + tooltip.canvas.scrollLeft : e.pageX;
			var y = window.event ? event.clientY + tooltip.canvas.scrollTop : e.pageY;
			tooltip.a(x, y);
		}
	},

	d: function(){
		tooltip.canvas = document.getElementsByTagName(document.compatMode && document.compatMode == "CSS1Compat" ? "HTML" : "BODY")[0];
		tooltip.t.setAttribute("id", "tooltip");
		document.body.appendChild(tooltip.t);
		if (tooltip.options.max_width) tooltip.t.style.maxWidth = tooltip.options.max_width + "px";
		var a = document.all && !window.opera ? document.all : document.getElementsByTagName("*");
		var l = a.length;
		for (var i = 0; i < l; i++){

			if (!a[i] || tooltip.options.skip_tags.in_array(a[i].tagName.toLowerCase())) continue;

			var tooltip_title = a[i].getAttribute("title");
			if (tooltip_title && typeof tooltip_title != "string") tooltip_title = "";

			var tooltip_alt = a[i].getAttribute("alt");
			var tooltip_blank = a[i].getAttribute("target") && a[i].getAttribute("target") == "_blank" && tooltip.options.blank_text;
			if (tooltip_title || tooltip_blank){
				a[i].setAttribute(tooltip.options.attr_name, tooltip_blank ? (tooltip_title ? tooltip_title + " " + tooltip.options.blank_text : tooltip.options.blank_text) : tooltip_title);
				if (a[i].getAttribute(tooltip.options.attr_name)){
					a[i].removeAttribute("title");
					if (tooltip_alt && a[i].complete) a[i].removeAttribute("alt");
					tooltip.l(a[i], "mouseover", tooltip.s);
					tooltip.l(a[i], "mouseout", tooltip.h);
				}
			}else if (tooltip_alt && a[i].complete){
				a[i].setAttribute(tooltip.options.attr_name, tooltip_alt);
				if (a[i].getAttribute(tooltip.options.attr_name)){
					a[i].removeAttribute("alt");
					tooltip.l(a[i], "mouseover", tooltip.s);
					tooltip.l(a[i], "mouseout", tooltip.h);
				}
			}
			if (!a[i].getAttribute(tooltip.options.attr_name) && tooltip_blank){
				//
			}
		}
		document.onmousemove = tooltip.m;
		window.onscroll = tooltip.h;
		tooltip.a(-99, -99);
	},
	
	_: function(s){
		s = s.replace(/\&/g,"&amp;");
		s = s.replace(/\</g,"&lt;");
		s = s.replace(/\>/g,"&gt;");
		return s;
	},

	s: function(e){
		if (typeof tooltip == "undefined") return;
		var d = window.event ? window.event.srcElement : e.target;
		if (!d.getAttribute(tooltip.options.attr_name)) return;
		var s = d.getAttribute(tooltip.options.attr_name);
		if (tooltip.options.newline_entity){
			var s = tooltip._(s);
			s = s.replace(eval("/" + tooltip._(tooltip.options.newline_entity) + "/g"), "<br />");
			tooltip.t.innerHTML = s;
		}else{
			if (tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
			tooltip.t.appendChild(document.createTextNode(s));
		}
		tooltip.c = setTimeout(function(){
			tooltip.t.style.visibility = 'visible';
		}, tooltip.options.delay);
		tooltip.g = true;
	},

	h: function(e){
		if (typeof tooltip == "undefined") return;
		tooltip.t.style.visibility = "hidden";
		if (!tooltip.options.newline_entity && tooltip.t.firstChild) tooltip.t.removeChild(tooltip.t.firstChild);
		clearTimeout(tooltip.c);
		tooltip.g = false;
		tooltip.a(-99, -99);
	},

	l: function(o, e, a){
		if (o.addEventListener) o.addEventListener(e, a, false);
		else if (o.attachEvent) o.attachEvent("on" + e, a);
			else return null;
	},

	a: function(x, y){
		var w_width = tooltip.canvas.clientWidth ? tooltip.canvas.clientWidth + tooltip.canvas.scrollLeft : window.innerWidth + window.pageXOffset;
		var w_height = window.innerHeight ? window.innerHeight + window.pageYOffset : tooltip.canvas.clientHeight + tooltip.canvas.scrollTop;

		if (document.all && document.all.item && !window.opera) tooltip.t.style.width = tooltip.options.max_width && tooltip.t.offsetWidth > tooltip.options.max_width ? tooltip.options.max_width + "px" : "auto";
		
		var t_width = tooltip.t.offsetWidth;
		var t_height = tooltip.t.offsetHeight;

		tooltip.t.style.left = x + 8 + "px";
		tooltip.t.style.top = y + 8 + "px";
		
		if (x + t_width > w_width) tooltip.t.style.left = w_width - t_width + "px";
		if (y + t_height > w_height) tooltip.t.style.top = w_height - t_height + "px";
	}
}

Array.prototype.in_array = function(value){
	var l = this.length;
	for (var i = 0; i < l; i++)
		if (this[i] === value) return true;
	return false;
};

var root = window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
if (root){
	if (root.addEventListener) root.addEventListener("load", tooltip.d, false);
	else if (root.attachEvent) root.attachEvent("onload", tooltip.d);
}

function caa(check)
{
	var err = '';
		
	if(!check)
	{
		gid('currentErrors').innerHTML = '';
		for (var i = 0; i < errsConf.length; i++)
		{
			if(typeof(errsConf[i][3]) != 'undefined')
			{
				gid(errsConf[i][1]).innerHTML = '';
				if(gid(errsConf[i][0]).value == errsConf[i][3] || gid(errsConf[i][0]).value == '')
				{
					err += '- '+errsConf[i][2] + '<br />';
					gid(errsConf[i][1]).innerHTML = errsConf[i][2];
				}
			}
			else
			{
				gid(errsConf[i][1]).innerHTML = '';
				if(gid(errsConf[i][0]).value == '')
				{
					err += '- '+errsConf[i][2] + '<br />';
					gid(errsConf[i][1]).innerHTML = errsConf[i][2];
				}
			}
		}
		
		if(err != '') 
		{
			alert('Одно из обязательных полей не заполнено, проверте правильность ввода данных!');
			gid('currentErrors').innerHTML = '<div class="row"><div class="col-lg-12"><div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Обязательные поля формы не заполнены!</strong><br>' + err + '</div></div></div>';
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		for (var i = 0; i < errsConf.length; i++)
		{
			if(check.id == errsConf[i][0]) 
			{
				if(check.value != '')	gid(errsConf[i][1]).innerHTML = ''; else gid(errsConf[i][1]).innerHTML = errsConf[i][2];
			}
		}
		return false;
	}
}

function tagSplit(val, newVal)
{
	arr = val.split(',');
	arr[(arr.length-1)] = newVal;
	for (var i = 0; i < arr.length; i++)
	{
		arr[i] = arr[i].replace(/^\s*|\s*$/g,"");
	}
	return  (arr instanceof Array ) ? arr.join(', ') : arr;
	
}


function vertMenu(id)
{
    if (gid(id).style.display == 'none')
    {
		$toggle(id);
        setCookie(id, true, 60 * 60 * 24 * 365 * 1000);
    }
    else
    {
		$toggle(id);
        delCookie(id);
    }
}

window.onload = function(){
	for( var i = 0; i < document.getElementsByTagName("table").length; i++ )
	{
		if(document.getElementsByTagName("table")[i].className == 'cont')
		{
			var parse = document.getElementsByTagName("table")[i];
			for( var z = 0; z < parse.rows.length; z++ )
			{	
				if(parse.rows[z].tagName == 'TR' && z%2 == 0)
					parse.rows[z].className = '_highlight_row';
				else
					parse.rows[z].className = 'cont_tr';
			}
			break;
		}
	}
}


/*
SimpleJS ver 0.1 beta
----------------------
SimpleJS is developed by Christophe "Dyo" Lefevre (http://bleebot.com/)
*/

/*
SimpleJS ver 0.1 beta
----------------------
SimpleJS is developed by Christophe "Dyo" Lefevre (http://bleebot.com/)
*/
function $(id){
return document.getElementById(id);
}
function STO(_24,_25){
return window.setTimeout(_24,_25);
}
function DecToHexa(_26){
var _27=parseInt(_26).toString(16);
if(_26<16){
_27="0"+_27;
}
return _27;
}
function addslashes(str){
str=str.replace(/\"/g,"\\\"");
str=str.replace(/\'/g,"\\'");
return str;
}
function $toggle(id){
if(act_height(id)==0){
$blinddown(id);
}else{
showhide(id);
}
}
function act_height(id){
height=$(id).clientHeight;
if(height==0){
height=$(id).offsetHeight;
}
return height;
}
function act_width(id){
width=$(id).clientWidth;
if(width==0){
width=$(id).offsetWidth;
}
return width;
}
function max_height(id){
var ids=$(id).style;
ids.overflow="hidden";
if(act_height(id)!=0){
return act_height(id);
}else{
origdisp=ids.display;
origheight=ids.height;
origpos=ids.position;
origvis=ids.visibility;
ids.visibility="hidden";
ids.height="";
ids.display="block";
ids.position="absolute";
height=act_height(id);
ids.display=origdisp;
ids.height=origheight;
ids.position=origpos;
ids.visibility=origvis;
return height;
}
}

function $blinddown(id,_32){
if(!_32){
_32=200;
}
acth=act_height(id);
if(acth==0){
maxh=max_height(id);
$(id).style.display="block";
$(id).style.height="0px";
var _33;
_33=Math.ceil(_32/maxh);
for(i=1;i<=maxh;i++){
STO("$('"+id+"').style.height='"+i+"px'",_33*i);
}
}
}
function $opacity(id,_35,_36,_37){
if($(id).style.width==0){
$(id).style.width=act_width(id);
}
var _38=Math.round(_37/100);
var _39=0;
if(_35>_36){
for(i=_35;i>=_36;i--){
STO("changeOpac("+i+",'"+id+"')",(_39*_38));
_39++;
}
}else{
if(_35<_36){
for(i=_35;i<=_36;i++){
STO("changeOpac("+i+",'"+id+"')",(_39*_38));
_39++;
}
}
}
}
function $pulsate(id,num,speed){
if (!speed) speed = 300;
for(i = 1; i <= num; i++) {
numx=i*((speed*2)+100)-(speed*2);
STO("$opacity('"+id+"', 100, 0, "+speed+")",numx);
STO("$opacity('"+id+"', 0, 100, "+speed+")",numx+speed+100);
}
}
function changeOpac(_3a,id){
var ids=$(id).style;
ids.opacity=(_3a/100);
ids.MozOpacity=(_3a/100);
ids.KhtmlOpacity=(_3a/100);
ids.filter="alpha(opacity="+_3a+")";
}
function $shiftOpacity(id,_3e){
if($(id).style.opacity<0.5){
$opacity(id,0,100,_3e);
}else{
$opacity(id,100,0,_3e);
}
}
function currentOpac(id,_40,_41){
var _42=100;
if($(id).style.opacity<100){
_42=$(id).style.opacity*100;
}
$opacity(id,_42,_40,_41);
}