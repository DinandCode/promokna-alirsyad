<?php

namespace App\Console\Commands;

use App\Jobs\SendConfirmationEmailJob;
use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendParticipantConfirmations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-confirmation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paidCount = Participant::whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        })->whereNot('email', '-')->count();

        $this->info("Anda akan mengirimkan email ke:");
        $this->line(" - $paidCount partisipan berbayar");

        if (! $this->confirm('Apakah sudah benar?')) {
            $this->warn('Dibatalkan oleh pengguna.');
            return;
        }

        $participants = Participant::whereHas('payment', function ($q) {
            $q->where('status', 'paid');
        })->whereNot('email', '-')->get();

        foreach ($participants as $participant) {
            SendConfirmationEmailJob::dispatch($participant);
        }

        $this->info("Semua email (total: $paidCount) telah dikirim ke antrian!");
    }
}
