# Employee Logout API

Revoke the current authentication token.

- **URL**: `/api/logout-karyawan`
- **Method**: `POST`
- **Auth required**: Yes (Bearer Token)
- **Headers**:
  - `Authorization: Bearer <your_token>`
  - `Accept: application/json`

- **Success Response**:
  - **Code**: 200 OK
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Logout Berhasil"
    }
    ```
