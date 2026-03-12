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
            'canExportDataBank' => $this->userCanExportDataBank($request->user()),
        ]);
    }

    /**
     * Export data bank as CSV. Restricted to certain users.
     */
    public function export(Request $request): StreamedResponse
    {
        if (! $request->user()->is_admin) {
            abort(403, 'You do not have permission to export the data bank.');
        }

        $entries = DataEntry::where('user_id', $request->user()->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data-bank.csv"',
        ];

        return response()->stream(function () {
        }, 200, $headers);
    }

    private function userCanExportDataBank($user): bool
    {
        return $user && $user->is_admin;
    }
}
