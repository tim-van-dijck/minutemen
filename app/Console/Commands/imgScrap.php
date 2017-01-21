<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Event;
use App\Organisation;
use App\Team;
use App\User;

class imgScrap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'img:scrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $db_imgs = [];
        $filetypes = ['.jpeg', '.png', '.jpg', '.JPG', '.gif'];
        $imgs = [
            'events'        => [
                'files' => scandir(public_path('img/events')),
                'db'    => Event::select('banner')->get(),
                'path'  => 'public/img/events/',
                'img'   => 'banner'
            ],
            'organisations' => [
                'files' => scandir(public_path('img/organisations')),
                'db'    => Organisation::select('thumb')->get(),
                'path'  => 'public/img/organisations/',
                'img'   => 'thumb'
            ],
            'teams'         => [
                'files' => scandir(public_path('img/teams')),
                'db'    => Team::select('emblem')->get(),
                'path'  => 'public/img/teams/',
                'img'   => 'emblem'
            ],
            'users'         => [
                'files' => scandir(public_path('img/users')),
                'db'    => User::select('img')->get(),
                'path'  => 'public/img/users/',
                'img'   => 'img'
            ],
        ];

        foreach($imgs as $index => $type) {
            foreach ($type['db'] as $db) {
                $db_imgs[] = $db->{$type['img']};
            }
            foreach ($type['files'] as $i => $img) {
                if (!in_array(substr($img, -4), $filetypes)) {
                    unset($type['files'][$i]);
                }
                if (in_array($type['path'].$img, $db_imgs)) { unset($type['files'][$i]); }
            }
            foreach ($type['files'] as $img) {
                unlink($type['path'].$img);
            }
        }
    }
}
