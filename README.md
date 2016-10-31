# Adjudicator_evaluation
パーラメンタリーディベートの大会のためのジャッジ評価システムを提供するWebアプリケーション
## Installation
1. .env.exampleを元に.envをrootに作成
2. frameworkをインストール
    ```
    $ composer install  
    ```
3. マイグレーション(事前に`adjudicator_evaluation`という名前のデータベースを作成して下さい．)
    ```
    $ php artisan migrate  
    ```
    もしくは，`database/migrations/migration.sql`をDBに対して実行．

4. サーバーを起動
    ```
    $ php -S localhost:8000 -t public  
    ```
5. [localhost:8000/feedbacks](localhost:8000/feedbacks)にアクセス
    ```
    PDOException in Connector.php line 55:
    SQLSTATE[HY000] [2002] No such file or directory
    ```
    - 以上のエラーが出る場合は`config/database.php`の66行目`            'unix_socket'=>'/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock',
`を削除，またはコメントアウトして下さい．
    ```
    ErrorException in Filesystem.php line 38:
    file_get_contents(****): failed to open stream: Permission denied
    ```
     - 上エラーのようにPermission deniedと表示される場合は`$ sudo chmod -R 777 bootstrap/cache`，`$ sudo chmod -R 777 storage`を実行して下さい．

## TODO
* Deleteを押したらポップアップで確認を出す
* XAMPP同封版にしてTabbieみたいにダウンロードのあとすぐ使えるようにしたい
* バックアップ，リストアをアプリケーション上で出来るようにしたい
* Tabbie, tabbycatのデータベースと連携出来るようにしたい

## Authors and Contact
- Daigo Kimura(木村大吾) ([e-mail](a91381@gmail.com))
