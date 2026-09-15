$(document).ready(function() {
    let $body = $('body');

    $body.on('click', '.set-rating', function() {
        let $this = $(this);

        let rating = parseInt(prompt('Enter a rating from 1 to 5'));
        if (isNaN(rating) || rating < 1 || rating > 5) {
            alert('Invalid rating');
            return false;
        }

        let url = '/admin/orders/set-rating?id=' + $this.parents('tr').data('key') + '&rating=' + rating;
        $.get(url, function(response) {
            $this.closest('td').html(response);
        });

        return false;
    });
});