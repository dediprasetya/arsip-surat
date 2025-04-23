<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Surat;

class SuratBaruNotification extends Notification
{
    use Queueable;

    protected $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Kirim via email dan database
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Surat Baru: ' . $this->surat->nomor_surat)
                    ->line('Perihal: ' . $this->surat->perihal)
                    ->action('Lihat Surat', url('/surat/' . $this->surat->id))
                    ->line('Mohon segera lakukan disposisi.');
    }

    public function toArray($notifiable)
    {
        return [
            'nomor_surat' => $this->surat->nomor_surat,
            'perihal' => $this->surat->perihal,
            'link' => url('/surat/' . $this->surat->id),
        ];
    }
}
