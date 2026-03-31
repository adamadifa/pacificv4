# Get Employee Profile API

Retrieve the profile of the currently authenticated employee.

- **URL**: `/api/me-karyawan`
- **Method**: `GET`
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
      "data": {
        "nik": "00.01.028",
        "nama_karyawan": "Elin Herlina",
        "nama_jabatan": "...",
        "nama_dept": "...",
        "nama_cabang": "...",
        ...
      }
    }
    ```
