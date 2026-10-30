{{-- Inquiry alert for Gayle, styled like the portfolio. Email clients need tables and inline styles; Fraunces loads in
     Apple Mail and falls back to Georgia elsewhere. --}}
@php
    $serif = "'Fraunces','Iowan Old Style',Georgia,serif";
    $sans = "'Manrope',-apple-system,'Segoe UI',Helvetica,Arial,sans-serif";
    $fields = [
        'Name' => $lead->name,
        'Email' => $lead->email,
        'Company' => $lead->company ?: 'Not given',
        'Service' => $lead->service,
    ];
@endphp
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light only">
<meta name="supported-color-schemes" content="light">
<title>New inquiry from {{ $lead->name }}</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..500&family=Manrope:wght@400..700&display=swap">
<style>
  @media (max-width:600px){ .card{border-radius:0 !important} .pad{padding-left:22px !important;padding-right:22px !important} .label{display:block !important;width:auto !important;padding-bottom:2px !important;border-bottom:0 !important} .value{display:block !important;padding-top:0 !important} }
  a{color:#7C0E44}
</style>
</head>
<body style="margin:0;padding:0;background:#F2DACE;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">{{ $lead->name }} is interested in {{ $lead->service }}. {{ \Illuminate\Support\Str::limit($lead->details, 90) }}</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F2DACE;">
  <tr><td align="center" style="padding:32px 12px;">
    <table role="presentation" class="card" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background:#FBF5F1;border-radius:20px;overflow:hidden;">

      {{-- Header: plum band with soft lava glows, like the hero --}}
      <tr><td class="pad" style="padding:30px 40px 34px;background:#7C0E44;background-image:radial-gradient(circle at 88% 0%,#D49A9A 0%,rgba(212,154,154,0) 46%),radial-gradient(circle at 0% 100%,#AA5E7A 0%,rgba(170,94,122,0) 50%);">
        <p style="margin:0 0 22px;font-family:{!! $serif !!};font-size:22px;line-height:1;color:#F4E4DC;">{{ config('portfolio.name') }}</p>
        <p style="margin:0 0 8px;font-family:{!! $sans !!};font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#E3B9BC;">
          <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#3BB273;margin-right:6px;vertical-align:1px;"></span>New inquiry
        </p>
        <h1 style="margin:0;font-family:{!! $serif !!};font-weight:300;font-size:34px;line-height:1.15;letter-spacing:-.01em;color:#FBF5F1;">{{ $lead->name }} wants to talk about {{ \Illuminate\Support\Str::lower($lead->service) === 'not sure yet' ? 'a project' : $lead->service }}.</h1>
      </td></tr>

      {{-- Submitted fields --}}
      <tr><td class="pad" style="padding:30px 40px 6px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
          @foreach ($fields as $label => $value)
          <tr>
            <td class="label" width="110" valign="top" style="width:110px;padding:12px 0;border-bottom:1px solid #E4CCC2;font-family:{!! $sans !!};font-size:13px;font-weight:600;color:#6E5560;">{{ $label }}</td>
            <td class="value" valign="top" style="padding:12px 0;border-bottom:1px solid #E4CCC2;font-family:{!! $sans !!};font-size:16px;line-height:1.45;color:#2A1520;">
              @if ($label === 'Email')<a href="mailto:{{ $lead->email }}" style="color:#7C0E44;text-decoration:underline;">{{ $value }}</a>@else{{ $value }}@endif
            </td>
          </tr>
          @endforeach
        </table>
      </td></tr>

      <tr><td class="pad" style="padding:22px 40px 8px;">
        <p style="margin:0 0 10px;font-family:{!! $sans !!};font-size:13px;font-weight:600;color:#6E5560;">Project details</p>
        <div style="padding:18px 20px;background:#F7EAE3;border-left:3px solid #AA5E7A;border-radius:4px 12px 12px 4px;font-family:{!! $sans !!};font-size:16px;line-height:1.6;color:#2A1520;">{!! nl2br(e($lead->details)) !!}</div>
      </td></tr>

      {{-- Reply button: Reply-To is already the sender, this just saves a click --}}
      <tr><td class="pad" style="padding:26px 40px 30px;">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
          <td style="border-radius:999px;background:#7C0E44;">
            <a href="mailto:{{ $lead->email }}?subject={{ rawurlencode('Re: your inquiry about '.$lead->service) }}" style="display:inline-block;padding:14px 26px;font-family:{!! $sans !!};font-size:15px;font-weight:600;color:#FBF5F1;text-decoration:none;border-radius:999px;">Reply to {{ \Illuminate\Support\Str::before($lead->name, ' ') }} &rarr;</a>
          </td>
        </tr></table>
      </td></tr>

      {{-- Where they came from, and when --}}
      <tr><td class="pad" style="padding:20px 40px 30px;background:#F7EAE3;border-top:1px solid #E4CCC2;">
        @if ($source)
        <p style="margin:0 0 8px;font-family:{!! $sans !!};font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#AA5E7A;">Where they came from</p>
        @foreach ($source as $label => $value)
        <p style="margin:0 0 4px;font-family:{!! $sans !!};font-size:13px;line-height:1.5;color:#6E5560;word-break:break-all;"><strong style="color:#2A1520;font-weight:600;">{{ $label }}:</strong> {{ $value }}</p>
        @endforeach
        @endif
        <p style="margin:{{ $source ? '14px' : '0' }} 0 0;font-family:{!! $sans !!};font-size:13px;line-height:1.5;color:#6E5560;">Submitted {{ $submittedAt->format('j M Y, g:i A T') }} &middot; Lead #{{ $lead->id }}</p>
      </td></tr>

    </table>
    <p style="margin:18px 0 0;font-family:{!! $sans !!};font-size:12px;color:#8E6A78;">Sent from the contact form on <a href="{{ url('/') }}" style="color:#8E6A78;">{{ parse_url(url('/'), PHP_URL_HOST) }}</a></p>
  </td></tr>
</table>
</body>
</html>
