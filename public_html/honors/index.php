<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Honors Portfolio</title>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/files/common.php") ?>
    <link href="/css/honors.css" rel="stylesheet" type="text/css">

    <script>
        $(function() {
            first = true;
            $.ajax({
                type: "POST",
                url: "./load.php",
                data: { year: window.location.hash.substring(1) },
                // serializes the form's elements.
                dataType: "json",
                success: appendCourses,
                error: function (jqXHR) {
                    load = false;
                    $("#courses").append(jqXHR.responseText);
                }
            });

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
                    $newCourse.find(".collapsible-header h5").text(post.course);
                    $newCourse.find(".collapsible-header h6").text(post.quarter.capitalize() + " " + gradeToYear(post.year));
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
                    $("#courses").append($newCourse);
                }

                // Redinit collapsible
                $('.collapsible').collapsible();
                $('.materialboxed').materialbox();
            }
        });
        /*var postId = 4;
        var loading = false;
        var load = true;

        $(window).scroll(function () {
            if (load = true) {
                checkEndlessScroll("endless-scroll");
            }
        });

        function checkEndlessScroll(el) {
            if (isInView(document.getElementById(el))) {
                //$("#all").click();
                if(!loading) {
                    loading = true;
                    getPosts();
                }
            }
        }

        function getPosts() {
            $.ajax({
                type: "POST",
                url: "./load.php",
                data: {id: postId},
                // serializes the form's elements.
                dataType: "html",
                success: function (data) {
                    $("#courses").append(data);
                    stagger($(".toggle"));
                    postId += 3;
                    loading = false;
                },
                error: function (jqXHR) {
                    load = false;
                    $("#courses").append(jqXHR.responseText);
                }
            });
        }

        function isInView(el) {
            var elemTop = el.getBoundingClientRect().top;
            var elemBottom = el.getBoundingClientRect().bottom;

            var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
            return isVisible;
        }

        $(document).ready(function (e) {
            stagger($(".toggle"));

            $(".visit").click(function (e) {
                e.stopPropagation();
            });
            $(".filter").click(function (e) {
                $(this).siblings().removeClass("selected");
                $(this).addClass("selected");

                if (this.id == 'all') {
                    $('.course').fadeIn(300);
                    courses = $('.toggle')
                } else {
                    el = $('.' + this.id).fadeIn(300);
                    if(el.length == 0 && load == true) {
                        if(!loading) {
                            loading = true;
                            getPosts();
                        }
                    }
                    $('.course').not(el).fadeOut(300);
                    courses = $('.' + this.id + ' .toggle');
                }

                stagger(courses);
            });
        });

        function stagger(list) {
            for (i = 0; i < list.length; i++) {
                course = list[i];
                if (i % 2 == 0) {
                    $(course).css("background-color", "#448AFF");
                    $(course).siblings(".toggleable").css("background-color", "unset");
                } else {
                    $(course).css("background-color", "#21447D");
                    $(course).siblings(".toggleable").css("background-color", "#F5F5F5");
                }
            }
        }*/
    </script>
</head>

<body>
<?php readfile($_SERVER['DOCUMENT_ROOT'] . "/files/header.html") ?>
<main>
    <div class="container">
        <div class="card">
            <ul class="tabs grey lighten-2">
                <li class="tab"><a class="active" href="">All</a></li>
                <li class="tab"><a href="#freshman">Freshman</a></li>
                <li class="tab"><a href="#sophomore">Sophomore</a></li>
                <li class="tab"><a href="#junior">Junior</a></li>
                <li class="tab"><a href="#senior">Senior</a></li>
            </ul>
        </div>
        <ul class="collapsible" id="courses" data-collapsible="accordion">
            <li class="template">
                <div class="collapsible-header teal lighten-1 white-text">
                    <div class="row">
                        <h5 class="col s9"></h5>
                        <a class="btn right blue col s2">
                            <span class="hide-on-med-and-down"></span><i class="material-icons right">open_in_new</i>
                        </a>
                        <h6 class="col s12"></h6>
                    </div>
                </div>
                <div class="collapsible-body grey lighten-5">
                    <ul class="subposts">
                        <li class="subpost container">
                            <div class="valign-wrapper row subtitle">
                                <h5 class="col s9"></h5>
                                <a class="btn right blue col s2">
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
    </div>
</main>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/files/footer.php") ?>
</body>
</html>

<!--
    <div class="container">
            <article class="content offset">
                <section class="main">
                    <h1>UW Honors Portfolio</h1>
                    <section id="filters" class="dropshadow">
                        <div id="all" class="filter trans selected">
                            <h3>All</h3>
                        </div>
                        <div id="freshman" class="filter trans ">
                            <h3>Freshman</h3>
                        </div>
                        <div id="sophomore" class="filter trans ">
                            <h3>Sophomore</h3>
                        </div>
                        <div id="junior" class="filter trans ">
                            <h3>Junior</h3>
                        </div>
                        <div id="senior" class="filter trans ">
                            <h3>Senior</h3>
                        </div>
                    </section>
                    <section class="dropshadow" id="courses">
                        <?php include("./load.php") ?>
                    </section>

                </section>
            </article>
            <!-- end .content
    </div>
    <!-- end .container
</body>

</html>
