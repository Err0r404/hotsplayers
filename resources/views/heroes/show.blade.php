@extends('layouts.layout')

@section('title')
    {{ $hero->name}}
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">{{ $hero->name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Map</th>
                        <th>Total games</th>
                        <th>Winrate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($maps as $map)
                    <tr>
                        <td>{{ $map->name }}</td>
                        <td>{{ $map->total_games }}</td>
                        <td>{{ $map->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection