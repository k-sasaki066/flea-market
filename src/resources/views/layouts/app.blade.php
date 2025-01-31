<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <a class="header-logo" href="/">
            <img class="header-logo__img" src="{{ asset('images/logo.svg') }}" alt="coachtech" width="240px">
        </a>

        <form class="search-form" action="/search" method="GET">
		@csrf
            <input class="search-form__keyword" type="text" placeholder="なにをお探しですか?" value="{{ request('keyword') }}" name="keyword">
            <button class="search-form__btn" type="submit">
                <img class="search-form__img" src="{{ asset('images/search.svg') }}" alt="search" width="20px">
            </button>
        </form>

        <nav class="header-nav__menu">
            <ul class="header-nav__list">
                @if(Auth::check())
                <li class="header-nav__item">
                    <form action="/logout" method="post">
                        @csrf
                        <button class="header-nav__link" type="submit">
                            ログアウト
                        </button>
                    </form>
                </li>
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
                @endif
            </ul>
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>

</body>
</html>