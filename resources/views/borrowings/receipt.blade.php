<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Peminjaman</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: #fff;
        }
        .receipt {
            width: 58mm; /* ukuran kertas thermal */
            padding: 5px;
            text-align: center;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }
        .header {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .code {
            font-size: 10px;
            margin-bottom: 8px;
        }
        .section {
            text-align: left;
            margin-bottom: 6px;
        }
        .label {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .footer {
            margin-top: 8px;
            font-size: 10px;
            text-align: center;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }
            .receipt {
                margin: 0 auto;
                border: none; /* hilangkan border */
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="title">STRUK PEMINJAMAN</div>
        <div class="header">Perpustakaan SMKN 1 Bangil</div>
        <div class="code">351415H01000001</div>

        <div class="section">
            <div><span class="label">Peminjam:</span> {{ $borrowing->user->name }} ({{ $borrowing->user->role }})</div>
            @if($borrowing->user->role == 'guru' && $borrowing->user->nip)
                <div><span class="label">NIP:</span> {{ $borrowing->user->nip }}</div>
            @elseif($borrowing->user->role == 'siswa' && $borrowing->user->nis)
                <div><span class="label">NIS:</span> {{ $borrowing->user->nis }}</div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="section">
            <div><span class="label">Judul Buku:</span> {{ $borrowing->book->title }}</div>
            <div><span class="label">Tgl Pinjam:</span> {{ $borrowing->borrow_date->format('d/m/Y') }}</div>
            <div><span class="label">Tgl Kembali:</span> {{ $borrowing->due_date->format('d/m/Y') }}</div>
        </div>

        <div class="divider"></div>

        <div class="section">
            <div><span class="label">Perpustakawan:</span> {{ $librarian->name }}</div>
        </div>

        <div class="footer">
            Terima kasih telah menggunakan<br>layanan perpustakaan kami 😄
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>