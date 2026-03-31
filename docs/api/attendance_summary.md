# Attendance Summary API

Retrieve a summary of attendance for the current month and the remaining leave balance.

- **URL**: `/api/attendance-summary`
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
        "hadir": 22,
        "izin": 1,
        "sisa_cuti": 11
      }
    }
    ```

## Field Descriptions
- **hadir**: Total number of days present in the current month (status `h`).
- **izin**: Total number of days on permission or sick leave in the current month (status `i` or `s`).
- **sisa_cuti**: Remaining annual leave quota for the current year (Quota is 12 days minus used leave code `C01`).
