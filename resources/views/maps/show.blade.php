@extends('layouts.layout')

@section('title')
    {{ $map->name}}
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">{{ $map->name }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h2 class="display-3">Heroes</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th>Map's name</th>
                        <th>Total games</th>
                        <th>Winrate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($heroes as $hero)
                    <tr>
                        <td><a href="{{ url('/heroes/'.$hero->id) }}">{{ $hero->name }}</a></td>
                        <td>{{ $hero->total_games }}</td>
                        <td>{{ $hero->percent_win }}%</td>
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