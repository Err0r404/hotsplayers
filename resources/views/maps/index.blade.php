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

    <div id="maps">
        <div class="row mb-4">
            <div class="col-sm-8">
                <button class="sort btn btn-outline-primary" data-sort="name">
                    Sort by name
                </button>

                <button class="sort btn btn-outline-primary" data-sort="games">
                    Sort by games
                </button>
            </div>

            <div class="col-sm-4">
                <input class="search-maps form-control" placeholder="Search for a map" />
            </div>
        </div>

        <ul class="list list-inline row">
            @foreach($maps as $key => $map)
                <li class="list-inline-item col-sm-3 col-6 mr-0 mb-4">
                    <a href="{{ url('maps/'.$map->id) }}" class="text-dark">
                        <div class="card">
                            <img class="card-img-top" src="{{ URL::asset('/images/maps/'.str_replace(':', '', $map->name).'.jpg') }}" alt="{{ $map->name }} battleground from Heroes Of The Storm">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-0"><span class="name">{{ $map->name }}</span></h6>
                                <p class="card-text mb-0">{{ $map->games }} games</p>
                                <span class="d-none games">{{ $map->original_games }}</span>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var options = {
            valueNames: [ 'name', 'games' ]
        };

        var mapsList = new List('maps', options);

        $('.search-maps').on('keyup', function() {
            var searchString = $(this).val();
            mapsList.search(searchString, ['name']);
        });

        $(".sort").on("click", function(e){
            $(".sort").find("i").remove();

            var $i = $("<i/>", {"class": "ml-1"}).appendTo($(this));
            if($(this).hasClass('desc')){
                $i.addClass("ion-android-arrow-dropdown");
            }
            else if($(this).hasClass('asc')){
                $i.addClass("ion-android-arrow-dropup");
            }
        });
    </script>
@endsection
