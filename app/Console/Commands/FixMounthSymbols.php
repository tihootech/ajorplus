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
        // $symbols = ['A', 'B', 'C', 'D', 'E'];
        // foreach ($symbols as $symbol) {
        //     Mounth::where('symbol', "F{$symbol}")->update( compact('symbol') );
        // }

        $bricks = [
            'T8', 'T10', 'T12', 'T15',
            'FB', 'FS', 'LS', 'L5','L55',
            'LI', 'S25', 'S30', 'ETC',
        ];

        $mounths = Mounth::all();
        foreach ($mounths as $mounth) {
            $key = rand(0, count($bricks) - 1);
            $mounth->brick = $bricks[$key];
            if( $mounth->brick == 'T8' ) $mounth->quantity = 13000;
            elseif( $mounth->brick == 'T10') $mounth->quantity = 12000;
            elseif( $mounth->brick == 'T12') $mounth->quantity = 8500;
            elseif( $mounth->brick == 'T15') $mounth->quantity = 8000;
            elseif( $mounth->brick == 'FB' ) $mounth->quantity = 4000;
            elseif( $mounth->brick == 'FS' ) $mounth->quantity = 8000;
            elseif( $mounth->brick == 'LS' ) $mounth->quantity = 26000;
            elseif( $mounth->brick == 'L5' ) $mounth->quantity = 18000;
            elseif( $mounth->brick == 'L55') $mounth->quantity = 18000;
            elseif( $mounth->brick == 'S25') $mounth->quantity = 1800;
            elseif( $mounth->brick == 'S30') $mounth->quantity = 1400;
            else {
                 $mounth->quantity = rand(1, 10) * 100;
            }
            $mounth->save();
        }


    }
}
