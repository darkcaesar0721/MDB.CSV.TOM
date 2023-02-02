const App = function() {
    let current_date = '';
    let current_date_str = '';
    let current_time = '8AM';
    let current_file_type = 'csv_xls';
    let current_mdb_path = window.localStorage.getItem('mdb_path');
    let current_csv_path = window.localStorage.getItem('csv_path');
    let current_csv_previous_path = window.localStorage.getItem('csv_previous_path');
    let current_xls_path = window.localStorage.getItem('xls_path');
    let current_xls_previous_path = window.localStorage.getItem('xls_previous_path');

    const setDefaultValue = function() {

        $('input[name=mdb_path]').val(current_mdb_path);
        $('input[name=csv_path]').val(current_csv_path);
        $('input[name=csv_previous_path]').val(current_csv_previous_path);
        $('input[name=xls_path]').val(current_xls_path);
        $('input[name=xls_previous_path]').val(current_xls_previous_path);

        handleTimeChange(current_time);

        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': false,
            'progressBar': false,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
    }

    const initAttachEvent = function() {
        $('input[name=mdb_path]').change(function(e) {
            current_mdb_path = e.target.value;
            window.localStorage.setItem('mdb_path', e.target.value);
        });

        $('input[name=csv_path]').change(function(e) {
            current_csv_path = e.target.value;
            window.localStorage.setItem('csv_path', e.target.value);
        });

        $('input[name=csv_previous_path]').change(function(e) {
            current_csv_previous_path = e.target.value;
            window.localStorage.setItem('csv_previous_path', e.target.value);
        });

        $('input[name=xls_path]').change(function(e) {
            current_xls_path = e.target.value;
            window.localStorage.setItem('xls_path', e.target.value);
        });

        $('input[name=xls_previous_path]').change(function(e) {
            current_xls_previous_path = e.target.value;
            window.localStorage.setItem('xls_previous_path', e.target.value);
        });

        $('select[name=download_time]').change(function(e) {
            current_time = e.target.value;
            handleTimeChange(current_time);
        });

        $('select[name=download_file_type]').change(function(e) {
            current_file_type = e.target.value;
            handleFileTypeChange(current_file_type)
        });

        $('#btn_download').click(download);
    }

    const handleTimeChange = function(time) {
        let myDate = new Date()
        let pstDate = myDate.toLocaleString("en-US", {
            timeZone: "America/Los_Angeles"
        })

        let date = new Date(pstDate);
        current_date = date;

        let str_month = date.getMonth() + 1;
        if (date.getMonth() + 1 < 10) str_month = '0' + str_month;

        let str_day = date.getDate();
        if (date.getDate() < 10) str_day = '0' + str_day;

        current_date_str = str_month + str_day + date.getFullYear();

        $('#folder_name').html(current_date_str + ' ' + time);

        const csv_files = {
            0: { first: '00_ALL_', last: '_CA Window Door shaisak@yahoo.com'},
            1: { first: '01_ALL_', last: '_KitchenBathDecksRenovate'},
            2: { first: '02_LA_', last: '_CA Window Door shaisak@yahoo.com'},
            3: { first: '03_SD_', last: ''},
            4: { first: '04_WA_', last: ''},
            5: { first: '05_BAY_', last: ' South'},
            6: { first: '06_BAY_', last: ' North'},
            7: { first: '07_OR_', last: ''},
            8: { first: '08_TX_Austin_', last: ''},
            9: { first: '09_TX_Houston_', last: ''},
            10: { first: '10_TX_Dallas_', last: ''},
        }
        for (let i = 0; i <= 10; i++) {
            $('.csv-file-' + i).html(csv_files[i].first + current_date_str + ' ' + time + csv_files[i].last);
        }

        $('#excel_file_name').html(current_date_str + ' ' + time + '_PALM');
    }

    const handleFileTypeChange = function(file_type) {
        switch(file_type) {
            case 'csv':
                $('#csv_group').addClass('select-group');
                $('#xls_group').removeClass('select-group');
                break;
            case 'xls':
                $('#csv_group').removeClass('select-group');
                $('#xls_group').addClass('select-group');
                break;
            default:
                $('#csv_group').addClass('select-group');
                $('#xls_group').addClass('select-group');
                break;
        }
    };

    const download = function() {
        // $('body').block({ message: 'wait for downloading file' });

        $.ajax({
            url:'api.php',
            type:'post',
            data: {
                date: current_date,
                date_str: current_date_str,
                time: current_time,
                file_type: current_file_type,
                mdb_path: current_mdb_path,
                csv_path: current_csv_path,
                csv_previous_path : current_csv_previous_path,
                xls_path: current_xls_path,
                xls_previous_path: current_xls_previous_path,
                folder: $('#folder_name').html(),
            },
            dataType: 'JSON',
            success: function(resp){
                // $('body').unblock();

                if (resp.status === 'error') {
                    toastr.error(resp.description);
                } else if (resp.status === 'warning') {
                    toastr.warn(resp.description);
                } else {
                    toastr.success('download success');
                }
            },
            error: function(){
            }
        });
    }

    return {
        init: function() {
            setDefaultValue();
            initAttachEvent();
        }
    }
}();

$(document).ready(function() {
    App.init();
});