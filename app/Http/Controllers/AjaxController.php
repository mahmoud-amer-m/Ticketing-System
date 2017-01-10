<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller {

    public function getBuildings($region) {
        $buildings = DB::table('buildings')
                ->where('building_region', '=', $region)
                ->get();
//        var_dump($buildings);
        return response()->json(array('buildings' => $buildings), 200);
    }

}
