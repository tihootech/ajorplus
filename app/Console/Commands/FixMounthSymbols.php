<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mounth;

class FixMounthSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing symbols for mounths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $symbols = ['A', 'B', 'C', 'D', 'E'];
        foreach ($symbols as $symbol) {
            Mounth::where('symbol', "F{$symbol}")->update( compact('symbol') );
        }
    }
}
