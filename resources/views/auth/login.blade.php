@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ユーザーログイン画面') }}</div>
                <style>
                     /* コンテナ要素のスタイリング */
                    .card-header {
                    display: flex; /* Flexboxを使用 */
                    justify-content: center; /* 水平方向の中央揃え */
                    align-items: center; /* 垂直方向の中央揃え */
                    text-align: center; /* テキストを中央揃え */
                    }
                    </style>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            
                            <div class="col-md-6">
                                <input id="email" type="email"  placeholder="アドレス" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                               
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                           
                            <div class="col-md-6">
                                <input id="password" type="password" placeholder="パスワード" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                        <div class="col-md-8 offset-md-2" style="display: flex; justify-content: center; align-items: center;">
                        <!-- 新規登録ボタンのコンテナ -->
                        <div style="margin-right: 10px;"> <!-- この余白はボタン間のスペースを調整するために使用します -->
                        <button type="submit" class="btn btn-new">
                        </button>
                        <a href="{{ route('register') }}" class="btn btn-primary">新規登録</a>
                        </div>
                         <!-- ログインボタンのコンテナ -->
                        <div style="margin-left: 10px;"> <!-- この余白はボタン間のスペースを調整するために使用します -->
                        <button type="submit" class="btn btn-primary">
                        {{ __('ログイン') }}
            </button>
        </div>
    </div>
</div>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
