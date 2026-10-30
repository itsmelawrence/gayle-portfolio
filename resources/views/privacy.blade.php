@extends('layouts.app')

@section('title', 'Privacy Policy | '.$name)
@section('description', 'How '.$name.' collects, uses, and protects the details you share through the contact form on this site.')

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
      <p>The site runs on trusted platforms, such as Laravel Cloud for hosting and Cloudflare for security, along with a small number of service providers that deliver email and fonts. They handle your information only to keep the site and form working, on my behalf, and never for their own marketing.</p>
    </li>
    <li>
      <h2>Cookies and storage</h2>
      <p>The site uses only essential cookies: one keeps your visit’s session and one protects the form against forged submissions. Cloudflare may set its own security cookie. Your browser also briefly remembers how you arrived, so it can be sent with your inquiry, and forgets it when you close the tab. There are no analytics, advertising, or tracking cookies.</p>
    </li>
    <li>
      <h2>How long I keep it</h2>
      <p>Inquiries are kept for as long as we’re working together or discussing a project, and for up to 24 months after our last contact. After that they’re deleted. You can ask for yours to be deleted sooner at any time.</p>
    </li>
    <li>
      <h2>Your choices</h2>
      <p>You can ask to see the information I hold about you, correct it, or have it deleted. Email {{ $reach }} and I’ll respond within 30 days. If you’re unhappy with how your data has been handled, you also have the right to contact your local data protection authority.</p>
    </li>
  </ol>

  <a class="btn btn-ghost legal-back" href="{{ route('home') }}#contact">Back to the contact form</a>
</article>
@endsection
