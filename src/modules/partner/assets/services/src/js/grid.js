$(document).ready(function() {
    let $body = $('body');

    $body.on('click', '.delete-price', function() {
        let $this = $(this);
        let name = $('.name', $this.closest('tr')).text();

        if (!confirm('Confirm that you no longer provide the service ' + name)) {
            return;
        }

        let url = '/partner/services/delete-price?id=' + $this.parents('tr').data('key');
        $.get(url, function(response) {
            $this.closest('td').html(response);
        });

        return false;
    });

    $body.on('click', '.set-price', function() {
        let price = parseInt(prompt('Enter the price'));
        if (isNaN(price) || price <= 0) {
            alert('Invalid price');
            return;
        }

        let $this = $(this);

        let url = '/partner/services/set-price?id=' + $this.parents('tr').data('key') + '&price=' + price;
        $.get(url, function(response) {
            $this.closest('td').html(response);
        });

        return false;
    });
});