@extends('layouts.app')

@section('content')
    <h1>Prediksi Nama</h1>
    <p class="sub">Masukkan nama untuk melihat prediksi asal negaranya (nationalize.io).</p>

    <div class="card">
        <form class="search" method="POST" action="{{ route('predictions.store') }}">
            @csrf
            <input type="text" name="name" value="{{ old('name', $result?->name) }}"
                   placeholder="Contoh: jaja" aria-label="Nama" required maxlength="100" autofocus>
            <button type="submit">Prediksi</button>
        </form>
        @error('name') <p class="error">{{ $message }}</p> @enderror
    </div>

    @if ($result)
        @include('predictions.partials.result', ['prediction' => $result])
    @endif

    @include('predictions.partials.history', ['predictions' => $history])
@endsection
