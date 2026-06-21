<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminGlobalSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function __invoke(Request $request, AdminGlobalSearch $search): JsonResponse
    {
        $query = (string) $request->query('q', '');

        return response()->json($search->search($query));
    }
}
