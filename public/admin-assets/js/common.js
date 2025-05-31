(function () {

    // $('.testSelAll2').SumoSelect({
    //     selectAll: true
    // });

    // $(".chzn-select").chosen({
    //     disable_search: true
    // });
    $(".chzn-select").chosen();

    $(".chzn-select-with-search").chosen();
    $('.chosen-multi').chosen();

    $('[data-toggle="tooltip"]').tooltip();

    $('.js-dtpicker-start-today').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '+0d'
    })

    $('.js-datepicker').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
    })

    $('.js-datetimepicker').datetimepicker({
        'format': 'DD-MM-YYYY'
    });

    $('.js-datepicker-max-yesterday').datetimepicker({
        'format': 'DD-MM-YYYY',
        maxDate: $.now()
    });

    $('.js-datepicker-start-todate').datetimepicker({
        'format': 'DD-MM-YYYY',
        minDate: $.now()
    });

    $('.js-domicile-issue-date-max').datetimepicker({
        'format': 'DD-MM-YYYY',
        minDate: '2000-11-09'
    });

    $('.from__date,.to__date').datetimepicker({
        format: 'DD-MM-YYYY',
        useCurrent: false,
        minDate: moment()
    });
    $('.from__date').datetimepicker().on('dp.change', function (e) {
        var incrementDay = moment(new Date(e.date));
        incrementDay.add(1, 'days');
        $('.to__date').data('DateTimePicker').minDate(incrementDay);
        $(this).data("DateTimePicker").hide();
    });

    $('.to__date').datetimepicker().on('dp.change', function (e) {
        var decrementDay = moment(new Date(e.date));
        decrementDay.subtract(1, 'days');
        $('.from__date').data('DateTimePicker').maxDate(decrementDay);
        $(this).data("DateTimePicker").hide();
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-green'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            color: '#ffffff',
            secondaryColor: '#dedede',
            jackColor: '#2ec4b6'
        });
    });

    $('.js-openMenu').on('click', function () {
        $('.c-f-navigation__wrapper-mainMenu').toggleClass('active');
    });

    $('.js-menuClose').on('click', function () {
        $('.c-f-navigation__wrapper-mainMenu').removeClass('active');
    });

    function sideBarFunction() {
        var sideBarMenu = $('.c-sideBar__navigation__item.js-sidebarItem');
        $(sideBarMenu).on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if ($(window).width() > 992) {
                if ($('body').hasClass('sub-sidebar sub-sidebar-md')) {
                    return;
                }
            }
            if ($(this).parent('.js-sidebarList').hasClass('selected')) {
                $(this).next('.sub-dropdown').slideUp();
                $(this).parent('.js-sidebarList').removeClass('selected');
            } else {
                sideBarMenu.next('.sub-dropdown').slideUp();
                sideBarMenu.parent('.js-sidebarList').removeClass('selected');
                $(this).next('.sub-dropdown').slideToggle();
                $(this).parent('.js-sidebarList').addClass('selected');
            };
        });
    };

    sideBarFunction();

})(jQuery);