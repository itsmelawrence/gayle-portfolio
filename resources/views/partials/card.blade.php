<a class="card {{ $project['size'] }}" href="#{{ $project['slug'] }}" data-cat="{{ $project['cat'] }}">
  @include('partials.mock')
  <span class="card-meta">{{ $categories[$project['cat']] }}<span class="sep">/</span>{{ Str::before($project['client'], ',') }}</span>
  <h3>{{ $project['title'] }}</h3>
</a>
