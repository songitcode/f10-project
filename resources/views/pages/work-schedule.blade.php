@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="bg-warning text-center">
            <h5 class="fw-bold p-2">LỊCH LÀM VIỆC TUẦN {{ $startOfWeek->format('d/m') }} - {{ $endOfWeek->format('d/m') }}</h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead>
                    <tr class="table-info">
                        <th class="bg-danger">Giờ</th>
                        @for ($i = 0; $i < 7; $i++)
                            <th>
                                @if ($i + 2 < 8)
                                    Thứ {{ $i + 2 }}
                                @else
                                    Chủ Nhật
                                @endif
                                <br>
                                {{ $startOfWeek->copy()->addDays($i)->format('d/m') }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hours as $hour)
                        <tr>
                            <td class="text-center pe-2 fw-bold bg-danger text-white">
                                @if ($hour <= 12)
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00 AM
                                @else
                                    <!-- {{ str_pad($hour - 12, 2, '0', STR_PAD_LEFT) }}:00 PM -->
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00 PM
                                @endif
                            </td>

                            @for ($i = 0; $i < 7; $i++)
                                @php
                                    $day = $startOfWeek->copy()->addDays($i)->toDateString();
                                    $shiftUsers = collect($grouped[$day] ?? [])->filter(function ($s) use ($hour) {
                                        $start = intval(\Carbon\Carbon::parse($s->start_time)->format('H'));
                                        $end = intval(\Carbon\Carbon::parse($s->end_time)->format('H'));
                                        return $hour >= $start && $hour <= $end;
                                    });
                                @endphp

                                <td style="min-width: 160px; vertical-align: middle;">
                                    @foreach ($shiftUsers as $s)
                                        <div class="badge d-block text-wrap mb-1"
                                            style="background-color: {{ sprintf('#%06X', crc32($s->user->real_name) & 0xFFFFFF) }}">
                                            {{ $s->user->real_name }}
                                        </div>
                                    @endforeach
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection