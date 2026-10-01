<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\Desa;
use App\Models\Faskes;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        }

        if ($user->isPuskesmas()) {
            return $this->puskesmasDashboard($user);
        }

        return $this->sekolahDashboard($user);
    }

    private function adminDashboard(Request $request): View
    {
        $desaId = $request->integer('desa_id') ?: null;

        $desas = Desa::orderBy('nama_desa')->get();

        $sekolahQuery = Sekolah::when($desaId, fn ($q) => $q->where('desa_id', $desaId));

        $totalSekolah = (clone $sekolahQuery)->count();
        $totalGuru = (int) (clone $sekolahQuery)->sum('jumlah_guru');

        $totalSiswaL = (int) (clone $sekolahQuery)->sum('jumlah_siswa_l');
        $totalSiswaP = (int) (clone $sekolahQuery)->sum('jumlah_siswa_p');
        $totalSiswa = $totalSiswaL + $totalSiswaP;

        $totalFaskes = Faskes::when($desaId, fn ($q) => $q->where('desa_id', $desaId))->count();
        $totalAnakTerdata = Anak::when($desaId, fn ($q) => $q->where('desa_id', $desaId))->count();
        $totalStunting = Anak::stunting()->when($desaId, fn ($q) => $q->where('desa_id', $desaId))->count();

        $rekapPerDesa = Desa::withCount([
                'sekolahs',
                'anaks as total_anak_terdata',
                'anaks as total_stunting' => fn ($q) => $q->where('status_stunting', 'Stunting'),
            ])
            ->withSum('sekolahs as total_guru', 'jumlah_guru')
            ->withSum('sekolahs as total_siswa_l', 'jumlah_siswa_l')
            ->withSum('sekolahs as total_siswa_p', 'jumlah_siswa_p')
            ->orderBy('nama_desa')
            ->get();

        return view('dashboard.admin', compact(
            'desas',
            'desaId',
            'totalSekolah',
            'totalGuru',
            'totalSiswa',
            'totalFaskes',
            'totalAnakTerdata',
            'totalStunting',
            'rekapPerDesa'
        ));
    }

    private function sekolahDashboard(User $user): View
    {
        $sekolah = $user->sekolah;

        return view('dashboard.sekolah', compact('sekolah'));
    }

    private function puskesmasDashboard(User $user): View
    {
        $faskes = $user->faskes;

        $totalAnakTerdata = 0;
        $totalStunting = 0;

        if ($faskes) {
            $totalAnakTerdata = $faskes->anaks()->count();
            $totalStunting = $faskes->anaks()->where('status_stunting', 'Stunting')->count();
        }

        $persentaseStunting = $totalAnakTerdata > 0
            ? round(($totalStunting / $totalAnakTerdata) * 100, 1)
            : 0;

        return view('dashboard.puskesmas', compact('faskes', 'totalAnakTerdata', 'totalStunting', 'persentaseStunting'));
    }
}
