<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIRegistrator extends Controller
{
    public function getKey(Request $request){
        $data = [ 'key' => base_convert(sha1(uniqid(mt_rand())), 16, 36)];
        return $data;
    }
}
