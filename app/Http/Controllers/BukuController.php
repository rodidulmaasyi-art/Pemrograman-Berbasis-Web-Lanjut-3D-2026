<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class BukuController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function index(): View
    {
        return view('buku.index', [
            'buku' => Buku::with('kategoriBuku')->get(),
        ]);
    }

    public function show(int $id): Response
    {
        $buku = Buku::with('kategoriBuku')->find($id);

        return response()->view('buku.show', [
            'buku' => $buku,
            'id'   => $id,
        ], $buku ? 200 : 404);
    }
}