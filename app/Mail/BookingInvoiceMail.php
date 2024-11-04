<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        $invoice = Invoice::where('booking_id', $data->id)->with(['detail'])->first();

        return $this->from('no.reply@'.env('APP_DOMAIN') , 'Invoice - Wardrobe Racing MX')
                    ->view('mail.booking_invoice')
                    ->with([
                        'data' => $this->data,
                        'invoice' => $invoice
                    ]);;
    }
}
