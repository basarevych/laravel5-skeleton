<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contracts\Repositories\Tokens;

class ExpireTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens.';

    /**
     * Tokens repository
     *
     * @var Tokens
     */
    protected $tokens;

    /**
     * Create a new command instance.
     *
     * @param Tokens $tokens
     * @return void
     */
    public function __construct(Tokens $tokens)
    {
        parent::__construct();

        $this->tokens = $tokens;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->tokens->deleteExpired();
    }
}
