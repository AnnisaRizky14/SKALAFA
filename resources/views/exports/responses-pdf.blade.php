<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #2563eb;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .filters {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .filters h3 {
            font-size: 12px;
            margin: 0 0 8px 0;
            color: #495057;
        }
        .filters ul {
            margin: 0;
            padding-left: 15px;
        }
        .filters li {
            font-size: 10px;
            margin-bottom: 2px;
        }
        .summary {
            margin-bottom: 20px;
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
        }
        .summary h3 {
            font-size: 12px;
            margin: 0 0 8px 0;
            color: #1976d2;
        }
        .summary p {
            margin: 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .rating-stars {
            display: flex;
            align-items: center;
        }
        .star {
            width: 8px;
            height: 8px;
            margin-right: 1px;
        }
        .star.filled {
            color: #ffc107;
        }
        .star.empty {
            color: #e9ecef;
        }
        .satisfaction-badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
        }
        .satisfaction-sangat-puas { background-color: #d4edda; color: #155724; }
        .satisfaction-puas { background-color: #cce5ff; color: #004085; }
        .satisfaction-cukup-puas { background-color: #fff3cd; color: #856404; }
        .satisfaction-tidak-puas { background-color: #f8d7da; color: #721c24; }
        .satisfaction-sangat-tidak-puas { background-color: #f5c6cb; color: #721c24; }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dibuat pada: {{ $generated_at }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        <ul>
            @foreach($filters as $filter)
            <li>{{ $filter }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="summary">
        <h3>Ringkasan</h3>
        <p>Total Respon: {{ $responses->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">Fakultas</th>
                <th style="width: 20%;">Kuisioner</th>
                <th style="width: 10%;">Tanggal Mulai</th>
                <th style="width: 10%;">Tanggal Selesai</th>
                <th style="width: 10%;">Rating Rata-rata</th>
                <th style="width: 12%;">Tingkat Kepuasan</th>
                <th style="width: 8%;">Durasi (Menit)</th>
                <th style="width: 10%;">Jumlah Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @foreach($responses as $response)
            <tr>
                <td style="text-align: center;">{{ $response->id }}</td>
                <td>{{ $response->faculty->name }}</td>
                <td>{{ $response->questionnaire->title }}</td>
                <td>{{ $response->started_at ? $response->started_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $response->completed_at ? $response->completed_at->format('d/m/Y H:i') : '-' }}</td>
                <td style="text-align: center;">
                    <div class="rating-stars">
                        @php $avgRating = $response->getAverageRating() @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <span class="star filled">★</span>
                            @else
                                <span class="star empty">☆</span>
                            @endif
                        @endfor
                        <span style="margin-left: 4px; font-size: 8px;">{{ number_format($avgRating, 1) }}/5</span>
                    </div>
                </td>
                <td style="text-align: center;">
                    @php $satisfaction = $response->getSatisfactionLevel() @endphp
                    @if($satisfaction == 'Sangat Puas')
                        <span class="satisfaction-badge satisfaction-sangat-puas">{{ $satisfaction }}</span>
                    @elseif($satisfaction == 'Puas')
                        <span class="satisfaction-badge satisfaction-puas">{{ $satisfaction }}</span>
                    @elseif($satisfaction == 'Cukup Puas')
                        <span class="satisfaction-badge satisfaction-cukup-puas">{{ $satisfaction }}</span>
                    @elseif($satisfaction == 'Tidak Puas')
                        <span class="satisfaction-badge satisfaction-tidak-puas">{{ $satisfaction }}</span>
                    @else
                        <span class="satisfaction-badge satisfaction-sangat-tidak-puas">{{ $satisfaction }}</span>
                    @endif
                </td>
                <td style="text-align: center;">{{ $response->getDurationInMinutes() }}</td>
                <td style="text-align: center;">{{ $response->answers->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Survei Universitas</p>
        <p>© {{ date('Y') }} Universitas - Semua hak dilindungi</p>
    </div>
</body>
</html>
