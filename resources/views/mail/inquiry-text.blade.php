New inquiry from {{ $lead->name }}

Name:     {{ $lead->name }}
Email:    {{ $lead->email }}
Company:  {{ $lead->company ?: 'Not given' }}
Service:  {{ $lead->service }}

Project details
{{ $lead->details }}

@if ($source)
Where they came from
@foreach ($source as $label => $value)
{{ $label }}: {{ $value }}
@endforeach

@endif
Submitted {{ $submittedAt->format('j M Y, g:i A T') }} · Lead #{{ $lead->id }}

Reply to this email to answer {{ $lead->name }} directly.
