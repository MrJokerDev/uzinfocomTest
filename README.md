
# Project Title

A brief description of what this project does and who it's for


## Installation

You need install 

```bash
  1. composer update
  2. cp .env.example .env
  3. php artisan key:generate
  4. php artisan jwt:secret
  5. php artisan migrate --seed
  6. php artsan serve
```
    
## Users

Users info:

- Admin
    - Email: admin@gmail.com
    - Password: admin
- Moderator
    - Email: moderator@gmail.com
    - Password: moderator
- User
    - Email: user@gmail.com
    - Password: user




## API 

#### POST login

```http
  POST /api/login
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | admin@gmail.com |
| `email` | `string` | admin |

#### POST register

```http
  POST /api/register
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name` | `string` | name |
| `email` | `string` | email |
| `password` | `string` | password |


#### POST logout

```http
  POST /api/logout
```

#### POST refresh

```http
  POST /api/refresh
```



## API 

#### GET admin

```http
  GET /api/files
```

#### POST admin

```http
  POST /api/upload-file
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `file` | `file` | .png, .jpeg, .jpg |


#### POST admin

```http
  DELETE /api/file/{id}
```

| Parameter | Type     |
| :-------- | :------- |
| `id` | `number` |

