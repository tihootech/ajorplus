<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MounthRequest;
use App\Models\Mounth;

class MounthsController extends Controller
{

    public function store(MounthRequest $request)
    {
        $data = $request->validated();
        $biggestNum = Mounth::first() ? Mounth::max('num') : 0;
        $data['num'] = $biggestNum + 1;
        $mounth = Mounth::create($data);
        return ['success' => true, 'mounth' => $mounth];
    }

    public function update(Mounth $mounth, MounthRequest $request)
    {
        $data = $request->validated();
        $mounth->update($data);
        return ['success' => true, 'mounth' => $mounth];
    }

    public function destroy(Mounth $mounth)
    {
        $mounth->delete();
        return ['success' => true];
    }
}
