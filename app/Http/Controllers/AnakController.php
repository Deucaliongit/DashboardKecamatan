<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnakRequest;
use App\Models\Anak;
use App\Models\Desa;
use App\Models\Sekolah;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    /** Batas umur [min, max] tahun untuk setiap pilihan filter kelompok umur. */
    private const KELOMPOK_UMUR = [
        'balita' => [0, 5],
        'anak' => [6, 12],
        'remaja' => [13, 18],
        'dewasa' => [19, 120],
    ];

    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        $desaId = $isAdmin ? ($request->integer('desa_id') ?: null) : null;
        $kelompok = (string) $request->query('kelompok_umur', '');
        $statusStunting = (string) $request->query('status_stunting', '');
        $cari = (string) $request->query('cari', '');

        $query = Anak::with(['sekolah', 'desa']);

        if ($isAdmin) {
            $query->when($desaId, fn ($q) => $q->where('desa_id', $desaId));
        } else {
            abort_unless($user->sekolah_id, 403, 'Akun Anda belum terhubung ke sekolah manapun.');
            $query->where('sekolah_id', $user->sekolah_id);
        }

        if ($cari !== '') {
            $query->where('nama_anak', 'like', "%{$cari}%");
        }

        if ($statusStunting !== '') {
            $query->where('status_stunting', $statusStunting);
        }

        if (array_key_exists($kelompok, self::KELOMPOK_UMUR)) {
            [$min, $max] = self::KELOMPOK_UMUR[$kelompok];
            $query->kelompokUmurAntara($min, $max);
        }

        $anaks = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $desas = $isAdmin ? Desa::orderBy('nama_desa')->get() : collect();

        return view('anak.index', compact('anaks', 'desas', 'desaId', 'kelompok', 'statusStunting', 'cari', 'isAdmin'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        $desas = $user->isAdmin() ? Desa::orderBy('nama_desa')->get() : collect();
        $sekolahs = $user->isAdmin() ? Sekolah::with('desa')->orderBy('nama_sekolah')->get() : collect();

        return view('anak.create', compact('desas', 'sekolahs'));
    }

    public function store(AnakRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($user->isAdmin()) {
            // Kalau sekolah dipilih, samakan desa_id dengan desa sekolah tsb agar data tetap konsisten
            if (! empty($data['sekolah_id'])) {
                $data['desa_id'] = Sekolah::findOrFail($data['sekolah_id'])->desa_id;
            }
        } else {
            $data['sekolah_id'] = $user->sekolah_id;
            $data['desa_id'] = $user->sekolah->desa_id;
        }

        Anak::create($data);

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil ditambahkan.');
    }

    public function edit(Request $request, Anak $anak): View
    {
        $this->authorizeAccess($request, $anak);

        $user = $request->user();
        $desas = $user->isAdmin() ? Desa::orderBy('nama_desa')->get() : collect();
        $sekolahs = $user->isAdmin() ? Sekolah::with('desa')->orderBy('nama_sekolah')->get() : collect();

        return view('anak.edit', compact('anak', 'desas', 'sekolahs'));
    }

    public function update(AnakRequest $request, Anak $anak): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($user->isAdmin()) {
            if (! empty($data['sekolah_id'])) {
                $data['desa_id'] = Sekolah::findOrFail($data['sekolah_id'])->desa_id;
            }
        } else {
            $data['sekolah_id'] = $anak->sekolah_id;
            $data['desa_id'] = $anak->desa_id;
        }

        $anak->update($data);

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil diperbarui.');
    }

    public function destroy(Request $request, Anak $anak): RedirectResponse
    {
        $this->authorizeAccess($request, $anak);

        $anak->delete();

        return redirect()->route('anak.index')->with('success', 'Data anak berhasil dihapus.');
    }

    private function authorizeAccess(Request $request, Anak $anak): void
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin() || $user->sekolah_id === $anak->sekolah_id,
            403,
            'Anda tidak memiliki akses ke data anak ini.'
        );
    }
}
