@extends('layouts.layout')

@section('title')
    Heroes
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">Heroes</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Total games</th>
                    <th>Winrate</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($heroes as $hero)
                    <tr>
                        <td>
                            <a href="{{ url('heroes/'.$hero->id) }}">{{ $hero->name }}</a>
                        </td>
                        <td>{{ $hero->total_games }}</td>
                        <td>{{ $hero->percent_win }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection