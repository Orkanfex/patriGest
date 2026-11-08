<?php

namespace App\Http\Controllers;

use App\Models\Patrimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        // 1. Visão do Administrador (Ver tudo no sistema)
        if ($user->role && $user->role->name === 'Admin') {
            $totalPatrimonios = Patrimony::count();
            $totalBons        = Patrimony::whereHas('state', fn($q) => $q->where('name', 'Bom'))->count();
            $totalAtencao     = Patrimony::whereHas('state', fn($q) => $q->whereIn('name', ['Regular', 'Ruim']))->count();
            $totalCriticos    = Patrimony::whereHas('state', fn($q) => $q->whereIn('name', ['Péssimo', 'Baixa']))->count();
        } 
        // 2. Visão do Técnico (Apenas ambientes sob sua responsabilidade)
        else {
            $environmentIds   = $user->environments()->pluck('id');

            $totalPatrimonios = Patrimony::whereIn('environment_id', $environmentIds)->count();
            $totalBons        = Patrimony::whereIn('environment_id', $environmentIds)
                                     ->whereHas('state', fn($q) => $q->where('name', 'Bom'))->count();
            $totalAtencao     = Patrimony::whereIn('environment_id', $environmentIds)
                                     ->whereHas('state', fn($q) => $q->whereIn('name', ['Regular', 'Ruim']))->count();
            $totalCriticos    = Patrimony::whereIn('environment_id', $environmentIds)
                                     ->whereHas('state', fn($q) => $q->whereIn('name', ['Péssimo', 'Baixa']))->count();
        }

        return view('home', compact(
            'totalPatrimonios',
            'totalBons',
            'totalAtencao',
            'totalCriticos'
        ));
    }
}
