<?php
/*
https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor

get: TOKENIDX=5071880408&SS=7&APP=ST&CONSTITUENCY=WBST

post: VAR1=S13&DATE.VAR1=&DATE.VAR2=&LIST.VAR1_CONTROLLER=LIST.VAR1&LIST.VAR1_MEMBERS=LIST.VAR1*LIST.VAR2*LIST.VAR3*LIST.VAR4&LIST.VAR1_MAX=5&LIST.VAR2_MAX=5&LIST.VAR3_MAX=5&LIST.VAR4_MAX=5&LIST.VAR1_1=&LIST.VAR2_1=&LIST.VAR3_1=&LIST.VAR4_1=&LIST.VAR1_2=&LIST.VAR2_2=&LIST.VAR3_2=&LIST.VAR4_2=&LIST.VAR1_3=&LIST.VAR2_3=&LIST.VAR3_3=&LIST.VAR4_3=&LIST.VAR1_4=&LIST.VAR2_4=&LIST.VAR3_4=&LIST.VAR4_4=&LIST.VAR1_5=&LIST.VAR2_5=&LIST.VAR3_5=&LIST.VAR4_5=&VAR7=&VAR8=&VAR3=&VAR6=G&VAR21=&VAR9=&RETURN.URL=https%3A%2F%2Fwebadvisor.uoguelph.ca%2FWebAdvisor%2FWebAdvisor%3FTOKENIDX%3D5071880408%26CONSTITUENCY%3DWBST%26TYPE%3DM%26PID%3DCORE-WBST&SUBMIT_OPTIONS=
*/

/*$http_req = new HttpRequest("https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TOKENIDX=7172245692&SS=2&APP=ST&CONSTITUENCY=WBST");
$http_req->setOptions(array(timeout=>10,useragent=>"MyScript"));
$http_req->send();
echo $http_req->getResponseBody();*/
//echo http_get("https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?APP=ST&CONSTITUENCY=WBST");
/*
    $r = new HttpRequest('http://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TOKENIDX=5071880408&SS=7&APP=ST&CONSTITUENCY=WBST', HttpRequest::METH_POST);
    //$r->setOptions(array('cookies' => array('lang' => 'de')));
    $r->addPostFields(
                      array(
                            'VAR1' => 'S13',
                            'VAR6' => 'G'
                        )
        );
    //$r->addPostFile('image', 'profile.jpg', 'image/jpeg');
    try {
        echo $r->send()->getBody();
    } catch (HttpException $ex) {
        echo $ex;
    }*/

include('datastructures.php');

function parseClass($class, $capacity, &$classrooms) {
  $days = preg_replace('/(.*)( \d\d:\d\d(AM|PM))( -.*)/','$1',$class);
  $start_time = preg_replace('/(.*)(\d\d:\d\d(AM|PM))( -.*)/','$2',$class);
  $end_time = preg_replace('/(.*)(\d\d:\d\d(AM|PM)),(.*)/','$2',$class);
  $building = preg_replace('/(.*(AM|PM), )(\w+)(, Room.*)/','$3',$class);
  $room = preg_replace('/(.*Room )(\d+)(.*)/','$2',$class);

  $start_time = date("H:i",strtotime($start_time));
  $end_time = date("H:i",strtotime($end_time));

  /*echo "Input parsed:<br>
  &nbsp;&nbsp;Days: " . $days . "<br>
  &nbsp;&nbsp;Time: " . $start_time . " - " . $end_time . "<br>
  &nbsp;&nbsp;Building: " . $building . " " . $room . "<br>";*/

  /*if(!isset($classrooms[trim($building)][trim($room)])) {
    $classrooms[trim($building)][trim($room)] = $calendar;
  }*/

  $lec_days_ar = explode(",", $days);

  $start_time_org= $start_time;
  foreach($lec_days_ar as $day) {
    $day = trim($day);
    //echo "$day<br>";
    while(strtotime($start_time) < strtotime($end_time)) {
      if(trim($building) == "MACK") {
        $classrooms[trim($building)][trim($room)]['schedule'][trim($day)][trim($start_time)] = true;
        if(!isset($classrooms[trim($building)][trim($room)]['capacity'])) {
          $classrooms[trim($building)][trim($room)]['capacity'] = $capacity;
        } else {
          $classrooms[trim($building)][trim($room)]['capacity'] = max($classrooms[trim($building)][trim($room)]['capacity'], $capacity);
        }
      }
      $start_time = date("H:i",strtotime("$start_time + 30 minutes"));
    }
    $start_time = $start_time_org;
  }

  //return $classrooms;
}

