//Hitung Masa Kerja
function calculateWorkDuration(startDate, endDate) {
    // Pastikan startDate dan endDate adalah objek Date
    if (!(startDate instanceof Date) || !(endDate instanceof Date)) {
        throw new Error("Input harus berupa objek Date");
    }

    // Hitung selisih waktu dalam milidetik
    let diff = endDate - startDate;

    // Satu hari dalam milidetik
    const oneDay = 1000 * 60 * 60 * 24;

    // Total hari
    let days = Math.floor(diff / oneDay);

    // Hitung tahun
    const years = Math.floor(days / 365);
    days -= years * 365;

    // Hitung bulan
    const months = Math.floor(days / 30);
    days -= months * 30;

    return {
        years,
        months,
        days
    };
}


function convertDateFormatToIndonesian(dateStr) {
    // Membuat objek Date dari string
    let dateObj = new Date(dateStr);

    // Mengambil hari, bulan, dan tahun
    let day = dateObj.getDate();
    let month = dateObj.getMonth(); // Bulan dimulai dari 0
    let year = dateObj.getFullYear();

    // Array nama bulan dalam bahasa Indonesia
    const monthsIndonesian = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    // Mengambil nama bulan berdasarkan indeks
    let monthName = monthsIndonesian[month];

    // Memastikan format dua digit untuk hari
    day = day < 10 ? '0' + day : day;

    // Menyusun kembali dalam format d M Y (dd NamaBulan yyyy)
    let formattedDate = day + ' ' + monthName + ' ' + year;

    return formattedDate;
}



function calculateMonthDifference(startDate, endDate) {
    // Pastikan startDate dan endDate adalah objek Date
    if (!(startDate instanceof Date) || !(endDate instanceof Date)) {
        throw new Error("Input harus berupa objek Date");
    }

    // Ekstrak tahun dan bulan dari kedua tanggal
    const startYear = startDate.getFullYear();
    const startMonth = startDate.getMonth();
    const endYear = endDate.getFullYear();
    const endMonth = endDate.getMonth();

    // Hitung perbedaan tahun dan bulan
    const yearDifference = endYear - startYear;
    const monthDifference = endMonth - startMonth;

    // Hitung total jumlah bulan
    const totalMonths = yearDifference * 12 + monthDifference;

    return totalMonths;
}

function convertToRupiah(number) {
    if (number) {
        var rupiah = "";
        var numberrev = number
            .toString()
            .split("")
            .reverse()
            .join("");
        for (var i = 0; i < numberrev.length; i++)
            if (i % 3 == 0) rupiah += numberrev.substr(i, 3) + ".";
        return (
            rupiah
                .split("", rupiah.length - 1)
                .reverse()
                .join("")
        );
    } else {
        return number;
    }
}
