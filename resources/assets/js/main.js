//var $ = require('jquery');
//require('fancybox')($); 
 
var selectedTickets = [];
var selectedNotifications = [];
function doSomething(value) {
//    alert(2);
        $('#overlay').fadeIn();
        var url = $("#buildings_url").val() + "/getBuildings/" + value; // the script where you handle the form input.
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (json)
            {
                console.log(json);
                $('#building').html("");
                var buildings = json["buildings"];
                $.each(buildings, function (i, value) {
                    console.log(value);
                    $('#building').append($('<option>').text(value["building_name"]).attr('value', value["building_id"]));
                });
                
                
            }
        });
    };
$('document').ready(function () {
    $('body').on('click', '#print_ticket_href', function (e) {
        e.stopPropagation();
        event.preventDefault();
        setTimeout(function () { // wait until all resources loaded
            $('#page').print();
        }, 250);
    });
//    $('.fancybox').fancybox();

    $('body').on('click', '.clickable_tr', function (e) {
        e.preventDefault();
        redirect_url = $(this).data("href");
        window.location.href = redirect_url;
    });
    $('body').on('mouseover', '.clickable_tr', function (e) {
        $(this).css('cursor', 'pointer');
        $(this).addClass("tickets_table_row_hover");
    }).on('mouseout', '.clickable_tr', function (e) {
        $(this).css('cursor', 'none');
        $(this).removeClass("tickets_table_row_hover");
    });
    $('body').on('mouseover', '.notifications_row', function (e) {
        $(this).css('cursor', 'pointer');
        $(this).addClass("tickets_table_row_hover");
    }).on('mouseout', '.notifications_row', function (e) {
        $(this).css('cursor', 'none');
        $(this).removeClass("tickets_table_row_hover");
    });
    $('body').on('submit', '#change_password_form', function (e) {
        e.preventDefault();
        $('#overlay').show();
        var url = $("#url").val();
//        $("#overlay").css("height", $(".col-xs-12").height());
        if ($("#password").val() == $("#confirm_password").val()) {
            $.ajax({
                type: "POST",
                url: url,
                data: $("#change_password_form").serialize(),
                success: function (data)
                {
                    if (data) {
                        alert("تم تغيير كلمة المرور بنجاح");
                        window.location.replace($("#redirect_url").val());
                    } else if (data = "confirmation-false") {
                        alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
                    }
                    $('#overlay').hide();
//                    $("#change_password_form").load(location.href + " #change_password_form");
                }
            });
        } else {
            $('#overlay').hide();
            alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
        }
    });
    $('body').on('click', '.action_only', function (e) {
        e.stopPropagation();
        e.preventDefault();
        title = $(this).data("title");
        var confirmed = confirm("هل تريد فعلا حذف الطلب - " + title);
        if (confirmed == true) {
            $("#ajax_container").hide();
            $('#overlay').show();
            var that = this;
            url = $(this).data("href");
            redirect_url = $(this).data("redirect");
            tID = $(this).data("tid");
            $.post(url, {ticketID: tID},
                    function (returnedData) {
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: redirect_url,
                            success: function (data)
                            {

                                $(".filter_list").removeClass("bordered-segment");
                                $("#ajax_container").html(data);
                                $('#overlay').hide();
                                $(that).addClass("bordered-segment");
                                $("#ajax_container").fadeIn();
                            }
                        });
                    });
        } else {

        }
    });
    $('body').on('click', '.filter_list', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(document).prop('title', $(this).attr("title"));
        $("#ajax_container").hide();
        $('#overlay').show();
        var that = this;
        url = $(this).data("url");
        segment = $(this).data("segment");
        $.ajax({
            context: this,
            type: "GET",
            url: url,
            success: function (data)
            {
                $(".filter_list").removeClass("bordered-segment");
                $("#ajax_container").html(data);
                $('#overlay').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").fadeIn();
            }
        });
    });
    $('body').on('click', '.emp_filter_list', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(document).prop('title', $(this).attr("title"));

        $("#ajax_container").hide();
        var that = this;
        $('#overlay').show();
        url = $(this).data("url");
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $("#add_ticket_link").removeClass("bordered-segment");
                $(".emp_filter_list").removeClass("bordered-segment");
                $('#overlay').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").html(data);
                $("#ajax_container").fadeIn();
            }
        });
        notifications_url = $("#hiddenURLNewNotifications").val();
        $.ajax({
            type: "GET",
            url: notifications_url,
            success: function (data)
            {
            }
        });
    });
