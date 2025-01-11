"use strict";

var KTDatatablesDataSourceAjaxServer = function () {

    var initTable1 = function () {
        var table = $('#kt_datatable');

        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: DATATABLE_URL,
                type: 'GET',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'id',
                        'fullname',
                        'cell_number',
                        'verified',
                        'actions'
                    ],
                },
            },
            columns: [
                { data: 'id' },
                { data: 'fullname' },
                { data: 'cell_number' },
                { data: 'verified' },
                { data: 'actions', responsivePriority: -1 },
            ],
        });
    };

    return {

        //main function to initiate the module
        init: function () {
            initTable1();
        },

    };

}();

jQuery(document).ready(function () {
    KTDatatablesDataSourceAjaxServer.init();
});
