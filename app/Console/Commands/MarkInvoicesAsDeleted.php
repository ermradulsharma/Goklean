<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkInvoicesAsDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete invoices that are 7 days old or more';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subMinutes(20);
        $updated = Invoice::where(['booking_completed' => 0, 'status' => 'created'])->whereNull('deleted_at')->whereDate('created_at', '<=', $cutoffDate)->update(['deleted_at' => Carbon::now()]);
        $this->info("Invoices updated: {$updated}");
    }
}
