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


function convertNumber(number) {
    // Hilangkan semua titik
    let formatted = number.replace(/\./g, '');
    // Ganti semua koma dengan titik
    formatted = formatted.replace(/,/g, '.');
    return formatted || 0;
}


function numberFormat(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
        dec = typeof dec_point === 'undefined' ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};
