<?php

namespace App\Modules\Contact\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;
    protected $contacts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contacts)
    {
        $this->contacts = $contacts;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('seham@ejjadh.com', 'Mailtrap')
            ->subject('Ejjadh News')
            ->view('email.contact_email')
            ->with('contacts', $this->contacts);
    }
}
