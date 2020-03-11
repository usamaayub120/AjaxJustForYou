define([
        'jquery',
        'underscore',
        'mage/template',
        'jquery/list-filter'
        ], function (
            $,
            _,
            template
        ) {
            function main(config, element) {
                var $element = $(element);
var YOUR_URL_HERE = config.AjaxUrl;
                $(document).on('click','yourID_Or_Class',function() {
                        var param = 'ajax=1';
                            $.ajax({
                                showLoader: true,
                                url: YOUR_URL_HERE,
                                data: param,
                                type: "POST",
                                dataType: 'json'
                            }).done(function (data) {
                                $('#test').removeClass('hideme');
                                var html = template('#test', {posts:data}); 
                                $('#test').html(html);
                            });
                    });
            };
        return main;
    });