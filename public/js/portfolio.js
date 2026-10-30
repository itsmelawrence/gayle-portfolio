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

  // Header: shadow once scrolled; tucks away while scrolling down, returns on scroll up (never while the menu is open or focused)
  var header=document.querySelector('.site-header'), lastY=window.scrollY;
  var onScroll=function(){
    var y=window.scrollY, open=document.getElementById('nav-links').classList.contains('open');
    header.classList.toggle('scrolled',y>8);
    if(Math.abs(y-lastY)>6){
      header.classList.toggle('tucked',y>lastY&&y>window.innerHeight*.6&&!open&&!header.contains(document.activeElement));
      lastY=y;
    }
  };
  window.addEventListener('scroll',onScroll,{passive:true}); onScroll();
  header.addEventListener('focusin',function(){header.classList.remove('tucked')});

  // Nav indicator: slides to the hovered link, otherwise rests on the link for the section in view
  var menu=document.querySelector('.nav-menu'), ind=menu.querySelector('.nav-ind');
  var navLinks=[].slice.call(menu.querySelectorAll('a')), current=null;
  function moveInd(a){
    if(!a){ind.style.opacity=0; return}
    ind.style.width=a.offsetWidth+'px'; ind.style.translate=a.offsetLeft+'px 0'; ind.style.opacity=1;
  }
  navLinks.forEach(function(a){a.addEventListener('pointerenter',function(){moveInd(a)})});
  menu.addEventListener('pointerleave',function(){moveInd(current)});
  if('IntersectionObserver' in window){
    var spy=new IntersectionObserver(function(entries){
      entries.forEach(function(en){
        var a=menu.querySelector('a[href="#'+en.target.id+'"]');
        if(en.isIntersecting){
          navLinks.forEach(function(l){l.removeAttribute('aria-current')});
          a.setAttribute('aria-current','true'); current=a;
        }else if(a===current){a.removeAttribute('aria-current'); current=null}
      });
      if(!menu.matches(':hover')) moveInd(current);
    },{rootMargin:'-45% 0px -50% 0px'});
    navLinks.forEach(function(a){var t=document.getElementById(a.getAttribute('href').slice(1)); if(t) spy.observe(t)});
  }

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
  // Remember where this visit started (UTM tags, referrer, landing page) and send it with the inquiry
  var visit=null;
  try{visit=JSON.parse(sessionStorage.getItem('visit'))}catch(e){}
  if(!visit){
    var q=new URLSearchParams(location.search);
    visit={utm_source:q.get('utm_source'),utm_medium:q.get('utm_medium'),utm_campaign:q.get('utm_campaign'),
      referrer:document.referrer&&new URL(document.referrer).host!==location.host?document.referrer:'',landing_page:location.pathname+location.search};
    try{sessionStorage.setItem('visit',JSON.stringify(visit))}catch(e){}
  }
  function fillAttribution(){Object.keys(visit).forEach(function(k){if(form.elements[k]) form.elements[k].value=(visit[k]||'').slice(0,500)})}
  fillAttribution();
  form.noValidate=true;
  var msgs={name:'Enter your name.',email:'Enter an email address, like name@company.com.',service:'Choose the service you are interested in.',details:'Add a few details about your project.'};
  function check(input){
    var key=input.name, err=document.getElementById('e-'+key); if(!err) return true;
    var bad=input.required&&!input.value.trim();
    if(!bad&&input.type==='email') bad=!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim());
    input.setAttribute('aria-invalid',bad); err.textContent=bad?msgs[key]:''; return !bad;
  }
  form.addEventListener('blur',function(e){if(e.target.matches('[required]')&&e.target.value) check(e.target)},true);
  // Clear an error as soon as the field is fixed. Waiting for blur would collapse the message mid-click on Send,
  // shifting the button out from under the pointer so the click never lands.
  form.addEventListener('input',function(e){if(e.target.getAttribute('aria-invalid')==='true') check(e.target)});
  form.addEventListener('change',function(e){if(e.target.getAttribute('aria-invalid')==='true') check(e.target)});
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var fields=[].slice.call(form.querySelectorAll('[required]')), first=null;
    fields.forEach(function(f){if(!check(f)&&!first) first=f});
    if(first){first.focus(); fstatus.textContent=''; return}
    var ts=form.elements['cf-turnstile-response'];
    if(form.querySelector('.cf-turnstile')&&(!ts||!ts.value)){fstatus.textContent='Please wait a moment while we check you’re not a bot, then try again.'; return}
    var btn=form.querySelector('button[type=submit]'); btn.disabled=true; fstatus.textContent='Sending…';
    var token=document.querySelector('meta[name=csrf-token]').content;
    fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json','X-CSRF-TOKEN':token}})
      .then(function(r){
        if(r.status===422) return r.json().then(function(data){
          var firstBad=null;
          Object.keys(data.errors||{}).forEach(function(key){
            var input=form.elements[key], err=document.getElementById('e-'+key);
            if(err) err.textContent=data.errors[key][0]; else fstatus.textContent=data.errors[key][0];
            if(!input||!input.focus||input.type==='hidden') return;
            input.setAttribute('aria-invalid',true); if(!firstBad) firstBad=input;
          });
          if(firstBad){firstBad.focus(); fstatus.textContent=''}
        });
        if(!r.ok) throw 0;
        form.reset(); fillAttribution(); fstatus.textContent='Inquiry sent. Thank you, I will reply by email soon.';
      })
      .catch(function(){fstatus.textContent='Your inquiry could not be sent. Check your connection and try again, or email me directly.'})
      .finally(function(){btn.disabled=false; if(window.turnstile) window.turnstile.reset()});
  });
})();

