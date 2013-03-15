<?php 
include("formparse.php"); 
include("parser.php"); 

$output = "";

foreach($classrooms as $building => $rooms) {
    if(($formbuilding != "" && $building == $formbuilding) || $formbuilding == "") {
        foreach($rooms as $room => $attrib) {
            if($attrib['capacity'] >= $formcapacity) {
                foreach($attrib['schedule'] as $day => $hours) {
                    if($day == $formday) {
                        if(!isset($hours[$formtime])) {
                            $output .= "<tr><td>$building</td><td>$room</td><td>" . $attrib['capacity'] . "</td>";

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

                            $output .= "<td>" . date("G:i", strtotime($duration)) . "h</td></tr>";
                            //echo "<td colspan='4'><pre>" . print_r($attrib, true) . "</pre></td>";
                        }
                    }
                }
            }
        }
    }
}
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
                                    <option <?php echo ($building == $formbuilding ? "selected" : "");?> value="<?=$building?>"><?=$building?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label class="inline">
                            Day
                            <select id="day" name="day" class="input-small">
                                <?php foreach($days as $day):?>
                                    <option <?=($day == $formday ? "selected" : "");?> value="<?=$day?>"><?=$day?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label class="inline">
                            Time
                            <select id="hour" name="hour" class="input-mini">
                                <?php $hours = array("08","09","10","11","12","13","14","15","16","17","18","19","20",);
                                foreach($hours as $hour):?>
                                    <option <?=($hour == $formhour ? "selected" : "");?> value="<?=$hour?>"><?=(int)$hour?></option>
                                <?php endforeach;?>
                            </select>
                            <span class="add-on">:</span>
                            <select id="min" name="min" class="input-mini">
                                <?php $mins = array("00","30");
                                foreach($mins as $min):?>
                                    <option <?=($min == $formmin ? "selected" : "");?> value="<?=$min?>"><?=$min?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                        <label class="inline">
                            Capacity
                            <input type="text" class="input-small" id="capacity" name="capacity" value="<?=($formcapacity != "" ? $formcapacity : "")?>">
                            <!-- <input type="range" name="capacity" min="1" max="600"> -->
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
                        <?php if($output != "") {
                            echo $output;
                        }?>
                    </table>
                    <?if($output == ""):?>
                        <div class="alert alert-info">
                            No results
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <hr>
            <footer>
                <div class="row">
                    <div class="span6">
                        <p>
                            &copy; 2013 Matthew Roberts. <a href="https://twitter.com/eruraindil" class="twitter-follow-button" data-show-count="false">Follow @eruraindil</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </p>
                    </div>
                    <div class="span6 text-right">
                        <p class="text-info">Problem? This is BETA software, let me know and I will try to fix it.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="span12 text-center">
                        <a href="https://www.github.com/eruraindil/webadvisor-scraper" class="btn">View on GitHub</a>
                    </div>
                </div>
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
