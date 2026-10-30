<x-mail::message>
# New inquiry from {{ $inquiry['name'] }}

**Email:** {{ $inquiry['email'] }}<br>
**Company:** {{ $inquiry['company'] ?: 'Not given' }}<br>
**Service:** {{ $inquiry['service'] }}

{{ $inquiry['details'] }}

Reply to this email to answer {{ $inquiry['name'] }} directly.
</x-mail::message>
