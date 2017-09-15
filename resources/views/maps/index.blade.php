@extends('layouts.layout')

@section('title')
    Maps
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">Maps</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Total games</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($maps as $map)
                    <tr>
                        <td>
                            <a href="{{ url('maps/'.$map->id) }}">{{ $map->name }}</a>
                        </td>
                        <td>{{ $map->total_games }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection