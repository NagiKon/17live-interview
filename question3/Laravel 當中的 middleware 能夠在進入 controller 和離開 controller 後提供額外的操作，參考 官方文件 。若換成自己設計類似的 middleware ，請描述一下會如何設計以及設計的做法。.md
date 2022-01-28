# Laravel 當中的 middleware 能夠在進入 controller 和離開 controller 後提供額外的操作，參考 官方文件 。若換成自己設計類似的 middleware ，請描述一下會如何設計以及設計的做法。

## 設計理念

middleware 最重要的功能是對 request 與 response 做額外的操作，但不應該影響到商業邏輯。
因此今天當我在設計 middleware 時，我會遵照以下這幾個原則來進行：
1. middleware 的有無，都不應該影響到商業邏輯。
2. 可以依使用者需求自由替換。
3. 每個 middleware 都只專門處理一件事情，並根據使用者需求客製化。

### 設計案例說明

因為 middleware 的本質仍然是處理 request 與 response，因此在初始的抽象類別上，不會做過多的設計。

```php
<?php

abstract class Middleware
{
    public abstract function handleRequest($request);
    public abstract function handleResponse($response);
}
```

為了讓使用者可以自由決定 middleware 要處理什麼種類的事情，這邊會添加幾個常見的 interface 供使用者實現。

```php
interface Auth
{
    public function auth();
}

interface Logging
{
    public function LogRequest();
    public function LogResponse();
}

interface validation
{
    public function validate();
}
```

### 實際運用

假設我們今天使用 JWT token 來做使用者的驗證，我們希望可以把驗證與商業邏輯切開，就可以像這樣自己實現一個 middleware，然後透過實作介面來表示這個 middleware 是做什麼事情的。

```php
<?php

class jwtAuthMiddleware extends Middleware implements Auth
{
    public function handleRequest($request)
    {
        if (!auth()) {
            // Block request
        }
    }

    public function handleResponse($response)
    {
        // do something...
    }

    public function auth()
    {
        // do jwt authentication...
    }
}
```

而如果你今天想改回傳統的 session_id 也可以在另外建立一個新的 middleware，並將原來的 jwtAuthMiddleware 移除。

```php
<?php

class sessionAuthMiddleware extends Middleware implements Auth
{
    public function handleRequest($request)
    {
        if (!auth()) {
            // Block request
        }
    }

    public function handleResponse($response)
    {
        // do something...
    }

    public function auth()
    {
        // do session authentication...
    }
}
```