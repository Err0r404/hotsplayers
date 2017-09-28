@extends('layouts.layout')

@section('title')
    {{ $map->name}}
@endsection

@section('container')
    <div class="row mb-5">
        <div class="col-sm-2">
            <img class="img-fluid" src="{{ URL::asset('/images/maps/'.$map->name.'.jpg') }}" alt="{{ $map->name }} battleground from Heroes Of The Storm">
        </div>

        <div class="col-sm-10">
            <h1 class="display-3">{{ $map->name }}</h1>
            <p class="mb-1 text-muted">{{ $map->total_games }} games played</p>
            <p class="mb-1 text-muted">{{ $map->percent_win }}% of victory</p>
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