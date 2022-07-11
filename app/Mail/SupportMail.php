<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SupportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected Collection $data
    ){}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from(config('custom.report_mail'))
            ->markdown('emails.support')
            ->subject('Заявка в тех. поддержку от ' . $this->data->fullName)
            ->with(['data' => $this->data]);
        $file = $this->data->file;
        if (!is_null($file)) {
            $this->attach($file, [
                'as' => "image.{$file->extension()}"
            ]);
        }
        return $this;
    }
}
