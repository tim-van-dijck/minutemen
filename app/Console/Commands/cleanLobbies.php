<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lobby;

class cleanLobbies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lobbies:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes lobbies whose meetup timestamps are in the past';

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
        Lobby::where('meet_at', '<', date('Y-m-d H:i:s'))->delete();
    }
}
