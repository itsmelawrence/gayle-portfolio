@extends('layouts.app')

@section('content')
<div class="home" id="top">

  <div class="hero-stage">
  <canvas class="lava" aria-hidden="true"></canvas>
  <section class="hero wrap" aria-labelledby="hero-title">
    <div class="hero-main">
    <h1 id="hero-title">Ads, creative, and the systems behind them.</h1>
    <div class="hero-row">
      <div class="fade">
        {{-- Each phrase jumps to the service that covers it (ids are built from service titles below) --}}
        @php($toService = fn ($title) => '#service-'.Str::slug($title))
        <p class="hero-intro"><strong class="intro-lead">I’m {{ $name }}, a digital marketing and operations specialist. I help businesses run better marketing:</strong> <a class="intro-link" href="{{ $toService('Creative research & production') }}">research-led ad creative</a>, <a class="intro-link" href="{{ $toService('Paid advertising & lead generation') }}">well-structured Meta campaigns</a>, and <a class="intro-link" href="{{ $toService('Funnel & CRM development') }}">the funnels, CRM, and automations</a> that turn leads into conversations.</p>
        <div class="hero-cta">
          <a class="btn btn-primary" href="#work">View my work</a>
          <a class="btn btn-ghost" href="#contact">Let’s work together</a>
        </div>
      </div>
@unless ($portrait)
      <p class="hero-aside fade"><strong>Currently</strong>Supporting three clients across DTC advertising, lead generation, and marketing operations.</p>
      @endunless
    </div>
    </div>
    @if ($portrait)
    <figure class="hero-portrait">
      <span class="halo" aria-hidden="true"></span>
      <img src="{{ asset($portrait) }}" width="700" height="1080" alt="{{ $name }}, smiling, in a white puff-sleeve top" fetchpriority="high">
      @if ($availability)
      <a class="chat-bubble" href="#contact" aria-label="{{ $availability }} Get in touch.">
        <span class="chat-typing" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="chat-msg" aria-hidden="true"><b class="chat-dot"></b><span class="chat-text">{{ $availability }}</span></span>
      </a>
      @endif
      <figcaption class="hero-aside hero-card fade"><strong>Currently</strong>Supporting three clients across DTC advertising, lead generation, and marketing operations.</figcaption>
    </figure>
    @endif
    <div class="teaser fade">
      <p>How the work connects</p>
      <ol>@foreach ($stages as $stage)<li><a href="#stage-{{ $loop->iteration }}">{{ $stage['name'] }}</a></li>@endforeach</ol>
    </div>
  </section>
  </div>

  <section class="work sec" id="work" aria-labelledby="work-title">
    <canvas class="dots" aria-hidden="true"></canvas>
    <div class="wrap">
      <div class="sec-head">
        <h2 id="work-title">Selected work</h2>
        <p class="lede">Client names are kept private. Each project shows the role, approach, and deliverables; results are only shown where they can be verified.</p>
      </div>
      <div class="filters" role="group" aria-label="Filter projects" hidden><button type="button" aria-pressed="true" data-filter="all">All</button>@foreach ($categories as $key => $label)<button type="button" aria-pressed="false" data-filter="{{ $key }}">{{ $label }}</button>@endforeach</div>
      <p class="sr-only" aria-live="polite" id="filter-status"></p>
      <div class="grid" id="grid">@foreach ($projects as $project)
@include('partials.card')
@endforeach</div>
    </div>
  </section>

  <section class="sec" id="services" aria-labelledby="services-title">
    <div class="wrap svc-grid">
      <div class="svc-intro">
        <h2 id="services-title">Services</h2>
        <p class="lede">Each service can stand alone, but they are designed to plug into one another. Pick one, or hand over a whole stage of the pipeline.</p>
        <a class="btn btn-ghost" href="#contact">Discuss a project</a>
      </div>
      <ul>@foreach ($services as $service)<li class="svc" id="service-{{ Str::slug($service['title']) }}">
  <h3>{{ $service['title'] }}</h3>
  <div>
    <p>{{ $service['summary'] }}</p>
    <p class="incl"><span>Includes</span> {{ implode(', ', $service['includes']) }}.</p>
    @php($stageIndex = array_search($service['stage'], array_column($stages, 'name')))
    <a class="stage-link" href="{{ $stageIndex === false ? '#system' : '#stage-'.($stageIndex + 1) }}">Where it fits: {{ $service['stage'] }}</a>
  </div>
</li>@endforeach</ul>
    </div>
  </section>

  <section class="system sec" id="system" aria-labelledby="system-title">
    <div class="wrap">
      <div class="sec-head">
        <h2 id="system-title">Five stages, one pipeline.</h2>
        <p class="lede">Most businesses hire for these separately. Each one works better when it is built with the others in mind: research shapes the creative, the creative feeds the campaign, and the campaign is only as good as what happens after the click.</p>
      </div>
      <div class="sys-grid">
        <nav class="rail" aria-label="Pipeline stages"><ol>@foreach ($stages as $stage)<li><a href="#stage-{{ $loop->iteration }}" data-step="{{ $loop->index }}"><span class="rn">{{ $loop->iteration }}</span>{{ $stage['name'] }}</a></li>@endforeach</ol></nav>
        <div class="stages">@foreach ($stages as $stage)<div class="stage" id="stage-{{ $loop->iteration }}" data-step="{{ $loop->index }}" style="--i:{{ $loop->index }}">
  <p class="stage-no">{{ $loop->iteration }} <span>of {{ $loop->count }}</span></p>
  <h3><span class="stage-name">{{ $stage['name'] }}.</span> {{ $stage['headline'] }}</h3>
  <p>{{ $stage['body'] }}</p>
  <ul class="inline">@foreach ($stage['items'] as $item)<li>{{ $item }}</li>@endforeach</ul>
</div>@endforeach</div>
      </div>
    </div>
  </section>

  <section class="sec" id="experience" aria-labelledby="exp-title">
    <div class="wrap">
      <div class="sec-head">
        <h2 id="exp-title">Three clients, running side by side</h2>
        <p class="lede">I currently support three businesses at once, each with a different model and a different part of the marketing stack.</p>
      </div>
      <ul class="eng-list">@foreach ($engagements as $engagement)<li class="eng">
  <p class="eng-client">{{ $engagement['client'] }}</p>
  <p class="eng-sector">{{ $engagement['sector'] }}</p>
  <h3>{{ $engagement['role'] }}</h3>
  <ul>@foreach ($engagement['tasks'] as $task)<li>{{ $task }}</li>@endforeach</ul>
  <p class="eng-built"><span>Strengths built</span> {{ $engagement['strengths'] }}</p>
</li>@endforeach</ul>
    </div>
  </section>

  <section class="about sec" id="about" aria-labelledby="about-title">
    <div class="wrap about-grid">
      @if ($portrait)
      {{-- The photo sits in an arch and is drawn twice: once clipped by the arch, once (top half only) on top, so her head breaks out of the frame --}}
      <figure class="about-photo">
        <div class="arch" aria-hidden="true"><img src="{{ asset($portrait) }}" width="700" height="1080" alt="" loading="lazy"></div>
        <img class="arch-pop" src="{{ asset($portrait) }}" width="700" height="1080" alt="{{ $name }}, smiling, in a white puff-sleeve top" loading="lazy">
        <svg class="ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
          <defs><path id="ring-path" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
          <circle cx="60" cy="60" r="58"/>
          <g class="ring-spin"><text textLength="276" lengthAdjust="spacing"><textPath href="#ring-path">Digital marketing · Operations · Automation ·</textPath></text></g>
          <path class="ring-star" d="M60 49 L62.6 57.4 L71 60 L62.6 62.6 L60 71 L57.4 62.6 L49 60 L57.4 57.4 Z"/>
        </svg>
      </figure>
      @else
      <div class="portrait">{{ ph('[Portrait photo]') }}</div>
      @endif
      <div class="about-copy">
        <h2 id="about-title">About</h2>
        <p class="big">I work on both sides of marketing: the visible side, like ads, video, and social content, and the operational side that keeps it running.</p>
        <p>I like understanding how a business actually works before touching its marketing. That is usually where the useful fixes are: a campaign that needs a clearer structure, a lead journey with gaps in it, or a task someone repeats by hand every week.</p>
        <p>From researching competitor ads and structuring lead generation campaigns to editing creatives, building funnels, and automating follow-up, I take projects from plan to launch and stay responsible for the details.</p>
        <p class="vision">Longer term, I’m building toward a small team that offers the same thing at greater scale: reliable execution and well-structured marketing systems.</p>
        <ul class="traits">@foreach ($traits as $trait => $description)<li><h3>{{ $trait }}</h3><p>{{ $description }}</p></li>@endforeach</ul>
      </div>
    </div>
  </section>

  <section class="tools sec" id="tools" aria-labelledby="tools-title">
    <div class="wrap">
      <div class="sec-head">
        <h2 id="tools-title">Already fluent in your marketing stack</h2>
        <p class="lede">No ramp-up time on day one. I work daily in Meta Business Suite, GoHighLevel, Canva, and CapCut, keep projects moving in Slack, Asana, and Notion, and use AI tools like ChatGPT, Gemini, and Claude to research, write, and automate faster.</p>
      </div>
      <div class="tool-grid">@foreach ($tools as $group => $items)<div class="tool-group"><h3>{{ $group }}</h3><ul>@foreach ($items as $tool)<li class="tool" tabindex="0" style="--brand:{{ $tool['brand'] }}">@isset($tool['icon']){!! file_get_contents(resource_path('icons/'.$tool['icon'].'.svg')) !!}@else<span class="tool-mono" aria-hidden="true">{{ $tool['monogram'] }}</span>@endisset<span class="tool-name">{{ $tool['name'] }}</span></li>@endforeach</ul></div>@endforeach</div>
    </div>
  </section>

  <section class="contact sec" id="contact" aria-labelledby="contact-title">
    <div class="wrap contact-grid">
      <div class="contact-info">
        <h2 id="contact-title">Tell me what you’re working on.</h2>
        <p class="lede">Share a little about your business and where you need help. I’ll reply by email within {{ $contact['response_time'] ?: ph('[response time]') }}.</p>
        <ul class="links">
          @include('partials.contact-link', ['label' => 'Email', 'value' => $contact['email'], 'href' => 'mailto:'.$contact['email'], 'placeholder' => 'email address'])
          @include('partials.contact-link', ['label' => 'LinkedIn', 'value' => $contact['linkedin'], 'href' => $contact['linkedin'], 'placeholder' => 'LinkedIn URL'])
          @include('partials.contact-link', ['label' => 'Instagram', 'value' => $contact['instagram'], 'placeholder' => 'Instagram handle'])
          @include('partials.contact-link', ['label' => 'Based in', 'value' => $contact['location'], 'placeholder' => 'City, time zone'])
        </ul>
      </div>
      @php($sent = session('inquiry_sent'))
      <div class="inquiry">
      <form id="inquiry" action="{{ route('inquiries.store') }}" method="POST" @if ($sent) hidden @endif>
        @csrf
        {{-- Honeypot: hidden from people and screen readers; bots that fill it are dropped quietly --}}
        <div class="hp" aria-hidden="true"><label for="f-website">Website</label><input id="f-website" name="website" tabindex="-1" autocomplete="off"></div>
        {{-- Attribution, filled in by portfolio.js from the first page view of the visit --}}
        @foreach (['utm_source', 'utm_medium', 'utm_campaign', 'referrer', 'landing_page'] as $field)<input type="hidden" name="{{ $field }}">@endforeach
        <div class="field">
          <label for="f-name">Name</label>
          <input id="f-name" name="name" value="{{ old('name') }}" autocomplete="name" required aria-describedby="e-name">
          <p class="err" id="e-name">{{ $errors->first('name') }}</p>
        </div>
        <div class="field">
          <label for="f-email">Email</label>
          <input id="f-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required aria-describedby="e-email">
          <p class="err" id="e-email">{{ $errors->first('email') }}</p>
        </div>
        <div class="field">
          <label for="f-company">Company <span class="opt">(optional)</span></label>
          <input id="f-company" name="company" value="{{ old('company') }}" autocomplete="organization">
        </div>
        <div class="field">
          <label for="f-service">Service you’re interested in</label>
          <select id="f-service" name="service" required aria-describedby="e-service">
            <option value="">Choose a service</option>
            @foreach ([...array_column($services, 'title'), 'Not sure yet'] as $option)<option @selected(old('service') === $option)>{{ $option }}</option>@endforeach
          </select>
          <p class="err" id="e-service">{{ $errors->first('service') }}</p>
        </div>
        <div class="field full">
          <label for="f-details">Project details</label>
          <textarea id="f-details" name="details" required aria-describedby="e-details" placeholder="What are you trying to achieve, and what’s in place already?">{{ old('details') }}</textarea>
          <p class="err" id="e-details">{{ $errors->first('details') }}</p>
        </div>
        @if ($turnstileKey = config('services.turnstile.site_key'))
        <div class="field full turnstile">
          <div class="cf-turnstile" data-sitekey="{{ $turnstileKey }}" data-theme="auto" data-size="flexible" data-appearance="interaction-only"></div>
          <p class="err" id="e-cf-turnstile-response">{{ $errors->first('cf-turnstile-response') }}</p>
        </div>
        @push('scripts')<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>@endpush
        @endif
        <div class="form-foot">
          <button class="btn btn-primary send-btn" type="submit"><span class="send-label">Send inquiry</span><span class="send-spin" aria-hidden="true"></span></button>
          <p class="status" id="form-status" role="status"></p>
        </div>
        <p class="form-note">Your details are only used to reply to your inquiry. See the <a href="{{ route('privacy') }}">privacy policy</a>.</p>
      </form>
      {{-- Replaces the form after a successful send and stays until the page is reloaded. portfolio.js fills in the name and email. --}}
      <div class="sent" id="inquiry-sent" tabindex="-1" @unless ($sent) hidden @endunless>
        <div class="sent-mark" aria-hidden="true">
          @for ($i = 0; $i < 8; $i++)<i style="--a:{{ $i * 45 + 22 }}deg"></i>@endfor
          <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="26"/><path d="M15.5 27.5l7 7 14-15.5"/></svg>
        </div>
        <h3 class="sent-title">Thanks<span class="sent-name">{{ is_array($sent) ? ', '.Str::before($sent['name'], ' ') : '' }}</span>! Your inquiry is in.</h3>
        <p class="sent-copy">I’ll reply to <strong class="sent-email">{{ is_array($sent) ? $sent['email'] : 'your inbox' }}</strong> {{ $contact['response_time'] ? 'within '.$contact['response_time'] : 'soon' }}. Keep an eye on your spam folder, just in case.</p>
        <ol class="sent-next">
          <li><b>1</b><span>I read through your details and goals.</span></li>
          <li><b>2</b><span>I reply with a few questions or a time to talk.</span></li>
          <li><b>3</b><span>We agree on a plan before any work starts.</span></li>
        </ol>
      </div>
      </div>
    </div>
  </section>

</div>

@foreach ($projects as $project)
@include('partials.case', ['next' => $projects[($loop->index + 1) % $loop->count]])
@endforeach
@endsection
