(function ($) {
    'use strict';
    let $body = $('body');
    let xhr = false;

    function sendRequest(url, data) {
        var urlReplace = url;
        if (data) {
            let urlObj = new URL(url);
            if (urlObj.search !== '') {
                urlReplace = url + '&' + data;
            } else {
                urlReplace = url + '?' + data;
            }
            urlReplace = convertParams(urlReplace);
        }

        if (xhr) {
            xhr.abort();
        }
        xhr = $.ajax({
            type: "GET",
            url: urlReplace,
            beforeSend: function () {
                var $project = $('.project-archive-content');
                $project.addClass('preloader');
            },
            success: function (data) {
                let $html = $(data);
                $('#main .project-archive-content').replaceWith($html.find('#main .project-archive-content'));
                window.history.pushState(null, null, urlReplace);
                xhr = false;
                $(document).trigger('hitboox-project-loaded');
                $(document).trigger('path-reload');
            }
        });
    }

    function convertParams(url) {
        const urlObj = new URL(url);
        const params = new URLSearchParams();
        urlObj.searchParams.forEach((value, key) => {
            if (params.has(key)) {
                params.set(key, params.get(key) + ',' + value);
            } else {
                params.set(key, value);
            }
        });

        return `${urlObj.origin}${urlObj.pathname}?${params}`;
    }

    $body.on('click', '.pagination a.page-numbers', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        sendRequest(url);
    });

    $body.on('change ', '.hitboox-project-filter input[type=checkbox]', function (e) {
        if ($(this).is(':checked')) {
            let $parent = $(this).closest('ul');
            if ($(this).val()) {
                $parent.find('input[value=""]').prop('checked', false)
            } else {
                $parent.find('input:not([value=""])').prop('checked', false)
            }
        }
    });


    $($body).on('submit', 'form.hitboox-project-filter', function (event) {
        event.preventDefault();
        $(this).find(':input').filter(function () {
            return !this.value;
        }).attr('disabled', 'disabled');

        $(this).find('select:not([disabled])').each(function () {

            var selectname = $(this).attr('name');

            if ($(this).closest('form').find('select[name="' + selectname + '"]').length > 1) {
                $(this).closest('form').find('select[name="' + selectname + '"]').attr('disabled', 'disabled');
            }
        });

        var url = $(this).attr('action');
        var formData = $(this).serialize();
        sendRequest(url, formData);
    });


})(jQuery);