<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
            ->subject('Welcome to Dental Clinic Management System')
            ->greeting('Welcome ' . $notifiable->name . '!')
            ->line('Thank you for joining our Dental Clinic Management System.')
            ->line('Your account has been successfully created with the role: ' . ucfirst($notifiable->role))
            ->line('You can now access the system and manage dental clinic operations.')
            ->action('Access Dashboard', url('/dashboard'))
            ->line('If you have any questions or need assistance, please contact the administrator.')
            ->line('Thank you for being part of our team!')
            ->salutation('Best regards, Dental Clinic Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
} 