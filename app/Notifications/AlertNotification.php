<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Log;
use \Carbon\Carbon;
use App\Models\User;

class AlertNotification extends Notification
{
    use Queueable;

    public function __construct($data)
    {
        $this->subject = $data['subject'];
        $this->title = $data['title'];
        $this->body = $data['body'];
        $this->button = $data['button'];
        $this->actionURL = $data['actionURL'];
        $this->footer = $data['footer'];
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        $update = User::findOrFail($notifiable->id);
        $update->update([ 'last_activity' => Carbon::now() ]);
            
        Log::info(['E-mail enviado', $notifiable->email]);
        return (new MailMessage)
                    ->subject($this->subject)
                    ->greeting($notifiable->name)
                    ->line($this->title)
                    ->line($this->body)
                    ->action($this->button, $this->actionURL)
                    ->line($this->footer);
    }

    public function toArray($notifiable)
    {
        return [

        ];
    }
}
