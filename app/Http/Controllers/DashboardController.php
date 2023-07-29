<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Mounth;

class DashboardController extends Controller
{
    public function home()
    {
        return $this->homeAndQomers('home');
    }

    public function qomers()
    {
        return $this->homeAndQomers('qomers');
    }

    public function homeAndQomers($from)
    {
        $symbols = ['A', 'B', 'C', 'D', 'E'];
        foreach ($symbols as $symbol) {
            $forges[$symbol] = Mounth::whereSymbol($symbol)->orderBy('num')->get();
        }

        $charts = [];
        $counts = [];

        // bricks array
        $bricksValues = [
            'T8', 'T10', 'T12', 'T15',
            'FB', 'FS', 'LS', 'L5','L55',
            'LI', 'S25', 'S30', 'ETC',
        ];
        $bricks = [];
        foreach ($bricksValues as $b) {
            $bricks[$b] = __("BRICK_NAME_{$b}");
        }

        if ($from == 'home') {
            for ($n=1; $n <= 4 ; $n++) {
                $charts['all'][$n] = Mounth::where('state', $n)->count();
                foreach ($symbols as $symbol) {
                    $charts[$symbol][$n] = Mounth::where('state', $n)->where('symbol', $symbol)->count();
                }
            }
            foreach ($bricks as $enBrick => $faBbrick) {
                $counts['all'][$enBrick] = Mounth::where('brick', $enBrick)->count();
                foreach ($symbols as $symbol) {
                    $counts[$symbol][$enBrick] = Mounth::where('brick', $enBrick)->where('symbol', $symbol)->count();
                }
            }
        }

        return Inertia::render('Dashboard/Admin/Qomers', compact('symbols', 'forges', 'charts', 'bricks', 'counts', 'from'));
    }

    public function data()
    {
        // TODO: use pagination for users
        $mounths = Mounth::orderBy('num')->get();
        return Inertia::render('Dashboard/Admin/Data', compact('mounths'));
    }

    public function users()
    {
        // TODO: use pagination for users
        $users = User::where('id', '!=', auth()->id())->latest()->get();
        return Inertia::render('Dashboard/Admin/Users', compact('users'));
    }

}
