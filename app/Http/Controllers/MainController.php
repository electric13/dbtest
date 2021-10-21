<?php

namespace App\Http\Controllers;

use App\Models\OrderLine;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function order($id) {

        $ols = OrderLine::where('order_id', (int)$id)->get();
        /*foreach ($ols as $orderline) {
            $prd = $orderline->product()->get();
            dd($prd[0]->product);
        }*/
        return view('welcome', [ 'ols' => $ols]);
    }
}
