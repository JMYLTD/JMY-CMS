var JASplit2Menu = new Class({
	
	initialize: function(el, options){
		this.options = Object.extend({
			minwidth: 0,
			maxwidth: 0,
			offwidth: 50,
			navwidth: 0,
			showactive: true
		}, options || {});
		if (!el) return;
		this.element = $(el);
		items = this.element.getChildren();
		if (items.length < 2) return;
		this._active = 0;
		var hw = 0;
		var sp = this.element.getElements('span')[0].getStyle('padding-left').toInt() + this.element.getElements('span')[0].getStyle('padding-right').toInt();
		if(this.options.showactive){
			if (!this.options.minwidth || !this.options.maxwidth)
			{
				if(!this.options.navwidth) this.options.navwidth = this.element.offsetWidth;
				this.options.minwidth = Math.round((this.options.navwidth - this.options.offwidth)/items.length);
				this.options.maxwidth = this.options.navwidth - this.options.minwidth*(items.length-1);
			}
			hw = this.options.minwidth
		} else {
			if (this.options.minwidth && this.options.maxwidth)
			{
				this.options.normalwidth = Math.floor(((items.length-1)*this.options.minwidth + this.options.maxwidth) / items.length);
			}else{
				if(!this.options.navwidth) this.options.navwidth = this.element.offsetWidth;
				this.options.normalwidth = Math.floor(this.options.navwidth / items.length);
				this.options.navwidth = this.options.normalwidth * items.length
				this.options.minwidth = this.options.normalwidth - Math.round(this.options.offwidth/(items.length-1));
				this.options.maxwidth = this.options.navwidth - this.options.minwidth*(items.length-1);
			}
			hw = this.options.normalwidth
		}
		var fx = new Fx.Elements(items, {wait: false, duration: 200, transition: Fx.Transitions.quadOut});
		items.each(function(item, i){
			//check if this is active one
			if(this.options.showactive){
				if (item.className.test('activeSl'))
				{
					this._active = i;
					item.setStyle('width', this.options.maxwidth);
				} else {
					item.setStyle('width', this.options.minwidth);
				}
			}else{
				item.setStyle('width', this.options.normalwidth);
			}
			item.getElements('span').setStyle('width', hw-sp);
			if (item.getElements('img'))
			{
				item.getElements('a').setStyles({'position': 'relative','overflow':'hidden'});
				item.getElements('img').setStyles({
					'position': 'absolute',
					'left': (hw + 1) + 'px'
				});
			}else{
				item.getElements('a').setStyle('background-position', (hw + 1) + 'px 0');
			}

			item.addEvent('mouseenter', function(e){
				var obj = {};
				obj[i] = {
					'width': [item.getStyle('width').toInt(), this.options.maxwidth]
				};
				items.each(function(other, j){
					if (other != item){
						var w = other.getStyle('width').toInt();
						if (w != this.options.minwidth) obj[j] = {'width': [w, this.options.minwidth]};
					}
				}.bind(this));
				fx.start(obj);
			}.bind(this));

		}.bind(this));

		this.element.setStyles({'width': this.options.navwidth+5,'visibility':'visible'});

		this.element.addEvent('mouseleave', function(e){
			if (this.options.showactive)
			{
				this._doactive();
			}else{
				var obj = {};
				items.each(function(other, j){
					obj[j] = {'width': [other.getStyle('width').toInt(), this.options.normalwidth]};
				}.bind(this));
				fx.start(obj);
			}
		}.bind(this));

		if (this.options.showactive)
		{
			this._doactive = function(){
				var obj = {};
				var item = items[this._active]
				obj[this._active] = {
					'width': [item.getStyle('width').toInt(), this.options.maxwidth]
				};
				items.each(function(other, j){
					if (other != item){
						var w = other.getStyle('width').toInt();
						if (w != this.options.minwidth) obj[j] = {'width': [w, this.options.minwidth]};
					}
				}.bind(this));
				fx.start(obj);
			}.bind(this);
		}
	}
	
});