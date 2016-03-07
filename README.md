# Adjudicator_evaluation
パーラメンタリーディベートの大会のためのジャッジ評価システムを提供するWebアプリケーション
# Install
1. .envをrootに作成
2. 以下を実行(ポート番号は任意)  
```
$ composer install  
```
```
$ php artisan migrate  
```
```
$ php -S localhost:8000 -t public  
```
3. [localhost:8000/feedbacks](localhost:8000/feedbacks)にアクセス
