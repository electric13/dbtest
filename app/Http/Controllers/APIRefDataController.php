<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasketItemCollection;
use App\Http\Resources\MaterialCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class APIRefDataController extends Controller
{
    public function apiMatIndex(Request $request){
        return new MaterialCollection(Material::all());
    }

    public function apiProdIndex(Request $request){
        return new ProductCollection(Product::all());
    }

}
