<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportOrderRequest;
use App\Services\OrderImportService;
use Illuminate\Http\JsonResponse;

class OrderImportController extends Controller
{
    public function __construct(
        private readonly OrderImportService $importService,
    ) {}

    public function store(ImportOrderRequest $request): JsonResponse
    {
        $result = $this->importService->import(
            $request->user(),
            $request->validated(),
        );

        $statusCode = $result['status'] === 'created' ? 201 : 200;

        return response()->json($result, $statusCode);
    }
}
