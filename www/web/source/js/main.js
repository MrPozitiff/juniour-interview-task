function getUserInfoModal(id) {
    $.ajax({
        url: employee_info_api,
        method: 'POST',
        data: {'id': id },
        success: function(data) {
            $('#user_info').html(data).modal('show');
        }
    } );
}

function calculateTotalSalary(id) {
    $.ajax({
        url: salary_calc_api,
        method: 'POST',
        data: {'id': id },
        success: function(data) {
            console.log(data);
            $('#user_total_salary').html(data.total);
            $('#total_salary_calc_at').html(data.calc_at);
        }
    });
}

function calculateTotalSalaryModal(id) {
    $.ajax({
        url: salary_calc_api,
        method: 'POST',
        data: {'id': id },
        success: function(data) {
            getUserInfoModal(id);
        }
    });
}

function setEmployeeDayOff(id) {
    var date = $("#" + id).data("date");
    var hasEvent = $("#" + id).data("hasEvent");
    var input_value = $('input#work_off_days').val();
    var selector_day = "#" + id + "_day";

    if (false === hasEvent) {
        if ($(selector_day).hasClass('badge badge-selected')) {
            input_value.replace('/,' + date + '|' + date + '/gi', '');
            $(selector_day).removeClass('badge badge-selected');
        } else {
            if (!input_value.length) {
                $('input#work_off_days').val(date);
            } else {
                $('input#work_off_days').val(input_value + ',' + date);
            }
            $(selector_day).addClass('badge badge-selected');
        }
    }

}