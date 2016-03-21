# Adjudicator_evaluation
パーラメンタリーディベートの大会のためのジャッジ評価システムを提供するWebアプリケーション
# Install
1. .env.exampleを元に.envをrootに作成
2. frameworkをインストール
    ```
    $ composer install  
    ```
3. マイグレーション
    ```
    $ php artisan migrate  
    ```
    もしくは，database/migrations/migration.sqlをDBに対して実行．
    
4. サーバーを起動
    ```
    $ php -S localhost:8000 -t public  
    ```
4. [localhost:8000/feedbacks](localhost:8000/feedbacks)にアクセス
