<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserTeamInviteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $teamName, string $defaultPassword = null)
    {
        $this->user = $user;
        $this->loginUrl = url('login');
        $this->teamName = $teamName;
        $this->defaultPassword = $defaultPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject('Convite para ingressar no time')->markdown('user.team.invite', [
                    'user' => $this->user,
                    'loginUrl' => $this->loginUrl,
                    'teamName' => $this->teamName,
                    'defaultPassword' => $this->defaultPassword
                ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [//
        ];
    }
}
