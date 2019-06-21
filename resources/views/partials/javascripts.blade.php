<script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script>
    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    function getCookieOptions() {
      return {
        expires: 0,
        path: '/'
      };
    }

    function setCookie(name, value, options) {
        options = Object.assign(getCookieOptions(), options);

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    }

    function deleteCookie(name) {
        setCookie(name, "", {
            expires: -1
        })
    }

    $orderRows = $('.link_table tr.link_row');
    $orderRows.click(function (e) {
      location.href = $(this).data('url');
    });

    $('.b_back').click(function(e) {
      history.back();
    });
</script>
