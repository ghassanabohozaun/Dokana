<x-mail::message>
    # Hello {!! $name !!}

    Thank You for reaching out to us . We appreciate your message and value your name

    ** Our Message **
    {!! $message !!}

    If you have any further questions , feel free to replay to this email .

    Best reqards ,
    <br>
    ** {{ config('app.name') }} **
    <br>
    {{ config('app.url') }}

    <x-mail::button :url="config('app.url')">
        Visit our website
    </x-mail::button>
    <br>

</x-mail::message>


