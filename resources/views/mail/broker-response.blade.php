@component('mail::message')

# Response from {{ $broker }}
Hey {{ $firstName }},\
Product Two made a request to {{ $broker }} on your behalf. You can check out your broker's response below.

Reply to this email if you have any questions or comments.

@component('mail::panel')
<pre>{{ $brokerResponse }}</pre>
@endcomponent

@endcomponent
