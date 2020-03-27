Sample 1 API
=====

[Lumen](https://lumen.laravel.com/) による HTTP API のサンプル。

* 商品のリストの追加・参照・変更・削除を REST で実装する。
* データは JOSN で受け渡しする。
* データベースには SQLite を利用する。
* データの削除は論理削除 ( Soft delete ) とする。
* このサンプルには認証・認可の機能を含まない。
* このサンプルには PHPUnit 等によるテストを含まない。


## Git と GitHub の利用に必要な設定

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
参照: https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup

GitHub のアカウントを作成する。

GitHub に登録する SSH キーを作成する。
Linux や Mac の場合は以下のコマンドを実行する。
既存のキーを書き潰さないように気をつけること。

```
$ ssh-keygen
```

デフォルトで ``~/.ssh/github_rsa.pub`` に公開キーが出力される。

Windows の場合は SSH クライアント製品の案内に従うこと。

作成した公開キーを https://github.com/settings/keys から登録する。

以上の手順は GitLab でもほとんど同じ。


## curl

HTTP API のテストの際に [curl](https://curl.haxx.se/) を使用する。
各 OS のインストールの手順は Git と同様。
Linux は最初から入っていることが多い。

```
curl --version
curl 7.64.1 (x86_64-apple-darwin19.0) libcurl/7.64.1 (SecureTransport) 
```

## PHP と Composer

Lumen 6.x の場合 PHP 7.2 以上が必要。

参照: https://lumen.laravel.com/docs/6.x

```
$ php --version
PHP 7.4.4 (cli) (built: Mar 19 2020 20:12:27) ( NTS )
```

Composer が必要なので https://getcomposer.org/download/ に記載されたコマンドを実行すること。
必ずこのページに記載された最新のコマンドを実行すること。

```
$ composer --version
Composer version 1.10.1 2020-03-13 20:34:27
```

## プロジェクトの利用

```
$ git clone git@github.com:MichinobuMaeda/sample1api.git
$ cd sample1api
$ composer install
```

``.env.example`` の内容をすべて ``.env`` にコピーする。

``.env`` の ``APP_KEY=`` の行を

```
$ php -r "echo 'APP_KEY=base64:' . base64_encode(hash('md5', time()));"
```

で出力した内容に置き換える。

``.env`` の ``DB_DATABASE=`` の行を絶対パスに置き換える。

データベースを初期化して、テスト用サーバを起動する。

```
$ touch storage/database.sqlite
$ php artisan migrate:refresh
$ php -S localhost:8000 -t public
```

## 参考：プロジェクトの作成

このプロジェクトは以下の手順で作成した。

Lumen のプロジェクトを作成し、 ``php artisan`` で使用できるコマンドを確認する。

```
$ composer create-project --prefer-dist laravel/lumen sample1api
$ cd sample1api
$ php artisan list
```

コマンドは Laravel より少ない。
``php artisan serve`` も無いのでテスト用のサーバは
``php -S localhost:8000 -t public`` のように起動する。

プロジェクトを GitHub に登録する。

https://github.com/new でリポジトリ ``sample1api`` を作成して、以下のコマンドを実行する。

```
$ git init
$ git add .
$ git commit -m "first commit"
$ git remote add origin git@github.com:MichinobuMaeda/sample1api.git
$ git push -u origin master
```

## プロジェクトの環境設定

### ``.gitignore`` 

一般的な設定を追加する。

参照: https://github.com/github/gitignore/tree/master/Global


### ``.env.example``

テスト用の設定にする。

```
APP_NAME="Sample 1 API"
 ... ...
APP_TIMEZONE=Asia/Tokyo

LOG_CHANNEL=single
 ... ...
DB_CONNECTION=sqlite
DB_DATABASE=storage/database.sqlite
 ... ...
```

テスト中はログのローテーションをしない方が作業しやすい。
``LOG_CHANNEL=single`` で出力先が ``ls storage/logs/lumen.log`` になる。


### ``.env``

``.env.example`` の内容をすべて ``.env`` にコピーする。

``APP_KEY=`` の行を

```
$ php -r "echo 'APP_KEY=base64:' . base64_encode(hash('md5', time()));"
```

で出力した内容に置き換える。

``DB_DATABASE=`` の行を絶対パスに置き換える。


### ``bootstrap/app.php``

以下の行のコメントを外す。

```
$app->withFacades();

$app->withEloquent();
```

### データベース

SQLite のデータベースのファイルを作成する。

```
$ touch storage/database.sqlite
```

``.gitignore`` にこのファイルのパスを追加する。

商品のテーブル ( items ) の定義を作成する。

```
php artisan make:migration create_items
```

生成されたファイル ``database/migrations/yyyy_mm_dd_hhmmss_create_items.php``
を編集してテーブルを作成する。

```
$ php migrate
```

商品の Model ( ``app/Item.php `` ) と
Repository Service ( ``app/Repositories/ItemRepository.php`` ) と
Json Resouce ( ``app/Http/Resources/ItemResource.php`` ) 
を作成する。

### HTTP API

``routes/web.php`` の定義方法は Laravel と若干異なる。

Controler ``app/Http/Controllers/ItemController.php `` は Laravel と同様でよい。

### テスト

データベースをクリアしてテスト用のサーバを起動する。

```
$ php artisan migrate:refresh
$ php -S localhost:8000 -t public
```

``curl`` で各メソッドのテストをする。以下の例のレスポンスの値は、読みやすいように整形している。

```
$ curl localhost:8000/items  
{
    "data": []
}                                                           
$ curl localhost:8000/items/1
{
    "status": false
}
$ curl -d '{"name":"name1","color":"black","length":10}' \
    -H "Content-Type: application/json" -X PUT localhost:8000/items/1
{
    "status": false
}
$ curl -X DELETE localhost:8000/items/1
{
    "status": false
}
$ curl -d '{"id":1,"name":"name1","color":"red","length":10}' \
    -H "Content-Type: application/json" -X POST localhost:8000/items  
{
    "data": {
        "id": 1,
        "name": "name1",
        "color": "red",
        "length": 10,
        "created_at": "2020-03-27T15:38:44.000000Z",
        "updated_at": "2020-03-27T15:38:44.000000Z",
        "deleted_at": null
    }
}
$ curl localhost:8000/items
{
    "data": [
        {
            "id": 1,
            "name": "name1",
            "color": "red",
            "length": 10,
            "created_at": "2020-03-27T15:38:44.000000Z",
            "updated_at": "2020-03-27T15:38:44.000000Z",
            "deleted_at": null
        }
    ]
}
$ curl localhost:8000/items/1
{
    "data": {
        "id": 1,
        "name": "name1",
        "color": "red",
        "length": 10,
        "created_at": "2020-03-27T15:38:44.000000Z",
        "updated_at": "2020-03-27T15:38:44.000000Z",
        "deleted_at":null
    }
}
$ curl -d '{"name":"nameA","color":"red","length":5}' \
    -H "Content-Type: application/json" -X PUT localhost:8000/items/1
{
    "status": true
}
$ curl localhost:8000/items/1
{
    "data": {
        "id": 1,
        "name": "nameA",
        "color": "red",
        "length": 5,
        "created_at": "2020-03-27T15:38:44.000000Z",
        "updated_at": "2020-03-27T15:39:30.000000Z",
        "deleted_at": null
    }
}
$ curl -X DELETE localhost:8000/items/1
{
    "status": true
}
$ curl localhost:8000/items             
{
    "data": []
}
```
