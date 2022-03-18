# API Documentation

### Authenticate

- [Sign Up](#signup)
- [Send verification code](#sendVerificationCode)
- [Verification](#verification)
- [Sign In](#signin)
- [Logout](#logout)

### Specialist

- [Create](#specialistCreate)
- [Get by id](#specialistGetById)
- [Me](#specialistMe)

### Client

- [Create](#clientCreate)
- [Get by id](#clientGetById)
- [Me](#clientMe)
---

### <a name="signup"></a> Authenticate - Sign Up 
```http request
POST /api/auth/signup
```
#### JSON Payload
```json
{
    "phone_number": "+79000000000",
    "password": "sample_pin",
    "password_confirmation": "sample_pin"
}
```
#### JSON Response
```json
// Successful scenario
// HTTP Code: 201 (Created)
{
    "status": "Success",
    "message": "User created",
    "data": {
        "phone_number": "+79000000000",
        "updated_at": "2022-03-18T13:13:42.000000Z",
        "created_at": "2022-03-18T13:13:42.000000Z",
        "id": 3
    }
}
```
---
### <a name="sendVerificationCode"></a> Authenticate - Send verification code
```http request
POST /api/auth/sendVerificationCode
```
#### JSON Payload
```json
{
    "phone_number": "+79000000000"
}
```
#### JSON Response
```json
// Successful scenario
// HTTP Code: 200 (OK)
{
    "status": "Success",
    "message": "Verification code sent",
    "data": null
}
```
```json
// If sms was not sent
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "Something went wrong",
    "data": null
}
```
```json
// If user not found
// HTTP Code: 404 (Not Found)
{
    "status": "Error",
    "message": "User not found",
    "data": null
}
```
```json
// If user is verified
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "User has already been verified",
    "data": null
}
```
---
### <a name="verification"></a> Authenticate - Verification
```http request
POST /api/auth/verification
```
#### JSON Payload
```json
{
    "phone_number": "+79000000000",
    "verification_code": "0000"
}
```
#### JSON Response
```json
// Successful scenario
// HTTP Code: 200 (OK)
{
    "status": "Success",
    "message": "User is verified",
    "data": null
}
```
```json
// If verfication code is not valid
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "Verification code is not valid",
    "data": null
}
```
```json
// If user not found
// HTTP Code: 404 (Not Found)
{
    "status": "Error",
    "message": "User not found",
    "data": null
}
```
```json
// If user is verified
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "User has already been verified",
    "data": null
}
```
---
### <a name="signin"></a> Authenticate - Sign In
```http request
POST /api/auth/signin
```
#### JSON Payload
```json
{
    "phone_number": "+79000000000",
    "password": "sample_pin"
}
```
#### JSON Response
```json
// Successful scenario
// HTTP Code: 200 (OK)
{
    "status": "Success",
    "message": "Authenticated",
    "data": "3|SpefeXVMt5ltzefsKI43IrZfhZlKUpEhIZDvRGse"
}
```
```json
// If user not found or invalid credentials
// HTTP Code: 401 (Unauthorized)
{
    "status": "Error",
    "message": "Invalid login or password",
    "data": null
}
```
```json
// If user not verified
// HTTP Code: 401 (Unauthorized)
{
    "status": "Error",
    "message": "User is not verified",
    "data": null
}
```
---
### <a name="logout"></a> Authenticate - Logout
```http request
POST /api/auth/logout
```
#### JSON Response
```json
// Successful scenario
// HTTP Code: 200 (OK)
{
    "status": "Success",
    "message": "Tokens revoked",
    "data": null
}
```
---
### <a name="specialistCreate"></a> Specialist - Create
```http request
POST /api/specialist/profile
```
### Form-data payload
```shell
avatar (type: file, nullable)
activity_kind_id (type: int)
card_title (type: string)
about (type: string)
address (type: string)
placement (type: string, nullable)
floor (type: int, nullable)
instagram_account (type: string, nullable)
youtube_account (type: string, nullable)
vk_account (type: string, nullable)
tiktok_account (type: string, nullable)
name (type: string)
surname (type: string)
```
### JSON Response
```json
// Successful scenario
// HTTP Code: 201 (Created)
{
    "status": "Success",
    "message": "Specialist created",
    "data": {
        "user_id": 1,
        "name": "Sample name",
        "surname": "Sample surname",
        "avatar": "/images/specialist/14:04:18-c81e728d9d4c2f636f067f89cc14862c.jpg",
        "activity_kind_id": "8",
        "card_title": "Sample card title",
        "about": "Sample about",
        "address": "Новороссийск, ул. Куникова 52",
        "placement": "79",
        "floor": "7",
        "instagram_account": "@test",
        "youtube_account": "@test",
        "vk_account": "@test",
        "tiktok_account": "@test",
        "updated_at": "2022-03-18T14:04:18.000000Z",
        "created_at": "2022-03-18T14:04:18.000000Z",
        "id": 1
    }
}
```
```json
// If specialist is existing
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "Specialist is already existing",
    "data": null
}
```
---
### <a name="specialistGetById"></a> Specialist - Get by id
```http request
GET /api/specialist/profile/{id}
```
### JSON Response
```json
// Succesful scenarion
// HTTP Code: 200 (OK)
{
    "id": 4,
    "name": "Sample name",
    "surname": "Sample surname",
    "phone": "+79000000000",
    "about": "Sample about",
    "avatar": "/images/specialist/14:04:18-c81e728d9d4c2f636f067f89cc14862c.jpg",
    "activity_kind": "Другое",
    "address": {
        "address": "Новороссийск, ул. Куникова 52",
        "placement": "79",
        "floor": "7"
    },
    "card_title": "Sample card title",
    "instagram_account": "@test",
    "youtube_account": "@test",
    "vk_account": "@test",
    "tiktok_account": "@test"
}
```
---
### <a name="specailistMe"></a> Specialist - Me
```http request
GET /api/specialist/profile
```
### JSON Response
```json
// Succesful scenarion
// HTTP Code: 200 (OK)
{
    "id": 4,
    "name": "Sample name",
    "surname": "Sample surname",
    "phone": "+79000000000",
    "about": "Sample about",
    "avatar": "/images/specialist/14:04:18-c81e728d9d4c2f636f067f89cc14862c.jpg",
    "activity_kind": "Другое",
    "address": {
        "address": "Новороссийск, ул. Куникова 52",
        "placement": "79",
        "floor": "7"
    },
    "card_title": "Sample card title",
    "instagram_account": "@test",
    "youtube_account": "@test",
    "vk_account": "@test",
    "tiktok_account": "@test"
}
```
---
### <a name="clientCreate"></a> Client - Create
```http request
POST /api/client/profile
```
### Form-data payload
```shell
avatar (type: file, nullable)
name (type: string)
surname (type: string)
```
### JSON Response
```json
// Successful scenario
// HTTP Code: 201 (Created)
{
    "status": "Success",
    "message": "Client created",
    "data": {
        "user_id": 1,
        "name": "Sample name",
        "surname": "Sample surname",
        "avatar": "/images/client/14:17:59-c81e728d9d4c2f636f067f89cc14862c.jpg",
        "updated_at": "2022-03-18T14:17:59.000000Z",
        "created_at": "2022-03-18T14:17:59.000000Z",
        "id": 1
    }
}
```
```json
// If client is existing
// HTTP Code: 400 (Bad Request)
{
    "status": "Error",
    "message": "Client is already existing",
    "data": null
}
```
---
### <a name="clientGetById"></a> Client - Get by id
```http request
GET /api/client/profile/{id}
```
### JSON Response
```json
// Succesful scenarion
// HTTP Code: 200 (OK)
{
    "id": 4,
    "name": "Sample name",
    "surname": "Sample surname",
    "phone": "+79000000000",
    "avatar": "/images/specialist/14:04:18-c81e728d9d4c2f636f067f89cc14862c.jpg"
}
```
---
### <a name="clientMe"></a> Client - Me
```http request
GET /api/client/profile
```
### JSON Response
```json
// Succesful scenarion
// HTTP Code: 200 (OK)
{
    "id": 4,
    "name": "Sample name",
    "surname": "Sample surname",
    "phone": "+79000000000",
    "avatar": "/images/specialist/14:04:18-c81e728d9d4c2f636f067f89cc14862c.jpg"
}
```
---
