# Attendance History API

Retrieve the last 30 attendance records for the currently authenticated employee.

- **URL**: `/api/attendance-history`
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
      "data": [
        {
          "tanggal": "2026-03-26",
          "hari": "Kamis",
          "jam_in": "07:55",
          "jam_out": "17:05",
          "status": "h",
          "terlambat_min": 0,
          "pulang_cepat_min": 0,
          "denda": 0,
          "keterangan": "Tepat Waktu"
        },
        {
          "tanggal": "2026-03-25",
          "hari": "Rabu",
          "jam_in": null,
          "jam_out": null,
          "status": "c",
          "terlambat_min": 0,
          "pulang_cepat_min": 0,
          "denda": 0,
          "keterangan": "Cuti (Cuti Tahunan)"
        },
        {
          "tanggal": "2026-03-24",
          "hari": "Selasa",
          "jam_in": "08:15",
          "jam_out": "17:00",
          "status": "h",
          "terlambat_min": 15,
          "pulang_cepat_min": 0,
          "denda": 15000,
          "keterangan": "Telat :00:15 (0.25)"
        }
      ]
    }
    ```

## Logic Notes
- **Status Codes**:
  - `h`: Hadir
  - `i`: Izin
  - `s`: Sakit
  - `c`: Cuti
- **Lateness**: Calculated against the scheduled `jam_masuk`.
- **Denda**: Penalties are applied based on late minutes following the HRD rules.
- **Pulang Cepat**: Calculated against the scheduled `jam_pulang`, considering break times.


