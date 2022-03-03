{{--
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/
--}}

@verbatim
{{--
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/
--}}

@component('mail::message')
    # Introduction

    The body of your message.

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
@endverbatim
