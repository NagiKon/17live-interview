# API 說明文件

## 錯誤清單列表

### Request Action 問題

| Code   | Message                            | Http Status Code | 說明 |
| ------ | ---------------------------------- | ---------------- | ---- |
| C00001 | Content Type of header is not JSON | 400              |      |
| C00002 | Content Type of body is not JSON   | 400              |      |

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

## Comment 相關 API