# API 說明文件

## 錯誤清單列表

### Request Action 問題

| Code   | Message                            | Http Status Code | 說明 |
| ------ | ---------------------------------- | ---------------- | ---- |
| C00001 | Content Type of header is not JSON | 400              |      |
| C00002 | Content Type of body is not JSON   | 400              |      |
| C01001 | This Post is not found.            | 404              |      |
| C01002 | This Comment is not found.         | 404              |      |

### 資料驗證問題

| Code   | Message           | Http Status Code | 說明 |
| ------ | ----------------- | ---------------- | ---- |
| B00001 | Field Error       | 422              |      |
| B00002 | Data Not Exists   | 422              |      |
| B00003 | Data Format Error | 422              |      |

### 程式執行問題

| Code   | Message | Http Status Code | 說明 |
| ------ | ------- | ---------------- | ---- |
| U00001 | 任意    | 500              |      |

## Post 相關 API

### 取得一筆文章

```text
GET /api/posts/{postId}
```

**Request**

| 欄位   | 欄位說明                  | 型別  | 預設值 | 必填 | 參數位置 | 備註 |
| ------ | ------------------------- | ----- | ------ | ---- | -------- | ---- |
| postId | Unique identifier of post | `int` | 無     | 是   | `path`   | 無   |

參數範例

```text
/api/posts/1
```

**Response**

回傳欄位說明

| 欄位     | 欄位說明                  | 型別        | 預設值 | 備註 |
| -------- | ------------------------- | ----------- | ------ | ---- |
| id       | Unique identifier of post | `int`       | 無     | 無   |
| title    | 文章標題                  | `string`    | 無     | 無   |
| content  | 文章內容                  | `string`    | 無     | 無   |
| createAt | 建立時間                  | `timestamp` | 無     | 無   |
| updateAt | 更新時間                  | `timestamp` | 無     | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Ut ut corporis quaerat saepe.",
        "content": "Alice. 'Nothing,' said Alice. Nothing WHATEVER?...",
        "createAt": "2022-01-26T10:17:51.000000Z",
        "updateAt": "2022-01-26T10:17:51.000000Z"
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "postId",
        "reason": "The selected post id is invalid."
    }
}
```

---

### 新增一筆文章

```text
POST /api/posts
```

**Request**

| 欄位    | 說明     | 型別     | 參數位置 | 必填 | 備註 |
| ------- | -------- | -------- | -------- | ---- | ---- |
| title   | 文章標題 | `string` | `body`   | 是   | 無   |
| content | 文章內容 | `string` | `body`   | 是   | 無   |

參數範例

```json
{
    "title": "Most shark attacks occur about 10 feet from the beach since that's where the people are.",
    "content": "I'd rather be a bird than a fish. The gloves protect my feet from excess work."
}
```

**Response**

成功

```json
{
    "status": "success",
    "data": []
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "title",
        "reason": "The title field is required."
    }
}
```

---

### 更新一筆文章

```text
PUT /api/posts/{postId}
```

**Request**

| 欄位    | 說明                      | 型別     | 參數位置 | 必填 | 備註 |
| ------- | ------------------------- | -------- | -------- | ---- | ---- |
| postId  | Unique identifier of post | `int`    | `path`   | 是   | 無   |
| title   | 文章標題                  | `string` | `body`   | 是   | 無   |
| content | 文章內容                  | `string` | `body`   | 是   | 無   |

參數範例

```text
/api/posts/11
```

```json
{
    "title": "The teenage boy was accused of breaking his arm simply to get out of the test.",
    "content": "He learned the hardest lesson of his life and had the scars, both physical and mental, to prove it..."
}
```

**Response**

回傳欄位說明

| 欄位     | 欄位說明                  | 型別        | 預設值 | 備註 |
| -------- | ------------------------- | ----------- | ------ | ---- |
| id       | Unique identifier of post | `int`       | 無     | 無   |
| title    | 更新過後的文章標題        | `string`    | 無     | 無   |
| content  | 更新過後的文章內容        | `string`    | 無     | 無   |
| createAt | 建立時間                  | `timestamp` | 無     | 無   |
| updateAt | 更新時間                  | `timestamp` | 無     | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "id": 11,
        "title": "The teenage boy was accused of breaking his arm simply to get out of the test.",
        "content": "He learned the hardest lesson of his life and had the scars, both physical and mental, to prove it...",
        "createAt": "2022-01-27T10:19:27.000000Z",
        "updateAt": "2022-01-27T11:02:59.000000Z"
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "title",
        "reason": "The title field is required."
    }
}
```

---

### 部分更新一筆文章

```text
PATCH /api/posts/{postId}
```

**Request**

