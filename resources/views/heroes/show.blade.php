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
            {{-- [START] Tabs --}}
            <ul class="nav nav-tabs nav-justified" id="hero-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="maps-tab" data-toggle="tab" href="#maps" role="tab" aria-controls="maps">Maps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="enemies-tab" data-toggle="tab" href="#enemies" role="tab" aria-controls="enemies" aria-expanded="true">Enemies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="allies-tab" data-toggle="tab" href="#allies" role="tab" aria-controls="allies" aria-expanded="true">Allies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="players-tab" data-toggle="tab" href="#players" role="tab" aria-controls="players">Players</a>
                </li>
            </ul>
            {{-- [END] Tabs --}}

            {{-- [START] Tabs's content --}}
            <div class="tab-content pt-5" id="hero-tabs-content">
                {{-- [START] Maps --}}
                <div class="tab-pane fade show active" id="maps" role="tabpanel" aria-labelledby="maps-tab">
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

                {{-- [START] Enemies --}}
                <div class="tab-pane fade" id="enemies" role="tabpanel" aria-labelledby="enemies-tab">
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
                                <th>Games against {{ $hero->name }}</th>
                                <th>Winrate against {{ $hero->name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enemies as $enemy)
                                <?php $class = "" ?>

                                @if($enemy->type != "All games")
                                    <?php $class = "d-none" ?>
                                @endif

                                <tr data-type="{{ $enemy->type }}" class="{{ $class }}">
                                    <td class="p-0">
                                        <a href="{{ url('heroes/'.$enemy->id) }}">
                                            <img class="img-responsive mr-3" src="{{ URL::asset('/images/heroes/'.$enemy->name.'.jpg') }}" alt="Small portrait of {{ $enemy->name }} from the game Heroes Of The Storm" height="49px">
                                            {{ $enemy->name }}
                                        </a>
                                    </td>
                                    <td>{{ $enemy->total_games }}</td>
                                    <td class="text-center p-2 w-50">
                                        <small>{{ $enemy->percent_win }}%</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $enemy->percent_win }}%; height: 3px;" aria-valuenow="{{ $enemy->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Enemies --}}

                {{-- [START] Allies --}}
                <div class="tab-pane fade" id="allies" role="tabpanel" aria-labelledby="allies-tab">
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
                                <th>Games with {{ $hero->name }}</th>
                                <th>Winrate with {{ $hero->name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allies as $ally)
                                <?php $class = "" ?>

                                @if($ally->type != "All games")
                                    <?php $class = "d-none" ?>
                                @endif

                                <tr data-type="{{ $ally->type }}" class="{{ $class }}">
                                    <td class="p-0">
                                        <a href="{{ url('heroes/'.$ally->id) }}">
                                            <img class="img-responsive mr-3" src="{{ URL::asset('/images/heroes/'.$ally->name.'.jpg') }}" alt="Small portrait of {{ $ally->name }} from the game Heroes Of The Storm" height="49px">
                                            {{ $ally->name }}
                                        </a>
                                    </td>
                                    <td>{{ $ally->total_games }}</td>
                                    <td class="text-center p-2 w-50">
                                        <small>{{ $ally->percent_win }}%</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $ally->percent_win }}%; height: 3px;" aria-valuenow="{{ $ally->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Allies --}}

                {{-- [START] Players --}}
                <div class="tab-pane fade" id="players" role="tabpanel" aria-labelledby="players-tab">
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
                                <th>Battletag</th>
                                <th>Total games</th>
                                <th>Average length</th>
                                <th>Winrate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($players as $player)
                                <?php $class = "" ?>

                                @if($player->type != "All games")
                                    <?php $class = "d-none" ?>
                                @endif

                                <tr data-type="{{ $player->type }}" class="{{ $class }}">
                                    <td><a href="{{ url('/players/'.$player->id) }}">{{ $player->battletag }}</a></td>
                                    <td>{{ $player->total_games }}</td>
                                    <td>{{ $player->avg_length }}</td>
                                    <td class="text-center p-2 w-25">
                                        <small>{{ $player->percent_win }}%</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $player->percent_win }}%; height: 3px;" aria-valuenow="{{ $player->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Players --}}
            </div>
            {{-- [END] Tabs's content --}}
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
