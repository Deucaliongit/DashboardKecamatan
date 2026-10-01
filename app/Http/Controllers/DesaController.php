<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesaRequest;
use App\Models\Desa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DesaController extends Controller
{
    public function index(): View
    {
        $desas = Desa::withCount(['sekolahs', 'anaks', 'faskes'])
            ->orderBy('nama_desa')
            ->paginate(10);

        return view('desa.index', compact('desas'));
    }

    public function create(): View
    {
        return view('desa.create');
    }

    public function store(DesaRequest $request): RedirectResponse
    {
        Desa::create($request->validated());

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil ditambahkan.');
    }

    public function edit(Desa $desa): View
    {
        return view('desa.edit', compact('desa'));
    }

    public function update(DesaRequest $request, Desa $desa): RedirectResponse
    {
        $desa->update($request->validated());

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil diperbarui.');
    }

    public function destroy(Desa $desa): RedirectResponse
    {
        $desa->delete();

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil dihapus.');
    }
}
