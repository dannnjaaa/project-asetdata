@extends('layouts.app') {{-- Ganti dengan layout utama kamu --}}

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        {{-- Monitoring Asset --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div class="ms-3">
                        <small>Monitoring Asset</small>
                        <div class="fw-bold">{{ $monitoringCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengajuan Diterima --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="fa fa-thumbs-up"></i>
                    </div>
                    <div class="ms-3">
                        <small>Pengajuan Diterima</small>
                        <div class="fw-bold">{{ $pengajuanCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Asset --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="fa fa-box"></i>
                    </div>
                    <div class="ms-3">
                        <small>Asset</small>
                        <div class="fw-bold">{{ $assetCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- User --}}
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="ms-3">
                        <small>User</small>
                        <div class="fw-bold">{{ $userCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