//from http://php.net/manual/en/function.ksort.php
function tksort(&$array) {
  ksort($array);
  foreach(array_keys($array) as $k)
    {
    if(gettype($array[$k])=="array")
      {
      tksort($array[$k]);
      }
  }
}
    //include("simplehtmldom_1_5/simple_html_dom.php");

    // Create DOM from URL or file
    //$html = file_get_html('webadvisor_results4.html');
//    $html = file_get_html('https://webadvisor.uoguelph.ca/WebAdvisor/WebAdvisor?TYPE=M&PID=CORE-WBMAIN');

    //$ret = $html->find('p[id*=SEC_MEETING_INFO]');
    //echo "<pre>" . print_r($ret) . "</pre>";
    $lines = file("meeting_info.txt");
    $capacities = file("capacity.txt");
    
    $classrooms = array();
    
    foreach($lines as $line_num => $line) {
      //echo "<hr>" . $line . "<br>";
        if(preg_match('/.*(LEC|SEM|LAB|EXAM).*/', $line) && !preg_match('/.*(LEC|LAB|SEM|Distance Education) Days TBA.*/', $line)) {
            //echo $line . "<br>";
            //$result = preg_replace('/(.*(LEC|LAB|SEM) )(.*)/', '$3', $line, 1);
            $result = preg_replace('/(\d+\/\d+\/\d+-\d+\/\d+\/\d+ )(.*)/', '$2', $line);

            $result = preg_replace('/( \d+\/\d+\/\d+-\d+\/\d+\/\d+ EXAM.*)/', '', $result);

            //echo $capacities[$line_num] . "<br>";
            $capacity = preg_replace('/\d+ \/ (\d+)/', '$1', $capacities[$line_num]);
            //echo $capacity . "<br>";

            //echo "result now $result <br>";
            //echo $result . "<br>";

            if(preg_match('/.*LEC.*/', $line)) {
              $lecture = preg_replace('/LEC (.*)/', '$1', $result);
              
              preg_replace('/(LAB)/', '$1', $lecture, -1, $count);
              //echo "count $count<br>";

              for($i = 0; $i < $count; $i++) {
                $lecture = preg_replace('/(.*) (\d)+\/(\d)+\/(\d)+.-.(\d)+\/(\d)+\/(\d)+ (LAB).*/', '$1', $lecture);
              }
              $lecture = preg_replace('/(.*) (\d)+\/(\d)+\/(\d)+.-.(\d)+\/(\d)+\/(\d)+ (SEM).*/', '$1', $lecture);
              $lecture = preg_replace('/(.*) (\d)+\/(\d)+\/(\d)+.-.(\d)+\/(\d)+\/(\d)+ (LEC).*/', '$1', $lecture);

              if(!preg_match('/.*Room TBA.*/', $lecture)) {
                parseClass($lecture,$capacity, $classrooms);
              }
            }

            if(preg_match('/.*(LAB).*/', $line)) {
              $lab = preg_replace('/(.*(LAB) )(.*)/', '$3', $result);
              $lab = preg_replace('/(.*) (\d)+\/(\d)+\/(\d)+.-.(\d)+\/(\d)+\/(\d)+ (SEM).*/', '$1', $lab);
              //echo "Lab " . $lab . "<br>";

              if(!preg_match('/.*Room TBA.*/', $lab)) {

                preg_replace('/(LAB)/', '$1', $lab, -1, $count);
                //echo "count $count<br>";

                //for($i = 0; $i < $count; $i++) {
                  //$lab = preg_replace('/(.*) (\d)+\/(\d)+\/(\d)+.-.(\d)+\/(\d)+\/(\d)+ (LAB).*/', '$1', $lab);
                  parseClass($lab, $capacity, $classrooms);
                //}
              }
            }

            if(preg_match('/.*(SEM).*/', $line)) {
              $sem = preg_replace('/(.*(SEM) )(.*)/', '$3', $result);
              //echo "Lab " . $lab . "<br>";
              //$sem = preg_replace('/(.*SEM )(.*)/', '$2', $result);
              if(!preg_match('/.*Room TBA.*/', $sem)) {
                parseClass($sem, $capacity, $classrooms);
              }
            }

            //echo "<pre>" . print_r($lab_days_ar, true) . "</pre>";
        }
        //echo $item->plaintext . "<br>";
    }

    tksort($classrooms);

    //echo "<pre>" . print_r($classrooms, true) . "</pre>";

    //$fp = fopen("classrooms.txt", "w");
    //fwrite($fp, print_r($classrooms, true));
    //fclose($fp);

   /* $string = 'April 15, 2003';
    $pattern = '/(\w+) (\d+), (\d+)/i';
    $replacement = '${1}1,$3';
    echo preg_replace($pattern, $replacement, $string);*/

   //$str = $html;
   //echo $html;

?>