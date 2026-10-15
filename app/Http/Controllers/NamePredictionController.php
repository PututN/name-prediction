<?php

namespace App\Http\Controllers;

use App\Http\Requests\PredictNameRequest;
use App\Models\NamePrediction;
use App\Services\NamePredictionService;
use App\Services\Nationalize\NationalizeException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NamePredictionController extends Controller
{
    private const HISTORY_LIMIT = 20;

    public function index(): View
    {
        return view('predictions.index', [
            'result' => null,
            'history' => $this->history(),
        ]);
    }

    public function store(PredictNameRequest $request, NamePredictionService $service): RedirectResponse
    {
        try {
            $prediction = $service->predict($request->validated('name'));
        } catch (NationalizeException $e) {
            report($e);

            return back()->withInput()->withErrors(['name' => $e->getMessage()]);
        }

        return redirect()->route('predictions.show', $prediction);
    }

    public function show(NamePrediction $prediction): View
    {
        return view('predictions.index', [
            'result' => $prediction->load('countries'),
            'history' => $this->history(),
        ]);
    }

    public function destroy(NamePrediction $prediction): RedirectResponse
    {
        $prediction->delete();

        return redirect()->route('predictions.index');
    }

    private function history(): Collection
    {
        return NamePrediction::with('countries')->latest()->limit(self::HISTORY_LIMIT)->get();
    }
}
