"use strict";

jQuery(document).ready(function () {
    var calendar = null;
    var events = [];

    $(document).ready(function () {

        var todayDate = moment().startOf('day');
        var TODAY = todayDate.format('YYYY-MM-DD');

        var calendarEl = document.getElementById('kt_calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [
                'interaction',
                'dayGrid',
                'timeGrid',
                'list'
            ],

            // isRTL: KTUtil.isRTL(),
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },

            // height: 800,
            // contentHeight: 750,
            // aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

            views: {
                dayGridMonth: { buttonText: 'month' },
                timeGridWeek: { buttonText: 'week' },
                timeGridDay: { buttonText: 'day' },
                listDay: { buttonText: 'list' },
                listWeek: { buttonText: 'list' }
            },

            defaultView: 'listWeek',
            defaultDate: TODAY,

            // editable: true,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,
            events: events,
            events: function (fetchInfo, successCallback, failureCallback) {
                blockPage();

                var startDate = new Date(fetchInfo.startStr);
                var endDate = new Date(fetchInfo.endStr);
                var branch_id = $('[name="branch_id"]').val();

                startDate = startDate.toISOString();
                endDate = endDate.toISOString();

                $.ajax({
                    type: 'GET',
                    url: APPOINTMENTS_AJAX_URL + '?start_date=' + startDate + '&end_date=' + endDate + '&branch_id=' + branch_id,
                    success: function (data) {

                        successCallback(data.appointments ?? []);
                        unblockPage();
                    },
                    error: function (error) {
                        unblockPage()

                        showErrorAlert(error.responseJSON.message, () => {
                            KTUtil.scrollTop();
                        });
                    }
                });
            },
            eventClick: function(eventClickInfo) {
                window.location.href = eventClickInfo.event.extendedProps.reports_route;
            },
            eventRender: function (info) {

                var _html = info.event.extendedProps.options_html ?? '';
                var element = $(info.el);
                element.find('.fc-list-item-title')
                    .html(info.event.extendedProps.titleClickable)
                    .append(_html);
            }
        });

        calendar.render();

        $(document).on('click', ".fc-list-item-title a[target='_blank']", function () {
            event.stopPropagation();
            event.preventDefault();

            window.open($(this).data('href'));
        });

        $(document).on('click', ".appointment-delete", function () {
            event.stopPropagation();
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $(this).closest('form').submit();
                }
            });
        });

        $(document).on('change', '[name="branch_id"]', function () {
            calendar.refetchEvents();
        });

        // $('[name="branch_id"]').trigger('change');
    });
});
