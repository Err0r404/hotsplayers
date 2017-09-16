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
                        <td><a href="{{ url('/maps/'.$map->id) }}">{{ $map->name }}</a></td>
                        <td>{{ $map->total_games }}</td>
                        <td>{{ $map->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="col">
            <h2 class="display-3">Best players</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Battletag</th>
                        <th>Total games</th>
                        <th>Winrate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td><a href="{{ url('/players/'.$player->id) }}">{{ $player->battletag }}</a></td>
                        <td>{{ $player->total_games }}</td>
                        <td>{{ $player->percent_win }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection