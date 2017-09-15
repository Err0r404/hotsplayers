<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{url('/')}}">
            <img src="{{URL::asset('/images/nexus.png')}}" width="30" height="30" class="d-inline-block align-top" alt="">
            HOTS Players
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item{{ Request::path() == 'players' ? ' active' : '' }}">
                    <a class="nav-link" href="{{url('/players/')}}">Players</a>
                </li>
                <li class="nav-item{{ Request::path() == 'heroes' ? ' active' : '' }}">
                    <a class="nav-link" href="{{url('/heroes/')}}">Heroes</a>
                </li>
                <li class="nav-item{{ Request::path() == 'maps' ? ' active' : '' }}">
                    <a class="nav-link" href="{{url('/maps/')}}">Maps</a>
                </li>
            </ul>

            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>