// Pipeline stages on small screens: as the next card slides over a stuck card, the covered one shrinks and dims (--cover 0..1)
(function(){
  var cards=[].slice.call(document.querySelectorAll('.stage')), small=window.matchMedia('(max-width:900px)');
  if(cards.length<2||window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var ticking=false;
  function update(){
    ticking=false;
    cards.forEach(function(card,i){
      var next=cards[i+1], cover=0;
      if(small.matches&&next){
        var r=card.getBoundingClientRect(), n=next.getBoundingClientRect();
        cover=Math.max(0,Math.min(1,(r.bottom-n.top)/r.height));
      }
      card.style.setProperty('--cover',cover.toFixed(3));
    });
  }
  function onScroll(){if(!ticking){ticking=true; requestAnimationFrame(update)}}
  window.addEventListener('scroll',onScroll,{passive:true});
  window.addEventListener('resize',onScroll);
  if(small.addEventListener) small.addEventListener('change',update);
  update();
})();

// Hero chat bubble: once the loader has cleared and the portrait has risen in, it pops in with a typing indicator,
// stretches to fit the message, then types the message in letter by letter. Reduced motion shows it finished.
(function(){
  var bubble=document.querySelector('.chat-bubble');
  if(!bubble) return;
  var text=bubble.querySelector('.chat-text'), full=text.textContent;
  // Shrink-wrap the bubble to its longest line (balanced wrapping leaves spare width otherwise)
  function fitWidth(){
    bubble.style.width='';
    var range=document.createRange(); range.selectNodeContents(text);
    var right=Math.max.apply(null,[].map.call(range.getClientRects(),function(r){return r.right}));
    var box=bubble.getBoundingClientRect(), pad=parseFloat(getComputedStyle(bubble).paddingRight);
    return Math.ceil(right-box.left+pad+1);
  }
  window.addEventListener('resize',function(){if(bubble.classList.contains('ready')&&!bubble.classList.contains('typing')) bubble.style.width=fitWidth()+'px'});
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){bubble.classList.add('ready'); bubble.style.width=fitWidth()+'px'; return}
  // One span per letter; plain spaces between words keep normal line wrapping
  text.textContent='';
  full.split(' ').forEach(function(word,w){
    if(w) text.appendChild(document.createTextNode(' '));
    word.split('').forEach(function(c){var ch=document.createElement('span'); ch.className='ch'; ch.textContent=c; text.appendChild(ch)});
  });
  var chars=[].slice.call(text.querySelectorAll('.ch'));
  function run(){
    bubble.classList.add('typing','ready','pop');
    setTimeout(function(){
      // Stretch from the typing-indicator size to the message size, with an overshoot
      var from=bubble.getBoundingClientRect();
      bubble.classList.remove('typing');
      var toW=fitWidth(); bubble.style.width=toW+'px';
      var toH=bubble.getBoundingClientRect().height;
      bubble.style.width=from.width+'px'; bubble.style.height=from.height+'px'; bubble.style.overflow='hidden';
      bubble.offsetWidth;
      bubble.style.transition='width .55s cubic-bezier(.3,1.45,.45,1),height .55s cubic-bezier(.3,1.45,.45,1)';
      bubble.style.width=toW+'px'; bubble.style.height=toH+'px';
      setTimeout(function(){
        bubble.style.cssText='width:'+toW+'px';
        chars.forEach(function(ch,i){setTimeout(function(){ch.classList.add('on')},i*34)});
      },560);
    },1300);
  }
  var root=document.documentElement;
  function afterLoad(){setTimeout(run,1700)}   // the portrait's reveal runs ~1.75s after the loader clears
  if(!root.classList.contains('loading')) afterLoad();
  else new MutationObserver(function(_,obs){if(!root.classList.contains('loading')){obs.disconnect(); afterLoad()}}).observe(root,{attributes:true,attributeFilter:['class']});
})();

