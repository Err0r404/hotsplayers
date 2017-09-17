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


    <?php $col = 0  ?>
    @foreach($maps as $key => $map)
        @if($col == 0)
            <div class="row mb-5">
        @endif

            <div class="col-sm-3">
                <a href="{{ url('maps/'.$map->id) }}" class="text-dark">
                    <div class="card">
                        <img class="card-img-top" src="{{ URL::asset('/images/maps/'.str_replace(':', '', $map->name).'.jpg') }}" alt="{{ $map->name }} battleground from Heroes Of The Storm">
                        {{--<img class="card-img-top" src="//via.placeholder.com/255x145" alt="{{ $map->name }} battleground from Heroes Of The Storm">--}}
                        <div class="card-body p-2">
                            <h6 class="card-title mb-0">{{ $map->name }}</h6>
                            <p class="card-text mb-0">{{ $map->total_games }} games</p>
                        </div>
                    </div>
                </a>
            </div>

            <?php $col = $col+1 ?>

        @if($col == 4 || $key+1 == sizeof($maps))
            <?php $col = 0 ?>
            </div>
        @endif
    @endforeach
@endsection