<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\OrderLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $data = Invoice::Order()
            ->where('midtrans_status', 'settlement')
            ->where('flag_id', 14)
            ->get();        
        foreach ($data as $d) {
            if(now() > $d->finish_date)
            {
                $d->update([
                    'flag_id' => 16,
                ]);
                OrderLog::create([
                    'invoice_id' => $d->id,
                    'flag_id' => 16,
                    'keterangan' => 'Sudah 7 hari dari estimasi paket sampai.',
                ]);
            }
        }
        info('Invoice Check executed');
    }
}
