@extends('layouts.layout')

@section('title')
    Search '{{ $search }}'
@endsection

@section('container')
    <div class="row">
        <div class="col">
            <h1 class="display-2">Searching for «{{ $search }}»</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h2 class="display-3">Players</h2>

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

    {{--<div class="row">--}}
        {{--<div class="col">--}}
            {{--{{ $players->links('vendor.pagination.bootstrap-4') }}--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection