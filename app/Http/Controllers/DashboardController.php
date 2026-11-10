<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Patrimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        // 1. Visão do Administrador
        if ($user->role && $user->role->name === 'Admin') {
            $totalAmbientes   = Environment::count();
            $totalPatrimonios = Patrimony::count();
            $totalBons        = Patrimony::whereHas('state', fn($q) => $q->where('name', 'Bom'))->count();
            $totalRegulares   = Patrimony::whereHas('state', fn($q) => $q->where('name', 'Regular'))->count();
            $totalRuins       = Patrimony::whereHas('state', fn($q) => $q->where('name', 'Ruim'))->count();
            $totalCriticos    = Patrimony::whereHas('state', fn($q) => $q->whereIn('name', ['Péssimo', 'Baixa']))->count();
        } 
        // 2. Visão do Técnico
        else {
            $environmentIds   = $user->environments()->pluck('environments.id');
            $totalAmbientes   = $environmentIds->count();

            $totalPatrimonios = Patrimony::whereIn('environment_id', $environmentIds)->count();
            $totalBons        = Patrimony::whereIn('environment_id', $environmentIds)
                                        ->whereHas('state', fn($q) => $q->where('name', 'Bom'))->count();
            $totalRegulares   = Patrimony::whereIn('environment_id', $environmentIds)
                                        ->whereHas('state', fn($q) => $q->where('name', 'Regular'))->count();
            $totalRuins       = Patrimony::whereIn('environment_id', $environmentIds)
                                        ->whereHas('state', fn($q) => $q->where('name', 'Ruim'))->count();
            $totalCriticos    = Patrimony::whereIn('environment_id', $environmentIds)
                                        ->whereHas('state', fn($q) => $q->whereIn('name', ['Péssimo', 'Baixa']))->count();
        }

        return view('home', compact(
            'totalAmbientes',
            'totalPatrimonios',
            'totalBons',
            'totalRegulares',
            'totalRuins',
            'totalCriticos'
        ));
    }
}
