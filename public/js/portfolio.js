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

// Hero lava lamp: metaballs in the palette tokens (--lava-*), drifting up and down.
// The cursor drags a blob of its own, pulls nearby blobs toward it, and stirs them when moved fast; a click pushes them away.
(function(){
  var stage=document.querySelector('.hero-stage'), canvas=stage&&stage.querySelector('.lava');
  if(!canvas||!canvas.getContext) return;
  var ctx=canvas.getContext('2d'), reduce=window.matchMedia('(prefers-reduced-motion: reduce)');
  var CELL=4, W=0, H=0, cols=0, rows=0, img=null, colors=[], glow=[0,0,0], alpha=.4;
  var blobs=[], running=false, visible=true, last=0, t=0;
  var cursor={x:0,y:0,px:0,py:0,vx:0,vy:0,r:0,on:false};

  function rgb(v){
    var el=document.createElement('i'); el.style.color=v; document.body.appendChild(el);
    var m=getComputedStyle(el).color.match(/\d+/g); el.remove(); return m.slice(0,3).map(Number);
  }
  function readTheme(){
    var cs=getComputedStyle(document.documentElement);
    colors=[1,2,3].map(function(n){return rgb(cs.getPropertyValue('--lava-'+n).trim())});
    glow=rgb(cs.getPropertyValue('--lava-glow').trim());
    alpha=parseFloat(cs.getPropertyValue('--lava-alpha'))||.4;
  }

  function seed(){
    var n=W<700?6:8, base=Math.sqrt(W*H);
    blobs=[];
    for(var i=0;i<n;i++){
      var bx=W*(i+.5)/n;
      blobs.push({
        x:bx, y:H*Math.random(), vx:0, vy:0,
        r:base*(.06+Math.random()*.06), c:i%3,
        bx:bx, ax:W*(.04+Math.random()*.08),
        wx:.05+Math.random()*.08, wy:.035+Math.random()*.05,
        px:Math.random()*6.28, py:Math.random()*6.28
      });
    }
  }

  function resize(){
    var w=stage.clientWidth, h=stage.clientHeight; if(!w||!h) return;
    var first=!W; W=w; H=h;
    cols=Math.ceil(W/CELL); rows=Math.ceil(H/CELL);
    canvas.width=cols; canvas.height=rows; img=ctx.createImageData(cols,rows);
    if(first) seed(); else blobs.forEach(function(b,i){b.bx=W*(i+.5)/blobs.length});
    draw();
  }

  function step(dt){
    t+=dt;
    // Ease the cursor blob in and out, and track how fast the pointer moves
    cursor.r+=((cursor.on?Math.sqrt(W*H)*.055:0)-cursor.r)*Math.min(1,dt*4);
    cursor.vx=(cursor.x-cursor.px)/Math.max(dt,.001); cursor.vy=(cursor.y-cursor.py)/Math.max(dt,.001);
    cursor.px=cursor.x; cursor.py=cursor.y;
    var reach=Math.sqrt(W*H)*.35;
    blobs.forEach(function(b){
      // Each blob springs toward a slowly rising and sinking home point, like wax in a lamp
      var hx=b.bx+Math.sin(t*b.wx+b.px)*b.ax;
      var hy=H*(.5+.62*Math.sin(t*b.wy+b.py));
      b.vx+=(hx-b.x)*.35*dt; b.vy+=(hy-b.y)*.35*dt;
      if(cursor.on){
        var dx=cursor.x-b.x, dy=cursor.y-b.y, d=Math.sqrt(dx*dx+dy*dy)+1;
        if(d<reach){
          var f=1-d/reach;
          b.vx+=dx/d*f*90*dt+cursor.vx*f*.9*dt;
          b.vy+=dy/d*f*90*dt+cursor.vy*f*.9*dt;
        }
      }
      var damp=Math.pow(.25,dt); b.vx*=damp; b.vy*=damp;
      b.x+=b.vx*dt; b.y+=b.vy*dt;
    });
  }

  function draw(){
    if(!img) return;
    var data=img.data, n=blobs.length, all=blobs.slice(), i, k;
    if(cursor.r>1) all.push({x:cursor.x,y:cursor.y,r:cursor.r,c:1});
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
    ctx.putImageData(img,0,0);
  }

  function frame(now){
    if(!running) return;
    var dt=Math.min(.05,(now-(last||now))/1000); last=now;
    step(dt); draw();
    requestAnimationFrame(frame);
  }
  function play(){
    var should=visible&&!document.hidden&&!reduce.matches;
    if(should&&!running){running=true; last=0; requestAnimationFrame(frame)}
    if(!should) running=false;
  }

  function point(e){
    var rect=stage.getBoundingClientRect();
    cursor.x=e.clientX-rect.left; cursor.y=e.clientY-rect.top;
  }
  stage.addEventListener('pointerenter',function(e){point(e); cursor.px=cursor.x; cursor.py=cursor.y; cursor.on=true});
  stage.addEventListener('pointermove',function(e){point(e); cursor.on=true},{passive:true});
  stage.addEventListener('pointerleave',function(){cursor.on=false});
  stage.addEventListener('pointerdown',function(e){
    if(reduce.matches) return;
    point(e);
    blobs.forEach(function(b){
      var dx=b.x-cursor.x, dy=b.y-cursor.y, d=Math.sqrt(dx*dx+dy*dy)+1, f=Math.max(0,1-d/(Math.sqrt(W*H)*.5));
      b.vx+=dx/d*f*520; b.vy+=dy/d*f*520;
    });
  });

  readTheme(); resize();
  canvas.classList.add('on');
  if('ResizeObserver' in window) new ResizeObserver(resize).observe(stage); else window.addEventListener('resize',resize);
  if('IntersectionObserver' in window) new IntersectionObserver(function(en){visible=en[0].isIntersecting; play()}).observe(stage);
  document.addEventListener('visibilitychange',play);
  if(reduce.addEventListener) reduce.addEventListener('change',function(){play(); draw()});
  var scheme=window.matchMedia('(prefers-color-scheme: dark)');
  if(scheme.addEventListener) scheme.addEventListener('change',function(){readTheme(); draw()});
  new MutationObserver(function(){readTheme(); draw()}).observe(document.documentElement,{attributes:true,attributeFilter:['data-theme']});
  play();
})();
