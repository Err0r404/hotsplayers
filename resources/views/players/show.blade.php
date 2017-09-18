@extends('layouts.layout')

@section('title')
    {{ $player->battletag }}
@endsection

@section('container')
    <div class="row mb-5">
        <div class="col-4 col-sm-3 col-md-2">
            <img class="img-fluid" src="{{ URL::asset('/images/heroes/Player.jpg') }}" alt="Nexus portrait from Heroes Of The Storm">
        </div>
        <div class="col-8 col-sm-9 col-md-10">
            <h1 class="display-3">{{ $player->battletag }}</h1>
            <p class="mb-1 text-muted">{{ $player->total_games }} games played</p>
            <p class="mb-1 text-muted">{{ $player->percent_win }}% of victory</p>
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