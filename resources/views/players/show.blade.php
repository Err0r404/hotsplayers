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
            <nav class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                <a class="nav-item nav-link active" id="nav-heroes-tab" data-toggle="tab" href="#nav-heroes" role="tab" aria-controls="nav-home" aria-expanded="true">Heroes</a>
                <a class="nav-item nav-link" id="nav-maps-tab" data-toggle="tab" href="#nav-maps" role="tab" aria-controls="nav-maps">Maps</a>
                <a class="nav-item nav-link" id="nav-teammates-tab" data-toggle="tab" href="#nav-teammates" role="tab" aria-controls="nav-profile">Teammates</a>
            </nav>

            <div class="tab-content pt-5" id="nav-tabContent">
                {{-- [START] Heroes --}}
                <div class="tab-pane fade show active" id="nav-heroes" role="tabpanel" aria-labelledby="nav-heroes-tab">
                    <div class="dropdown games-type">
                        <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            All games
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item active" href="#">All games</a>
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
                                <th>Total games</th>
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
                                        <img class="img-responsive mr-3" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="Small portrait of {{ $hero->name }} from the game Heroes Of The Storm" width="49px">
                                        {{ $hero->name }}
                                    </a>
                                </td>
                                {{--<td>{{ $hero->type }}</td>--}}
                                <td>{{ $hero->total_games }}</td>
                                <td class="text-center p-2">
                                    <small>{{ $hero->percent_win }}%</small>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $hero->percent_win }}%" aria-valuenow="{{ $hero->percent_win }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- [END] Heroes --}}

                {{-- [START] Maps --}}
                <div class="tab-pane fade" id="nav-maps" role="tabpanel" aria-labelledby="nav-maps-tab">
                    <table class="table table-hover table-responsive">
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
                {{-- [END] Maps --}}

                {{-- [START] Teammates --}}
                <div class="tab-pane fade" id="nav-teammates" role="tabpanel" aria-labelledby="nav-teammates-tab">
                    Coming soon...
                </div>
                {{-- [END] Teammates --}}
            </div>
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

            	// Change the active class
            	$(this).siblings().removeClass('active');
            	$(this).addClass('active');

                // Hide all rows
                $("table.table tbody tr").addClass("d-none");

                // Show only wanted rows
                $("table.table tbody tr[data-type='"+selectedType+"']").removeClass("d-none");
            });
        });
    </script>
@endsection
