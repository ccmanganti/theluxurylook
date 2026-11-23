@extends('layouts.site')
@section('content')
<article class="lux-contact">
    <header class="lux-contact-hero"><div class="lux-contact-hero__inner lux-wrap"><p class="lux-overline">The Luxury Look / Project enquiries</p><h1>Every space has<br><em>a next chapter.</em></h1><p>Tell us what you're imagining. The best projects begin with an open conversation about what matters to you.</p><div><a href="#inquiry" class="lux-btn lux-btn--light">Share your project <span>&nearr;</span></a><span>SCOTTSDALE, ARIZONA / BY APPOINTMENT</span></div></div></header>
    <section class="lux-contact-intro lux-wrap"><div><p class="lux-overline">01 / Start here</p><h2>We would love<br><em>to hear your ideas.</em></h2></div><p>From a single room to an entire property, you can start by sharing a few details. Your plans don't need to be final for us to have a useful conversation.</p></section>
    <section id="inquiry" class="lux-contact-main lux-wrap">
        <div class="lux-contact-form"><div class="lux-contact-form__title"><span class="lux-overline">Project inquiry / Step one</span><h2>Tell us about<br><em>the space.</em></h2><p>Use the form below, or email Kristine directly if that's easier.</p></div>@if(session('success'))<p class="form-success" role="status">{{ session('success') }}</p>@endif
            <form action="{{ route('contact.submit') }}" method="post" class="contact-form">@csrf
<div class="revamp-inquiry-fields" data-conditional-inquiry>
    <p class="lux-overline">Tell us about your home</p>
    <label>How may we help? <span>*</span><select name="service" id="inquiry-service" required>
      <option value="">Choose the closest service</option>
      @foreach(['Custom Home Design','Luxury Renovation','Interior Furnishings & Styling','Second-Home Furnishing & Setup','Builder or Developer Design Partnership','Luxury Home Staging','Turnkey Furniture & Housewares','The Curated Circle','I’d Appreciate Your Guidance'] as $choice)
      <option value="{{ $choice }}" @selected(old('service',request('service'))===$choice)>{{ $choice }}</option>
      @endforeach
    </select></label>
    <div class="form-grid"><label>Where is your property? <input name="property_location" value="{{ old('property_location') }}" maxlength="255" placeholder="City, state / country"></label>
    <label>Ideal timing <input name="project_timing" value="{{ old('project_timing') }}" maxlength="180" placeholder="Expected start, move-in or listing"></label></div>
    <div class="form-grid"><label>Estimated investment range <input name="investment_range" value="{{ old('investment_range') }}" maxlength="180" placeholder="An estimate is fine"></label>
    <label>Project stage <select name="project_stage"><option value="">Select a stage</option>@foreach(['Exploring possibilities','Planning / design','Construction underway','Ready for furnishing','Preparing to list'] as $stage)<option value="{{ $stage }}" @selected(old('project_stage')===$stage)>{{ $stage }}</option>@endforeach</select></label></div>
    <div data-inquiry-branch="staging" hidden><div class="form-grid"><label>Is the property furnished?<select name="staging_occupancy"><option value="">Choose one</option>@foreach(['Vacant','Partially furnished','Fully furnished and occupied','Furnished but will be vacant'] as $opt)<option>{{ $opt }}</option>@endforeach</select></label><label>Planned listing date<input name="listing_date" placeholder="Date or tentative month"></label></div></div>
    <div data-inquiry-branch="builder" hidden><div class="form-grid"><label>Construction status<input name="construction_status" placeholder="Planning, underway, completed"></label><label>Number of properties<input name="property_count" type="number" min="1" max="999" placeholder="Optional"></label></div></div>
    <p class="revamp-note">We'll ask only the follow-up questions relevant to your selected service.</p>
</div>
<div class="honeypot" aria-hidden="true"><label for="website">Leave blank</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
                <div class="form-grid"><label>Your name <span>*</span><input required type="text" name="name" value="{{ old('name') }}" placeholder="Full name" maxlength="180" autocomplete="name">@error('name')<small role="alert">{{ $message }}</small>@enderror</label><label>Email address <span>*</span><input type="email" required name="email" placeholder="you@example.com" value="{{ old('email') }}" autocomplete="email">@error('email')<small role="alert">{{ $message }}</small>@enderror</label></div>
                <div class="form-grid"><label>Phone number<input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Optional" autocomplete="tel">@error('phone')<small role="alert">{{ $message }}</small>@enderror</label></div>
                <label>Tell us about your project <span>*</span><textarea required name="message" rows="7" maxlength="5000" minlength="10" placeholder="Where is the property? What would you like to change? Is there a timeline in mind?">{{ old('message') }}</textarea>@error('message')<small role="alert">{{ $message }}</small>@enderror</label>
                <button type="submit" class="lux-btn lux-btn--dark">Send my inquiry <span>&nearr;</span></button><p class="form-footnote">Your information is used to respond to your inquiry.</p>
            </form>
        </div>
        <aside class="lux-contact-aside"><div class="lux-contact-aside__art media-frame"><img loading="lazy" src="{{ config('editorial.pages.about.detail') }}" alt="The Luxury Look interior design reference"></div><div class="lux-contact-aside__text"><span class="lux-overline">Visit / Connect</span><h3>Let's meet<br><em>where you are.</em></h3><p>Studio appointments are arranged in advance. Reach out through the form or contact us directly.</p><dl><div><dt>Studio</dt><dd>4343 N Scottsdale Road<br>Suite 150, Scottsdale, AZ 85251</dd></div><div><dt>Phone</dt><dd><a href="tel:+16025786707">+1 (602) 578-6707</a></dd></div><div><dt>Email</dt><dd><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></dd></div></dl></div></aside>
    </section>
    <section class="lux-contact-next lux-wrap"><div><p class="lux-overline">02 / What comes next</p><h2>A conversation,<br><em>not a sales pitch.</em></h2></div><div class="lux-contact-next__steps"><div><span>01</span><h3>Share the essentials</h3><p>Tell us a little about the space and what you'd like to accomplish.</p></div><div><span>02</span><h3>We'll review your brief</h3><p>The studio can consider your goals and the type of project support you may need.</p></div><div><span>03</span><h3>Discuss the next step</h3><p>From there, we can explore the best direction for a consultation.</p></div></div></section>
</article>
@endsection
