# Attendance Store API

Submit a check-in or check-out attendance record.

- **URL**: `/api/attendance-store`
- **Method**: `POST`
- **Auth required**: Yes (Bearer Token)
- **Headers**:
  - `Authorization: Bearer <your_token>`
  - `Accept: application/json`
  - `Content-Type: application/json`

- **Parameters**:
  - `lokasi`: String (Comma separated Latitude and Longitude, e.g., `-6.1234,106.1234`).
  - `statuspresensi`: String (`masuk` for check-in, `pulang` for check-out).
  - `image`: String (Base64 encoded PNG image).

- **Success Response**:
  - **Code**: 200 OK
  - **Content**:
    ```json
    {
      "success": true,
      "message": "Terimkasih, Selamat Bekerja"
    }
    ```

- **Error Response (Outside Radius)**:
  - **Code**: 422 Unprocessable Entity
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Maaf Anda Berada Diluar Radius, Jarak Anda 150 meter dari Kantor",
      "type": "radius"
    }
    ```

- **Error Response (Already Checked In/Out)**:
  - **Code**: 422 Unprocessable Entity
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Maaf Gagal absen, Anda Sudah Melakukan Presensi Masuk"
    }
    ```

- **Error Response (Too Early)**:
  - **Code**: 422 Unprocessable Entity
  - **Content**:
    ```json
    {
      "success": false,
      "message": "Maaf Belum Waktunya Absen Masuk"
    }
    ```
