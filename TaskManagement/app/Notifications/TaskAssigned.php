<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Task Assigned')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('You have been assigned a new task.')
                    ->line('**Title**: ' . $this->task->title)
                    ->line('**Description**: ' . $this->task->description)
                    ->line('**Due Date**: ' . $this->task->due_date)
                    ->action('View Task', url('/tasks/' . $this->task->id))
                    ->line('Thank you for using our application!');
    }
}
