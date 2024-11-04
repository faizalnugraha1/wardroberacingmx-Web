<?php

namespace App\Jobs;

use App\Http\Controllers\SharedFunction;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MidtransUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SharedFunction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = Invoice::Order()->where('midtrans_status', null)->orWhere('midtrans_status', 'pending')->get();        
        foreach ($data as $d) {
            $this->midtrans_check($d);
        }
        info('Midtrans Check executed');
    }
}
