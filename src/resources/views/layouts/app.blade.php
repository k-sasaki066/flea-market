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

        <nav class="header-nav__menu">
            <ul class="header-nav__list">
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
            </ul>
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>

</body>
</html>