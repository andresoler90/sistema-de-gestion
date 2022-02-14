<!-- TOP Nav Bar -->
<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <div class="iq-sidebar-logo">
            <div class="top-logo">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
                    <span>{{env("APP_NAME")}}</span>
                </a>
            </div>
        </div>
        <div class="navbar-breadcrumb">
            <h5 class="mb-0">{{--env('APP_NAME')--}}@yield('title')</h5>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{__('Inicio')}}</a></li>
                    @php($name = Request::route()->getName())
                    @foreach(explode(".",$name) as $key=>$segment)
                        @if($key)
                            <li class="breadcrumb-item" aria-current="page"
                                style="text-transform: capitalize">{{__($segment)}}</li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ri-menu-3-line"></i>
            </button>
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu">
                    <div class="line-menu half start"></div>
                    <div class="line-menu"></div>
                    <div class="line-menu half end"></div>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto navbar-list">
                    <li class="nav-item text-red-dark">
                        @impersonating($guard = null)
                        <a href="{{ route('impersonate.leave') }}" class="bg-danger text-white">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                        </a>
                        @endImpersonating
                    </li>
                    @if(App::getLocale()=='es')
                        <li class="nav-item">
                            <a href="{{route('locale',"en")}}" class="iq-waves-effect">EN</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{route('locale',"es")}}" class="iq-waves-effect">ES</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="#" class="search-toggle iq-waves-effect"><i class="ri-notification-2-line"></i></a>
                        <div class="iq-sub-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-danger p-3">
                                        <h5 class="mb-0 text-white">
                                            {{ __('Todas las notificaciones') }}
                                            <small class="badge  badge-light float-right pt-1">0</small>
                                        </h5>
                                    </div>
                                    <a href="#" class="iq-sub-card">
                                        <div class="media align-items-center">
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0 ">Nueva notificación</h6>
                                                <small class="float-right font-size-12">Hace 23 horas</small>
                                                <p class="mb-0">Lorem is simply</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item iq-full-screen">
                        <a href="#" class="iq-waves-effect" id="btnFullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="search-toggle iq-waves-effect bg-primary text-white">
                        <img src="{{ asset('assets/images/user/1.jpg') }}" class="img-fluid rounded" alt="user">
                    </a>
                    <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                            <div class="iq-card-body p-0">
                                <div class="bg-primary p-3">
                                    <a href="#" class="iq-waves-effect iq-full-screen" style="color: white"
                                       id="btnFullscreen">
                                        <i class="ri-fullscreen-line"></i>
                                    </a>
                                    <h5 class="mb-0 text-white line-height"
                                        style="float: right">{{auth()->user()->name}}</h5>
                                    <span class="text-white font-size-12">{{ __('Disponible') }}</span>
                                </div>
                                <a href="{{ route('home') }}" class="iq-sub-card iq-bg-primary-hover">
                                    <div class="media align-items-center">
                                        <div class="rounded iq-card-icon iq-bg-primary">
                                            <i class="ri-file-user-line"></i>
                                        </div>
                                        <div class="media-body ml-3">
                                            <h6 class="mb-0 ">{{ __('Mi perfil') }}</h6>
                                            <p class="mb-0 font-size-12">{{ __('Ver detalles del perfil personal.') }}</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="d-inline-block w-100 text-center p-3">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="iq-bg-danger iq-sign-btn">
                                            {{ __('Salir') }}
                                             <i class="ri-login-box-line ml-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- TOP Nav Bar END -->
