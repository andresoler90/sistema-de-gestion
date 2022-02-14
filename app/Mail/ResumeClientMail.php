<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Register;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResumeClientMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $registers = Register::where('clients_id', $this->client->id)->get();
        return $this->view('mails.general.resume_client',compact('registers'));
    }
}
