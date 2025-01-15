<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function scoreRT()
    {
        $query = DB::table('nilai')
            ->select(
                'nama',
                'nama_pelajaran',
                'nisn',
                DB::raw('
            CASE 
                WHEN materi_uji_id = 7 THEN skor
                ELSE 0
            END AS nilaiRT
        ')
            )
            ->where('materi_uji_id', 7)
            ->get();

        $result = $query->groupBy('nisn')->map(function ($items) {
            $nama = $items->first()->nama;
            $nisn = $items->first()->nisn;
            $nilaiRT = $items->mapWithKeys(function ($item) {
                return [$item->nama_pelajaran => $item->nilaiRT];
            });
            return [
                'nama' => $nama,
                'nilaiRT' => $nilaiRT,
                'nisn' => $nisn
            ];
        });
        return response()->json($result);
    }

    public function getNilaiST()
    {
        
        $query = DB::table('nilai')
            ->select(
                'nama',
                'nisn',
                'nama_pelajaran',
                'pelajaran_id',
                'skor',
                DB::raw('
                CASE
                    WHEN pelajaran_id = 44 THEN skor * 41.67
                    WHEN pelajaran_id = 45 THEN skor * 29.67
                    WHEN pelajaran_id = 46 THEN skor * 100
                    WHEN pelajaran_id = 47 THEN skor * 23.81
                    ELSE 0
                END AS total_nilai
            ')
            )
            ->where('materi_uji_id', 4)  
            ->get();

        
        $result = $query->groupBy('nisn')->map(function ($items) {
            
            $nisn = $items->first()->nisn;
            $nama = $items->first()->nama;

            
            $total = $items->sum('total_nilai');

            
            $nilaiST = $items->mapWithKeys(function ($item) {
            return [$item->nama_pelajaran => $item->total_nilai];
            });

            
            return [
            'nama' => $nama,
            'nilaiST' => $nilaiST,
            'total' => $total,
            'nisn' => $nisn
            ];
        });

        
        $result = $result->sortByDesc('total');
        
        return response()->json($result);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
