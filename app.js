const App = function() {
    let myDate = new Date()
    let pstDate = myDate.toLocaleString("en-US", {
        timeZone: "America/Los_Angeles"
    });
    let current_date = pstDate.split(', ')[0];
    let current_date_str = '';

    const setDefaultValue = function() {
        getJSONData();

        handleTimeChange('8AM');

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

    const getJSONData = function() {
        $.ajax({
            url:'api.php',
            type:'post',
            data: {action: 'read_json'},
            dataType: 'JSON',
            success: function(resp){
                mdb_path.value = resp.mdb_path === undefined ? '' : resp.mdb_path;
                count_xls_path.value = resp.count_xls_path === undefined ? '' : resp.count_xls_path;
                csv_path.value = resp.csv_path === undefined ? '' : resp.csv_path;
                csv_previous_path.value = resp.csv_previous_path === undefined ? '' : resp.csv_previous_path;
                xls_path.value = resp.xls_path === undefined ? '' : resp.xls_path;
                xls_previous_path.value = resp.xls_previous_path === undefined ? '' : resp.xls_previous_path;
            },
            error: function(){}
        });
    }

    const initAttachEvent = function() {
        mdb_path.oninput = handleDirectoryChange;
        count_xls_path.oninput = handleDirectoryChange;
        csv_path.oninput = handleDirectoryChange;
        csv_previous_path.oninput = handleDirectoryChange;
        xls_path.oninput = handleDirectoryChange;
        xls_previous_path.oninput = handleDirectoryChange;

        download_time.onchange = handleTimeChange;
        download_file_type.onchange = handleFileTypeChange;

        btn_download.onclick = download;
    }

    const handleDirectoryChange = function() {
        const directory = {
            mdb_path: mdb_path.value,
            count_xls_path: count_xls_path.value,
            csv_path: csv_path.value,
            csv_previous_path : csv_previous_path.value,
            xls_path: xls_path.value,
            xls_previous_path: xls_previous_path.value,
        };

        $.ajax({
            url:'api.php',
            type:'post',
            data: {
                directory: JSON.stringify(directory),
                action: 'write_json'
            },
            dataType: 'JSON',
            success: function(resp){},
            error: function(){}
        });
    }

    const handleTimeChange = function() {
        let str_month = current_date.split('/')[0];
        if (str_month < 10) str_month = '0' + str_month;

        let str_day = current_date.split('/')[1];
        if (str_day < 10) str_day = '0' + str_day;

        current_date_str = str_month + str_day + current_date.split('/')[2];

        $('#folder_name').html(current_date_str + ' ' + download_time.value);

        const csv_files = {
            0: { first: '00_ALL_', last: '_CA Window Door'},
            1: { first: '01_ALL_', last: '_KitchenBathDecksRenovate'},
            2: { first: '02_LA_', last: '_CA Window Door'},
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
            $('.csv-file-' + i).html(csv_files[i].first + current_date_str + ' ' + download_time.value + csv_files[i].last);
        }

        $('#excel_file_name').html(current_date_str + ' ' + download_time.value + '_PALM');
    }

    const handleFileTypeChange = function() {
        switch(download_file_type.value) {
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
        if (validation()) {
            $('body').block({ message: 'wait for downloading file' });

            $.ajax({
                url:'api.php',
                type:'post',
                data: {
                    action: 'download',
                    date: current_date,
                    date_str: current_date_str,
                    time: download_time.value,
                    file_type: download_file_type.value,
                    mdb_path: mdb_path.value,
                    count_xls_path: count_xls_path.value,
                    csv_path: csv_path.value,
                    csv_previous_path : csv_previous_path.value,
                    xls_path: xls_path.value,
                    xls_previous_path: xls_previous_path.value,
                    folder: $('#folder_name').html(),
                },
                dataType: 'JSON',
                success: function(resp){
                    $('body').unblock();

                    if (resp.status === 'error') {
                        toastr.error(resp.description);
                    } else if (resp.status === 'warning') {
                        toastr.warning(resp.description);
                    } else {
                        csv_previous_path.value = resp.csv_previous_path;
                        xls_previous_path.value = resp.xls_previous_path;

                        handleDirectoryChange();

                        toastr.success('download success');
                    }
                },
                error: function(){
                }
            });
        }
    }

    const validation = function() {
        if (!mdb_path.value) {
            mdb_path.focus();
            toastr.warning('Please input mdb file path.');
            return false;
        }

        if (!count_xls_path.value) {
            count_xls_path.focus();
            toastr.warning('Please input count excel file path.');
            return false;
        }

        if (!csv_path.value) {
            csv_path.focus();
            toastr.warning('Please input csv download path.');
            return false;
        }

        if (!csv_previous_path.value) {
            csv_previous_path.focus();
            toastr.warning('Please input csv previous download path.');
            return false;
        }

        if (!xls_path.value) {
            xls_path.focus();
            toastr.warning('Please input xls download path.');
            return false;
        }

        if (!xls_previous_path.value) {
            xls_previous_path.focus();
            toastr.warning('Please input xls previous download path.');
            return false;
        }

        return true;
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