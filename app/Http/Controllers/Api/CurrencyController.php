<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TCMBException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyViewAnyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Services\TCMBImporter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function index(CurrencyViewAnyRequest $request, TCMBImporter $importer)
    {
        $startDate = request()->date('start_date') ?? now();

        if ($startDate->isWeekend()) {
            if ($startDate->isSunday()) {
                $startDate = $startDate->subDays(2);
            } else {
                $startDate = $startDate->subDays(1);
            }
        }

        $importer->setDateRange($startDate, $request->date('end_date') ?? null)->fetch()->store();
        $currencies = Currency::query();

        if ($request->has('end_date') && $request->end_date !== null) {

            $currencies->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $request->date('end_date') ?? now());
        } else {
            $currencies->whereDate('date', '=', $startDate);
        }

        if (request()->has('search') && !empty($request->search)) {
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
