<?php

namespace App\Mail;

use App\Model\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $task;

    public function __construct(User $user , Task  $task){

        $this->user = $user;
        $this->task = $task;

    }

    public function build(){

        return $this->subject('Notification')
            ->view('email.notification' , ['task' => $this->task , 'user' => $this->user]);
    }
}
