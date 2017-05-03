$(document).ready(function() {
    $('.selectpicker').selectpicker();
    if($('#from-date').length != 0)
        $('#from-date').persianDatepicker({
            'format': 'DD MMMM YYYY',
            'minDate': 1491975045000,
            'altField': '#from-date_altField',
            'altFormat': 'X',
            'onShow': function () {
                $('.datepicker-plot-area').width($('#from-date').parent().width());
            },
            'dayPicker': {
                'onSelect': function () {
                    alert(1);
                }
            }
        });
    if($('#to-date').length != 0)
        $('#to-date').persianDatepicker({
            'format': 'DD MMMM YYYY',
            'minDate': 1491975045000,
            'altField': '#to-date_altField',
            'altFormat': 'X',
            'onShow': function () {
                $('.datepicker-plot-area').width($('#to-date').parent().width())
            }
        });

    $('body').on('click', '.anchor-link', function() {
        var target = $($(this).attr('href')).offset().top;

        $('html, body').animate({
            scrollTop: target - 50
        }, 800);
        return false;
    });

    var counterRun = false;
    $(window).scroll(function(){
        if($(window).scrollTop() > ($('.counter-up').offset().top-600) && !counterRun){
            counterRun=true;
            $('.counter-up').each(function () {
                var $this = $(this),
                    countTo = $this.attr('data-count');

                $({countNum: $this.text()}).animate({
                        countNum: countTo
                    },
                    {
                        duration: 3000,
                        easing: 'linear',
                        step: function () {
                            $this.text(toPersianDigit(Math.floor(this.countNum)));
                        },
                        complete: function () {
                            $this.text(toPersianDigit(this.countNum));
                        }
                    });
            });
        }
    });
});

var toPersianDigit = function (digit) {
    var persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        digits = digit.toString().split(''),
        convertedDigit = '';
    for (var i = 0; i < digits.length; i++) {
        if(typeof persianDigits[digits[i]] != 'undefined')
            convertedDigit += persianDigits[digits[i]];
        else
            convertedDigit += '۰';
    }
    return convertedDigit;
};