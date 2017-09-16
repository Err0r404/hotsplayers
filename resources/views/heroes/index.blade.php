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

    <?php $col = 0  ?>
    @foreach($heroes as $key => $hero)
        @if($col == 0)
            <div class="row mb-5">
        @endif

                <div class="col-sm-2">
                    <a href="{{ url('heroes/'.$hero->id) }}" class="text-dark">
                        <div class="card">
                            <img class="card-img-top" src="{{ URL::asset('/images/heroes/'.$hero->name.'.jpg') }}" alt="Card image cap">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-0">{{ $hero->name }}</h6>
                                <p class="card-text mb-0">{{ $hero->total_games }} games</p>
                                <p class="card-text mb-0">{{ $hero->percent_win }}% winrate</p>
                            </div>
                        </div>
                    </a>
                </div>

        <?php $col = $col+1 ?>

        @if($col == 6 || $key+1 == sizeof($heroes))
            <?php $col = 0 ?>
            </div>
        @endif
    @endforeach
@endsection