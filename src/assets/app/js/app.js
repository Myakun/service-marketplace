$(document).ready(function() {
    initServicePage();
    initOrderPage();
});


function initServicePage() {
    let priceSliderValue = $('#search-partner-price-slider-value-value');
    let priceSlider = $('#search-partner-price-slider-slider input');
    let searchResult = $('#search-partner-result');
    let serviceId = $('#service').data('id');

    priceSlider.on('change', function() {
        priceSliderValue.text(priceSlider.val());
    });

    $('#search-partner-start-search').on('click', function() {
        $(this).addClass('d-none');
        $('#search-partner-price-slider').addClass('d-none');
        $('#search-partner-text').addClass('d-none');

        if (searchResult.hasClass('is-guest')) {
            searchResult.removeClass('d-none');
            return;
        }

        $.get({
            data: {
                price: priceSlider.val(),
                serviceId: serviceId
            },
            url: '/service/search-partners'
        }).done(function(data) {
            searchResult.html(data);
            searchResult.removeClass('d-none');
        });
    });

    $('#search-partner-start-search-guest').on('click', function() {
        let $this = $(this);

        if ($this.hasClass('active')) {
            return;
        }
        $this.addClass('active');

        $.get({
            data: {
                email: $('#search-partner-guest-data-email').val().trim(),
                price: priceSlider.val(),
                serviceId: serviceId
            },
            url: '/service/search-partners'
        }).done(function(data) {
            searchResult.html(data);
        });
    });
}

function initOrderPage() {
    let $order = $('#order');
    $('.select-partner', $order).on('click', function() {
        let $this = $(this);

        let offerId = $this.data('offer-id');
        $('.select-partner', $order).each(function() {
            let $btn = $(this);
            if ($btn.data('offer-id') !== offerId) {
                $btn.addClass('d-none');
            }
        });

        if ($this.hasClass('active')) {
            return;
        }
        $this.addClass('active');

        $.get({
            data: {
                offerId: offerId,
                orderId: $this.data('order-id')
            },
            url: '/order/select-partner'
        }).done(function(data) {
            $order.html($('#order', $(data)).html());
        });
    });
}