<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TCMBException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyViewAnyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Services\TCMBImporter;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function index(CurrencyViewAnyRequest $request, TCMBImporter $importer)
    {
        $startDate = request()->date('start_date') ?? now();

        $importer->setDateRange($startDate, $request->date('end_date') ?? null)->fetch()->store();

        $currencies = Currency::query();

        if ($request->has('end_date') && $request->end_date !== null) {

            $currencies->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $request->date('end_date') ?? now());
        } else {
            $currencies->whereDate('date', '=', $startDate);
        }

        if (request()->has('search') && $request->search !== null) {
            $search = request('search');

            $currencies->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            });
        }

        $currencies->orderBy('date', 'asc');

        return CurrencyResource::collection($currencies->paginate(23))->additional(['debug_messages' => $importer->debugMessages]);
    }
}
