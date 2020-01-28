<?php

/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;


use App\Models\MailQueue;

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
            $emails = MailQueue::all();
           
            if (!$emails) {
				$this->info("No posts exist");
                return;
            }
            foreach ($emails as $email) {
                $email->delete();
				break;
            }
            $this->info("Email sent {$email->email}:{$email->activate_code}}");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }
}