// Hero portrait: eases toward the cursor (--mx/--my, -1..1) so the photo tilts and the halo drifts behind it
(function(){
  var stage=document.querySelector('.hero-stage'), fig=stage&&stage.querySelector('.hero-portrait');
  if(!fig||window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var tx=0, ty=0, x=0, y=0, raf=0;
  function clamp(v){return Math.max(-1,Math.min(1,v))}
  function tick(){
    x+=(tx-x)*.08; y+=(ty-y)*.08;
    fig.style.setProperty('--mx',x.toFixed(3)); fig.style.setProperty('--my',y.toFixed(3));
    raf=Math.abs(tx-x)+Math.abs(ty-y)>.002?requestAnimationFrame(tick):0;
  }
  function aim(nx,ny){tx=nx; ty=ny; if(!raf) raf=requestAnimationFrame(tick)}
  stage.addEventListener('pointermove',function(e){
    var r=fig.getBoundingClientRect();
    aim(clamp((e.clientX-r.left-r.width/2)/(r.width*1.5)), clamp((e.clientY-r.top-r.height/2)/(r.height*1.2)));
  },{passive:true});
  stage.addEventListener('pointerleave',function(){aim(0,0)});
})();

// About photo: grows in when it scrolls into view, drifts and turns its ring with scroll, and a soft light follows the cursor
(function(){
  var fig=document.querySelector('.about-photo'), section=fig&&fig.closest('section');
  if(!fig) return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches||!('IntersectionObserver' in window)){fig.classList.add('in'); return}
  var ticking=false, onScreen=false;
  function update(){
    ticking=false;
    var r=section.getBoundingClientRect(), vh=window.innerHeight;
    var p=Math.max(0,Math.min(1,(vh-r.top)/(r.height+vh)));   // 0 as the section enters, 1 as it leaves
    fig.style.setProperty('--rise',((.5-p)*36).toFixed(1));
    fig.style.setProperty('--spin',(p*140).toFixed(1)+'deg');
  }
  function onScroll(){if(onScreen&&!ticking){ticking=true; requestAnimationFrame(update)}}
  new IntersectionObserver(function(en){
    onScreen=en[0].isIntersecting;
    if(onScreen){fig.classList.add('in'); update()}
  },{threshold:.15}).observe(fig);
  window.addEventListener('scroll',onScroll,{passive:true});
  fig.addEventListener('pointermove',function(e){
    var r=fig.getBoundingClientRect();
    fig.style.setProperty('--sx',((e.clientX-r.left)/r.width*100).toFixed(1)+'%');
    fig.style.setProperty('--sy',((e.clientY-r.top)/r.height*100).toFixed(1)+'%');
  },{passive:true});
  fig.addEventListener('pointerleave',function(){fig.style.setProperty('--sx','50%'); fig.style.setProperty('--sy','30%')});
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

    // Stylesheets load without blocking paint, so wait for them before reading tokens and measuring layout
    function start(){
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
    if(document.readyState==='complete') start(); else window.addEventListener('load',start);
    return {relayout:function(){if(s.W&&fx.mask){fx.mask(s); fx.draw(s)}}};
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
  // around the cursor like a lens; a click sends a ripple ring across the grid. Under text the dots stay faint and small
  // (fading back to full strength just outside it), so the field carries on without hurting readability.
  var GAP=26, work=document.querySelector('.work'), TEXT='.sec-head>*,.filters button,.card-meta,.card h3';
  var FLOOR=.22, FEATHER=36;
  var dots=backdrop(work, Math.min(window.devicePixelRatio||1,2), {
    dots:[], ripples:[], lens:0, base:[0,0,0], hot:[0,0,0], alpha:.5,
    mask:function(s){
      var box=s.stage.getBoundingClientRect(), rects=[].map.call(s.stage.querySelectorAll(TEXT),function(el){
        var r=el.getBoundingClientRect(); return r.width?[r.left-box.left,r.top-box.top,r.right-box.left,r.bottom-box.top]:null;
      }).filter(Boolean);
      this.dots.forEach(function(d){
        // Distance from the dot to the nearest text box (0 inside), eased from FLOOR up to full strength
        var near=rects.reduce(function(m,r){
          var dx=Math.max(r[0]-d.hx,0,d.hx-r[2]), dy=Math.max(r[1]-d.hy,0,d.hy-r[3]);
          return Math.min(m,Math.sqrt(dx*dx+dy*dy));
        },Infinity);
        var f=Math.min(1,near/FEATHER); d.dim=FLOOR+(1-FLOOR)*f*f*(3-2*f);
      });
    },
    theme:function(s, cs){
      this.base=rgb(cs.getPropertyValue('--dots-base').trim());
      this.hot=rgb(cs.getPropertyValue('--dots-hot').trim());
      this.alpha=parseFloat(cs.getPropertyValue('--dots-alpha'))||.5;
    },
    resize:function(s){
      var dots=this.dots=[], cols=Math.ceil(s.W/GAP)+1, rows=Math.ceil(s.H/GAP)+1;
      var ox=(s.W-(cols-1)*GAP)/2, oy=(s.H-(rows-1)*GAP)/2;
      for(var y=0;y<rows;y++) for(var x=0;x<cols;x++) dots.push({hx:ox+x*GAP, hy:oy+y*GAP, x:ox+x*GAP, y:oy+y*GAP, heat:0, dim:1});
      this.mask(s);
    },
    step:function(s, dt){
      var p=s.pointer, R=190, ease=Math.min(1,dt*7), ripples=this.ripples;
      this.lens+=((p.on?1:0)-this.lens)*Math.min(1,dt*5);
      ripples.forEach(function(r){r.r+=dt*520; r.life-=dt*.9});
      this.ripples=ripples=ripples.filter(function(r){return r.life>0});
      var lens=this.lens;
      this.dots.forEach(function(d){
        var tx=d.hx, ty=d.hy, heat=0;
        if(lens>.01){
          var dx=d.hx-p.x, dy=d.hy-p.y, dist=Math.sqrt(dx*dx+dy*dy)+.01;
          if(dist<R){var f=1-dist/R; f=f*f*(3-2*f)*lens; tx+=dx/dist*f*26; ty+=dy/dist*f*26; heat=f}
        }
        for(var i=0;i<ripples.length;i++){
          var rp=ripples[i], ex=d.hx-rp.x, ey=d.hy-rp.y, rd=Math.sqrt(ex*ex+ey*ey)+.01, band=1-Math.abs(rd-rp.r)/80;
          if(band>0){band*=rp.life; tx+=ex/rd*band*16; ty+=ey/rd*band*16; heat=Math.max(heat,band)}
        }
        d.x+=(tx-d.x)*ease; d.y+=(ty-d.y)*ease; d.heat+=(heat-d.heat)*ease;
      });
    },
    press:function(s){this.ripples.push({x:s.pointer.x, y:s.pointer.y, r:0, life:1})},
    draw:function(s){
      var ctx=s.ctx, t=s.t, base=this.base, hot=this.hot, alpha=this.alpha, TAU=Math.PI*2, cool='rgb('+base.join(',')+')';
      ctx.setTransform(s.scale,0,0,s.scale,0,0); ctx.clearRect(0,0,s.W,s.H);
      this.dots.forEach(function(d){
        // Two slow crossing waves give the field its rolling swell; dim keeps dots (and the cursor lens) quiet under text
        var wave=.5+.25*Math.sin(d.hx*.011+t*.7)+.25*Math.sin(d.hy*.014-t*.5+d.hx*.004);
        var h=d.heat*d.dim, r=(1.3+wave*1.7+h*3.4)*(.55+.45*d.dim);
        ctx.globalAlpha=Math.min(1,alpha*(.4+wave*.6+h*.7))*d.dim;
        ctx.fillStyle=h<.01?cool:'rgb('+Math.round(base[0]+(hot[0]-base[0])*h)+','+Math.round(base[1]+(hot[1]-base[1])*h)+','+Math.round(base[2]+(hot[2]-base[2])*h)+')';
        ctx.beginPath(); ctx.arc(d.x,d.y,r,0,TAU); ctx.fill();
      });
      ctx.globalAlpha=1;
    }
  });
  // Filtering moves cards around, and web fonts can reflow the text after first layout
  if(dots){
    var grid=document.getElementById('grid');
    if(grid) new MutationObserver(function(){requestAnimationFrame(dots.relayout)}).observe(grid,{subtree:true,attributes:true,attributeFilter:['hidden','class']});
    if(document.fonts) document.fonts.ready.then(dots.relayout);
  }
})();
