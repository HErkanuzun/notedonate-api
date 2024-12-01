# NoteApp API Documentation

## Base URL
```
http://http://127.0.0.1:8000/api/v1
```

## Authentication
API kimlik doğrulaması için Laravel Sanctum kullanılmaktadır. Token'ı header'da göndermek için:
```
Authorization: Bearer YOUR_TOKEN
```

## Endpoints

### Authentication

#### Register
- **URL**: `/register`
- **Method**: POST
- **Auth Required**: No
- **Body**:
```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

#### Login
- **URL**: `/login`
- **Method**: POST
- **Auth Required**: No
- **Body**:
```json
{
    "email": "string",
    "password": "string"
}
```

#### Logout
- **URL**: `/logout`
- **Method**: POST
- **Auth Required**: Yes

### Notes

#### Get All Notes
- **URL**: `/note`
- **Method**: GET
- **Auth Required**: Yes

#### Get Single Note
- **URL**: `/note/show/{id}`
- **Method**: GET
- **Auth Required**: Yes

#### Create Note
- **URL**: `/note/store`
- **Method**: POST
- **Auth Required**: Yes
- **Body**:
```json
{
    "title": "string",
    "content": "string",
    "category_id": "number"
}
```

#### Update Note
- **URL**: `/note/update/{id}`
- **Method**: POST
- **Auth Required**: Yes
- **Body**:
```json
{
    "title": "string",
    "content": "string",
    "category_id": "number"
}
```

#### Delete Note
- **URL**: `/note/destroy/{id}`
- **Method**: POST
- **Auth Required**: Yes

### Exams

#### Get All Exams
- **URL**: `/exam`
- **Method**: GET
- **Auth Required**: Yes

#### Get Single Exam
- **URL**: `/exam/show/{id}`
- **Method**: GET
- **Auth Required**: Yes

#### Create Exam
- **URL**: `/exam/store`
- **Method**: POST
- **Auth Required**: Yes
- **Body**:
```json
{
    "title": "string",
    "description": "string",
    "exam_date": "date"
}
```

#### Update Exam
- **URL**: `/exam/update/{id}`
- **Method**: POST
- **Auth Required**: Yes
- **Body**:
```json
{
    "title": "string",
    "description": "string",
    "exam_date": "date"
}
```

#### Delete Exam
- **URL**: `/exam/destroy/{id}`
- **Method**: POST
- **Auth Required**: Yes

### Comments

#### Get Comments
- **URL**: `/comments/{type}/{id}`
- **Method**: GET
- **Auth Required**: Yes

#### Add Comment
- **URL**: `/comments`
- **Method**: POST
- **Auth Required**: Yes
- **Body**:
```json
{
    "content": "string",
    "commentable_type": "string",
    "commentable_id": "number"
}
```

### Filtering

#### Filter Notes
- **URL**: `/notes/filter`
- **Method**: GET
- **Auth Required**: Yes
- **Query Parameters**:
  - `search`: string
  - `category`: number
  - `sort`: string (asc/desc)

#### Filter Exams
- **URL**: `/exams/filter`
- **Method**: GET
- **Auth Required**: Yes
- **Query Parameters**:
  - `search`: string
  - `date`: date
  - `sort`: string (asc/desc)

## Error Responses

Tüm hata yanıtları aşağıdaki formatta döner:
```json
{
    "message": "Error message here",
    "errors": {
        "field": ["Error details"]
    }
}
```

## Başarılı Yanıtlar

Başarılı yanıtlar aşağıdaki formatta döner:
```json
{
    "status": true,
    "message": "Success message",
    "data": {
        // Response data here
    }
}
