@extends('layouts.app')

@section('content')
<div class="home" id="top">

  <div class="hero-stage">
  <canvas class="lava" aria-hidden="true"></canvas>
  <section class="hero wrap" aria-labelledby="hero-title">
    <h1 id="hero-title">Ads, creative, and the systems behind them.</h1>
    <div class="hero-row">
      <div class="fade">
        <p class="hero-intro">I’m {{ $name }}, a digital marketing and operations specialist. I help businesses run better marketing: research-led ad creative, well-structured Meta campaigns, and the funnels, CRM, and automations that turn leads into conversations.</p>
        <div class="hero-cta">
          <a class="btn btn-primary" href="#work">View my work</a>
          <a class="btn btn-ghost" href="#contact">Let’s work together</a>
        </div>
      </div>
      <p class="hero-aside fade"><strong>Currently</strong>Supporting three clients across DTC advertising, lead generation, and marketing operations.</p>
    </div>
    <div class="teaser fade">
      <p>How the work connects</p>
      <ol>@foreach ($stages as $stage)<li><a href="#stage-{{ $loop->iteration }}">{{ $stage['name'] }}</a></li>@endforeach</ol>
    </div>
  </section>
  </div>

  <section class="system sec" id="system" aria-labelledby="system-title">
    <div class="wrap">
      <div class="sec-head">
        <h2 id="system-title">Five stages, one pipeline.</h2>
        <p class="lede">Most businesses hire for these separately. Each one works better when it is built with the others in mind: research shapes the creative, the creative feeds the campaign, and the campaign is only as good as what happens after the click.</p>
      </div>
      <div class="sys-grid">
        <nav class="rail" aria-label="Pipeline stages"><ol>@foreach ($stages as $stage)<li><a href="#stage-{{ $loop->iteration }}" data-step="{{ $loop->index }}"><span class="rn">{{ $loop->iteration }}</span>{{ $stage['name'] }}</a></li>@endforeach</ol></nav>
        <div class="stages">@foreach ($stages as $stage)<div class="stage" id="stage-{{ $loop->iteration }}" data-step="{{ $loop->index }}">
  <p class="stage-no">{{ $loop->iteration }} <span>of {{ $loop->count }}</span></p>
  <h3><span class="stage-name">{{ $stage['name'] }}.</span> {{ $stage['headline'] }}</h3>
  <p>{{ $stage['body'] }}</p>
  <ul class="inline">@foreach ($stage['items'] as $item)<li>{{ $item }}</li>@endforeach</ul>
</div>@endforeach</div>
      </div>
    </div>
  </section>

  <section class="sec" id="services" aria-labelledby="services-title">
    <div class="wrap svc-grid">
      <div class="svc-intro">
        <h2 id="services-title">Services</h2>
        <p class="lede">Each service can stand alone, but they are designed to plug into one another. Pick one, or hand over a whole stage of the pipeline.</p>
        <a class="btn btn-ghost" href="#contact">Discuss a project</a>
      </div>
      <ul>@foreach ($services as $service)<li class="svc">
  <h3>{{ $service['title'] }}</h3>
  <div>
    <p>{{ $service['summary'] }}</p>
    <p class="incl"><span>Includes</span> {{ implode(', ', $service['includes']) }}.</p>
    <a class="stage-link" href="#system">Where it fits: {{ $service['stage'] }}</a>
  </div>
</li>@endforeach</ul>
    </div>
  </section>

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
      <div class="portrait">{{ ph('[Portrait photo]') }}</div>
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
        <h2 id="tools-title">Tools I work in</h2>
        <p class="lede">The software I use day to day in client work.</p>
      </div>
      <div class="tool-grid">@foreach ($tools as $group => $items)<div class="tool-group"><h3>{{ $group }}</h3><ul>@foreach ($items as $item)<li>{{ $item }}</li>@endforeach</ul></div>@endforeach</div>
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
      <form id="inquiry" action="{{ route('inquiries.store') }}" method="POST" data-connected="{{ $contact['email'] ? 'true' : 'false' }}">
        @csrf
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
        <div class="form-foot">
          <button class="btn btn-primary" type="submit">Send inquiry</button>
          <p class="status" id="form-status" role="status">{{ session('inquiry_sent') ? 'Inquiry sent. Thank you, I will reply by email soon.' : '' }}</p>
        </div>
      </form>
    </div>
  </section>

</div>

@foreach ($projects as $project)
@include('partials.case', ['next' => $projects[($loop->index + 1) % $loop->count]])
@endforeach
@endsection