| 欄位    | 說明                      | 型別     | 參數位置 | 必填 | 備註              |
| ------- | ------------------------- | -------- | -------- | ---- | ----------------- |
| postId  | Unique identifier of post | `int`    | `path`   | 是   | 無                |
| title   | 文章標題                  | `string` | `body`   | 否   | 有給 Key 才會更新 |
| content | 文章內容                  | `string` | `body`   | 否   | 有給 Key 才會更新 |

參數範例

只更新 `title`，不更新 `content`。

```text
/api/posts/11
```

```json
{
    "title": "Her hair was windswept as she rode in the black convertible.",
}
```

**Response**

回傳欄位說明

| 欄位     | 欄位說明                  | 型別        | 預設值 | 備註 |
| -------- | ------------------------- | ----------- | ------ | ---- |
| id       | Unique identifier of post | `int`       | 無     | 無   |
| title    | 更新過後的文章標題        | `string`    | 無     | 無   |
| content  | 更新過後的文章內容        | `string`    | 無     | 無   |
| createAt | 建立時間                  | `timestamp` | 無     | 無   |
| updateAt | 更新時間                  | `timestamp` | 無     | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "id": 11,
        "title": "Her hair was windswept as she rode in the black convertible.",
        "content": "He learned the hardest lesson of his life and had the scars, both physical and mental, to prove it...",
        "createAt": "2022-01-27T10:19:27.000000Z",
        "updateAt": "2022-01-27T12:06:28.000000Z"
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "title",
        "reason": "The title field is required."
    }
}
```

---

### 刪除一筆文章

```text
DELETE /api/posts/{postId}
```

**Request**

| 欄位   | 欄位說明                  | 型別  | 預設值 | 必填 | 參數位置 | 備註 |
| ------ | ------------------------- | ----- | ------ | ---- | -------- | ---- |
| postId | Unique identifier of post | `int` | 無     | 是   | `path`   | 無   |

參數範例

```text
/api/posts/1
```

**Response**

成功

```json
{
    "status": "success",
    "data": []
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "postId",
        "reason": "The selected post id is invalid."
    }
}
```

## Comment 相關 API

### 取得某文章的所有留言

```text
GET /posts/{postId}/comments
```

**Request**

| 欄位    | 欄位說明                  | 型別  | 預設值 | 必填 | 參數位置 | 備註 |
| ------- | ------------------------- | ----- | ------ | ---- | -------- | ---- |
| postId  | Unique identifier of post | `int` | 無     | 是   | `path`   | 無   |
| page    | 指定頁數                  | `int` | 1      | 否   | `query`  | 無   |
| perPage | 每頁要呈現的留言數量      | `int` | 6      | 否   | `query`  | 無   |

參數範例

取得文章 ID 為 1 的所有留言，從第 1 頁開始顯示，每次只顯示 3 筆。

```text
/api/posts/1/comments?page=1&perPage=3
```

**Response**

回傳欄位說明

**paginationInfo**

| 欄位         | 名稱                       | 型別      |
| ------------ | -------------------------- | --------- |
| totalPage    | 總頁數                     | `int`     |
| currentPage  | 當前所瀏覽的頁面位於第幾頁 | `int`     |
| totalAmount  | 留言的總數量               | `int`     |
| nextPage     | 是否有下一頁               | `boolean` |
| previousPage | 是否有上一頁               | `boolean` |

**commentList**

| 欄位     | 名稱                         | 型別        | 備註 |
| -------- | ---------------------------- | ----------- | ---- |
| id       | Unique identifier of comment | `int`       | 無   |
| message  | 留言訊息                     | `string`    | 無   |
| createAt | 建立時間                     | `timestamp` | 無   |
| updateAt | 更新時間                     | `timestamp` | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "paginationInfo": {
            "totalPage": 2,
            "currentPage": 1,
            "totalAmount": 6,
            "nextPage": true,
            "previousPage": false
        },
        "commentList": [
            {
                "id": 1,
                "message": "Lizard, who seemed too much overcome to do anything but sit with its mouth open, gazing up into the roof of the court.",
                "createAt": "2022-01-26T10:17:51.000000Z",
                "updateAt": "2022-01-26T10:17:51.000000Z"
            },
            {
                "id": 2,
                "message": "I think I could, if I only knew how to begin.' For, you see, so many out-of-the-way things had happened lately.",
                "createAt": "2022-01-26T10:17:51.000000Z",
                "updateAt": "2022-01-26T10:17:51.000000Z"
            },
            {
                "id": 3,
                "message": "ME' beautifully printed on it in large letters. It was all very well to say 'Drink me.",
                "createAt": "2022-01-26T10:17:51.000000Z",
                "updateAt": "2022-01-26T10:17:51.000000Z"
            }
        ]
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "postId",
        "reason": "The selected post id is invalid."
    }
}
```

---

### 取得某文章的一筆留言

```text
GET /api/posts/{postId}/comments/{commentId}
```

