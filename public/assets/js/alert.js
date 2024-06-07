function SwalWarning(name, message) {
    Swal.fire({
        title: "Oops!",
        text: message,
        icon: "warning",
        showConfirmButton: true,
        didClose: (e) => {
            $("#" + name).focus();
        },
    });
}


