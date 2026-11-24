<details>
    <summary class="keuangan-button">Ajukan Bukti</summary>
    <form method="POST" action="{{ route('siswa.tagihan.ajukan', $item->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="nominal" value="{{ $item->nominal }}">
        <label>Metode Pembayaran
            <select name="metode_pembayaran_id" required>
                <option value="">Pilih metode</option>
                @foreach ($metodePembayaran as $metode)
                    <option value="{{ $metode->id }}">{{ $metode->label }}</option>
                @endforeach
            </select>
        </label>
        <label>Tanggal Pembayaran
            <input type="date" name="tanggal_pembayaran" max="{{ today()->toDateString() }}">
        </label>
        <label>Bukti Pembayaran
            <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required>
        </label>
        <button type="submit" class="keuangan-button keuangan-button-primary">Kirim Bukti</button>
    </form>
</details>