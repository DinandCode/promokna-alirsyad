<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ParticipantConfirmationMail;
use App\Models\MailLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendConfirmationEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $participant;

    public function __construct($participant = null)
    {
        $this->participant = $participant;
    }

    public function handle()
    {
        if ($this->participant->email) {
            $log = MailLog::create([
                'to' => $this->participant->email ?? '-',
                'subject' => config('app.name') . ' | Konfirmasi Pengambilan Ridepack',
                'content' => view('emails.participant_confirmation', [
                    'participant' => $this->participant
                ])->render(),
                'status' => 'pending',
            ]);

            Mail::to($this->participant->email)->send(new ParticipantConfirmationMail($this->participant));

            $log->update(['status' => 'sent']);
        }
    }

    public function failed(\Throwable $exception)
    {
        $log = MailLog::where('to', $this->participant->email)
            ->where('subject', config('app.name') . ' | Konfirmasi Pengambilan Ridepack')
            ->latest()
            ->first();

        if ($log) {
            $log->update(['status' => 'failed']);
        }

        Log::error("Mail send failed for BIB {$this->participant->bib}: " . $exception->getMessage());
    }

    public function uniqueId()
    {
        return $this->participant->id;
    }

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }
}
