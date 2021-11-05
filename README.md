## 設定環境

* 安裝composer
* 安裝node.js
* 安裝php

## 安裝package

pull下來後 下下列指立

server `composer require`

client `npm install`

需複製 `.env.example`為`.env`

最後產出網站密鑰 `php artisan key:generate`

## 啟動指令

server 

`php artisan serve`

client

`develop:npm run watch `

`production:npm run production`

## 設定更新 elasticsearch 索引
`php artisan elastic:update-mapping "App\Models\KnowledgeArticle"`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
