<?php defined('SHIELDON_VIEW') || exit('Life is short, why are you wasting time?');
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<script>

    $(function() {

        var checkToggleStatus = function() {
            $('.toggle-block').each(function() {
                var target = $(this).attr('data-target');

                if (this.checked) {
                    $('[data-parent="' + target + '"]').fadeIn(500);
                } else {
                    $('[data-parent="' + target + '"]').hide();
                }
            });
        };

        var dataDriverFiles = function() {
            let value = $('input[name="driver_type"]:checked').val();
            $('.data-driver-options').hide();
            $('.data-driver-options-' + value).fadeIn(500);
        };

        $('.data-driver-options').hide();

        checkToggleStatus();
        dataDriverFiles();

        $('.toggle-block').change(function() {
            checkToggleStatus();
        });

        $('input[name="driver_type"]').change(function() {
            dataDriverFiles();
        });

        $('input[name="tabs"]').change(function() {
            $('input[name="tab"]').val($(this).val());
        });

        $('#iptables-watch-folder').html($('input[name="iptables__config__watching_folder"]').val());

        $('input[name="iptables__config__watching_folder"]').keyup(function() {
            $('#iptables-watch-folder').html($(this).val());
            $('#code2').val($('#code1').text());
        });

        $('#code2').val($('#code1').text());

        // Keep tabl position after refreshing page.
        var hash = window.location.hash;

        $('input:radio[name="tabs"]').on('change', function() {
            window.location.hash = $(this).val();
            $('form').attr('action', window.location.hash);
        });

        if (window.location.hash !== '') {
            $('input:radio[name="tabs"]').each(function() {
                if (hash === $(this).val()) {
                    $(this).attr('checked', 'checked');
                } else {
                    $(this).attr('checked', '');
                }
            });
        }
    });

</script>