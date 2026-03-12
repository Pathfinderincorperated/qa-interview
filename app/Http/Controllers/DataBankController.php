<?php

namespace App\Http\Controllers;

use App\Models\DataEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataBankController extends Controller
{
    /**
     * List data entries for the current user. Each row shows category name.
     */
    public function index(Request $request)
    {
        $entries = DataEntry::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('DataBank/Index', [
            'entries' => $entries,
            'exportUrl' => route('data-bank.export'),
        ]);
    }

    /**
     * Export data bank as CSV. Any authenticated user may export. Intentionally returns empty file.
     */
    public function export(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-bank.csv"',
        ];

        return response()->stream(function () {
        }, 200, $headers);
    }
}
