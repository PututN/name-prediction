<div class="card">
    <h2>Hasil untuk “{{ $prediction->name }}”</h2>
    @forelse ($prediction->countries as $country)
        @php($percentage = $country->pivot->probability * 100)
        <div class="row">
            <span>{{ $country->flag }} {{ $country->name }} <span class="meta">({{ $country->code }})</span></span>
            <span class="num">{{ number_format($percentage, 1) }}%</span>
            <div class="bar"><span style="width: {{ round($percentage, 1) }}%"></span></div>
        </div>
    @empty
        <p class="empty">Tidak ada data negara untuk nama ini.</p>
    @endforelse
    <p class="meta num">Jumlah sampel: {{ number_format($prediction->sample_count) }}</p>
</div>
