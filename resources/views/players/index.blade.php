@extends('layouts.layout')

@section('title')
    Players
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-1">Players</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                <tr>
                    <th>BattleTag</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td>
                            <a href="{{ url('players/'.$player->id) }}">{{ $player->battletag }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="row">
        <div class="col">
            {{ $players->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection