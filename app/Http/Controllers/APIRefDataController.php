<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemCollection;
use App\Http\Resources\ItemGroupCollection;
use App\Http\Resources\MaterialCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Item;
use App\Models\ItemGroup;
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

    public function apiGroupsIndex(Request $request)
    {
        return new ItemGroupCollection(ItemGroup::all());
    }

    public function apiItemsIndex(Request $request)
    {
        return new ItemCollection(Item::all());
    }


}
