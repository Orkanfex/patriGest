@extends('layouts.default')

@section('page-title', 'Dashboard')

@php
    $breadcrumbs = [
        ['label' => 'Home', 'route' => route('home')],
    ];
@endphp

@section('content')
    <!--begin::Row Top Cards-->
    <div class="row">
        <!-- Card 1: Bom Estado (Verde) -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $totalBons }}</h3>
                    <p>Bom Estado</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <a href="{{ route('patrimonies.index', ['status' => 'bom']) }}" class="small-box-footer link-light link-underline-opacity-0">
                    Mais detalhes <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Estado Regular (Azul) -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalRegulares }}</h3>
                    <p>Estado Regular</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                <a href="{{ route('patrimonies.index', ['status' => 'regular']) }}" class="small-box-footer link-light link-underline-opacity-0">
                    Mais detalhes <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>

        <!-- Card 3: Estado Ruim (Amarelo) -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $totalRuins }}</h3>
                    <p>Estado Ruim</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>
                <a href="{{ route('patrimonies.index', ['status' => 'ruim']) }}" class="small-box-footer link-dark link-underline-opacity-0">
                    Mais detalhes <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>

        <!-- Card 4: Péssimo / Baixa (Vermelho) -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $totalCriticos }}</h3>
                    <p>Péssimo / Baixa</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                </svg>
                <a href="{{ route('patrimonies.index', ['status' => 'critico']) }}" class="small-box-footer link-light link-underline-opacity-0">
                    Mais detalhes <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </div>
    <!--end::Row Top Cards-->

    <!--begin::Row Resumo-->
    <div class="row">
        <div class="col-12">
            <!-- RESUMO PATRIMONIAL DO USUÁRIO / CENTRO -->
            <div class="card text-white bg-primary bg-gradient border-primary mb-4">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="bi bi-building me-1"></i> Resumo de Ocupação — Minha Gestão
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body text-center py-4">
                    <i class="bi bi-box-seam display-1 text-white-50"></i>
                    <p class="mt-2 mb-0">Visão Geral dos Ativos sob Responsabilidade de <strong>{{ auth()->user()->name }}</strong></p>
                </div>

                <div class="card-footer border-0">
                    <div class="row text-dark">
                        <!-- 1. Laboratórios/Ambientes vinculados ao usuário -->
                        <div class="col-6 text-center border-end border-white-50">
                            <h4 class="fw-bold mb-0 text-white">
                                {{ $totalAmbientes }}
                            </h4>
                            <div class="text-white-50 small">
                                Total de ambientes 
                            </div>
                        </div>

                        <!-- 2. Patrimônios vinculados aos ambientes do usuário -->
                        <div class="col-6 text-center">
                            <h4 class="fw-bold mb-0 text-white">
                                {{ $totalPatrimonios }}
                            </h4>
                            <div class="text-white-50 small">
                                Total de Patrimônios
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row Resumo-->
@endsection