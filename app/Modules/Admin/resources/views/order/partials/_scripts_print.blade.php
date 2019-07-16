<script src="{{ asset('js/rsvp-3.1.0.min.js') }}"></script>
<script src="{{ asset('js/sha-256.min.js') }}"></script>
<script src="{{ asset('js/qz-tray.min.js') }}"></script>
<script>
    {{-- For Zebra printer --}}
    var printerName = "ZTC-GK420d";

    {{-- TODO: need to add certificate --}}
    // qz.websocket.connect().then(function() {
    //     qz.printers.find(printerName).then(function(found) {
    //         console.log("Printer: " + found);
    //     });
    // });

    function print(ticketId) {
        var printUrl = "{{ route('tickets.zebraPrint', ':ticketId') }}";
            printUrl = printUrl.replace(':ticketId', ticketId);

        $.get({
            url: printUrl,
            success: function(html) {
                var config = qz.configs.create(printerName, {
                    orientation: 'landscape',
                    rotation: 180,
                    density: 600,
                    margins: {
                        top: 1.3,
                        left: 1
                    }
                });

                var data = [{
                    type: 'html',
                    format: 'plain',
                    data: html
                }];

                qz.print(config, data).catch(function(e) { console.error(e); });
            }
        });
    }
</script>
