{!! $opening_tag !!}
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/

namespace {{ $namespace }}\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class {{ $studly_param }} extends Mailable
{
    use Queueable, SerializesModels;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct()
    {
        //
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->markdown('{{ $studly }}::mail.{{$snake_param}}');
    }
}
