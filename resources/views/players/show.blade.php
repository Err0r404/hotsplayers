@extends('layouts.layout')

@section('title')
    {{ $player->battletag }}
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">{{ $player->battletag }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>Hero</th>
                        <th>Total games</th>
                        <th>Winrate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($heroes as $hero)
                    <tr>
                        <td>{{ $hero->name }}</td>
                        <td>{{ $hero->total_games }}</td>
                        <td>{{ $hero->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection