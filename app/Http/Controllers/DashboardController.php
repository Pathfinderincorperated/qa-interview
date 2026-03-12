<?php

namespace App\Http\Controllers;

use App\Models\DataEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $dataBankEntriesCount = DataEntry::where('user_id', $user->id)->count();

        $categoriesCount = DataEntry::where('user_id', $user->id)
            ->select('category_id')
            ->groupBy('category_id')
            ->get()
            ->count();

        $recentEntries = DataEntry::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($entry) => [
                'id' => $entry->id,
                'title' => $entry->title,
                'category' => $entry->category?->name ?? null,
                'created_at' => $entry->created_at->toIso8601String(),
            ]);

        return Inertia::render('Dashboard', [
            'dataBankEntriesCount' => $dataBankEntriesCount,
            'categoriesCount' => $categoriesCount,
            'recentEntries' => $recentEntries,
        ]);
    }
}
