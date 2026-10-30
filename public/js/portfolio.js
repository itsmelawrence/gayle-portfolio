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
