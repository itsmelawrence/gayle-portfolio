<article class="case" id="{{ $project['slug'] }}" aria-labelledby="{{ $project['slug'] }}-t">
  <div class="wrap">
    <a class="back" href="#work">Back to selected work</a>
    <p class="case-cat">{{ $categories[$project['cat']] }}</p>
    <h2 id="{{ $project['slug'] }}-t" tabindex="-1">{{ $project['title'] }}</h2>
    <p class="case-lead">{{ fmt($project['overview']) }}</p>
    <dl class="case-facts">
      <div><dt>Client</dt><dd>{{ fmt($project['client']) }}</dd></div>
      <div><dt>Industry</dt><dd>{{ fmt($project['industry']) }}</dd></div>
      <div><dt>My role</dt><dd>{{ fmt($project['role']) }}</dd></div>
      <div><dt>Tools</dt><dd>{{ fmt(implode(', ', $project['tools'])) }}</dd></div>
    </dl>
    <div class="case-hero">@include('partials.mock')<p class="media-note">{{ ph('[Replace with a project screenshot or video]') }}</p></div>
    <div class="case-body">
      <section><h3>Business objective</h3><p>{{ fmt($project['objective']) }}</p></section>
      <section><h3>Challenges</h3><p>{{ fmt($project['challenges']) }}</p></section>
      <section><h3>Approach and execution</h3><ul>@foreach ($project['approach'] as $step)<li>{{ fmt($step) }}</li>@endforeach</ul></section>
      <section><h3>Deliverables</h3><ul>@foreach ($project['deliverables'] as $item)<li>{{ fmt($item) }}</li>@endforeach</ul></section>
      <section><h3>Results</h3><p>{{ fmt($project['results'] ?? $results_placeholder) }}</p></section>
    </div>
    <div class="gallery">
      <div class="shot">{{ ph('[Screenshot]') }}</div><div class="shot">{{ ph('[Screenshot]') }}</div><div class="shot">{{ ph('[Video or image]') }}</div>
    </div>
    <a class="next" href="#{{ $next['slug'] }}"><span>Next project</span>{{ $next['title'] }}</a>
  </div>
</article>
