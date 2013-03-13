<?php 
include("formparse.php"); 
include("parser.php"); 
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">
                        WebAdvisor Scraper
                    </a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                        <!-- <form class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Email">
                            <input class="span2" type="password" placeholder="Password">
                            <button type="submit" class="btn">Sign in</button>
                        </form> -->
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="span12">
                    <h1>WebAdvisor Scraper</h1>
                    <form method="get" class="form-inline" action="">
                        <label class="inline">
                            Building
                            <select id="building" name="building">
                                <option value="">all</option>
                                <?php foreach($classrooms as $building => $rooms):?>
                                    <option <?php echo ($building == $formblding ? "selected" : "");?> value="<?=$building?>"><?=$building?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label class="inline">
                            Day
                            <select id="day" name="day" class="input-small">
                                <?php foreach($days as $day):?>
                                    <option <?php echo ($day == $formday ? "selected" : "");?> value="<?=$day?>"><?=$day?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label class="inline">
                            Time
                            <select id="hour" name="hour" class="input-mini">
                                <option value="08">8</option>
                                <option value="09">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                            </select>
                            <span class="add-on">:</span>
                            <select id="min" name="min" class="input-mini">
                                <option value="00">00</option>
                                <option value="30">30</option>
                            </select>
                        </label>
                        <label class="inline">
                            Capacity
                            <!-- <input type="text" class="input-small" id="capacity" name="capacity"> -->
                            <input type="range" name="capacity" min="1" max="600">
                        </label>
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                    <table class="table table-condensed table-striped table-bordered">
                        <tr>
                            <th>building</th><th>room</th><th>capacity</th><th>duration</th>
                        </tr>
                    <?php foreach($classrooms as $building => $rooms) {
                        foreach($rooms as $room => $attrib) {
                            if($attrib['capacity'] >= $formcapacity) {
                                foreach($attrib['schedule'] as $day => $hours) {
                                    if($day == $formday) {
                                        if(!isset($hours[$formtime])) {
                                            echo("<tr><td>$building</td><td>$room</td><td>" . $attrib['capacity'] . "</td>");

                                            $uptime = $formtime;
                                            $duration = "00:30";
                                            //echo "<p>duration = " . date("H:i", $duration) . "</p>";

                                            $uptime = date("H:i", strtotime("$uptime + 30 minutes"));
                                            //echo $hours[$uptime];
                                            while(!isset($hours[$uptime]) && (strtotime($uptime) < strtotime("20:00"))) {
                                                $duration = date("H:i", strtotime("$duration + 30 minutes"));
                                                //echo "<p>" . date($duration) . "</p>";

                                                //loop increment
                                                $uptime = date("H:i", strtotime("$uptime + 30 minutes"));
                                            }

                                            echo "<td>" . date("G:i", strtotime($duration)) . "h</td></tr>";
                                            //echo "<pre>" . print_r($hours, true) . "</pre>";
                                        }
                                    }
                                }
                            }
                        }
                    }?>
                    </table>
                </div>
            </div>

            <!-- Main hero unit for a primary marketing message or call to action -->
            <!--<div class="hero-unit">
                <h1>Hello, world!</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
            </div>-->

            <!-- Example row of columns
            <div class="row">
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
               </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
            </div>-->

            <hr>

            <footer>
                <p>&copy; Matthew Roberts 2013</p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
