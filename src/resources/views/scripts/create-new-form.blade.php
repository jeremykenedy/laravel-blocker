<script>
    $(function() {
        var blockedType = $('#typeId');
        var blockedValue = $('#value');
        var blockerValueLabel1 = $('#blockerValueLabel1');
        var blockerValueLabel2 = $('#blockerValueLabel2');
        var blockedUser = $('#userId');
        var blockerUserLabel1 = $('#blockerUserLabel1');
        var blockerUserLabel2 = $('#blockerUserLabel2');

        var disabledClass = 'disabled';

        blockedType.on('change', function() {
            var type = $(this).find(':selected').data('type');

            blockedValue.removeClass(disabledClass).val('');
            blockerValueLabel1.removeClass(disabledClass);
            blockerValueLabel2.removeClass(disabledClass);

            blockedUser.addClass(disabledClass).val('');
            blockerUserLabel1.addClass(disabledClass);
            blockerUserLabel2.addClass(disabledClass);

            if (type == 'username') {
                blockedValue.addClass(disabledClass).val('');
                blockerValueLabel1.addClass(disabledClass);
                blockerValueLabel2.addClass(disabledClass);

                blockedUser.removeClass(disabledClass);
                blockerUserLabel1.removeClass(disabledClass);
                blockerUserLabel2.removeClass(disabledClass);
            };
        });

        blockedUser.on('change', function() {
            var email = $(this).find(':selected').data('email');
            blockedValue.val(email);
        });

    });
</script>
