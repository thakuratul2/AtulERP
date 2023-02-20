// $(function () {

//     $(window).on("dragover", function (e) {
//         e.preventDefault();
//     }, false);

//     $(window).on("drop", function (e) {
//         e.preventDefault();
//     }, false);

//     var hash = $('.main_session').val();
//     setTimeout(function (argument) {
//         $.ajaxSetup({
//             data: {
//                 hash: hash
//             },
//             cache: false
//         });
//     }, 100)


//     $('[data-toggle="tooltip"]').tooltip();
//     // open last active tab
//     var url = document.location.toString();
//     if (url.match('#')) {
//         var val_hash = url.split('#')[1];
//         $('.nav-tabs a[href="#' + val_hash + '"]').tab('show');
//     }
//     $('.nav-tabs a').on('shown.bs.tab', function (e) {
//         //window.location.hash = e.target.hash;
//         $('body').scrollTop(0);
//     });

//     intervalUpdates = setTimeout(Br_intervalUpdates, 6000);
//     setTimeout(Br_UpdateLastSeen, 40000);
//     setTimeout(Br_IsLogged, 30000);

//     //  dropdown won't close on click
//     $('.dropdown-menu.request-list, .dropdown-menu.post-recipient, .dropdown-menu.post-options').click(function (e) {
//         e.stopPropagation();
//     });
//     scrolled = 0;
// });

// function Br_CloseModels() {
//     $('.modal').modal('hide');
// }

// // update user last seen
// function Br_UpdateLastSeen() {
//     $.get(Br_Ajax_Requests_File(), {
//         f: 'update_lastseen'
//     }, function () {
//         setTimeout(Br_UpdateLastSeen, 40000);
//     });
// }

// // js function
// function Br_CheckUsername(username) {
//     var check_container = $('.checking');
//     var check_input = $('#username').val();
//     if (check_input == '') {
//         check_container.empty();
//         return false;
//     }
//     check_container.removeClass('unavailable').removeClass('available').html('<i class="fa fa-clock-o"></i><span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
//     $.get(Br_Ajax_Requests_File(), {
//         f: 'check_username',
//         username: username
//     }, function (data) {
//         if (data.status == 200) {
//             check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('unavailable').addClass('available');
//         } else if (data.status == 300) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 400) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 500) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 600) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         }
//     });
// }

// function Br_CheckPagename(pagename, page_id) {
//     var check_container = $('.checking');
//     var check_input = $('#page_name').val();
//     if (check_input == '') {
//         check_container.empty();
//         return false;
//     }
//     check_container.removeClass('unavailable').removeClass('available').html('<i class="fa fa-clock-o"></i><span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
//     $.get(Br_Ajax_Requests_File(), {
//         f: 'check_pagename',
//         pagename: pagename,
//         page_id: page_id
//     }, function (data) {
//         if (data.status == 200) {
//             check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass('unavailable').addClass('available');
//         } else if (data.status == 300) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 400) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 500) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         } else if (data.status == 600) {
//             check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass('available').addClass('unavailable');
//         }
//     });
// }

// // scroll to top function
// function scrollToTop() {
//     verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : 0;
//     element = $('html');
//     offset = element.offset();
//     offsetTop = offset.top;
//     $('html, body').animate({
//         scrollTop: offsetTop
//     }, 300, 'linear');
// }

// // check if user is logged in function
// function Br_IsLogged() {
//     $.post(Br_Ajax_Requests_File() + '?f=session_status', function (data) {
//         setTimeout(Br_UpdateLastSeen, 30000);
//         if (data.status == 200) {
//             $('#logged-out-modal').modal({
//                 show: true
//             });
//         }
//     });
// }

// // request permission on page load
// // document.addEventListener('DOMContentLoaded', function () {
// //     if (Notification.permission !== "granted")
// //       Notification.requestPermission();
// //   });

// //   function Br_NotifyMe(icon, title, notification_text, url) {
// //     if (!Notification) {
// //       return;
// //     }
// //     if (Notification.permission !== "granted")
// //       Notification.requestPermission();
// //     else {
// //       var notification = new Notification(title, {
// //         icon: icon,
// //         body: notification_text,
// //       });

// //       notification.onclick = function () {
// //         window.open(url);
// //         notification.close();
// //         Br_OpenNotificationsMenu();
// //       };
// //     }
// //   }

// function Br_UpdateLocation(position) {
//     if (!position) {
//         return false;
//     }
//     $.post(Br_Ajax_Requests_File() + '?f=save_user_location', { lat: position.coords.latitude, lng: position.coords.longitude }, function (data, textStatus, xhr) {
//         if (data.status == 200) {
//             return true;
//         }
//     });
//     return false;
// }

// function decodeHtml(html) {
//     var txt = document.createElement("textarea");
//     txt.innerHTML = html;
//     return txt.value;
// }

// function isInArray(value, array) {
//     return array.indexOf(value) > -1;
// }

// function escapeHtml(html) {
//     var text = document.createTextNode(html);
//     var div = document.createElement('div');
//     div.appendChild(text);
//     return div.innerHTML;
// }

// function _getCookie(cname) {
//     var name = cname + "=";
//     var decodedCookie = decodeURIComponent(document.cookie);
//     var ca = decodedCookie.split(';');
//     for (var i = 0; i < ca.length; i++) {
//         var c = ca[i];
//         while (c.charAt(0) == ' ') {
//             c = c.substring(1);
//         }
//         if (c.indexOf(name) == 0) {
//             return c.substring(name.length, c.length);
//         }
//     }
//     return "";
// }

function Br_progressIconLoader(e) {
    e.each(function () {
        return progress_icon_elem = $(this).find("i.progress-icon"),
            default_icon = progress_icon_elem.attr("data-icon"),
            hide_back = !1,
            1 == progress_icon_elem.hasClass("hidde") && (hide_back = !0),
            1 == $(this).find("i.fa-spinner").length ? (progress_icon_elem.removeClass("fa-spinner").removeClass("fa-spin").addClass("fa-" + default_icon),
                1 == hide_back && progress_icon_elem.hide()) : progress_icon_elem.removeClass("fa-" + default_icon).addClass("fa-spinner fa-spin").show(), !0
    })
}
function Br_StartBar() {
    $(".barloading").css("display", "block")
}
function Br_FinishBar() {
    $(".barloading").css("display", "none")
}
