<meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/css/materialize.css" rel="stylesheet">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="/files/js/materialize.min.js"></script>
    <script src="/files/js/analytics.js"></script>
    <script src="/files/js/topNav.js"></script>
    <script src="/files/js/jquery.form.min.js"></script>

    <script>
        // For footer social links scrollfire, #social-links
        var options=[{
            selector: '#social-links',
            offset: 0,
            callback: function(el) {
                Materialize.showStaggeredList($(el));
            }
        }];
        Materialize.scrollFire(options);

        // For hamburger menu
        $(function() {
            // Fix for nav drag area height when collapsible opens
            $("#university").click(function() {
                setTimeout(function() {
                    $(".drag-target").css({top: $("header").height() + $("#slidey-nav").height() +
                    "px"});
                }, 350); // from collapsible.js
            });

            $(".button-collapse").topNav();

            // Contact us modal form
            $('.contact-us').leanModal({
                complete: clearContactForm,
                ready: function() {
                    $("body").toggleClass("no-scroll");
                }
            });

            $("#contact-us").click(function() {
                $('.button-collapse').sideNav('hide');
            });

            $("#contact-form").ajaxForm({
                beforeSubmit: function() {
                    $("#contact").closeModal();
                },
                success: function() {
                    clearContactForm();
                    Materialize.toast("Message sent!", 4000);
                },
                error: function() {
                    $("body").toggleClass("no-scroll");
                }
            });

            // Error modal
            $("#nope").click(clearContactForm);
            $("#sure").click(function() {
               $("#contact").openModal();
            });

            function clearContactForm() {
                $("#contact-form").resetForm();
                Materialize.updateTextFields();
                $("#contact-form").find("textarea").trigger('autoresize');
                $("body").toggleClass("no-scroll");
            }
        });
    </script>


    <?php readfile($_SERVER['DOCUMENT_ROOT'] . "/files/favicons.html") ?>

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->