(function ($) {
    "use strict";
    /*----------------------------------------
     passward show hide
     ----------------------------------------*/
    $(".show-hide").show();
    $(".show-hide span").addClass("show");

    $(".show-hide span").click(function (e) {
        e.preventDefault();
        var type = $(this).parent().parent().find(".password").attr("type");
        if (type == "password") {
            $("svg.feather.feather-eye").replaceWith(
                feather.icons["eye-off"].toSvg()
            );
            $(this).parent().parent().find(".password").attr("type", "text");
        } else if (type == "text") {
            $("svg.feather.feather-eye-off").replaceWith(
                feather.icons["eye"].toSvg()
            );
            $(this)
                .parent()
                .parent()
                .find(".password")
                .attr("type", "password");
        }
    });
    $('form button[type="submit"]').on("click", function () {
        $(".show-hide span").addClass("show");
        $(".show-hide")
            .parent()
            .find('input[name="password"]')
            .attr("type", "password");
    });

    $("input[name='username'], input[name='password']").on("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            $("#btn-login").click();
        }
    });

    // window.addEventListener('load', function() {
    //     // var forms = document.getElementsByClassName('needs-validation');
    //     // var validation = Array.prototype.filter.call(forms, function(form) {
    //     //     form.addEventListener('submit', function(event) {
    //     //         if (form.checkValidity() === false) {
    //     //             event.preventDefault();
    //     //             event.stopPropagation();
    //     //         }
    //     //         form.classList.add('was-validated');
    //     //     }, false);
    //     // });


    //     $('#btn-login').click(function() {
    //         var forms = document.getElementsByClassName('needs-validation');
    //         var validation = Array.prototype.filter.call(forms, function(form) {
    //             if (!form.checkValidity()) {
    //                 form.querySelector(".form-control:invalid").focus();
    //                 event.preventDefault();
    //                 event.stopPropagation();
    //             } else {
    //                 form.classList.remove('was-validated');
    //                 var username = $("input[name='username']").val();
    //                 var password = $("input[name='password']").val();
    //                 var remember = $("input[name='remember']").is(":checked");
    //                 var token = $("meta[name='csrf-token']").attr("content");
    //                 $.ajax({
    //                     type: "POST",
    //                     url: "{{ route('admin.login-process') }}",
    //                     dataType: "json",
    //                     data: {
    //                         "username": username,
    //                         "password": password,
    //                         "remember": remember,
    //                         "_token": token
    //                     },
    //                     success: function(res, status, xhr) {
    //                         if (xhr.status == 200 && status == "success") {
    //                             window.location.href =
    //                                 "{{ route('home') }}";
    //                         } else {
    //                             swal({
    //                                 icon: 'warning',
    //                                 title: 'Warning',
    //                                 text: res.message,
    //                             });
    //                         }
    //                     },
    //                     error: function(xhr, status, error) {
    //                         if (xhr.status == 400) {
    //                             var errors = xhr.responseJSON.errors;
    //                             swal({
    //                                 icon: 'error',
    //                                 title: error,
    //                                 text: xhr.responseJSON.message,
    //                             });
    //                         } else if (xhr.status == 500) {
    //                             swal({
    //                                 icon: 'error',
    //                                 title: error,
    //                                 text: "Silahkan hubungi administrator!",
    //                             });
    //                         }
    //                     }
    //                 });
    //             }
    //             form.classList.add('was-validated');
    //         });
    //     })
    // }, false);



})(jQuery);
