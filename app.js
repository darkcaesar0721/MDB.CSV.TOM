const App = function() {
    let current_date = '';

    const setDefaultValue = function() {
        getJSONData();

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
                trc_path.value = resp.trc_path === undefined ? '' : resp.trc_path;
                trc_previous_path.value = resp.trc_previous_path === undefined ? '' : resp.trc_previous_path;
                download_time.value = resp.download_time === undefined ? '' : resp.download_time;
                folder_name.value = resp.folder_name === undefined ? '' : resp.folder_name;

                setCurrentDate();
                setFolderName();
                changeDownloadFileInfo();
            },
            error: function(){}
        });
    }

    const initAttachEvent = function() {
        mdb_path.oninput = saveJSON;
        count_xls_path.oninput = saveJSON;
        csv_path.oninput = saveJSON;
        csv_previous_path.oninput = saveJSON;
        xls_path.oninput = saveJSON;
        xls_previous_path.oninput = saveJSON;
        trc_path.oninput = saveJSON;
        trc_previous_path.oninput = saveJSON;

        folder_name.oninput = handleFolderNameChange;
        download_time.onchange = handleDownloadTimeChange;
        download_file_type.onchange = handleFileTypeChange;

        btn_download.onclick = download;

        shai1.onclick = handleShai1Click;
        shai2.onclick = handleShai2Click;
        palm1.onclick = handlePalm1Click;
    }

    const handleShai1Click = function() {
        if (validationShai1()) {
            $('body').block({ message: 'wait for sending email' });

            $.ajax({
                url:'mail.php',
                type:'post',
                data: {
                    action: 'shai1',
                    path: csv_previous_path.value,
                    folder_name: csv_previous_path.value.split("\\")[csv_previous_path.value.split("\\").length - 1],
                },
                dataType: 'JSON',
                success: function(resp){
                    if (resp.status === 'success') {
                        toastr.success('email send success.');
                        $('body').unblock();
                    } else {
                        toastr.success('email send error.');
                        $('body').unblock();
                    }
                },
                error: function(){}
            });
        }
    }

    const validationShai1 = function() {
        if (!csv_previous_path.value) {
            csv_previous_path.focus();
            toastr.warning('Please input CSV previous download folder path.');
            return false;
        }

        return true;
    }

    const handleShai2Click = function() {
        if (validationShai2()) {
            $('body').block({ message: 'wait for sending email' });

            $.ajax({
                url:'mail.php',
                type:'post',
                data: {
                    action: 'shai2',
                    path: csv_previous_path.value,
                    folder_name: csv_previous_path.value.split("\\")[csv_previous_path.value.split("\\").length - 1],
                },
                dataType: 'JSON',
                success: function(resp){
                    if (resp.status === 'success') {
                        toastr.success('email send success.');
                        $('body').unblock();
                    } else {
                        toastr.success('email send error.');
                        $('body').unblock();
                    }
                },
                error: function(){}
            });
        }
    }

    const validationShai2 = function() {
        if (!csv_previous_path.value) {
            csv_previous_path.focus();
            toastr.warning('Please input CSV previous download folder path.');
            return false;
        }

        return true;
    }
    
    const handlePalm1Click = function() {
        if (validationPalm1()) {
            $('body').block({ message: 'wait for sending email' });

            $.ajax({
                url:'mail.php',
                type:'post',
                data: {
                    action: 'palm1',
                    path: xls_previous_path.value,
                    folder_name: xls_previous_path.value.split("\\")[xls_previous_path.value.split("\\").length - 1],
                },
                dataType: 'JSON',
                success: function(resp){
                    if (resp.status === 'success') {
                        toastr.success('email send success.');
                        $('body').unblock();
                    } else {
                        toastr.success('email send error.');
                        $('body').unblock();
                    }
                },
                error: function(){}
            });
        }
    }

    const validationPalm1 = function() {
        if (!xls_previous_path.value) {
            xls_previous_path.focus();
            toastr.warning('Please input XLS previous download folder path.');
            return false;
        }
        return true;
    }

    const handleFolderNameChange = function() {
        setCurrentDate();
        changeDownloadFileInfo();
        saveJSON();
    }

    const handleDownloadTimeChange = function() {
        setFolderName();
        changeDownloadFileInfo();
        saveJSON();
    }

    const setCurrentDate = function() {
        if (!folder_name.value) {
            current_date = new Date()
            let pstDate = current_date.toLocaleString("en-US", {
                timeZone: "America/Los_Angeles"
            });
            current_date = pstDate.split(', ')[0];
        } else {
            const month = folder_name.value.split(' ')[0].substr(0, 2) * 1;
            const day = folder_name.value.split(' ')[0].substr(2, 2) * 1;
            const year = folder_name.value.split(' ')[0].substr(4, 4) * 1;
            current_date = month + '/' + day + '/' + year;
        }
    }

    const saveJSON = function() {
        const directory = {
            mdb_path: mdb_path.value,
            count_xls_path: count_xls_path.value,
            csv_path: csv_path.value,
            csv_previous_path : csv_previous_path.value,
            xls_path: xls_path.value,
            xls_previous_path: xls_previous_path.value,
            trc_path: trc_path.value,
            trc_previous_path: trc_previous_path.value,
            download_time : download_time.value,
            folder_name : folder_name.value,
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

    const setFolderName = function() {
        let str_month = current_date.split('/')[0];
        if (str_month < 10) str_month = '0' + str_month;

        let str_day = current_date.split('/')[1];
        if (str_day < 10) str_day = '0' + str_day;

        const str_year = current_date.split('/')[2];

        folder_name.value = str_month + str_day + str_year + ' ' + download_time.value;
        saveJSON();
    }

    const changeDownloadFileInfo = function() {
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
            $('.csv-file-' + i).html(csv_files[i].first + folder_name.value + csv_files[i].last);
        }

        $('#excel_file_name').html(folder_name.value + '_PALM');
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
            case 'trc':
                $('#csv_group').removeClass('select-group');
                $('#xls_group').removeClass('select-group');
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
                    time: download_time.value,
                    file_type: download_file_type.value,
                    mdb_path: mdb_path.value,
                    count_xls_path: count_xls_path.value,
                    csv_path: csv_path.value,
                    csv_previous_path : csv_previous_path.value,
                    xls_path: xls_path.value,
                    xls_previous_path: xls_previous_path.value,
                    trc_path: trc_path.value,
                    trc_previous_path: trc_previous_path.value,
                    folder: folder_name.value,
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
                        trc_previous_path.value = resp.trc_previous_path;

                        if (download_time.value === '2PM') {
                            let date = new Date(current_date);
                            date.setDate(date.getDate() + 1);
                            let pstDate = date.toLocaleString();
                            current_date = pstDate.split(', ')[0];
                        }

                        download_time.value = download_time.value === '2PM' ? '8AM' : '2PM';
                        handleDownloadTimeChange();

                        saveJSON();

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
            toastr.warning('Please input CSV download folder path.');
            return false;
        }

        if (!csv_previous_path.value) {
            csv_previous_path.focus();
            toastr.warning('Please input CSV previous download folder path.');
            return false;
        }

        if (!xls_path.value) {
            xls_path.focus();
            toastr.warning('Please input XLS download folder path.');
            return false;
        }

        if (!xls_previous_path.value) {
            xls_previous_path.focus();
            toastr.warning('Please input XLS previous download folder path.');
            return false;
        }

        if (!trc_path.value) {
            trc_path.focus();
            toastr.warning('Please input TRC download folder path.');
            return false;
        }

        if (!trc_previous_path.value) {
            trc_previous_path.focus();
            toastr.warning('Please input TRC previous download folder path.');
            return false;
        }

        if (!folder_name.value) {
            folder_name.focus();
            toastr.warning('Please input folder name.');
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