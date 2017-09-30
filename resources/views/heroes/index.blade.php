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

    <div id="heroes">
        <div class="row mb-4">
            <div class="col-sm-8">
                <button class="sort btn btn-outline-primary" data-sort="name">
                    Sort by name
                </button>

                <button class="sort btn btn-outline-primary" data-sort="games">
                    Sort by games
                </button>

                <button class="sort btn btn-outline-primary" data-sort="winrate">
                    Sort by winrate
                </button>
            </div>

            <div class="col-sm-4">
                <input class="search-heroes form-control" placeholder="Search for a hero" />
            </div>
        </div>

        <ul class="list list-inline row">
            @foreach($heroes as $key => $hero)
                <li class="list-inline-item col-lg-2 col-sm-3 col-6 mr-0 mb-4">
                    <a href="{{ url('heroes/'.$hero->id) }}" class="text-dark">
                        <div class="card">
                            <img class="card-img-top" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="Card image cap">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-0"><span class="name">{{ $hero->name }}</span></h6>
                                <p class="card-text mb-0">{{ $hero->games }} games</p>
                                <p class="card-text mb-0"><span class="winrate">{{ $hero->winrate }}</span>% winrate</p>
                                <span class="d-none games">{{ $hero->original_games }}</span>
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
            valueNames: [ 'name', 'games', 'winrate' ]
        };

        var heroesList = new List('heroes', options);

        $('.search-heroes').on('keyup', function() {
            var searchString = $(this).val();
            heroesList.search(searchString, ['name']);
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
