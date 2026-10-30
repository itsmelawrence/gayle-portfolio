@extends('layouts.app')

@section('title', 'Privacy Policy | '.$name)
@section('description', 'How '.$name.' collects, uses, and protects the details you share through the contact form on this site.')

{{-- Text in [square brackets] is a placeholder for Gayle to confirm before launch. --}}
@php($reach = $contact['email'] ? new \Illuminate\Support\HtmlString('<a href="mailto:'.e($contact['email']).'">'.e($contact['email']).'</a>') : ph('[email address]'))
@section('content')
<article class="legal wrap">
  <header class="legal-head">
    <p class="legal-kicker">Privacy policy</p>
    <h1>Your details, handled with care.</h1>
    <p class="lede">This site only collects what you choose to send through the contact form, plus the small amount of technical data needed to keep the form safe. Here is exactly what that means.</p>
    <p class="legal-updated">Last updated 30 October 2026</p>
  </header>

  <ol class="legal-list">
    <li>
      <h2>What I collect</h2>
      <p>When you send an inquiry, I receive the details you enter: your name, email address, company (optional), the service you’re interested in, and your project details.</p>
      <p>Alongside that, the form records your IP address and browser type, plus how you found the site (the referring page and any campaign tags in the link). This helps me spot spam and understand which channels bring people here.</p>
    </li>
    <li>
      <h2>How I use it</h2>
      <p>Only to read and reply to your inquiry, follow up on the conversation, and keep a simple record of who has been in touch. I don’t add you to a mailing list, and I never sell or rent your information.</p>
    </li>
    <li>
      <h2>Who else processes it</h2>
      <p>A few trusted services help run this site and only handle your data on my behalf:</p>
      <ul>
        <li><strong>Laravel Cloud</strong> hosts the site and its database, where inquiries are stored.</li>
        <li><strong>Resend</strong> delivers the email that notifies me of your inquiry.</li>
        <li><strong>Cloudflare Turnstile</strong> checks that the form is being used by a person, not a bot.</li>
        <li><strong>Google Fonts</strong> serves the typefaces used on the site.</li>
      </ul>
    </li>
    <li>
      <h2>Cookies and storage</h2>
      <p>The site uses only essential cookies: one keeps your visit’s session and one protects the form against forged submissions. Cloudflare Turnstile may set its own security cookie. Your browser also briefly remembers how you arrived, so it can be sent with your inquiry, and forgets it when you close the tab. There are no analytics, advertising, or tracking cookies.</p>
    </li>
    <li>
      <h2>How long I keep it</h2>
      <p>Inquiries are kept for as long as we’re working together or discussing a project, and for up to {{ ph('[24 months]') }} after our last contact. After that they’re deleted. You can ask for yours to be deleted sooner at any time.</p>
    </li>
    <li>
      <h2>Your choices</h2>
      <p>You can ask to see the information I hold about you, correct it, or have it deleted. Email {{ $reach }} and I’ll respond within {{ ph('[30 days]') }}. If you’re unhappy with how your data has been handled, you also have the right to contact your local data protection authority.</p>
    </li>
  </ol>

  <a class="btn btn-ghost legal-back" href="{{ route('home') }}#contact">Back to the contact form</a>
</article>
@endsection
