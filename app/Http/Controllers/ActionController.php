<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionRequest;
use App\Models\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActionController extends Controller
{
    public function index(Request $request): View
    {
        $sortColumn = [
            'action_code' => 'action_code',
            'theme' => 'theme',
            'status' => 'status',
            'priority' => 'priority',
            'updated_on' => 'updated_on',
        ][$request->string('sort')->toString()] ?? 'action_code';

        $actions = Action::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search');
                $query->where(function ($query) use ($search): void {
                    $query->where('action_code', 'like', "%{$search}%")
                        ->orWhere('theme', 'like', "%{$search}%")
                        ->orWhere('owner', 'like', "%{$search}%")
                        ->orWhere('source_note', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')))
            ->when($request->string('deadline')->toString() === 'overdue', fn ($query) => $query
                ->whereNot('status', 'Selesai')
                ->whereDate('due_date', '<', now()->startOfDay()))
            ->when($request->string('deadline')->toString() === 'upcoming', fn ($query) => $query
                ->whereNot('status', 'Selesai')
                ->whereBetween('due_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()]))
            ->orderBy($sortColumn)
            ->paginate(12)
            ->withQueryString();

        return view('tindakan.index', [
            'actions' => $actions,
            'statuses' => Action::query()->select('status')->distinct()->orderBy('status')->pluck('status'),
            'priorities' => ['Tinggi', 'Sederhana', 'Rendah'],
            'deadlines' => ['overdue' => 'Lewat tempoh', 'upcoming' => 'Dalam 7 hari'],
        ]);
    }

    public function create(): View
    {
        return view('tindakan.form', ['action' => new Action, 'formTitle' => 'Tambah tindakan']);
    }

    public function store(ActionRequest $request): RedirectResponse
    {
        Action::create($request->validated());

        return to_route('tindakan.index')->with('success', 'Tindakan berjaya ditambah.');
    }

    public function edit(Action $action): View
    {
        return view('tindakan.form', ['action' => $action, 'formTitle' => 'Edit tindakan']);
    }

    public function update(ActionRequest $request, Action $action): RedirectResponse
    {
        $action->update($request->validated());

        return to_route('tindakan.index')->with('success', 'Tindakan berjaya dikemas kini.');
    }

    public function destroy(Action $action): RedirectResponse
    {
        $action->delete();

        return to_route('tindakan.index')->with('success', 'Tindakan berjaya dipadam.');
    }
}
