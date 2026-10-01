<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaskesRequest;
use App\Models\Desa;
use App\Models\Faskes;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaskesController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $desaId = $request->integer('desa_id') ?: null;

            $faskes = Faskes::with('desa')
                ->when($desaId, fn ($q) => $q->where('desa_id', $desaId))
                ->orderBy('nama_faskes')
                ->paginate(10)
                ->withQueryString();

            $desas = Desa::orderBy('nama_desa')->get();

            return view('faskes.index', compact('faskes', 'desas', 'desaId'));
        }

        // Admin Puskesmas langsung diarahkan ke halaman edit fasilitasnya sendiri
        abort_unless(
            $user->isPuskesmas() && $user->faskes_id,
            403,
            'Akun Anda belum terhubung ke fasilitas kesehatan manapun.'
        );

        return redirect()->route('faskes.edit', $user->faskes_id);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        $desas = Desa::orderBy('nama_desa')->get();

        return view('faskes.create', compact('desas'));
    }

    public function store(FaskesRequest $request): RedirectResponse
    {
        $this->authorizeAdmin();

        Faskes::create($request->validated());

        return redirect()->route('faskes.index')->with('success', 'Data fasilitas kesehatan berhasil ditambahkan.');
    }

    public function edit(Request $request, Faskes $faskes): View
    {
        $this->authorizeAccess($request, $faskes);

        $desas = Desa::orderBy('nama_desa')->get();

        return view('faskes.edit', compact('faskes', 'desas'));
    }

    public function update(FaskesRequest $request, Faskes $faskes): RedirectResponse
    {
        $faskes->update($request->validated());

        $redirectRoute = $request->user()->isAdmin() ? 'faskes.index' : 'dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Data fasilitas kesehatan berhasil diperbarui.');
    }

    public function destroy(Faskes $faskes): RedirectResponse
    {
        $this->authorizeAdmin();

        $faskes->delete();

        return redirect()->route('faskes.index')->with('success', 'Data fasilitas kesehatan berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Admin Kecamatan yang dapat melakukan aksi ini.');
    }

    private function authorizeAccess(Request $request, Faskes $faskes): void
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin() || ($user->isPuskesmas() && $user->faskes_id === $faskes->id),
            403,
            'Anda tidak memiliki akses ke data fasilitas kesehatan ini.'
        );
    }
}
