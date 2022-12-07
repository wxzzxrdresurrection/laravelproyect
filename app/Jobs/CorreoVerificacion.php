<?php

namespace App\Jobs;

use App\Mail\VerificacionMailer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CorreoVerificacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->correo)->send(new VerificacionMailer($this->user, $this->url));
    }
}
