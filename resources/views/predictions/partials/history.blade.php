<div class="card">
    <h2>Riwayat pencarian</h2>
    @if ($predictions->isEmpty())
        <p class="empty">Belum ada nama yang diprediksi.</p>
    @else
        <table>
            <thead>
            <tr><th>Nama</th><th>Negara teratas</th><th></th></tr>
            </thead>
            <tbody>
            @foreach ($predictions as $prediction)
                @php($top = $prediction->countries->first())
                <tr>
                    <td><a href="{{ route('predictions.show', $prediction) }}">{{ $prediction->name }}</a></td>
                    <td>
                        @if ($top)
                            {{ $top->flag }} {{ $top->name }} ({{ number_format($top->pivot->probability * 100, 1) }}%)
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('predictions.destroy', $prediction) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
