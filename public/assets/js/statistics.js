(function($) {
    'use strict';

    // تحديث إحصائيات حاوية محددة
    function refreshStats($container) {
        const url = $container.data('stats-url');

        if (!url) {
            console.warn('لم يتم تحديد رابط تحديث الإحصائيات.');
            return;
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            cache: false,

            success: function(response) {
                const stats = response.stats || response;

                Object.keys(stats).forEach(function(key) {
                    const $value = $container.find(
                        '[data-stat-key="' + key + '"]'
                    );

                    if ($value.length) {
                        $value.text(stats[key]);
                    }
                });

                $container.trigger('stats:updated', [stats]);
            },

            error: function(xhr) {
                console.error(
                    'تعذر تحديث الإحصائيات.',
                    xhr.responseText
                );
            }
        });
    }

    // تحديث حاوية واحدة أو جميع حاويات الإحصائيات
    $(document).on('stats:refresh', function(event, selector) {
        const $containers = selector ?
            $(selector).filter('[data-stats-url]') :
            $('[data-stats-url]');

        $containers.each(function() {
            refreshStats($(this));
        });
    });

    // إتاحة الدالة للصفحات التي تحتاج إلى تحديث حاوية بعينها
    window.StatsComponent = {
        refresh: function(selector) {
            $(document).trigger('stats:refresh', [selector]);
        }
    };

})(jQuery);