<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SMSVerificacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user,$url,$code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $url,$code)
    {
        $this->user = $user;
        $this->url = $url;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::post('https://rest.nexmo.com/sms/json',[
            'from'=>"Vonage APIs",
            'text'=>"Codigo de verificacion: $this->code",
            'to'=>"52".$this->user->telefono,
            'api_key'=>"0ff442b0",
            'api_secret'=>"QtZzZW5glUgmiXBv"
        ]);
    }
}
