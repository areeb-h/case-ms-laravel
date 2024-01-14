<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Password;

class NewUserCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;

        // Generate a token for the user
        $this->token = Password::broker()->createToken($this->user);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('areeb.hussain.w@gmail.com')
            ->view('emails.newUserCredentials')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'reset_url' => url("password/reset/{$this->token}?email={$this->user->email}")
            ]);
    }
}
