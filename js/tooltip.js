// checkVersion('tooltip', 1);

// http://sixrevisions.com/tutorials/javascript_tutorial/create_lightweight_javascript_tooltip
// requires base64.js

var tooltip=function(){
	var top = 3;
	var left = 3;
	var maxw = 300;
	var speed = 10;
	var timer = 20;
	var endalpha = 95;
	var alpha = 0;
	var tt,t,c,b,h;
	var ie = document.all ? true : false;
	return{
		show:function(v,w,cn,a){
			if (typeof hideTooltips == 'boolean' && !cn) {
				return;
			}
			if (a && a.hideThisTooltip) {
				return;
			}
			if(tt == null){
				tt = document.createElement('div');
				t = document.createElement('div');
				t.setAttribute('class', 'tttop');
				c = document.createElement('div');
				c.setAttribute('class', 'ttcont');
				b = document.createElement('div');
				b.setAttribute('class', 'ttbot');
				tt.appendChild(t);
				tt.appendChild(c);
				tt.appendChild(b);
				document.body.appendChild(tt);
				tt.style.opacity = 0;
				tt.style.filter = 'alpha(opacity=0)';
				document.onmousemove = this.pos;
			}
			tt.setAttribute('class',(cn ? cn : 'tt'));
			tt.style.display = 'block';
			c.innerHTML = v;
			tt.style.width = w ? w + 'px' : 'auto';
			if(!w && ie){
				t.style.display = 'none';
				b.style.display = 'none';
				tt.style.width = tt.offsetWidth;
				t.style.display = 'block';
				b.style.display = 'block';
			}
			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
			h = parseInt(tt.offsetHeight) + top;
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(1)},timer);
		},
		base64:function(e,w,cn,a) {
			this.show(base64_decode(e),w,cn,a);
		},
		pos:function(e){
			var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
			tt.style.top = (u - h) + 'px';
			tt.style.left = (l + left) + 'px';
		},
		fade:function(d){
			var a = alpha;
			if((a != endalpha && d == 1) || (a != 0 && d == -1)){
				var i = speed;
				if(endalpha - a < speed && d == 1){
					i = endalpha - a;
				}else if(alpha < speed && d == -1){
					i = a;
				}
				alpha = a + (i * d);
				tt.style.opacity = alpha * .01;
				tt.style.filter = 'alpha(opacity=' + alpha + ')';
			}else{
				clearInterval(tt.timer);
			}
			if(d == -1){tt.style.display = 'none'}
		},
		hide:function(){
			if (tt && tt.style.display != 'none') {
				clearInterval(tt.timer);
				tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
		}	}
	};
}();
