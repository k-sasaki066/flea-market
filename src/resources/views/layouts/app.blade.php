<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="https://unpkg.com/ress@5.0.2/dist/ress.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    @php
        $isEmailVerifyPage = request()->is('email/verify');
    @endphp
    <header class="header grid">
        @if (!$isEmailVerifyPage)
        <a class="header-logo" href="/">
            <img class="header-logo__img" src="{{ asset('images/logo.svg') }}" alt="coachtech" width="260px">
        </a>
        @else
        <div class="header-logo">
            <img class="header-logo__img" src="{{ asset('images/logo.svg') }}" alt="coachtech" width="260px">
        </div>
        @endif

        <form class="search-form flex" action="/search" method="GET">
            <input class="search-form__keyword" type="text" placeholder="なにをお探しですか?" value="{{ session('search_keyword', '') }}" name="keyword">
            <button class="search-form__btn" type="submit">
                <img class="search-form__img" src="{{ asset('images/search.svg') }}" alt="search" width="20px">
            </button>
        </form>

        <nav class="header-nav__menu grid">
            <ul class="header-nav__list flex">
                @auth
                <li class="header-nav__item">
                    <form action="/logout" method="POST" onsubmit="return confirm('ログアウトしますか？');">
                        @csrf
                        <button class="header-nav__link" type="submit">
                            ログアウト
                        </button>
                    </form>
                </li>
                    @if (!$isEmailVerifyPage)
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="/mypage">
                            マイページ
                        </a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link-sell" href="/sell">
                            出品
                        </a>
                    </li>
                    @endif
                @else
                <li class="header-nav__item">
                    <a class="header-nav__link" href="/register">
                        会員登録
                    </a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__link" href="/login">
                        ログイン
                    </a>
                </li>
                @endauth
            </ul>
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>