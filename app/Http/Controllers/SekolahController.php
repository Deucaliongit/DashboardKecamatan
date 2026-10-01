<?php

namespace App\Http\Controllers;

use App\Http\Requests\SekolahRequest;
use App\Models\Desa;
use App\Models\Sekolah;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $desaId = $request->integer('desa_id') ?: null;

            $sekolahs = Sekolah::with('desa')
                ->when($desaId, fn ($q) => $q->where('desa_id', $desaId))
                ->orderBy('nama_sekolah')
                ->paginate(10);

            $desas = Desa::orderBy('nama_desa')->get();

            return view('sekolah.index', compact('sekolahs', 'desas', 'desaId'));
        }

        // User Sekolah langsung diarahkan ke halaman edit sekolahnya sendiri
        abort_unless($user->sekolah_id, 403, 'Akun Anda belum terhubung ke sekolah manapun.');

        return redirect()->route('sekolah.edit', $user->sekolah_id);
    }

    public function create(): View
    {
        $this->authorizeAdmin();

        $desas = Desa::orderBy('nama_desa')->get();

        return view('sekolah.create', compact('desas'));
    }

    public function store(SekolahRequest $request): RedirectResponse
    {
        $this->authorizeAdmin();

        Sekolah::create($request->validated());

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit(Request $request, Sekolah $sekolah): View
    {
        $this->authorizeAccess($request, $sekolah);

        $desas = Desa::orderBy('nama_desa')->get();

        return view('sekolah.edit', compact('sekolah', 'desas'));
    }

    public function update(SekolahRequest $request, Sekolah $sekolah): RedirectResponse
    {
        $sekolah->update($request->validated());

        $redirectRoute = $request->user()->isAdmin() ? 'sekolah.index' : 'dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah): RedirectResponse
    {
        $this->authorizeAdmin();

        $sekolah->delete();

        return redirect()->route('sekolah.index')->with('success', 'Data sekolah berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Admin Kecamatan yang dapat melakukan aksi ini.');
    }

    private function authorizeAccess(Request $request, Sekolah $sekolah): void
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin() || $user->sekolah_id === $sekolah->id,
            403,
            'Anda tidak memiliki akses ke data sekolah ini.'
        );
    }
}
