Sample 1 API
=====

## Git と GitHub の設定

各OSの手順で Git をインストールする。
Linux は ``yum`` や ``apt`` で入れる。
Windows は [Chocolatey](https://chocolatey.org/) 、
Mac は [Homebrew](https://brew.sh/) を使うのが無難。

```
$ git --version 
git version 2.21.1 (Apple Git-122.3)
```

Git のユーザ名とメールアドレスを必ず設定する。

```
$ git config --global user.name "John Doe"
$ git config --global user.email johndoe@example.com
```
See https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup

GitHub に登録する SSH キーを作成する。

```
$ ssh-keygen
```

デフォルトで ``~/.ssh/github_rsa.pub`` に公開キーが出力されるので、
https://github.com/settings/keys で登録する。


## PHP と　Composer

Lumen 6.x の場合 PHP 7.2 以上が必要。

See https://lumen.laravel.com/docs/6.x

```
$ php --version
PHP 7.4.4 (cli) (built: Mar 19 2020 20:12:27) ( NTS )
```

Composer が必要なので https://getcomposer.org/download/ に記載されたコマンドを実行すること。

```
$ composer --version
Composer version 1.10.1 2020-03-13 20:34:27
```

Lumen のプロジェクトを作成し、 ``php artisan`` で使用できるコマンドを確認する。

```
$ composer create-project --prefer-dist laravel/lumen sample1api
$ cd sample1api
$ php artisan 
```

コマンドは Laravel より少ない。
``php artisan serve`` も無いのでテスト用のサーバは
``php -S localhost:8000 -t public`` のように起動する。
