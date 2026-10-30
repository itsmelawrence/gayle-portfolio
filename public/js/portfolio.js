(function(){
  var doc=document.documentElement, body=document.body;
  body.classList.remove('no-js'); doc.classList.add('js');
  var reduce=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var baseTitle=document.title;

  // Hero headline: split into words for the load reveal (screen readers get the original text)
  var h1=document.getElementById('hero-title');
  if(h1){
    var text=h1.textContent;
    h1.setAttribute('aria-label',text);
    h1.innerHTML=text.split(' ').map(function(w,i){
      return '<span class="w" aria-hidden="true"><span style="--i:'+i+'">'+w+'</span></span>';
    }).join(' ');
  }

  // Header border once scrolled
  var header=document.querySelector('.site-header');
  var onScroll=function(){header.classList.toggle('scrolled',window.scrollY>8)};
  window.addEventListener('scroll',onScroll,{passive:true}); onScroll();

  // Mobile menu
  var menuBtn=document.querySelector('.menu-btn'), links=document.getElementById('nav-links');
  menuBtn.addEventListener('click',function(){
    var open=menuBtn.getAttribute('aria-expanded')!=='true';
    menuBtn.setAttribute('aria-expanded',open); links.classList.toggle('open',open);
  });
  links.addEventListener('click',function(e){
    if(e.target.closest('a')){menuBtn.setAttribute('aria-expanded','false');links.classList.remove('open')}
  });
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape'&&links.classList.contains('open')){menuBtn.click();menuBtn.focus()}
  });

  // Pipeline rail: highlight the stage nearest the middle of the viewport (no scroll hijacking)
  var stages=[].slice.call(document.querySelectorAll('.stage'));
  var railLinks=[].slice.call(document.querySelectorAll('.rail a'));
  var railList=document.querySelector('.rail ol');
  if('IntersectionObserver' in window && stages.length){
    var io=new IntersectionObserver(function(entries){
      entries.forEach(function(en){
        if(!en.isIntersecting) return;
        var n=+en.target.dataset.step;
        railLinks.forEach(function(a,i){a.classList.toggle('on',i===n); if(i===n) a.setAttribute('aria-current','step'); else a.removeAttribute('aria-current')});
        railList.style.setProperty('--p',stages.length>1?n/(stages.length-1):1);
      });
    },{rootMargin:'-45% 0px -45% 0px'});
    stages.forEach(function(s){io.observe(s)});
  }

  // Project filter
  var filterBar=document.querySelector('.filters'), grid=document.getElementById('grid');
  var cards=[].slice.call(grid.querySelectorAll('.card')), status=document.getElementById('filter-status');
  filterBar.hidden=false;
  filterBar.addEventListener('click',function(e){
    var b=e.target.closest('button'); if(!b) return;
    var f=b.dataset.filter, shown=0;
    filterBar.querySelectorAll('button').forEach(function(x){x.setAttribute('aria-pressed',x===b)});
    cards.forEach(function(c){var on=f==='all'||c.dataset.cat===f; c.hidden=!on; if(on) shown++});
    grid.classList.toggle('filtering',f!=='all');
    status.textContent='Showing '+shown+' project'+(shown===1?'':'s');
  });

  // Case study routing via #case-… (CSS :target does the showing; this handles focus, title, scroll)
  var wasCase=false;
  function route(){
    var h=location.hash, el=h.length>1?document.getElementById(decodeURIComponent(h.slice(1))):null;
    var isCase=!!(el&&el.classList.contains('case'));
    document.querySelectorAll('.case.active').forEach(function(c){c.classList.remove('active')});
    body.classList.toggle('viewing-case',isCase);
    if(isCase){
      el.classList.add('active');
      window.scrollTo(0,0);
      var t=el.querySelector('h2'); t.focus({preventScroll:true});
      document.title=t.textContent+' | '+baseTitle.split(' | ')[0];
    }else{
      document.title=baseTitle;
      if(wasCase&&el) requestAnimationFrame(function(){el.scrollIntoView({behavior:'instant'})});
    }
    wasCase=isCase;
  }
  window.addEventListener('hashchange',route); route();

  // Inquiry form: inline validation, then submit to the Laravel inquiries route
  var form=document.getElementById('inquiry'), fstatus=document.getElementById('form-status');
  form.noValidate=true;
  var msgs={name:'Enter your name.',email:'Enter an email address, like name@company.com.',service:'Choose the service you are interested in.',details:'Add a few details about your project.'};
  function check(input){
    var key=input.name, err=document.getElementById('e-'+key); if(!err) return true;
    var bad=input.required&&!input.value.trim();
    if(!bad&&input.type==='email') bad=!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim());
    input.setAttribute('aria-invalid',bad); err.textContent=bad?msgs[key]:''; return !bad;
  }
  form.addEventListener('blur',function(e){if(e.target.matches('[required]')&&e.target.value) check(e.target)},true);
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var fields=[].slice.call(form.querySelectorAll('[required]')), first=null;
    fields.forEach(function(f){if(!check(f)&&!first) first=f});
    if(first){first.focus(); fstatus.textContent=''; return}
    if(form.dataset.connected!=='true'){
      fstatus.textContent='This form is not connected yet. Set PORTFOLIO_EMAIL in the site settings to start receiving inquiries.'; return;
    }
    var btn=form.querySelector('button[type=submit]'); btn.disabled=true; fstatus.textContent='Sending…';
    var token=document.querySelector('meta[name=csrf-token]').content;
    fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json','X-CSRF-TOKEN':token}})
      .then(function(r){
        if(r.status===422) return r.json().then(function(data){
          var firstBad=null;
          Object.keys(data.errors||{}).forEach(function(key){
            var input=form.elements[key], err=document.getElementById('e-'+key); if(!input||!err) return;
            input.setAttribute('aria-invalid',true); err.textContent=data.errors[key][0]; if(!firstBad) firstBad=input;
          });
          if(firstBad) firstBad.focus(); fstatus.textContent='';
        });
        if(!r.ok) throw 0;
        form.reset(); fstatus.textContent='Inquiry sent. Thank you, I will reply by email soon.';
      })
      .catch(function(){fstatus.textContent='Your inquiry could not be sent. Check your connection and try again, or email me directly.'})
      .finally(function(){btn.disabled=false});
  });
})();

