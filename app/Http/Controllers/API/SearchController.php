<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request) {
        $data = $request->get('data');
        $search = Keyword::where('keyword', 'like', "%{$data}%")->paginate(15);

        return response()->json($search);
    }
}
