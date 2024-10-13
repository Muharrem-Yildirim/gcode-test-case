<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TCMBException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyViewAnyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Services\TCMBImporter;

class CurrencyController extends Controller
{
    public function index(CurrencyViewAnyRequest $request, TCMBImporter $importer)
    {
        $startDate = request()->date('start_date') ?? now();

        try {
            $importer->setDateRange($startDate, $request->date('end_date') ?? null)->fetch()->store();
        } catch (\Throwable $e) {
            if ($e instanceof TCMBException) {
                return response()->json(['message' => 'TCMB Error'], 500);
            }
        }

        $currencies = Currency::whereDate('date', '>=', $startDate);

        if ($request->has('end_date') && $request->end_date !== null) {
            $currencies->whereDate('date', '<=', $request->date('end_date') ?? now());
        }

        return CurrencyResource::collection($currencies->paginate(10))->additional(['debug_messages' => $importer->debugMessages]);
    }
}
