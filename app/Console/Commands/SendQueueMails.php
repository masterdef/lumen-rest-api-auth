<?php

/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;


use App\Model\User;

use Exception;
use Illuminate\Console\Command;



/**
 * @category Console_Command
 * @package  App\Console\Commands
 */
class SendQueueMails extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "mails-queue:send";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send Emails from Queue";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $posts = User::all();
var_dump($posts);exit;
           
            if (!$posts) {
            $this->info("No posts exist");
                return;
            }
            foreach ($posts as $post) {
                $post->delete();
            }
            $this->info("All posts have been deleted");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }
}