**Request**

| 欄位      | 欄位說明                     | 型別  | 預設值 | 必填 | 參數位置 | 備註 |
| --------- | ---------------------------- | ----- | ------ | ---- | -------- | ---- |
| postId    | Unique identifier of post    | `int` | 無     | 是   | `path`   | 無   |
| commentId | Unique identifier of comment | `int` | 無     | 是   | `path`   | 無   |

參數範例

取得文章 ID 為 1，留言 ID 為 5 的留言。

```text
/api/posts/1/comments/5
```

**Response**

回傳欄位說明

| 欄位     | 欄位說明                     | 型別        | 預設值 | 備註 |
| -------- | ---------------------------- | ----------- | ------ | ---- |
| id       | Unique identifier of comment | `int`       | 無     | 無   |
| message  | 留言訊息                     | `string`    | 無     | 無   |
| createAt | 建立時間                     | `timestamp` | 無     | 無   |
| updateAt | 更新時間                     | `timestamp` | 無     | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "id": 5,
        "message": "What IS the use of a book, thought Alice without pictures or conversations?",
        "createAt": "2022-01-26T10:17:51.000000Z",
        "updateAt": "2022-01-26T10:17:51.000000Z"
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "ERROR_ACTION",
        "message": "This comment is not exist.",
        "code": "C01002",
        "detail": "Comment's ID: 50"
    }
}
```

---

### 新增某文章的一筆留言

```text
POST /posts/{postId}/comments
```

**Request**

| 欄位    | 說明                      | 型別     | 參數位置 | 必填 | 備註 |
| ------- | ------------------------- | -------- | -------- | ---- | ---- |
| postId  | Unique identifier of post | `int`    | `path`   | 是   | 無   |
| message | 留言訊息                  | `string` | `body`   | 是   | 無   |

參數範例

新增一筆留言，該留言所屬的文章 ID 為 1。

```text
/posts/1/comments
```

```json
{
    "message": "The door slammed on the watermelon."
}
```

**Response**

成功

```json
{
    "status": "success",
    "data": []
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "message",
        "reason": "The message field is required."
    }
}
```

---

### 更新某文章的一筆留言

```text
PUT /posts/{postId}/comments/{commentId}
```

**Request**

| 欄位      | 欄位說明                     | 型別     | 預設值 | 必填 | 參數位置 | 備註 |
| --------- | ---------------------------- | -------- | ------ | ---- | -------- | ---- |
| postId    | Unique identifier of post    | `int`    | 無     | 是   | `path`   | 無   |
| commentId | Unique identifier of comment | `int`    | 無     | 是   | `path`   | 無   |
| message   | 留言訊息                     | `string` | 無     | 是   | `body`   | 無   |

參數範例

更新留言的 ID 為 5，所屬文章的 ID 為 1 的一筆留言。

```text
/posts/1/comments/5
```

```json
{
    "message": "The fish dreamed of escaping the fishbowl and into the toilet where he saw his friend go."
}
```

**Response**

回傳欄位說明

| 欄位     | 欄位說明                     | 型別        | 預設值 | 備註 |
| -------- | ---------------------------- | ----------- | ------ | ---- |
| id       | Unique identifier of comment | `int`       | 無     | 無   |
| message  | 更新過後的留言訊息           | `string`    | 無     | 無   |
| createAt | 建立時間                     | `timestamp` | 無     | 無   |
| updateAt | 更新時間                     | `timestamp` | 無     | 無   |

成功

```json
{
    "status": "success",
    "data": {
        "id": 5,
        "message": "The fish dreamed of escaping the fishbowl and into the toilet where he saw his friend go.",
        "createAt": "2022-01-26T10:17:51.000000Z",
        "updateAt": "2022-01-27T13:19:09.000000Z"
    }
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "message",
        "reason": "The message field is required."
    }
}
```

---

### 刪除某文章的一筆留言

```text
DELETE /posts/{postId}/comments/{commentId}
```

**Request**

| 欄位      | 欄位說明                     | 型別  | 預設值 | 必填 | 參數位置 | 備註 |
| --------- | ---------------------------- | ----- | ------ | ---- | -------- | ---- |
| postId    | Unique identifier of post    | `int` | 無     | 是   | `path`   | 無   |
| commentId | Unique identifier of comment | `int` | 無     | 是   | `path`   | 無   |

參數範例

刪除留言的 ID 為 5，所屬文章的 ID 為 1 的一筆留言。

```text
/posts/1/comments/5
```

**Response**

成功

```json
{
    "status": "success",
    "data": []
}
```

失敗

```json
{
    "status": "error",
    "error": {
        "type": "VALIDATION_ERROR",
        "message": "Field Error",
        "code": "B00001",
        "field": "commentId",
        "reason": "The selected comment id is invalid."
    }
}
```