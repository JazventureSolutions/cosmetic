<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function select2(Request $request)
    {
        $branches = Branch::select([
            'branches.id AS id',
            'branches.name AS text',
        ]);

        if ($request->has('q')) {
            $branches = $branches
                ->where('id', $request->q)
                ->orWhere('name', 'LIKE', '%' . $request->q . '%');
        }

        $branches = $branches
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $branches,
            'request' => $request->all()
        ]);
    }
}
