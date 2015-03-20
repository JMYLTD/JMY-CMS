/**
* SRAX Storage v1.1 beta (build 2), http://www.fullajax.ru
* Based on Dojo Toolkit v1.2.3,  http://dojotoolkit.org/
* Created by Ruslan Sinistkiy, 2008.
* Use is subject to license terms.
*/
SRAX.Default.STORAGE_SWF = SRAX.Default.STORAGE_SWF || '/templates/index/scripts/SRAX.Storage.v1.1.b1.swf';
SRAX.Default.STORAGE_ETAG = true;
SRAX.Default.USE_STORAGE = true;

if (!SRAX.Storage){

    dojo = dojox = {};
    dojo.config = { 
        dojoPath : '/',
        swfLoc : SRAX.Default.STORAGE_SWF ? SRAX.Default.STORAGE_SWF : 'SRAX.Storage.v1.1.b1.swf'
    }
    
    dojo.byId = id;
    
    (function(){
        // fill in the rendering support information in dojo.render.*
        var n = navigator;
        var dua = n.userAgent;
        var dav = n.appVersion;
        var tv = parseFloat(dav);
        
        dojo.isOpera = (dua.indexOf("Opera") >= 0) ? tv : 0;
        // safari detection derived from:
        //		http://developer.apple.com/internet/safari/faq.html#anchor2
        //		http://developer.apple.com/internet/safari/uamatrix.html
        var idx = Math.max(dav.indexOf("WebKit"), dav.indexOf("Safari"), 0);
        if(idx){
            // try to grab the explicit Safari version first. If we don't get
            // one, look for 419.3+ as the indication that we're on something
            // "Safari 3-ish". Lastly, default to "Safari 2" handling.
            dojo.isSafari = parseFloat(dav.split("Version/")[1]) || ( ( parseFloat(dav.substr(idx+7)) >= 419.3 ) ? 3 : 2 ) || 2;
        }
        dojo.isAIR = (dua.indexOf("AdobeAIR") >= 0) ? 1 : 0;
        dojo.isKhtml = (dav.indexOf("Konqueror") >= 0 || dojo.isSafari) ? tv : 0;
        dojo.isMozilla = dojo.isMoz = (dua.indexOf("Gecko") >= 0 && !dojo.isKhtml) ? tv : 0;
        dojo.isFF = dojo.isIE = 0;
        if(dojo.isMoz){
            dojo.isFF = parseFloat(dua.split("Firefox/")[1]) || 0;
        }
        if(document.all && !dojo.isOpera){
            dojo.isIE = parseFloat(dav.split("MSIE ")[1]) || 0;
        }
                
    })();
    
    dojo.global = this;
    
    dojo.doc = window["document"] || null;
    
    dojo.body = function(){
        return dojo.doc.body || dojo.doc.getElementsByTagName("body")[0]; // Node
    }	   
    
    dojo.isString = function(it){
        return !!arguments.length && it != null && (typeof it == "string" || it instanceof String);
    }
    dojo.isArray = function(/*anything*/ it){
        return it && (it instanceof Array || typeof it == "array"); // Boolean
    }
    
    dojo.isFunction = (function(){
        var _isFunction = function(/*anything*/ it){
            return it && (typeof it == "function" || it instanceof Function); // Boolean
        };
        
        return dojo.isSafari ?
        // only slow this down w/ gratuitious casting in Safari since it's what's b0rken
        function(/*anything*/ it){
            if(typeof it == "function" && it == "[object NodeList]"){ return false; }
            return _isFunction(it); // Boolean
        } : _isFunction;
    })();
    

    dojo._docScroll = function(){
            var _b = dojo.body(), _w = dojo.global, de = dojo.doc.documentElement;
            return {
                    y: (_w.pageYOffset || de.scrollTop || _b.scrollTop || 0),
                    x: (_w.pageXOffset || dojo._fixIeBiDiScrollLeft(de.scrollLeft) || _b.scrollLeft || 0)
            };
    };
	
    dojo._fixIeBiDiScrollLeft = function(/*Integer*/ scrollLeft){
            if(dojo.isIE){
              var dd = dojo.doc;
                    var de = dd.compatMode == "BackCompat" ? dd.body : dd.documentElement;
                    return scrollLeft + de.clientWidth - de.scrollWidth; // Integer
            }
            return scrollLeft; // Integer
    }
    
    
    
    var _getParts = function(arr, obj, cb){
        return [ 
            dojo.isString(arr) ? arr.split("") : arr, 
            obj || dojo.global,
            dojo.isString(cb) ? new Function("item", "index", "array", cb) : cb
        ];
    }
    dojo.forEach =  function(arr, callback, thisObject){
        if(!arr || !arr.length){ return; }
        var _p = _getParts(arr, thisObject, callback); arr = _p[0];
        for(var i=0,l=_p[0].length; i<l; i++){ 
            _p[2].call(_p[1], arr[i], i, arr);
        }
    }  
    
    
    dojo.map = function(/*Array|String*/arr, /*Function|String*/callback, /*Function?*/thisObject){
        
        var _p = _getParts(arr, thisObject, callback); arr = _p[0];
        var outArr = (arguments[3] ? (new arguments[3]()) : []);
        for(var i=0;i<arr.length;++i){
            outArr.push(_p[2].call(_p[1], arr[i], i, arr));
        }
        return outArr; // Array
    }
    
    
    dojo.fromJson = function(/*String*/ json){
        return eval("(" + json + ")"); // Object
    }
    
    dojo._escapeString = function(/*String*/str){
        return ('"' + str.replace(/(["\\])/g, '\\$1') + '"').
        replace(/[\f]/g, "\\f").replace(/[\b]/g, "\\b").replace(/[\n]/g, "\\n").
        replace(/[\t]/g, "\\t").replace(/[\r]/g, "\\r"); // string
    }
    
    dojo.toJsonIndentStr = "\t";
    dojo.toJson = function(/*Object*/ it, /*Boolean?*/ prettyPrint, /*String?*/ _indentStr){
        
        if(it === undefined){
            return "undefined";
        }
        var objtype = typeof it;
        if(objtype == "number" || objtype == "boolean"){
            return it + "";
        }
        if(it === null){
            return "null";
        }
        if(dojo.isString(it)){ 
            return dojo._escapeString(it); 
        }
        if(it.nodeType && it.cloneNode){ // isNode
            return ""; // FIXME: would something like outerHTML be better here?
        }
        // recurse
        var recurse = arguments.callee;
        // short-circuit for objects that support "json" serialization
        // if they return "self" then just pass-through...
        var newObj;
        _indentStr = _indentStr || "";
        var nextIndent = prettyPrint ? _indentStr + dojo.toJsonIndentStr : "";
        if(typeof it.__json__ == "function"){
            newObj = it.__json__();
            if(it !== newObj){
                return recurse(newObj, prettyPrint, nextIndent);
            }
        }
        if(typeof it.json == "function"){
            newObj = it.json();
            if(it !== newObj){
                return recurse(newObj, prettyPrint, nextIndent);
            }
        }
        
        var sep = prettyPrint ? " " : "";
        var newLine = prettyPrint ? "\n" : "";
        
        // array
        if(dojo.isArray(it)){
            var res = dojo.map(it, function(obj){
                var val = recurse(obj, prettyPrint, nextIndent);
                if(typeof val != "string"){
                    val = "undefined";
                }
                return newLine + nextIndent + val;
            });
            return "[" + res.join("," + sep) + newLine + _indentStr + "]";
        }
        if(objtype == "function"){
            return null; // null
        }
        // generic object code path
        var output = [];
        for(var key in it){
            var keyStr;
            if(typeof key == "number"){
                keyStr = '"' + key + '"';
            }else if(typeof key == "string"){
            keyStr = dojo._escapeString(key);
        }else{
        // skip non-string or number keys
        continue;
    }
    val = recurse(it[key], prettyPrint, nextIndent);
    if(typeof val != "string"){
        // skip non-serializable values
        continue;
    }
    // FIXME: use += on Moz!!
    //	 MOW NOTE: using += is a pain because you have to account for the dangling comma...
    output.push(newLine + nextIndent + keyStr + ":" + sep + val);
}
return "{" + output.join("," + sep) + newLine + _indentStr + "}"; // String
}


dojox.flash = {
    ready: false,
    url: null,
    
    _visible: true,
    _loadedListeners: new Array(),
    _installingListeners: new Array(),
    
    setSwf: function(/* String */ url, /* boolean? */ visible){
        // summary: Sets the SWF files and versions we are using.
        // url: String
        //	The URL to this Flash file.
        // visible: boolean?
        //	Whether the Flash file is visible or not. If it is not visible we hide it off the
        //	screen. This defaults to true (i.e. the Flash file is visible).
        this.url = url;
        
        if(typeof visible != "undefined"){
            this._visible = visible;
        }
        
        // initialize ourselves		
        this._initialize();
    },
    
    addLoadedListener: function(/* Function */ listener){
        // summary:
        //	Adds a listener to know when Flash is finished loading. 
        //	Useful if you don't want a dependency on dojo.event.
        // listener: Function
        //	A function that will be called when Flash is done loading.
        if (this.isReady) listener(); else  this._loadedListeners.push(listener);
    },
    
    addInstallingListener: function(/* Function */ listener){
        // summary:
        //	Adds a listener to know if Flash is being installed. 
        //	Useful if you don't want a dependency on dojo.event.
        // listener: Function
        //	A function that will be called if Flash is being
        //	installed
        
        this._installingListeners.push(listener);
    },	
    
    loaded: function(){
        // summary: Called back when the Flash subsystem is finished loading.
        // description:
        //	A callback when the Flash subsystem is finished loading and can be
        //	worked with. To be notified when Flash is finished loading, add a
        //  loaded listener: 
        //
        //  dojox.flash.addLoadedListener(loadedListener);
        this.isReady = dojox.flash.ready = true;
        if(dojox.flash._loadedListeners.length > 0){
            for(var i = 0; i < dojox.flash._loadedListeners.length; i++){
                try{
                    dojox.flash._loadedListeners[i].call(null);
                } catch (ex){
                    error(ex);
                }
            }
        }
        dojox.flash._loadedListeners = [];        
    },
    
    installing: function(){
        // summary: Called if Flash is being installed.
        // description:
        //	A callback to know if Flash is currently being installed or
        //	having its version revved. To be notified if Flash is installing, connect
        //	your callback to this method using the following:
        //	
        //	dojo.event.connect(dojox.flash, "installing", myInstance, "myCallback");
        
        if(dojox.flash._installingListeners.length > 0){
            for(var i = 0; i < dojox.flash._installingListeners.length; i++){
                dojox.flash._installingListeners[i].call(null);
            }
        }
    },
    
    // Initializes dojox.flash.
    _initialize: function(){
        dojox.flash.obj = new dojox.flash.Embed(this._visible);
        dojox.flash.obj.write();
        
        // setup the communicator
        dojox.flash.comm = new dojox.flash.Communicator();        
    }
    
};



dojox.flash.Embed = function(visible){
    // summary: A class that is used to write out the Flash object into the page.
    // description:
    //	Writes out the necessary tags to embed a Flash file into the page. Note that
    //	these tags are written out as the page is loaded using document.write, so
    //	you must call this class before the page has finished loading.
    
    this._visible = visible;
}

dojox.flash.Embed.prototype = {
    // width: int
    //	The width of this Flash applet. The default is the minimal width
    //	necessary to show the Flash settings dialog. Current value is 
    //  215 pixels.
    width: 215,
    
    // height: int 
    //	The height of this Flash applet. The default is the minimal height
    //	necessary to show the Flash settings dialog. Current value is
    // 138 pixels.
    height: 138,
    
    // id: String
    // 	The id of the Flash object. Current value is 'flashObject'.
    id: "flashObject",
    
    // Controls whether this is a visible Flash applet or not.
    _visible: true,
    
    protocol: function(){
        switch(window.location.protocol){
            case "https:":
            return "https";
            break;
            default:
            return "http";
            break;
        }
    },
    
    write: function(useWrite){
        if (this.isWrited) return;
        
        // determine our container div's styling
        var containerStyle = "";
        containerStyle += ("width: " + this.width + "px; ");
        containerStyle += ("height: " + this.height + "px; ");
        if(!this._visible){
            containerStyle += "position: absolute; z-index: 10000; top: -1000px; left: -1000px; ";
        }
        
        // figure out the SWF file to get and how to write out the correct HTML
        // for this Flash version
        var objectHTML;
        var swfloc = dojox.flash.url;
        var swflocObject = swfloc;
        var swflocEmbed = swfloc;
        // IE/Flash has an evil bug that shows up some time: if we load the
        // Flash and it isn't in the cache, ExternalInterface works fine --
        // however, the second time when its loaded from the cache a timing
        // bug can keep ExternalInterface from working. The trick below 
        // simply invalidates the Flash object in the cache all the time to
        // keep it loading fresh. -- Brad Neuberg
        swflocObject += "?cachebust=" + new Date().getTime();
    

var domain = location.protocol == 'file:' ? 'always' : 'sameDomain';

objectHTML =
'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '
+ 'codebase="'
+ this.protocol()
+ '://fpdownload.macromedia.com/pub/shockwave/cabs/flash/'
+ 'swflash.cab#version=8,0,0,0"\n '
+ 'width="' + this.width + '"\n '
+ 'height="' + this.height + '"\n '
+ 'id="' + this.id + '"\n '
+ 'name="' + this.id + '"\n '
+ 'align="middle">\n '
+ '<param name="allowScriptAccess" value="'+domain+'"></param>\n '
+ '<param name="movie" value="' + swflocObject + '"></param>\n '
+ '<param name="quality" value="high"></param>\n '
+ '<param name="bgcolor" value="#ffffff"></param>\n '
+ '<embed src="' + swflocEmbed + '" '
+ 'quality="high" '
+ 'bgcolor="#ffffff" '
+ 'width="' + this.width + '" '
+ 'height="' + this.height + '" '
+ 'id="' + this.id + 'Embed' + '" '
+ 'name="' + this.id + '" '
+ 'swLiveConnect="true" '
+ 'align="middle" '
+ 'allowScriptAccess="'+domain+'" '
+ 'type="application/x-shockwave-flash" '
+ 'pluginspage="'
+ this.protocol()
+'://www.macromedia.com/go/getflashplayer" '
+ '></embed>\n'
+ '</object>\n';

// using same mechanism on all browsers now to write out
// Flash object into page

// document.write no longer works correctly
// due to Eolas patent workaround in IE;
// nothing happens (i.e. object doesn't
// go into page if we use it)
var contId = this.id + "Container";
if (useWrite){
    var html = '<div id="'+contId+'" style="'+containerStyle+'">'+objectHTML+'</div>';
    SRAX.writeln(html);
    this.isWrited = true;
    SRAX.Storage.antiblock(contId);
} else {
  var _this = this;
  SRAX.onReady(function (){
      if (_this.isWrited) return;
      var div = document.createElement("div");
      div.setAttribute("id", contId);
      div.setAttribute("style", containerStyle);
      div.innerHTML = objectHTML;

      var body = document.getElementsByTagName("body");
      if(!body || !body.length){
          throw new Error("No body tag for this page");
      }
      body = body[0];
      body.appendChild(div);
      _this.isWrited = true;
      SRAX.Storage.antiblock(div);
  })
}

},  

get: function(){ /* Object */
    // summary: Gets the Flash object DOM node.
    if(dojo.isIE || dojo.isSafari){
        return document.getElementById(this.id);
    }else{
    // different IDs on OBJECT and EMBED tags or
    // else Firefox will return wrong one and
    // communication won't work; 
    // also, document.getElementById() returns a
    // plugin but ExternalInterface calls don't
    // work on it so we have to use
    // document[id] instead
    return document[this.id + "Embed"];
}
},

setVisible: function(/* Boolean */ visible){
    //console.debug("setVisible, visible="+visible);
    
    // summary: Sets the visibility of this Flash object.		
    var container = dojo.byId(this.id + "Container");
    if(visible == true){
        container.style.position = "absolute"; // IE -- Brad Neuberg
        container.style.visibility = "visible";
    }else{
    container.style.position = "absolute";
    container.style.x = "-1000px";
    container.style.y = "-1000px";
    container.style.visibility = "hidden";
}
},

center: function(){
    // summary: Centers the flash applet on the page.
    
    var elementWidth = this.width;
    var elementHeight = this.height;
    
    var viewport = dojo.getViewport();
    
    // compute the centered position    
    var x = viewport.l + (viewport.w - elementWidth) / 2;
    var y = viewport.t + (viewport.h - elementHeight) / 2; 
    
    // set the centered position
    var container = dojo.byId(this.id + "Container");
    container.style.top = y + "px";
    container.style.left = x + "px";
}
};


dojox.flash.Communicator = function(){
}

dojox.flash.Communicator.prototype = {
    // Registers the existence of a Flash method that we can call with
    // JavaScript, using Flash 8's ExternalInterface. 
    _addExternalInterfaceCallback: function(methodName){
        
        var _this = this;
        this[methodName] = function(){
            // some browsers don't like us changing values in the 'arguments' array, so
            // make a fresh copy of it
            var methodArgs = new Array(arguments.length);
            for(var i = 0; i < arguments.length; i++){
                methodArgs[i] = _this._encodeData(arguments[i]);
            }
            
            var results = _this._execFlash(methodName, methodArgs);
            results = _this._decodeData(results);
            
            return results;
        };
    },
    
    // Encodes our data to get around ExternalInterface bugs that are still
    // present even in Flash 9.
    _encodeData: function(data){
    		if(!data || typeof data != "string"){
    			return data;
    		}
    		
    		// transforming \ into \\ doesn't work; just use a custom encoding
    		data = data.replace("\\", "&custom_backslash;");
    
    		// also use custom encoding for the null character to avoid problems 
    		data = data.replace(/\0/g, "&custom_null;");
    
    		return data;

    
        /*
        //old realization
        if(!data || typeof data != "string"){
            return data;
        }
        
        // double encode all entity values, or they will be mis-decoded
        // by Flash when returned
        var entityRE = /\&([^;]*)\;/g;
        data = data.replace(entityRE, "&amp;$1;");
        
        // entity encode XML-ish characters, or Flash's broken XML serializer
        // breaks
        data = data.replace(/</g, "&lt;");
        data = data.replace(/>/g, "&gt;");
        
        // transforming \ into \\ doesn't work; just use a custom encoding
        data = data.replace("\\", "&custom_backslash;");
        
        data = data.replace(/\0/g, "\\0"); // null character
        data = data.replace(/\"/g, "&quot;");
        
        return data;
        */
    },
    
    // Decodes our data to get around ExternalInterface bugs that are still
    // present even in Flash 9.
    _decodeData: function(data){
    		//console.debug("decodeData, data=", data);
    		// wierdly enough, Flash sometimes returns the result as an
    		// 'object' that is actually an array, rather than as a String;
    		// detect this by looking for a length property; for IE
    		// we also make sure that we aren't dealing with a typeof string
    		// since string objects have length property there
    		if(data && data.length && typeof data != "string"){
    			data = data[0];
    		}
    		
    		if(!data || typeof data != "string"){
    			return data;
    		}
    		
    		// needed for IE; \0 is the NULL character 
    		data = data.replace(/\&custom_null\;/g, "\0");
    	
    		// certain XMLish characters break Flash's wire serialization for
    		// ExternalInterface; these are encoded on the 
    		// DojoExternalInterface side into a custom encoding, rather than
    		// the standard entity encoding, because otherwise we won't be able to
    		// differentiate between our own encoding and any entity characters
    		// that are being used in the string itself
    		data = data.replace(/\&custom_lt\;/g, "<")
    			.replace(/\&custom_gt\;/g, ">")
    			.replace(/\&custom_backslash\;/g, '\\');
    		
    		return data;    
        /*
        //old realiztion
        // wierdly enough, Flash sometimes returns the result as an
        // 'object' that is actually an array, rather than as a String;
        // detect this by looking for a length property; for IE
        // we also make sure that we aren't dealing with a typeof string
        // since string objects have length property there
        if(data && data.length && typeof data != "string"){
            data = data[0];
        }
        
        if(!data || typeof data != "string"){
            return data;
        }
        
        // certain XMLish characters break Flash's wire serialization for
        // ExternalInterface; these are encoded on the 
        // DojoExternalInterface side into a custom encoding, rather than
        // the standard entity encoding, because otherwise we won't be able to
        // differentiate between our own encoding and any entity characters
        // that are being used in the string itself
        data = data.replace(/\&custom_lt\;/g, "<");
        data = data.replace(/\&custom_gt\;/g, ">");
        data = data.replace(/\&custom_backslash\;/g, '\\');
        
        // needed for IE; \0 is the NULL character
        data = data.replace(/\\0/g, "\0");
        
        return data;
        */
    },
    
    // Executes a Flash method; called from the JavaScript wrapper proxy we
    // create on dojox.flash.comm.
    _execFlash: function(methodName, methodArgs){
        var plugin = dojox.flash.obj.get();
        methodArgs = (methodArgs) ? methodArgs : [];
        
        // encode arguments that are strings
        for(var i = 0; i < methodArgs; i++){
            if(typeof methodArgs[i] == "string"){
                methodArgs[i] = this._encodeData(methodArgs[i]);
            }
        }
        
        // we use this gnarly hack below instead of 
        // plugin[methodName] for two reasons:
        // 1) plugin[methodName] has no call() method, which
        // means we can't pass in multiple arguments dynamically
        // to a Flash method -- we can only have one
        // 2) On IE plugin[methodName] returns undefined -- 
        // plugin[methodName] used to work on IE when we
        // used document.write but doesn't now that
        // we use dynamic DOM insertion of the Flash object
        // -- Brad Neuberg
        var flashExec = function(){ 
            return eval(plugin.CallFunction(
                "<invoke name=\"" + methodName
                + "\" returntype=\"javascript\">" 
                + __flash__argumentsToXML(methodArgs, 0) 
                + "</invoke>")); 
        };
        var results = flashExec.call(methodArgs);
        
        if(typeof results == "string"){
            results = this._decodeData(results);
        }
        
        return results;
    }
}


dojo.getViewport = function(){
    //	summary
    //	Returns the dimensions and scroll position of the viewable area of a browser window
    
    var _window = dojo.global;
    var _document = dojo.doc;
    
    // get viewport size
    var w = 0, h = 0;
    var de = _document.documentElement;
    var dew = de.clientWidth, deh = de.clientHeight;
    if(dojo.isMozilla){
        // mozilla
        // _window.innerHeight includes the height taken by the scroll bar
        // clientHeight is ideal but has DTD issues:
        // #4539: FF reverses the roles of body.clientHeight/Width and documentElement.clientHeight/Width based on the DTD!
        // check DTD to see whether body or documentElement returns the viewport dimensions using this algorithm:
        var minw, minh, maxw, maxh;
        var dbw = _document.body.clientWidth;
        if(dbw > dew){
            minw = dew;
            maxw = dbw;
        }else{
        maxw = dew;
        minw = dbw;
    }
    var dbh = _document.body.clientHeight;
    if(dbh > deh){
        minh = deh;
        maxh = dbh;
    }else{
    maxh = deh;
    minh = dbh;
}
w = (maxw > _window.innerWidth) ? minw : maxw;
h = (maxh > _window.innerHeight) ? minh : maxh;
}else if(!dojo.isOpera && _window.innerWidth){
//in opera9, dojo.body().clientWidth should be used, instead
//of window.innerWidth/document.documentElement.clientWidth
//so we have to check whether it is opera
w = _window.innerWidth;
h = _window.innerHeight;
}else if(dojo.isIE && de && deh){
w = dew;
h = deh;
}else if(dojo.body().clientWidth){
// IE5, Opera
w = dojo.body().clientWidth;
h = dojo.body().clientHeight;
}

// get scroll position
var scroll = dojo._docScroll();

return { w: w, h: h, l: scroll.x, t: scroll.y };	//	object
};



dojox.storage = {};
dojox.storage.FlashStorageProvider = function(){
    this.initialize();
    this.onReady(this.ax_initialize);
}
dojox.storage.FlashStorageProvider.prototype = {
    constructor: function(){
    },
    
    // SUCCESS: String
    //	Flag that indicates a put() call to a 
    //	storage provider was succesful.
    SUCCESS: "success",
    
    // FAILED: String
    //	Flag that indicates a put() call to 
    //	a storage provider failed.
    FAILED: "failed",
    
    // PENDING: String
    //	Flag that indicates a put() call to a 
    //	storage provider is pending user approval.
    PENDING: "pending",
    
    // DEFAULT_NAMESPACE: String
    //	The namespace for all storage operations. This is useful if several
    //	applications want access to the storage system from the same domain but
    //	want different storage silos. 
    DEFAULT_NAMESPACE: "default",
    
    onHideSettingsUI: null,

    initialized: false,
    
    _available: null,
    _statusHandler: null,
    _flashReady: false,
    _pageReady: false,
    
    swfLoc: dojo.config.swfLoc ? dojo.config.swfLoc : "Storage.swf",
    
    initialize: function(){
        //console.debug("FlashStorageProvider.initialize");
        if(dojo.config["disableFlashStorage"] == true){
            return;
        }
        
        // initialize our Flash
        
        var _this = this;
        dojox.flash.addLoadedListener(function(){
            _this._flashReady = true;
            if(_this._flashReady && _this._pageReady){
                _this._loaded();
            }
        });
        dojox.flash.setSwf(this.swfLoc, false);
        
        // wait till page is finished loading
        SRAX.onReady(function(){
            _this._pageReady = true;            
            if(_this._flashReady && _this._pageReady){
                _this._loaded();
            }
        });
    },
    
    //	Set a new value for the flush delay timer.
    //	Possible values:
    //	  0 : Perform the flush synchronously after each "put" request
    //	> 0 : Wait until 'newDelay' ms have passed without any "put" request to flush
    //	 -1 : Do not  automatically flush
    setFlushDelay: function(newDelay){
        if(newDelay === null || typeof newDelay === "undefined" || isNaN(newDelay)){
            throw new Error("Invalid argunment: " + newDelay);
        }
        
        dojox.flash.comm.setFlushDelay(String(newDelay));
    },
    
    getFlushDelay: function(){
        return Number(dojox.flash.comm.getFlushDelay());
    },
    
    flush: function(namespace){
        //FIXME: is this test necessary?  Just use !namespace
        if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		
        dojox.flash.comm.flush(namespace);
    },
    
    isAvailable: function(){
        return (this._available = !dojo.config["disableFlashStorage"]);
    },
    
    put: function(key, value, resultsHandler, namespace){
        if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		
        
        namespace += '/';
        var _this = this;
        this._statusHandler = function(){
            if (arguments[2]) arguments[2] = _this._transformKey(arguments[2].substring(namespace.length),1) 
            resultsHandler.apply(arguments.callee, arguments)
        };
        
        // serialize the value;
        // handle strings differently so they have better performance
        if(dojo.isString(value)){
            value = "string:" + value;
        } else{
            value = dojo.toJson(value);
        }
    
    dojox.flash.comm.put('data', value, namespace + this._transformKey(key));
},
_transformKey : function(key, reverse){
    var obj = {
        '//' : '/_SPRT_/',
        '?' : '_QUEST_',
        ':' : '_DBL_POINT_'
    }
    for (var i in obj){
        if (reverse) {
          key = key.replaceAll(obj[i], i);
        } else {
          key = key.replaceAll(i, obj[i]);
        } 
    }
    if (reverse){
      if (key.startWith('_SPRT_/')) key = '/' + key.substring(7);
      if (key.endWith('_SPRT_')) key = key.substring(0, key.length - 6) + '/';
    } else {
      if (key.startWith('/')) key = '_SPRT_/' + key.substring(1); 
      if (key.endWith('/')) key = key.substring(0, key.length - 1) + '_SPRT_';
    }
    return key;
},

get: function(key, namespace){   
    if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		    
    var results = dojox.flash.comm.get('data', namespace + '/' + this._transformKey(key));    
    if(results == "") return null;    
    return this._destringify(results);
},

_destringify: function(results){
    // destringify the content back into a 
    // real JavaScript object;
    // handle strings differently so they have better performance
    if(dojo.isString(results) && (/^string:/.test(results))){
        results = results.substring("string:".length);
    } else{
      results = eval("(" + results + ")");
    }

return results;
},

getKeys: function(namespace){
    if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		
    
    namespace += '/';
    var result = [], 
        arr = this._getNamespaces(),
        nl = namespace.length;
    for (var i = 0, n = arr.length; i < n; i++){
        if (arr[i].indexOf(namespace) == 0){
            result.push(this._transformKey(arr[i].substring(nl), 1))    
        }
    }
    result.sort();
    return result;
},

getNamespaces: function(){
    var result = this._getNamespaces(),
        obj = {},
        namespaces = [];        
    for (var i = 0, n = result.length; i < n; i++){
        var ind = result[i].indexOf('/');
        if (ind > -1){
            var ns = result[i].substring(0,ind);
            if (!obj[ns]){
              obj[ns] = 1;
              namespaces.push(ns)
            }
        }
    }
    namespaces.sort();    
    return namespaces;
},

_getNamespaces: function(){
    var results = dojox.flash.comm.getNamespaces();
    
    // Flash incorrectly returns an empty string as "null"
    if(results == null || results == "null"){
        results = strg.DEFAULT_NAMESPACE;
    }
    
    results = results.split(",");
    results.sort();
    
    return results;
},

clear: function(namespace){
    namespace += '/';
    var arr = this._getNamespaces();
    for (var i = 0, n = arr.length; i < n; i++){
        if (arr[i].indexOf(namespace) == 0){
            this._clear(arr[i]);
        }
    }

},

_clear: function(namespace){
    if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		
    
    dojox.flash.comm.clear(namespace);
},

remove: function(key, namespace){
    if(namespace == null) namespace = strg.DEFAULT_NAMESPACE;		
    this._clear(namespace + '/' + this._transformKey(key));
},

showSettingsUI: function(){
    dojox.flash.comm.showSettings();
    dojox.flash.obj.setVisible(true);
    dojox.flash.obj.center();
},

hideSettingsUI: function(){
    // hide the dialog
    dojox.flash.obj.setVisible(false);
    
    // call anyone who wants to know the dialog is
    // now hidden
    if(dojo.isFunction(dojox.storage.onHideSettingsUI)){
        dojox.storage.onHideSettingsUI.call(null);	
    }
},

_loadedListeners: [],
addLoadedListener: function(listener){
    if (this.isReady) listener(); else this._loadedListeners.push(listener);
},
/** Called when Flash and the page are finished loading. */
_loaded: function(){
    
    this.isReady = this.initialized = true;
    if (this._loadedListeners.length > 0){
        for(var i = 0;i < this._loadedListeners.length; i++){
            try{
                this._loadedListeners[i].call(null);
            } catch (ex){
                error(ex);
            }            
        }
    }
    this._loadedListeners = [];
    
    // indicate that this storage provider is now loaded
},

//	Called if the storage system needs to tell us about the status
//	of a put() request. 
_onStatus: function(statusResult, key, namespace){
    //console.debug("onStatus, statusResult="+statusResult+", key="+key);
    var ds = dojox.storage;
    var dfo = dojox.flash.obj;
    
    if(statusResult == ds.PENDING){
        dfo.center();
        dfo.setVisible(true);
    }else{
    dfo.setVisible(false);
}

if(ds._statusHandler){
    ds._statusHandler.call(null, statusResult, key, namespace);		
}
},

onReady : function(handler){
    this.addLoadedListener(handler);
},

antiblock: function(parent){
  parent = SRAX.get(parent)
  if (parent.antiblock > 5) return;
  setTimeout(function(){
      parent.antiblock++;      
      var object = parent.getElementsByTagName('object')[0];
      var embed = parent.getElementsByTagName('embed')[0];
      if (!embed && !object){
          var blocker = parent.getElementsByTagName('div')[0];
          if (blocker && blocker.onclick) blocker.onclick();
      } else {
          SRAX.Storage.antiblock(parent);
      }
  }, parent.antiblock ? 1000 : 100);
  if (!parent.antiblock) parent.antiblock = 0;      
},

ax_initialize : function(){
    if (SRAX.browser.msie){
        window.__flash__removeCallback = function(instance, name){
          if (instance) instance[name] = null;
        }        
    }
    function isUse(val){
        return val == null ? SRAX.Default.USE_STORAGE : val;
    }
    function isEtag(val){
        return val == null ? SRAX.Default.STORAGE_ETAG : val;
    }
    if (SRAX.Html){
        SRAX.Html.onall('beforerequest', function(ops){
            try{
                var o = ops.options;
                if (isUse(o.storage) && !o.html && !o.anticache) {
                    if (isEtag(o.etag)) {
                        var etag = strg.get(ops.url, 'etag');
                        var data = strg.get(ops.url, 'data');                                                     
                        if (data && etag){
                            if (!o.headers) o.headers = {};
                            o.headers['If-None-Match'] = etag; 
                        }
                    } else {
                        o.html = strg.get(ops.url, 'data');
                    }
                }
            } catch (ex){
                error(ex);
            }
        })
        SRAX.Html.onall('response', function(ops){
            try{
                var o = ops.options;
                if (ops.success && isUse(o.storage) && !o.html) {
                    if (isEtag(o.etag)){
                        var newEtag = ops.xhr.getResponseHeader('Etag'),
                            etag = strg.get(ops.url, 'etag'),
                            save = 1;
                        if (newEtag && etag == newEtag){
                            var data = strg.get(ops.url, 'data');
                            if (data) {
                                ops.text = ops.responseText = data;
                                save = 0;
                            }
                        }
                        if (save){
                            strg.put(ops.url, newEtag, function(status, key){}, 'etag');
                            strg.put(ops.url, ops.text, function(status, key){}, 'data');
                        }
                    } else {
                        strg.put(ops.url, strg.getNewnessEtag(ops.url), function(status, key){}, 'etag');
                        strg.put(ops.url, ops.text, function(status, key){}, 'data');
                    }                    
                }
            } catch (ex){
                error(ex);
            }
        })
    }
    if (SRAX.Data){
        SRAX.Data.onall('beforerequest', function(ops){
            try{
                var o = ops.options;
                if (isUse(o.storage) && !o.text && !o.xml && !o.anticache) {
                    if (isEtag(o.etag)) {
                        var etag = strg.get(ops.url, 'etag');
                        var data = strg.get(ops.url, 'data');
                        if (data && etag){
                            if (!o.headers) o.headers = {};
                            o.headers['If-None-Match'] = etag; 
                        }
                    } else {
                        var val = strg.get(ops.url, 'data');
                        if (val) {
                            o.xml = val.xml;
                            o.text = val.text;
                            o.status = val.status;
                        }
                    }
                }
            } catch (ex){
                error(ex);
            }
        })
        SRAX.Data.onall('response', function(ops){
            try{
                var o = ops.options;
                if (ops.success && isUse(o.storage) && !o.text && !o.xml) {
                    if (isEtag(o.etag)){
                        var newEtag = ops.xhr.getResponseHeader('Etag'),
                            etag = strg.get(ops.url, 'etag'),
                            save = 1;
                        if (newEtag && etag == newEtag){
                            var data = strg.get(ops.url, 'data');
                            if (data){
                                ops.xml = ops.responseXML = data.xml;
                                ops.text = ops.responseText = data.text;
                                ops.status = data.status;
                                save = 0;
                            }
                        } 
                        if (save) {
                            strg.put(ops.url, newEtag, function(status, key){}, 'etag');
                            strg.put(ops.url, {text:ops.text, xml:ops.xml, status:ops.status}, function(status, key){}, 'data');
                        }                        
                    } else {
                        strg.put(ops.url, strg.getNewnessEtag(ops.url), function(status, key){}, 'etag');
                        strg.put(ops.url, {text:ops.text, xml:ops.xml, status:ops.status} , function(status, key){}, 'data');
                    }                    
                }
            } catch (ex){
                error(ex);
            }
        })        
    }
    if (SRAX.Default.STORAGE_ETAG) return;
    /**
    * ѕроверка новизны (newness) данных - такой вариант оптимален, дл€ малого количества страниц    
    **/
    var nws = strg.Newness;
    var keys = strg.getKeys('etag');
    for (var i = 0, n = keys.length; i < n; i++){
      for (var k in nws){
          if (keys[i] && strg.equalsKey(keys[i], k)){
              if (strg.get(keys[i], 'etag') == nws[k]) continue;
              strg.remove(keys[i], 'data');
              strg.put(keys[i], nws[k], null, 'etag');
          }
      }
    }
    
    strg.put('storage', strg.version, null, 'version');
    
    
},

equalsKey : function(key1, key2){
    return key1 == key2;
},

getNewnessEtag : function(key){
    var nws = this.Newness;
    for (var k in nws){
        if (this.equalsKey(key, k)) return nws[k];
    }
},

isPosible : function(){
    var exist = false;
    try {
	exist = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.8");
    } catch (e) {
        exist = navigator.plugins['Shockwave Flash'];
    }
    this.isPosible = function(){
        return exist;
    }
    return exist;
},

write : function(){
  dojox.flash.obj.write(true);    
},

version: 'v1.1 beta (build 2)'


}

var strg = SRAX.Storage = dojox.storage = new dojox.storage.FlashStorageProvider();




}
