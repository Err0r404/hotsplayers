@extends('layouts.layout')

@section('title')
    {{ $hero->name}}
@endsection

@section('container')
    <div class="row mb-5">
        <div class="col-sm-2">
            <img class="img-fluid" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="{{ $hero->name }} portrait from Heroes Of The Storm">
        </div>

        <div class="col-sm-10">
            <h1 class="display-3">{{ $hero->name }}</h1>
            <p class="mb-1 text-muted">{{ $hero->total_games }} games played</p>
            <p class="mb-1 text-muted">{{ $hero->percent_win }}% of victory</p>
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