//    $("body").on("click", ".pagination a", function (e) {
//        e.preventDefault();
//        $("#ajax_container").hide();
//        $("#overlay p").css("top", '50%');
//        url = $("#hiddenURL").val();
//        $('#overlay').show();
//        var page = $(this).attr("data-page"); //get page number from link
//        $("#ajax_container").load(url, {"page": page}, function () { //get content from PHP page
//            $("#overlay").hide(); //once done, hide loading element
//            $("#ajax_container").fadeIn();
//        });
//    });
    $("body").on("click", ".nav_btn", function (e) {
        $(".nav_btn").removeClass("bordered-segment");
        $(this).addClass("bordered-segment")
    });
    setInterval(function () {
        $(".new_ticket_label").toggle();
    }, 500);
    setInterval(function () {
        if ($("#hiddenURLNewNotifications").length) {
            notifications_url = $("#hiddenURLNewNotifications").val();
            $.ajax({
                type: "GET",
                url: notifications_url,
                success: function (data)
                {
                    $("#notifications_num_lbl").html(data);
                }
            });
        }

    }, 30000);
    setInterval(function () {
        if ($("#hiddenURLStaffTickets").length) {
            notifications_url = $("#hiddenURLStaffTickets").val();
            $.ajax({
                type: "GET",
                url: notifications_url,
                success: function (data)
                {
                    $("#staf_opened_tickets_label").html(data);
                }
            });
        }
    }, 30000);
    $('body').on('change', '#building_report_select', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $('#overlay').show();
        var that = this;
        var selectedBuilding = $(this).val();
        var url = $("#buildings_url").val() + "?buildingID=" + selectedBuilding;
        window.location.replace(url);
    });
    $('body').on('click', '#add_ticket_link', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(document).prop('title', "تقديم طلب جديد");
        $("#ajax_container").hide();
        var that = this;
//        $("#overlay").css("height", $(".col-xs-12").height());
        $('#overlay').show();
        url = $("#hiddenURLAddTicket").val();
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $(".emp_filter_list").removeClass("bordered-segment");
                $('#overlay').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").html(data);
                $("#ajax_container").fadeIn();
            }
        });
    });
    $('body').on('click', '.notifications_row', function (e) {
        var url = $(this).data("seenurl");
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $("#notifications_num_lbl").html(data);
            }
        });
    });

    $(".closebtn").click(function () {
        $(".alert_container").hide();
    });
    function doSomething() {
        alert(2);
    };
    $('body').on('change', '#region_select', function (e) {
        alert(2);
        e.preventDefault(); // avoid to execute the actual submit of the form.
//        $("#overlay").css("height", $(".col-xs-12").height());
        $('#overlay').fadeIn();
        var url = $("#buildings_url").val() + "/getBuildings/" + $(this).val(); // the script where you handle the form input.
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (json)
            {
                console.log(json);
                $('#building').html("");
                var buildings = json["buildings"];
                $.each(buildings, function (i, value) {
                    console.log(value);
                    $('#building').append($('<option>').text(value["building_name"]).attr('value', value["building_id"]));
                });
                
                
            }
        });
    });
    /*Check All Tickets*/
    $('body').on('click', '#select_all_tickets', function (e) {

        var status = this.checked; // "select all" checked status
        if (this.checked == true) {
            enableDisableMultipleAction(false);
            $('.staff_ticket_checkmark').each(function () { //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
                $(this).closest('tr').addClass("checked_row");
                selectedTickets.push($(this).val());
            });
        } else {
            enableDisableMultipleAction(true);
            $('.staff_ticket_checkmark').each(function () { //iterate all listed checkbox items
                $(this).closest('tr').removeClass("checked_row");
                this.checked = status; //change ".checkbox" checked status
            });
            selectedTickets = [];
        }
        $("#selectedTickets").val(selectedTickets);
    });
    /*Check one ticket*/
    $('body').on('click', '.clickable_tr input', function (e) {
        e.stopPropagation();
        if (this.checked == false) { //if this item is unchecked
            $(this).closest('tr').removeClass("checked_row");
            enableDisableMultipleAction(true);
            $("#select_all_tickets")[0].checked = false; //change "select all" checked status to false
        } else {
            $(this).closest('tr').addClass("checked_row");
            enableDisableMultipleAction(false);
        }

        selectedTickets = [];
        $('input[type="checkbox"][name="ticket_checked"]').each(function () {
            if ($(this).is(':checked')) {
                //if checked, Add to selected tickets array
                selectedTickets.push($(this).val());
            } else {

            }
        });
        $("#selectedTickets").val(selectedTickets);
        if (selectedTickets.length > 0) {
//Show selectbox action
            enableDisableMultipleAction(false);
        } else {
            enableDisableMultipleAction(true);
        }
    });
    var enableDisableMultipleAction = function (disabled) {
//        $("#multiple_form_fieldset").attr("disabled", disabled);
        if (disabled)
            $("#multiple_action_container").hide();
        else
            $("#multiple_action_container").show();
    }

    $('body').on('submit', '#multiple_notification_container', function (e) {
        e.preventDefault();
        $('#overlay').show();
        var url = $("#submitURL").val();
        var notsURL = $("#notsURL").val();
        if (selectedNotifications.length > 0) {

            $.ajax({
                type: "POST",
                url: url,
                data: $("#multiple_notification_container").serialize(), //selectedTickets.serializeArray(),
                success: function (data)
                {
                    $.ajax({
                        type: "GET",
                        url: notsURL,
                        success: function (data)
                        {
                            $('#overlay').hide();
                            $("#ajax_container").html(data);
                            $("#ajax_container").fadeIn();
                            selectedTickets = [];
                            enableDisableMultipleActionNotifications(true);
                            notifications_url = $("#hiddenURLNewNotifications").val();
                            $.ajax({
                                type: "GET",
                                url: notifications_url,
                                success: function (data)
                                {
                                    $("#notifications_num_lbl").html(data);
                                }
                            });
                        }

                    });
                    $('#overlay').hide();
                }
            });
        }
    });
    /*Check All notifications*/
    $('body').on('click', '#all_notifications_checked', function (e) {

        var status = this.checked; // "select all" checked status
        if (this.checked == true) {
            enableDisableMultipleActionNotifications(false);
            $('.notifications_checkmark').each(function () { //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
                $(this).closest('tr').addClass("checked_row");
                selectedNotifications.push($(this).val());
            });
        } else {
            enableDisableMultipleActionNotifications(true);
            $('.notifications_checkmark').each(function () { //iterate all listed checkbox items
                $(this).closest('tr').removeClass("checked_row");
                this.checked = status; //change ".checkbox" checked status
            });
            selectedNotifications = [];
        }
        $("#selectedNotifications").val(selectedNotifications);
    });
    /*Check one notification*/
    $('body').on('click', '.notifications_row input', function (e) {
        e.stopPropagation();
        if (this.checked == false) { //if this item is unchecked
            enableDisableMultipleActionNotifications(true);
            $(this).closest('tr').removeClass("checked_row");
            $("#all_notifications_checked").checked = false; //change "select all" checked status to false
        } else {
            $(this).closest('tr').addClass("checked_row");
            enableDisableMultipleActionNotifications(false);
        }

        selectedNotifications = [];
        $('input[type="checkbox"][name="notification_checked"]').each(function () {
            if ($(this).is(':checked')) {
//if checked, Add to selected tickets array
                selectedNotifications.push($(this).val());
            } else {

            }
        });
        $("#selectedNotifications").val(selectedNotifications);
        if (selectedNotifications.length > 0) {
//Show selectbox action
            enableDisableMultipleActionNotifications(false);
        } else {
            enableDisableMultipleActionNotifications(true);
        }
    });
    var enableDisableMultipleActionNotifications = function (disabled) {
        if (disabled)
            $("#multiple_notification_container").hide();
        else
            $("#multiple_notification_container").show();
    }

    $('body').on('submit', '#multiple_action_container', function (e) {
        e.preventDefault();
        if ($("#selected_tickets_select").val() == 77) {
            var confirmed = confirm("هل تريد فعلا حذف الطلبات التي اخترتها؟");
            if (confirmed == false) {
                return;
            }
        }
        $('#overlay').show();
        var url = $("#submitURL").val();
        var redirectURL = $("#hiddenURL").val();
        var that = $(this);
        console.log($(that).serialize());
        if (selectedTickets.length > 0) {
            $.ajax({
                type: "POST",
                url: url,
                data: $(that).serialize(), //selectedTickets.serializeArray(),
                success: function (data)
                {
                    window.location.href = '/home/index/0';
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: url,
                data: $("#add_ticket_form").serialize(),
                success: function (data)
                {
                    if (data) {
                        $("#ajax_container").hide();
                        var that = this;
                        $.ajax({
                            type: "GET",
                            url: redirectURL,
                            success: function (data)
                            {
                                $(".alert_container").show();
                                $("#add_ticket_link").removeClass("bordered-segment");
                                $(".emp_filter_list").removeClass("bordered-segment");
                                $('#overlay').hide();
                                $("#all_tickets_link").addClass("bordered-segment");
                                $("#ajax_container").html(data);
                                $("#ajax_container").fadeIn();
                            }
                        });
                    } else if (data = "confirmation-false") {
                        condole.log(data);
                        alert("حدث خطأ, برجاء المحاولة مرة أخرى");
                    }
                    $('#overlay').hide();
                }
            });
        }

    });

    $('body').on('submit', '#search_form', function (e) {
        e.preventDefault();
        var url = $("#searchURL").val();
        var that = $("#term");
        $('#overlay').show();
        $.ajax({
            type: "GET",
            url: url + "&term=" + $(that).val(),
            success: function (data)
            {
                $(".alert_container").show();
                $('#overlay').hide();
                $("#ajax_container").html(data);
                $("#ajax_container").fadeIn();
                $('#overlay').hide();
                var searchInput = $('#term');
// Multiply by 2 to ensure the cursor always ends up at the end;
// Opera sometimes sees a carriage return as 2 characters.
                var strLength = searchInput.val().length * 2;
                searchInput.focus();
                searchInput[0].setSelectionRange(strLength, strLength);
            }
        });
    });

    /*UPLOAD*/
    var uploaded_files_count = 0;
    var filesArr = "";
    $('body').on('click', '.btn-upload', function (e) {
//        e.preventDefault();
//        var upload_url = $("#upload_url").val();
//
//        var fd = new FormData();
//        var files_data = $('.upload-form .files-data'); // The <input type="file" /> field
//        var formData = new FormData();
//        for (var i = 0, len = document.getElementById('file').files.length; i < len; i++) {
//            formData.append("file" + i, document.getElementById('file').files[i]);
//        }
//
//        formData.append('action', 'cvf_upload_files');
//        formData.append('upload_dir', $('#upload_dir').val());
//        $('#overlay').show();
//        //reset upload counter && empty upload response div
////        uploaded_files_count = 0;
////        $(".upload-response").html("");
//        $.ajax({
//            type: 'POST',
//            url: upload_url,
//            data: formData,
//            contentType: false,
//            processData: false,
//            success: function (data)
//            {
//                var json;
//                try {
//                    json = jQuery.parseJSON(data);
//                } catch (e) {
//                    $('#overlay').hide();
//                    alert("خطأ");
//                }
//                if (json.length > 0) {
//
//                    $.map(json, function (file) {
//                        filesArr += file.path + ",";
//                        $(".upload-response").append("<div><a href='#' class='remove_just_uploaded' data-path='" + file.path + "'><span style='margin-left: 3px; float:left;' class='glyphicon glyphicon-remove-circle'></span></a><div class='uploaded_file_result_container'><a class='uploaded_file_result_link attach_view' style='float:left; ' href='" + file.url + "' target='_blank'><span class='glyphicon glyphicon-paperclip'></span>" + file.file_name + "</a></div></div>");
//                    });
//
//                    $('<input>').attr({
//                        type: 'hidden',
//                        class: 'uploaded_files',
//                        id: 'uploaded_files',
//                        name: 'uploaded_files',
//                        value: filesArr
//                    }).appendTo('#add_ticket_form');
//                    alert("تم رفع " + json.length + " ملف بنجاح");
//                    $("#file").val('');
//                    $('#upload_confirm_btn').attr('disabled', true);
//                    checkTempUploadsFolder();
//                }
//                $('#overlay').hide();
//            },
//            error: function () {
//                $('#overlay').hide();
//                alert("خطأ");
//                checkTempUploadsFolder();
//            }
//        });

    });
    $('body').on('click', '.remove_just_uploaded', function (e) {
        e.preventDefault();
        var that = $(this);
        $('#overlay').show();
        url = $("#delete_upload_url").val() + "?file_path=" + $(this).data("path");
        checkDirURL = $("#check_temp_uploads_dir").val();
        $.ajax({
            type: 'POST',
            url: url,
            success: function (data)
            {
                $(that).parent().remove();
                $("#file").val('');
                $('#upload_confirm_btn').attr('disabled', true);
                checkTempUploadsFolder();
                $('#overlay').hide();
            },
            error: function () {
                $('#overlay').hide();
                checkTempUploadsFolder();
                alert("خطأ");
            }
        });
    });

//    $('body').on('change', '#file', function (e) {
//        var that = $(this);
//        files = that[0]["files"]
//        if (files.length > 0) {
//            $('#upload_confirm_btn').attr('disabled', false);
//        }
//    });
    var checkTempUploadsFolder = function () {
        checkDirURL = $("#check_temp_uploads_dir").val();
        $.ajax({
            type: 'GET',
            url: checkDirURL,
            success: function (data)
            {
                uploaded_files_count = data;
                if (data == 0) {
                    filesArr = "";
                    $(".upload-response").html("");
                }
            }
        });
    }

    $('body').on('click', '.attach_view', function () {
//        e.preventDefault();
        src = $(this).attr("href");
        $.fancybox({
            'width': '100%', // or whatever
            'height': '100%',
            'autoDimensions': true,
            'content': '<embed src="' + src + '#nameddest=self&page=1&view=FitH,0&zoom=80,0,0" height="99%" width="100%" />',
            'onClosed': function () {
                $("#fancybox-inner").empty();
            },
            afterLoad: function () {
                this.title = '<a class="attach_download_btn" href="' + src + '" download>تنزيل</a> ';
            },
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        });
        return false;
    });
});

