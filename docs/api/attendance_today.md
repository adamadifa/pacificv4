# Attendance Today API

Retrieve the attendance status and schedule for the current day for the authenticated employee.

- **URL**: `/api/attendance-today`
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
        "cek": {
          "id": 1,
          "nik": "12.02.061",
          "tanggal": "2026-03-27",
          "jam_in": "2026-03-27 08:00:00",
          "jam_out": null,
          "foto_in": "12.02.061-2026-03-27-in.png",
          "foto_out": null,
          "lokasi_in": "-6.1234,106.1234",
          "lokasi_out": null,
          "kode_jadwal": "JD001",
          "kode_jam_kerja": "JK01",
          "status": "h"
        },
        "lok_kantor": {
          "kode_cabang": "PST",
          "nama_cabang": "PUSAT",
          "lokasi_cabang": "-6.2345,106.3456",
          "radius_cabang": 100
        },
        "jam_kerja": {
          "kode_jam_kerja": "JK01",
          "nama_jam_kerja": "JAM KERJA NORMAL",
          "jam_masuk": "08:00:00",
          "jam_pulang": "16:00:00",
          "lintashari": "0"
        },
        "jadwal": {
          "kode_jadwal": "JD001",
          "hari": "Jumat",
          "kode_jam_kerja": "JK01",
          "nama_jadwal": "NON SHIFT"
        },
        "status_libur": false,
        "status_wfh": false,
        "status_libur_pengganti": false
      }
    }
    ```

## Field Descriptions
- **cek**: Existing attendance record for today (null if not yet checked in).
- **lok_kantor**: Branch location and allowed radius in meters.
- **jam_kerja**: Working hours for the determined schedule.
- **jadwal**: The schedule and day name determined for the current employee.
- **status_libur**: Boolean indicating if today is a national holiday for the employee.
- **status_wfh**: Boolean indicating if today is a Work From Home day.
- **status_libur_pengganti**: Boolean indicating if today is a replacement holiday.
