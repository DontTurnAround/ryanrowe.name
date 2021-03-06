<?php session_start() ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Honors Portfolio</title>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/files/common.php") ?>
    <link href="/css/honors.css" rel="stylesheet" type="text/css">

    <script>
        $(function() {
            var first = true;
            var loading = false;
            var maxId = -1;
            var done = false;

            checkEndlessScroll("endless-scroll");

            function load(year, id) {
                loading = true;
                $("#loading").slideDown();

                var data = {};
                if (year !== "" && year !== "all") {
                    data.year = year
                }
                if (id > -1) {
                    data.id = id;
                }
                $.ajax({
                    type: "POST",
                    url: "./load.php",
                    data: data,
                    // serializes the form's elements.
                    dataType: "json",
                    success: appendCourses,
                    error: function(jqXHR) {
                        if(jqXHR.status == 404) {
                            noMore();
                        } else {
                            Materialize.toast($('<span class="valign-wrapper"><i class="material-icons red-text left">error_outline</i>Error loading posts</span>'), 4000);
                        }
                        loading = false;
                        $("#loading").slideUp();
                    }
                });
            }

            function noMore() {
                if (!$("#courses").children().not(".template").length) {
                    $('<li style="display: none"><div class="collapsible-header grey darken-1"><span class="flow-text white-text">There are no posts for this year.</span></div></li>')
                        .appendTo($("#courses")).slideDown();
                } else {
                    Materialize.toast(
                        $('<span class="valign-wrapper"><i class="material-icons blue-text left">info_outline</i>No more posts for this year</span>'),
                        4000
                    );
                }
                done = true;
            }

            String.prototype.capitalize = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            };

            function gradeToYear(grade) {
                switch (grade) {
                    case "senior":
                        return "2018 - 2019";
                    case "junior":
                        return "2017 - 2018";
                    case "sophomore":
                        return "2016 - 2017";
                    default:
                        return "2015 - 2016";
                }
            }

            function appendCourses(json) {
                var $template = $("#courses").find(".template");
                for (var i in json) {
                    var post = json[i];
                    // Clone template
                    var $newCourse = $template.clone();
                    // Set course name
                    $newCourse.find(".collapsible-header h5").text(post.title);
                    $newCourse.find(".collapsible-header h6.quarter").text(post.quarter.capitalize() + " " + gradeToYear(post.year));
                    $newCourse.find(".collapsible-header h6.course").text(post.course);
                    // Auto-open if first
                    if (first) {
                        $newCourse.find(".collapsible-header").addClass("active");
                        first = false;
                    }

                    var $subpostTemplate = $newCourse.find(".subpost").remove();
                    for (var j in post.posts) {
                        var subpost = post.posts[j];
                        var $newSubpost = $subpostTemplate.clone();

                        if (post.posts.length == 1 || j == 0 && subpost.title === "") {
                            $newSubpost.find(".subtitle a").remove();
                            var $anchor = $newCourse.find(".collapsible-header a");
                        } else {
                            if ($newCourse.find(".collapsible-header a").length && !$newCourse.find("" +
                                    ".collapsible-header a")[0].hasAttribute("href")) {
                                $newCourse.find(".collapsible-header a").remove();
                            }
                            var $anchor = $newSubpost.find(".subtitle a");
                        }

                        if (subpost.button === "") {
                            $anchor.remove();
                        } else {
                            $anchor.find("span").text(subpost.button);
                            $anchor.attr("href", subpost.url);
                        }
                        $newSubpost.find("h5").html(subpost.title);
                        $newSubpost.append(subpost.description);
                        $newCourse.find(".subposts").append($newSubpost);
                    }

                    $newCourse.removeClass("template");
                    $newCourse.appendTo($("#courses"));
                    maxId = Math.max(maxId, post.id);
                }

                if (json.length < 4) {
                    noMore();
                }

                // Redinit collapsible
                $('.collapsible').collapsible();
                $('.materialboxed').materialbox();
                loading = false;
                $("#loading").slideUp();
            }

            $(window).scroll(function () {
                checkEndlessScroll("endless-scroll");
            });

            function checkEndlessScroll(el) {
                if (isInView(document.getElementById(el))) {
                    if(!loading && !done) {
                        console.log("loading past id: " + maxId);
                        load(window.location.hash.substring(1), maxId);
                    }
                }
            }

            function isInView(el) {
                var elemTop = el.getBoundingClientRect().top;
                var elemBottom = el.getBoundingClientRect().bottom;

                var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
                return isVisible;
            }

            // We need the University links to trigger a page reload here
            $("#university").parent().find("li a").click(function() {
                var url = $(this).attr("href");
                $('.button-collapse').topNav('hide');
                $('#year-tabs').tabs('select_tab', url.substring(url.indexOf("#") + 1));
            });
            $('#year-tabs').tabs({
                onShow: tabSwitch
            });

            function tabSwitch(context) {
                first = true;
                done = false;
                maxId = -1;
                $("#courses").children().not(".template").slideUp(function() {
                    $(this).remove();
                });
                setTimeout(function() {
                    window.location.hash = context.selector;
                    load(context.selector.substring(1));
                }, 400);
            }
        });
    </script>
</head>

<body>
<?php readfile($_SERVER['DOCUMENT_ROOT'] . "/files/header.html") ?>
<main>
    <div class="container">
        <ul class="tabs" id="year-tabs">
            <li class="tab"><a class="active" href="">All</a></li>
            <li class="tab"><a href="#freshman">Fresh<span class="hide-on-small-and-down">man</span></a></li>
            <li class="tab"><a href="#sophomore">Soph<span class="hide-on-small-and-down">omore</span></a></li>
            <li class="tab"><a href="#junior">Junior</a></li>
            <li class="tab"><a href="#senior">Senior</a></li>
        </ul>
        <ul class="collapsible" id="courses" data-collapsible="accordion">
            <li class="template">
                <div class="collapsible-header">
                    <div class="row">
                        <h5 class="col s9"></h5>
                        <a class="btn right col s2">
                            <span class="hide-on-med-and-down"></span><i class="material-icons right">open_in_new</i>
                        </a>
                        <h6 class="col s6 quarter"></h6><h6 class="col s6 course"></h6>
                    </div>
                </div>
                <div class="collapsible-body">
                    <ul class="subposts">
                        <li class="subpost container">
                            <div class="valign-wrapper row subtitle">
                                <h5 class="col s9"></h5>
                                <a class="btn right col s2">
                                    <span class="hide-on-med-and-down"></span><i class="material-icons
                                    right">open_in_new</i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <div id="endless-scroll"></div>
        <div class="grey lighten-2 valign-wrapper card" id="loading">
            <div class="container">
                <div class="progress deep-purple lighten-4">
                    <div class="indeterminate deep-purple"></div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/files/footer.php") ?>
</body>
</html>