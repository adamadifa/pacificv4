# Employee Login API

Authenticate an employee and receive a Sanctum token.

- **URL**: `/api/login-karyawan`
- **Method**: `POST`
- **Auth required**: No
- **Request Body**:
  ```json
  {
    "nik": "string (Required)",
    "password": "string (Required)"
  }
  ```
- **Headers**:
  - `Accept: application/json`
  - `Content-Type: application/json`

- **Success Response**:
  - **Code**: 200 OK
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Login Berhasil",
      "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
      "employee": {
        "nik": "00.01.028",
        "nama_karyawan": "Elin Herlina",
        ...
      }
    }
    ```
- **Error Response**:
  - **Code**: 401 Unauthorized
  - **Content**:
    ```json
    {
      "success": false,
      "message": "NIK atau Password salah."
    }
    ```
