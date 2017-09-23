@extends('layouts.layout')

@section('title')
    {{ $player->battletag }}
@endsection

@section('container')
    <div class="row mb-5">
        <div class="col-4 col-sm-3 col-md-2">
            <img class="img-fluid rounded-circle" src="{{ URL::asset('/images/heroes/Player.jpg') }}" alt="Nexus portrait from Heroes Of The Storm">
        </div>
        <div class="col-8 col-sm-9 col-md-10">
            <h1 class="display-3">{{ $player->battletag }}</h1>
            <p class="mb-1 text-muted">{{ $player->total_games }} games played</p>
            <p class="mb-1 text-muted">{{ $player->percent_win }}% of victory</p>
            <p class="mb-1 text-muted">{{ $player->total_length }} played</p>
        </div>
    </div>

    <div class="row">
        <div class="col">
            {{-- [START] Tabs --}}
            <ul class="nav nav-tabs nav-justified" id="player-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="heroes-tab" data-toggle="tab" href="#heroes" role="tab" aria-controls="heroes" aria-expanded="true">Heroes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="maps-tab" data-toggle="tab" href="#maps" role="tab" aria-controls="maps">Maps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="teammates-tab" data-toggle="tab" href="#teammates" role="tab" aria-controls="teammates">Teammates</a>
                </li>
            </ul>
            {{-- [END] Tabs --}}

            {{-- [START] Tabs's contents --}}
            <div class="tab-content pt-5" id="player-tabs-content">
                {{-- [START] Heroes --}}
                <div class="tab-pane fade show active" id="heroes" role="tabpanel" aria-labelledby="heroes-tab">
                    <div class="dropdown games-type pb-5">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            All games
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">All games</a>
                            @foreach($types as $type)
                                <a class="dropdown-item" href="#">{{$type->name}}</a>
                            @endforeach
                        </div>
                    </div>

                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>Hero</th>
                                {{--<th>Type</th>--}}
                                <th>Games</th>
                                <th>Average length</th>
                                <th>Winrate</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($heroes as $hero)
                                <?php $class = "" ?>

                                @if($hero->type != "All games")
                                    <?php $class = "d-none" ?>
                                @endif

                                <tr data-type="{{ $hero->type }}" class="{{ $class }}">
                                <td class="p-0">
                                    <a href="{{ url('heroes/'.$hero->id) }}">
                                        <img class="img-responsive mr-3" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="Small portrait of {{ $hero->name }} from the game Heroes Of The Storm" height="49px">
                                        {{ $hero->name }}
                                    </a>
                                </td>
                                {{--<td>{{ $hero->type }}</td>--}}
                                <td class="w-25">{{ $hero->total_games }}</td>
                                <td class="w-25">{{ $hero->avg_length }}</td>
                                <td class="text-center p-2 w-25">
                                    <small>{{ $hero->percent_win }}%</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $hero->percent_win }}%; height: 3px;" aria-valuenow="{{ $hero->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Heroes --}}

                {{-- [START] Maps --}}
                <div class="tab-pane fade" id="maps" role="tabpanel" aria-labelledby="maps-tab">
                    <div class="dropdown games-type pb-5">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            All games
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">All games</a>
                            @foreach($types as $type)
                                <a class="dropdown-item" href="#">{{$type->name}}</a>
                            @endforeach
                        </div>
                    </div>

                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>Map</th>
                                <th>Games</th>
                                <th>Average length</th>
                                <th>Winrate</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($maps as $map)
                                <?php $class = "" ?>

                                @if($map->type != "All games")
                                    <?php $class = "d-none" ?>
                                @endif

                                <tr data-type="{{ $map->type }}" class="{{ $class }}">
                                <td class="p-0">
                                    <a href="{{ url('maps/'.$map->id) }}">
                                        <img class="img-responsive mr-3" src="{{ URL::asset('/images/maps/'.str_replace(':', '', $map->name).'.jpg') }}" alt="Small representation of the battleground {{ $map->name }} from the game Heroes Of The Storm" height="49px">
                                        {{ $map->name }}
                                    </a>
                                </td>
                                <td>{{ $map->total_games }}</td>
                                <td>{{ $map->avg_length }}</td>
                                <td class="text-center p-2 w-25">
                                    <small>{{ $map->percent_win }}%</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $map->percent_win }}%; height: 3px;" aria-valuenow="{{ $map->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Maps --}}

                {{-- [START] Teammates --}}
                <div class="tab-pane fade" id="teammates" role="tabpanel" aria-labelledby="teammates-tab">
                    <table class="table table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>Battletag</th>
                                <th>Heroes</th>
                                <th>Games</th>
                                <th>Average length</th>
                                <th>Winrate</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($teammates as $teammate)
                                <tr>
                                <td>
                                    <a href="{{ url('players/'.$teammate->id) }}">
                                        {{ $teammate->battletag }}
                                    </a>
                                </td>
                                <td class="p-0">
                                    @foreach($teammate->heroes as $hero)
                                        <img class="img-responsive float-left" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="Small portrait of {{ $hero->name }} from the game Heroes Of The Storm" height="49px" data-toggle="tooltip" title="{{ $hero->name }} : {{ $hero->total_games }} games">
                                    @endforeach
                                </td>
                                <td>{{ $teammate->total_games }}</td>
                                <td>{{ $teammate->avg_length }}</td>
                                <td class="text-center p-2 w-25">
                                    <small>{{ $teammate->percent_win }}%</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $teammate->percent_win }}%; height: 3px;" aria-valuenow="{{ $teammate->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Teammates --}}
            </div>
            {{-- [END] Tabs's contents --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $(".games-type .dropdown-item").on("click", function(e){
                e.preventDefault();

                // Get the wanted type
                var selectedType = $(this).text();

                // Change the text's button
                $(".games-type button").text(selectedType);

                // Hide all rows
                $("table.table tbody tr[data-type]").addClass("d-none");

                // Show only wanted rows
                $("table.table tbody tr[data-type='"+selectedType+"']").removeClass("d-none");
            });
        });
    </script>
@endsection
