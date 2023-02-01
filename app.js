const App = function() {
    const setDefaultValue = function() {
        $('input[name=mdb_path]').val(window.localStorage.getItem('mdb_path'));
        $('input[name=csv_path]').val(window.localStorage.getItem('csv_path'));
        $('input[name=xls_path]').val(window.localStorage.getItem('xls_path'));
    }

    const initAttachEvent = function() {
        $('input[name=mdb_path]').change(function(e) {
            window.localStorage.setItem('mdb_path', e.target.value);
        });

        $('input[name=csv_path]').change(function(e) {
            window.localStorage.setItem('csv_path', e.target.value);
        });

        $('input[name=xls_path]').change(function(e) {
            window.localStorage.setItem('xls_path', e.target.value);
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