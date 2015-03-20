/**
* @name FX Effect
*
* @author Ruslan Sinitskiy (si-rus)
*
* http://www.fullajax.ru/#:license
*/



var Fax = {

    timer : function(startValue, endValue, timeout, count){
        if (timeout == null) this.timeout = 10; else this.timeout = timeout;
        if (count == null) this.count = 20; else this.count = count;

        var i = 1;
        this.finishPercent = i/this.count;
        this.startPercent = 1 - this.finishPercent;
        this.isEnd = false;

        
        this.percent = function (){
            return this.finishPercent;
        };  

        this.value = function (){
            return startValue + i * (endValue - startValue)/this.count;
        };  
  
        this.stop = function(){this.isEnd = true;};
  
        this.change = function(){};
  
        this.update = function() {  
            this.change();     
            i++;
            this.finishPercent = i/this.count;
            this.startPercent = 1 - this.finishPercent;
        };

        this.start = function start() {
            if ((i <= this.count && !this.isEnd) || (startValue == endValue)) {
                this.update();
                var _this = this;
                this.recall = function() {_this.start()};
                setTimeout(this.recall, this.timeout);
            } else {
                this.isEnd = true;
                this.afterEnd();
            }
        };
        
        this.afterEnd = function(){};

    }, 
    
    jerk : function(obj){
        if (typeof obj == 'string') obj = id(obj);
        var tmr = new Fax.timer(0, 0, 100, 1);
        var delta = 10;

        tmr.change = function(){
            var pos = Fax.findPosition(obj);
            obj.style.top = nextPosition(pos[1]) + 'px';   
            obj.style.left = nextPosition(pos[0]) + 'px';   
        }

        function nextPosition(px){
            val = Math.random();
            if (Math.random() >= .5) val = -val;
            px += val;
            return px ;               
        }
        

        tmr.start();
    },


    jerk2 : function(obj){
        if (typeof obj == 'string') obj = id(obj);
        var tmr = new Fax.timer(0, 0, 100, 1);
        var start = Fax.findPosition(obj);
        var delta = 10;
        var plusH = (Math.random() >= .5) ? true : false;
        var plusW = (Math.random() >= .5) ? true : false;
        tmr.change = function(){
            var pos = Fax.findPosition(obj);
            if (start[0] + delta < pos[0]) plusH = false;
            if (start[0] - delta > pos[0]) plusH = true;
            if (start[1] + delta < pos[1]) plusW = false;
            if (start[1] - delta > pos[1]) plusW = true;
            pos[0] += plusH ? Math.random() : -Math.random();
            pos[1] += plusW ? Math.random() : -Math.random();
            obj.style.top = pos[1] + 'px';   
            obj.style.left = pos[0] + 'px';   
        }

        tmr.start();
    },

    jerk3 : function(obj){
        var objPos = new Array();
        for (var i = 0; i < obj.length; i++){
            obj[i] = SRAX.get(obj[i]);
            if (!obj[i]) continue;
            var opos = Fax.findPosition(obj[i]);
            objPos[i] = opos;
        }
        var tmr = new Fax.timer(0, 0, 100, 1);
        var deltaH = 30;
        var deltaW = 10;
        var start = [0,0];
        var pos = [0,0];
        var plusH = (Math.random() >= .5) ? true : false;
        var plusW = (Math.random() >= .5) ? true : false;
        tmr.change = function(){

            pos[0] += plusH ? Math.random() : -Math.random();
            pos[1] += plusW ? Math.random() : -Math.random();

            if (start[0] + deltaH < pos[0]) plusH = false;
            if (start[0] - deltaH > pos[0]) plusH = true;
            if (start[1] + deltaW < pos[1]) plusW = false;
            if (start[1] - deltaW > pos[1]) plusW = true;

            for (var i = 0; i < obj.length; i++){
                obj[i] = SRAX.get(obj[i]);
                if (!obj[i]) continue;
                var val = objPos[i][1] + pos[1];
                obj[i].style.top =  val + 'px';   
                val = objPos[i][0] + pos[0];
                obj[i].style.left = val + 'px';   
    
            }

        }

        tmr.start();
    },

    size : function(obj, timeout, count){
        if (typeof obj == 'string') obj = id(obj);
        end = obj.clientWidth;
        var tmr = new Fax.timer(0, end, timeout, count);
        
        var fontSize = Fax.getStyle(obj,'fontSize');
        var ed = '%';
        if (fontSize.indexOf(ed) == -1) ed = 'px';
        fontSize = parseFloat(fontSize.substring(0,fontSize.length - ed.length));

        var right = parseFloat(obj.style.left.substring(0,obj.style.left.length - 2)) + obj.clientWidth;
        var left = parseFloat(obj.style.left.substring(0,obj.style.left.length - 2));

        tmr.change = function(){
            var val = tmr.value();
            obj.style.left = (left - val) + 'px';
            obj.style.fontSize = Math.ceil(fontSize + tmr.percent() * fontSize) + ed;
        }

        tmr.start();
    }, 

    puff : function(obj){

        var dubl = document.createElement('div');        
        var pos = Fax.findPosition(obj);

        dubl.style.fontSize = Fax.getStyle(obj, 'fontSize');
        dubl.style.color = Fax.getStyle(obj, 'color');
        dubl.style.fontColor = Fax.getStyle(obj, 'fontColor');
        dubl.style.position = 'absolute';
        dubl.style.left = pos[0] + 'px';
        dubl.style.top = pos[1] + 'px';
        var a = obj.getElementsByTagName('a');
        if (a && a[0] && a[0].innerHTML) dubl.innerHTML = a[0].innerHTML; else return; // dubl.innerHTML = obj.innerHTML;

        document.body.appendChild(dubl);

        if (SRAX.browser.msie) dubl.style.backgroundColor = 'white';

        new Fax.size(dubl, 20, 20);
        var op = new Fax.opacity(dubl, 1, 0, 200, 100);
        op.afterEnd = function(){
            dubl.parentNode.removeChild(dubl);
        }

    },

    findPosition : function(obj) {
        if (typeof obj == 'string') obj = id(obj);
        var curleft = curtop = 0;
        if (obj.offsetParent) {
            curleft = obj.offsetLeft
            curtop = obj.offsetTop
            while (obj = obj.offsetParent) {
                if (obj.id == 'linkerLayer') break;
                curleft += obj.offsetLeft
                curtop += obj.offsetTop
            }
        }
        return [curleft,curtop];        
    },


    getStyle : function(obj, name){
        var val;
        
        if (document.defaultView && document.defaultView.getComputedStyle) {
            var css = document.defaultView.getComputedStyle(obj, null);
            val = css ? css[name] : null;
        } else if (obj.currentStyle) {
            val = obj.currentStyle[name];
        }

        return val;
    },

    opacity : function(obj, start, end, timeout, type, count){
        if (typeof obj == 'string') obj = id(obj);
        var tmr = new Fax.timer(start, end, timeout, count);
        tmr.count = 5;
        var agt = navigator.userAgent.toLowerCase();
        if (window.ActiveXObject) obj.style.zoom = 1;
  
        tmr.change = function(){
            var opacity = tmr.value();

            if (window.ActiveXObject) {
                if (type == 1) {           
                    if (opacity < 0.3) {
                        obj.style.filter = "alpha(opacity=0)";
                    } else {
                        //obj.style.filter = "alpha(opacity=1)";
                        obj.style.filter = "AlphaImageLoader(src='', sizingMethod='scale')";
                    }
                } else {
                    obj.style.filter = "alpha(opacity=" + opacity*100 + ")";
                }
            } else {
                obj.style.KHTMLOpacity = opacity; // Safari and Konqueror
                obj.style.MozOpacity = opacity; // Old Mozilla and Firefox
                obj.style.opacity = opacity;   
            }
   
        };
        var _this = this;
        tmr.afterEnd = function(){
            _this.afterEnd();
        };

        this.afterEnd = function(){};

        tmr.start();
        this.timer = tmr;

    }

}

