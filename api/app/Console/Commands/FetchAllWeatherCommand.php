<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\FetchWeatherForUser;

class FetchAllWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:fetch-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch current weather for all users';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //
        
        $users = User::all();
        foreach ($users as $user) {
            FetchWeatherForUser::dispatch($user);
        }
    }
}