// Ambient canvas backdrops (hero lava, work dots). backdrop() handles sizing, theme colours, pointer tracking,
// and only animates while the section is on screen, the tab is visible, and reduced motion is off (otherwise it draws one still frame).
(function(){
  var reduce=window.matchMedia('(prefers-reduced-motion: reduce)');
  var probe=document.createElement('i');
  function rgb(v){
    probe.style.color=v; document.body.appendChild(probe);
    var m=getComputedStyle(probe).color.match(/[\d.]+/g); probe.remove(); return m.slice(0,3).map(Number);
  }

  function backdrop(stage, scale, fx){
    var canvas=stage&&stage.querySelector(':scope > canvas');
    if(!canvas||!canvas.getContext) return;
    var s={stage:stage, canvas:canvas, ctx:canvas.getContext('2d'), W:0, H:0, scale:scale, t:0, rgb:rgb,
           pointer:{x:0,y:0,px:0,py:0,vx:0,vy:0,on:false}};
    var running=false, visible=true, last=0;

    function theme(){fx.theme(s, getComputedStyle(document.documentElement)); fx.draw(s)}
    function resize(){
      var w=stage.clientWidth, h=stage.clientHeight; if(!w||!h) return;
      var first=!s.W; s.W=w; s.H=h;
      canvas.width=Math.ceil(w*scale); canvas.height=Math.ceil(h*scale);
      fx.resize(s, first); fx.draw(s);
    }
    function frame(now){
      if(!running) return;
      var dt=Math.min(.05,(now-(last||now))/1000), p=s.pointer; last=now; s.t+=dt;
      p.vx=(p.x-p.px)/Math.max(dt,.001); p.vy=(p.y-p.py)/Math.max(dt,.001); p.px=p.x; p.py=p.y;
      fx.step(s, dt); fx.draw(s);
      requestAnimationFrame(frame);
    }
    function play(){
      var should=visible&&!document.hidden&&!reduce.matches;
      if(should&&!running){running=true; last=0; requestAnimationFrame(frame)}
      if(!should) running=false;
    }
    function point(e){var r=stage.getBoundingClientRect(); s.pointer.x=e.clientX-r.left; s.pointer.y=e.clientY-r.top}

    stage.addEventListener('pointerenter',function(e){point(e); s.pointer.px=s.pointer.x; s.pointer.py=s.pointer.y; s.pointer.on=true});
    stage.addEventListener('pointermove',function(e){point(e); s.pointer.on=true},{passive:true});
    stage.addEventListener('pointerleave',function(){s.pointer.on=false});
    stage.addEventListener('pointerdown',function(e){if(!reduce.matches&&fx.press){point(e); fx.press(s)}});

    fx.theme(s, getComputedStyle(document.documentElement)); resize();
    canvas.classList.add('on');
    if('ResizeObserver' in window) new ResizeObserver(resize).observe(stage); else window.addEventListener('resize',resize);
    if('IntersectionObserver' in window) new IntersectionObserver(function(en){visible=en[0].isIntersecting; play()}).observe(stage);
    document.addEventListener('visibilitychange',play);
    if(reduce.addEventListener) reduce.addEventListener('change',function(){play(); fx.draw(s)});
    var scheme=window.matchMedia('(prefers-color-scheme: dark)');
    if(scheme.addEventListener) scheme.addEventListener('change',theme);
    new MutationObserver(theme).observe(document.documentElement,{attributes:true,attributeFilter:['data-theme']});
    play();
  }

  // Hero lava lamp: metaballs in the --lava-* tokens, drawn on a canvas at a quarter of screen resolution and scaled up smoothly.
  // The cursor drags a blob of its own, pulls nearby blobs toward it, and stirs them when moved fast; a click pushes them away.
  var CELL=4;
  backdrop(document.querySelector('.hero-stage'), 1/CELL, {
    blobs:[], cursorR:0, colors:[], glow:[0,0,0], alpha:.4, img:null,
    theme:function(s, cs){
      this.colors=[1,2,3].map(function(n){return rgb(cs.getPropertyValue('--lava-'+n).trim())});
      this.glow=rgb(cs.getPropertyValue('--lava-glow').trim());
      this.alpha=parseFloat(cs.getPropertyValue('--lava-alpha'))||.4;
    },
    resize:function(s, first){
      this.img=s.ctx.createImageData(s.canvas.width,s.canvas.height);
      var n=s.W<700?6:8, base=Math.sqrt(s.W*s.H), blobs=this.blobs;
      if(!first){blobs.forEach(function(b,i){b.bx=s.W*(i+.5)/blobs.length}); return}
      for(var i=0;i<n;i++){
        var bx=s.W*(i+.5)/n;
        blobs.push({
          x:bx, y:s.H*Math.random(), vx:0, vy:0,
          r:base*(.06+Math.random()*.06), c:i%3,
          bx:bx, ax:s.W*(.04+Math.random()*.08),
          wx:.05+Math.random()*.08, wy:.035+Math.random()*.05,
          px:Math.random()*6.28, py:Math.random()*6.28
        });
      }
    },
    step:function(s, dt){
      var p=s.pointer, W=s.W, H=s.H, t=s.t, reach=Math.sqrt(W*H)*.35;
      // Ease the cursor blob in and out
      this.cursorR+=((p.on?Math.sqrt(W*H)*.055:0)-this.cursorR)*Math.min(1,dt*4);
      this.blobs.forEach(function(b){
        // Each blob springs toward a slowly rising and sinking home point, like wax in a lamp
        var hx=b.bx+Math.sin(t*b.wx+b.px)*b.ax;
        var hy=H*(.5+.62*Math.sin(t*b.wy+b.py));
        b.vx+=(hx-b.x)*.35*dt; b.vy+=(hy-b.y)*.35*dt;
        if(p.on){
          var dx=p.x-b.x, dy=p.y-b.y, d=Math.sqrt(dx*dx+dy*dy)+1;
          if(d<reach){
            var f=1-d/reach;
            b.vx+=dx/d*f*90*dt+p.vx*f*.9*dt;
            b.vy+=dy/d*f*90*dt+p.vy*f*.9*dt;
          }
        }
        var damp=Math.pow(.25,dt); b.vx*=damp; b.vy*=damp;
        b.x+=b.vx*dt; b.y+=b.vy*dt;
      });
    },
    press:function(s){
      var p=s.pointer, reach=Math.sqrt(s.W*s.H)*.5;
      this.blobs.forEach(function(b){
        var dx=b.x-p.x, dy=b.y-p.y, d=Math.sqrt(dx*dx+dy*dy)+1, f=Math.max(0,1-d/reach);
        b.vx+=dx/d*f*520; b.vy+=dy/d*f*520;
      });
    },
    draw:function(s){
      if(!this.img) return;
      var data=this.img.data, colors=this.colors, glow=this.glow, alpha=this.alpha;
      var all=this.blobs.slice(), cols=s.canvas.width, rows=s.canvas.height, k, n;
      if(this.cursorR>1) all.push({x:s.pointer.x,y:s.pointer.y,r:this.cursorR,c:1});
      n=all.length;
      var bx=new Float32Array(n), by=new Float32Array(n), br=new Float32Array(n);
      for(k=0;k<n;k++){bx[k]=all[k].x; by[k]=all[k].y; br[k]=all[k].r*all[k].r}
      var p=0;
      for(var y=0;y<rows;y++){
        var py=(y+.5)*CELL;
        for(var x=0;x<cols;x++){
          var px=(x+.5)*CELL, f=0, r=0, g=0, b=0;
          for(k=0;k<n;k++){
            var dx=px-bx[k], dy=py-by[k], w=br[k]/(dx*dx+dy*dy+1), c=colors[all[k].c];
            f+=w; r+=w*c[0]; g+=w*c[1]; b+=w*c[2];
          }
          if(f<.05){data[p+3]=0; p+=4; continue}
          r/=f; g/=f; b/=f;
          // Lighter toward the core, for a soft inner glow
          var lit=f>1?.22*(1-1/f):0;
          r+=(glow[0]-r)*lit; g+=(glow[1]-g)*lit; b+=(glow[2]-b)*lit;
          var edge=Math.min(1,Math.max(0,(f-.9)/.14)); edge=edge*edge*(3-2*edge);
          data[p]=r; data[p+1]=g; data[p+2]=b;
          data[p+3]=255*alpha*edge;
          p+=4;
        }
      }
      s.ctx.putImageData(this.img,0,0);
    }
  });

  // Selected work: a dot field rolling in a slow wave, in the --dots-* tokens. Dots swell, brighten, and part
  // around the cursor like a lens; a click sends a ripple ring across the grid.
  var GAP=26;
  backdrop(document.querySelector('.work'), Math.min(window.devicePixelRatio||1,2), {
    dots:[], ripples:[], lens:0, base:[0,0,0], hot:[0,0,0], alpha:.5,
    theme:function(s, cs){
      this.base=rgb(cs.getPropertyValue('--dots-base').trim());
      this.hot=rgb(cs.getPropertyValue('--dots-hot').trim());
      this.alpha=parseFloat(cs.getPropertyValue('--dots-alpha'))||.5;
    },
    resize:function(s){
      var dots=this.dots=[], cols=Math.ceil(s.W/GAP)+1, rows=Math.ceil(s.H/GAP)+1;
      var ox=(s.W-(cols-1)*GAP)/2, oy=(s.H-(rows-1)*GAP)/2;
      for(var y=0;y<rows;y++) for(var x=0;x<cols;x++) dots.push({hx:ox+x*GAP, hy:oy+y*GAP, x:ox+x*GAP, y:oy+y*GAP, heat:0});
    },
    step:function(s, dt){
      var p=s.pointer, R=150, ease=Math.min(1,dt*7), ripples=this.ripples;
      this.lens+=((p.on?1:0)-this.lens)*Math.min(1,dt*5);
      ripples.forEach(function(r){r.r+=dt*520; r.life-=dt*.9});
      this.ripples=ripples=ripples.filter(function(r){return r.life>0});
      var lens=this.lens;
      this.dots.forEach(function(d){
        var tx=d.hx, ty=d.hy, heat=0;
        if(lens>.01){
          var dx=d.hx-p.x, dy=d.hy-p.y, dist=Math.sqrt(dx*dx+dy*dy)+.01;
          if(dist<R){var f=1-dist/R; f=f*f*(3-2*f)*lens; tx+=dx/dist*f*18; ty+=dy/dist*f*18; heat=f}
        }
        for(var i=0;i<ripples.length;i++){
          var rp=ripples[i], ex=d.hx-rp.x, ey=d.hy-rp.y, rd=Math.sqrt(ex*ex+ey*ey)+.01, band=1-Math.abs(rd-rp.r)/60;
          if(band>0){band*=rp.life; tx+=ex/rd*band*10; ty+=ey/rd*band*10; heat=Math.max(heat,band)}
        }
        d.x+=(tx-d.x)*ease; d.y+=(ty-d.y)*ease; d.heat+=(heat-d.heat)*ease;
      });
    },
    press:function(s){this.ripples.push({x:s.pointer.x, y:s.pointer.y, r:0, life:1})},
    draw:function(s){
      var ctx=s.ctx, t=s.t, base=this.base, hot=this.hot, alpha=this.alpha, TAU=Math.PI*2, cool='rgb('+base.join(',')+')';
      ctx.setTransform(s.scale,0,0,s.scale,0,0); ctx.clearRect(0,0,s.W,s.H);
      this.dots.forEach(function(d){
        // Two slow crossing waves give the field its rolling swell
        var wave=.5+.25*Math.sin(d.hx*.011+t*.7)+.25*Math.sin(d.hy*.014-t*.5+d.hx*.004);
        var h=d.heat, r=1+wave*1.1+h*2.6;
        ctx.globalAlpha=alpha*(.35+wave*.4+h*.6);
        ctx.fillStyle=h<.01?cool:'rgb('+Math.round(base[0]+(hot[0]-base[0])*h)+','+Math.round(base[1]+(hot[1]-base[1])*h)+','+Math.round(base[2]+(hot[2]-base[2])*h)+')';
        ctx.beginPath(); ctx.arc(d.x,d.y,r,0,TAU); ctx.fill();
      });
      ctx.globalAlpha=1;
    }
  });
})();
