<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use App\Mail\CountUserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class RegisteredUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email of registered users';

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
     * @return mixed
     */
    public function handle()
    {
        $totalUsers = User::whereDate('created_at', Carbon::today())
            ->count();
        Mail::to('le.thi.be@sun-asterisk.com')->send(new CountUserMail($totalUsers));
    }
}
