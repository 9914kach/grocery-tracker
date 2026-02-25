<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index(): Response
    {
        $unmatched = StoreProduct::with('store')
            ->whereNull('product_id')
            ->orderBy('name')
            ->paginate(20)
            ->through(fn($sp) => [
                'id'                 => $sp->id,
                'name'               => $sp->name,
                'store'              => $sp->store->name,
                'unit_size'          => $sp->unit_size,
                'external_id'        => $sp->external_id,
                'barcode'            => $sp->barcode,
            ]);

        return Inertia::render('Review/Index', [
            'unmatched' => $unmatched,
        ]);
    }
}
