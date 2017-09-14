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
            <h2 class="display-3">Heroes</h2>

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
                        <td><a href="{{ url('heroes/'.$hero->id) }}">{{ $hero->name }}</a></td>
                        <td>{{ $hero->total_games }}</td>
                        <td>{{ $hero->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="col">
            <h2 class="display-3">Maps</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Map's name</th>
                        <th>Total games</th>
                        <th>Winrate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($maps as $map)
                    <tr>
                        <td><a href="{{ url('maps/'.$map->id) }}">{{ $map->name }}</a></td>
                        <td>{{ $map->total_games }}</td>
                        <td>{{ $map->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection