<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\Models\User;
use App\TokenStore\TokenCache;
use App\TimeZones\TimeZones;
use DateInterval;

class EventGeneratingController extends Controller
{
    public function openGeneratingPage(){
        $viewData = $this->loadViewData();

        $graph = $this->getGraph();
        if (microtime(true) > session('tokenExpires')){
            return redirect('/signin');
        }

        $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);
        $startOfWeek = new \DateTimeImmutable('first day of this year', $timezone);
        $endOfWeek = new \DateTimeImmutable('first day of next year', $timezone);
        $now = new \DateTimeImmutable('now', $timezone);
        $currentMonth = new \DateTimeImmutable('now', $timezone);
        //dd($now);
        $viewData['dateRange'] = $startOfWeek->format('M j, Y').' - '.$endOfWeek->format('M j, Y');

    if (microtime(true) > session('tokenExpires')){
        return redirect('/signin');
    }

        // $viewData['events']=$formevents;
        $viewData['now']=$now->format('Y-m-d\TH:i:s');
        $viewData['currentMonth']=$currentMonth->format('Y-m');
        $viewData['timezone']=$timezone->getName();
        $viewData['nowtime']=$now->format('H:i');

        return view('generateEvent', $viewData);
    }
    private function getGraph(): Graph
  {
    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();
    //dd($tokenCache->getExpires());
    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    return $graph;
  }
  public function getGenerateEventForm()
{
  $viewData = $this->loadViewData();
  $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);
  $now = new \DateTimeImmutable('now', $timezone);
  $viewData['nowtime']=$now->format('H:i');
  $viewData['nowdatee']=$now->format('Y-m-d');
  return view('generatesingle', $viewData);
}
public function generateEvent(Request $request)
{
    $validated = $request->validate([
    'teamName' => 'required|max:30',
    'query' => 'required'
    ]);
    //   dd($request->eventSubject);
    // Validate required fields
    //   $request->validate([
    //     'eventSubject' => 'nullable|string',
    //     'eventAttendees' => 'nullable|string',
    //     'eventStart' => 'required|date',
    //     'eventEnd' => 'required|date',
    //     'eventBody' => 'nullable|string'
    //   ]);
    //dd($request->eventDay, $request->eventDay ,$request->rezerve,$request->minutes,$request->hours);
    $dayCount = $request->interval;
    $viewData = $this->loadViewData();
    $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);
    $start = new \DateTimeImmutable($request->eventDay . "T" . '00:00', $timezone);
    $tempStart= new \DateTimeImmutable($request->eventDay . "T" . '08:00', $timezone);
    $tempEnd=new \DateTimeImmutable($request->eventDay . "T" . '19:00', $timezone);
    $temp0= new \DateTimeImmutable($request->eventDay . "T" . '00:00', $timezone);
    $temp0 = $temp0->add(new DateInterval('P1D'));
    $startinit = new \DateTimeImmutable($request->eventDay . "T" . $request->timeStart, $timezone);
    $graph = $this->getGraph();
    if (microtime(true) > session('tokenExpires')){
    return redirect('/signin');
}
  $endofInterval = $startinit->add(new DateInterval('P' . $dayCount . 'D'));

  $queryParams = array(
    'startDateTime' => $startinit->format(\DateTimeInterface::ISO8601),
    'endDateTime' => $endofInterval->format(\DateTimeInterface::ISO8601),
    // Only request the properties used by the app
    '$select' => 'subject,organizer,start,end,location,bodyPreview,categories,id',
    // Sort them by start time
    '$orderby' => 'start/dateTime,end/dateTime',
    // Limit results to 25
    '$top' => 100
  );
  $getEventsUrl = '/me/calendarView?'.http_build_query($queryParams);
    //dd(microtime(true));
    if (microtime(true) > session('tokenExpires')){
        return redirect('/signin');
    }
    $events = $graph->createRequest('GET', $getEventsUrl)
      // Add the user's timezone to the Prefer header
      ->addHeaders(array(
        'Prefer' => 'outlook.timezone="'.$viewData['userTimeZone'].'"'
      ))
      ->setReturnType(Model\Event::class)
      ->execute();
  $array = [];
  foreach($events as $event) {
    $array[] = [new \DateTimeImmutable($event->getStart()->getDateTime(), $timezone), new \DateTimeImmutable($event->getEnd()->getDateTime(), $timezone)];
  }
//   dd($array);
  $days = [];
  $temp = new \DateTimeImmutable($request->eventDay, $timezone);
  foreach(range(0, $dayCount - 1) as $i){
      if ($temp->format('N') < 6){
        $times = [];
        $days[] = [$temp, $times];
      }
      $temp = $temp->add(new DateInterval('P1D'));
  }
  //dd($days);
  $arrays = $this->getFreeTimes($array, $days, $startinit, $start, $tempStart, $tempEnd, $temp0, $timezone);
  $result = $this->getCorrectTime($arrays, $request->rezerve,$request->minutes,$request->hours);
//   dd($result[0]->format('Y-m-d') . "T" . $result[0]->format('H:i'), $result[1]->format('Y-m-d') . "T" . $result[1]->format('H:i'));
  $EndTimeStart = $result[0]->format('Y-m-d') . "T" . $result[0]->format('H:i');
  $EndTimeDate = $result[1]->format('Y-m-d') . "T" . $result[1]->format('H:i');
    // dd($request->eventSubject, $request->eventDay, $request->timeStart, $request->timeEnd, $request->eventBody, $EndTimeDate);
  // Build the event
  $newEvent = [
    'subject' => $request->eventSubject,
    'start' => [
      'dateTime' => $EndTimeStart,
      'timeZone' => $viewData['userTimeZone']
    ],
    'end' => [
      'dateTime' => $EndTimeDate,
      'timeZone' => $viewData['userTimeZone']
    ],
    'body' => [
      'content' => $request->eventBody,
      'contentType' => 'text'
    ]
  ];
  $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();
//   dd($result);
  //   dd($arrays);
//   dd($arrays);
//   dd($start->format('Y-m-d') . 'T' . $start->format('H:i'), $end->format('Y-m-d') . 'T' . $end->format('H:i'), $startfixed->format('Y-m-d') . 'T' . $startfixed->format('H:i'),
//   $endfixed->format('Y-m-d') . 'T' . $endfixed->format('H:i'));
  return redirect('/calendar')->with('wtf', session('accessToken'));
}
private function getFreeTimes($array, $days, $startinit, $start, $tempStart, $tempEnd, $temp0, $timezone)
{
    $end = $startinit;
    $arrays = [];
    $i = 0;
    $daynumber = 0;
    while($i < count($array)){
        // echo '<br/>';echo '<br/>';
        // print_r($array[$i][0]->format('Y-m-d\TH:i'));
        // echo '<br/>';
        // print_r($array[$i][1]->format('Y-m-d\TH:i'));
        // echo '<br/>';
        // print_r($start->format('Y-m-d\TH:i'));
        // echo '<br/>';
        // print_r($end->format('Y-m-d\TH:i'));
        // echo '<br/>';
        // if($daynumber == 56){
        //     dd($days);
        // }
        // print_r($days[$daynumber][0]->format('Y-m-d\TH:i'));

        // //dd($array[$i][0]->format('Y-m-d'), $days[$daynumber][0]->format('Y-m-d'));
        $compare= new \DateTimeImmutable($array[$i][0]->format('Y-m-d'), $timezone);

        if($compare > $days[$daynumber][0]){
            //dd($start->format('Y-m-d\TH:i'), $tempStart->format('Y-m-d\TH:i'));
            if($start <= $tempStart){ // 08:00 >=  start
                //dd($end->format('Y-m-d\TH:i'), $tempStart->format('Y-m-d\TH:i'));
                if($end <= $tempStart){ // 08:00 >=  end
                    // print_r('3' );
                    $days[$daynumber][1][] = [$tempStart ,$tempEnd];
                    if($temp0->format('N') < 6){
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P1D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                    }
                    else{
                        $temp0 = $temp0->add(new DateInterval('P2D'));
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P3D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                    }
                    $end = $tempStart;
                    $daynumber = $daynumber + 1;
                }
                else{ // 08:00 <  end
                    // print_r('10 ');
                    if($end >= $tempEnd){ // 19:00 <=  end
                        // print_r('2 ');
                        if($temp0->format('N') < 6){
                            $start = $temp0;
                            $temp0 = $temp0->add(new DateInterval('P1D'));
                            $tempStart = $tempStart->add(new DateInterval('P1D'));
                            $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                        }
                        else{
                            $temp0 = $temp0->add(new DateInterval('P2D'));
                            $start = $temp0;
                            $temp0 = $temp0->add(new DateInterval('P1D'));
                            $tempStart = $tempStart->add(new DateInterval('P3D'));
                            $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                        }
                        $end = $tempStart;
                        $daynumber = $daynumber + 1;
                    }
                    else{ // 19:00 >  end
                        // print_r('1 ');
                        $days[$daynumber][1][] = [$end ,$tempEnd];
                        if($temp0->format('N') < 6){
                            $start = $temp0;
                            $temp0 = $temp0->add(new DateInterval('P1D'));
                            $tempStart = $tempStart->add(new DateInterval('P1D'));
                            $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                        }
                        else{
                            $temp0 = $temp0->add(new DateInterval('P2D'));
                            $start = $temp0;
                            $temp0 = $temp0->add(new DateInterval('P1D'));
                            $tempStart = $tempStart->add(new DateInterval('P3D'));
                            $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                        }
                        $end = $tempStart;
                        $daynumber = $daynumber + 1;

                    }
                }
            }
            else{
                // print_r('0 ');
                $days[$daynumber][1][] = [$end ,$tempEnd];
                if($temp0->format('N') < 6){
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P1D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                }
                else{
                    $temp0 = $temp0->add(new DateInterval('P2D'));
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P3D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                }
                $end = $tempStart;
                $daynumber = $daynumber + 1;
            }

        }
        else{
            // print_r('taipas');
            if($end >= $array[$i][0]){
                if($end >= $array[$i][1]){
                    $i = $i + 1;
                    // print_r('taip');
                }
                else{
                    $end = $array[$i][1];
                    $i = $i + 1;
                    // print_r('taip1');
                }
            }
            else if($tempEnd <= $array[$i][1]){
                if($end < $array[$i][0]){

                    $days[$daynumber][1][] = [$end ,$array[$i][0]];
                }
                $i = $i + 1;
                if($temp0->format('N') < 6){
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P1D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                }
                else{
                    $temp0 = $temp0->add(new DateInterval('P2D'));
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P3D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                }

                $end = $tempStart;
                $daynumber = $daynumber + 1;
                // print_r('taip2');
            }
            else{
                $start = $array[$i][0];
                if($tempStart >= $end){
                    $days[$daynumber][1][] = [$tempStart ,$start];
                }
                else{
                    $days[$daynumber][1][] = [$end ,$start];
                }

                $end = $array[$i][1];
                $i = $i + 1;
                // print_r('taip3');
            }
        }

    }
    while($daynumber < count($days)){
        if($start <= $tempStart){ // 08:00 >=  start
            //dd($end->format('Y-m-d\TH:i'), $tempStart->format('Y-m-d\TH:i'));
            if($end <= $tempStart){ // 08:00 >=  end
                $days[$daynumber][1][] = [$tempStart ,$tempEnd];
                if($temp0->format('N') < 6){
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P1D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                }
                else{
                    $temp0 = $temp0->add(new DateInterval('P2D'));
                    $start = $temp0;
                    $temp0 = $temp0->add(new DateInterval('P1D'));
                    $tempStart = $tempStart->add(new DateInterval('P3D'));
                    $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                }
                $end = $tempStart;
                $daynumber = $daynumber + 1;
            }
            else{ // 08:00 <  end
                if($end >= $tempEnd){ // 19:00 <=  end
                    if($temp0->format('N') < 6){
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P1D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                    }
                    else{
                        $temp0 = $temp0->add(new DateInterval('P2D'));
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P3D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                    }
                    $end = $tempStart;
                    $daynumber = $daynumber + 1;
                }
                else{ // 19:00 >  end
                    $days[$daynumber][1][] = [$end ,$tempEnd];
                    if($temp0->format('N') < 6){
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P1D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P1D'));
                    }
                    else{
                        $temp0 = $temp0->add(new DateInterval('P2D'));
                        $start = $temp0;
                        $temp0 = $temp0->add(new DateInterval('P1D'));
                        $tempStart = $tempStart->add(new DateInterval('P3D'));
                        $tempEnd = $tempEnd->add(new DateInterval('P3D'));
                    }
                    $end = $tempStart;
                    $daynumber = $daynumber + 1;

                }
            }
        }
        else{
            $days[$daynumber][1][] = [$end ,$tempEnd];
            if($temp0->format('N') < 6){
                $start = $temp0;
                $temp0 = $temp0->add(new DateInterval('P1D'));
                $tempStart = $tempStart->add(new DateInterval('P1D'));
                $tempEnd = $tempEnd->add(new DateInterval('P1D'));
            }
            else{
                $temp0 = $temp0->add(new DateInterval('P2D'));
                $start = $temp0;
                $temp0 = $temp0->add(new DateInterval('P1D'));
                $tempStart = $tempStart->add(new DateInterval('P3D'));
                $tempEnd = $tempEnd->add(new DateInterval('P3D'));
            }
            $end = $tempStart;
            $daynumber = $daynumber + 1;
        }
    }

    return $days;
}
private function getCorrectTime($days, $rezerve,$minutes,$hours){
    $count1 = 0;
    $count2 = 0;
    foreach($days as $day){
        foreach($day[1] as $time){
            if($time[0]->format("H:i") == "08:00"){
                $wantedResult = $rezerve + $minutes + $hours * 60;
                $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'));
                if ($wantedResult <= $timeResult){
                    return [$time[0], $ending];
                }
                // dd($timeResult);
            }
            else if ($time[1]->format("H:i") == "19:00"){
                $wantedResult = $rezerve + $minutes + $hours * 60;
                $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                $starting = $time[0]->add(new DateInterval('PT'.$rezerve.'M'));
                $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes + $rezerve.'M'));
                if ($wantedResult <= $timeResult){
                    return [$starting, $ending];
                }
            }
            else{
                $wantedResult = $rezerve + $minutes + $hours * 60;
                $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                $starting = $time[0]->add(new DateInterval('PT'.$rezerve.'M'));
                $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes + $rezerve.'M'));
                if ($wantedResult <= $timeResult){
                    return [$starting, $ending];
                }
            }


        }
    }
    return false;

}
public function getGenerateSprintForm()
{
  $viewData = $this->loadViewData();
  $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);
  $now = new \DateTimeImmutable('now', $timezone);
  $viewData['nowtime']=$now->format('H:i');
  $viewData['nowdatee']=$now->format('Y-m-d');
  return view('generatesprint', $viewData);
}
public function generateSprint(Request $request)
{
//   dd($request->eventSubject);
  // Validate required fields
//   $request->validate([
//     'eventSubject' => 'nullable|string',
//     'eventAttendees' => 'nullable|string',
//     'eventStart' => 'required|date',
//     'eventEnd' => 'required|date',
//     'eventBody' => 'nullable|string'
//   ]);
    //dd($request->eventDay, $request->eventDay ,$request->rezerve,$request->minutes,$request->hours);

  $viewData = $this->loadViewData();
  $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);

  $graph = $this->getGraph();
  if (microtime(true) > session('tokenExpires')){
    return redirect('/signin');
}
  $dayCount = $request->OneSprintLength * 7 * $request->SprintLength;
  $now = new \DateTimeImmutable('now', $timezone);
  $now = new \DateTimeImmutable($now->format('Y-m-d'), $timezone);
  $now = $now->add(new DateInterval('P'. 8 - $now->format('N') .'D'));
  $end = $now->add(new DateInterval('P'. $request->OneSprintLength * 7 * $request->SprintLength.'D'));
  $start = new \DateTimeImmutable($now->format('Y-m-d'). "T" . '00:00', $timezone);
  $tempStart= new \DateTimeImmutable($now->format('Y-m-d') . "T" . '08:00', $timezone);
  $tempEnd=new \DateTimeImmutable($now->format('Y-m-d') . "T" . '19:00', $timezone);
  $temp0= new \DateTimeImmutable($now->format('Y-m-d') . "T" . '00:00', $timezone);
  $temp0 = $temp0->add(new DateInterval('P1D'));
  $sprintHours = [$request->sprintSUHours, $request->sprintSPHours, $request->sprintRHours, $request->sprintSAHours];
  $sprintMinutes = [$request->sprintSUMinutes, $request->sprintSPMinutes, $request->sprintRMinutes, $request->sprintSAMinutes];

//   dd($start, $tempStart, $tempEnd, $temp0, $now, $end);
  $queryParams = array(
    'startDateTime' => $now->format(\DateTimeInterface::ISO8601),
    'endDateTime' => $end->format(\DateTimeInterface::ISO8601),
    // Only request the properties used by the app
    '$select' => 'subject,organizer,start,end,location,bodyPreview,categories,id',
    // Sort them by start time
    '$orderby' => 'start/dateTime,end/dateTime',
    // Limit results to 25
    '$top' => 100
  );
  $getEventsUrl = '/me/calendarView?'.http_build_query($queryParams);
    //dd(microtime(true));
    if (microtime(true) > session('tokenExpires')){
        return redirect('/signin');
    }
    $events = $graph->createRequest('GET', $getEventsUrl)
      // Add the user's timezone to the Prefer header
      ->addHeaders(array(
        'Prefer' => 'outlook.timezone="'.$viewData['userTimeZone'].'"'
      ))
      ->setReturnType(Model\Event::class)
      ->execute();
  $array = [];
  foreach($events as $event) {
    $array[] = [new \DateTimeImmutable($event->getStart()->getDateTime(), $timezone), new \DateTimeImmutable($event->getEnd()->getDateTime(), $timezone)];
  }

  $days = [];
  $temp = $now;
  foreach(range(0, $dayCount - 1) as $i){
      if ($temp->format('N') < 6){
        $times = [];
        $days[] = [$temp, $times];
      }
      $temp = $temp->add(new DateInterval('P1D'));
  }

  $arrays = $this->getFreeTimes($array, $days, $now, $start, $tempStart, $tempEnd, $temp0, $timezone);
  $result = $this->getCorrectSprint($arrays, $request->rezerve,$sprintHours,$sprintMinutes, $request->OneSprintLength, $request->SprintLength);
//   dd($result);
  //   dd($result[0]->format('Y-m-d') . "T" . $result[0]->format('H:i'), $result[1]->format('Y-m-d') . "T" . $result[1]->format('H:i'));

  foreach($result as $r){
      if($r[0] != "Nera tokio laiko"){
        $EndTimeStart = $r[0]->format('Y-m-d') . "T" . $r[0]->format('H:i');
        $EndTimeDate = $r[1]->format('Y-m-d') . "T" . $r[1]->format('H:i');
        if ('Sreview' == $r[2]){
        $newEvent = [
            'subject' => $request->eventBody,
            'start' => [
              'dateTime' => $EndTimeStart,
              'timeZone' => $viewData['userTimeZone']
            ],
            'end' => [
              'dateTime' => $EndTimeDate,
              'timeZone' => $viewData['userTimeZone']
            ],
            'body' => [
              'content' => $request->eventBody,
              'contentType' => 'text'
            ],
            'categories' => ['Red category']
          ];
          $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();
        }
        else if ('SprintPlanning' == $r[2]){
            $newEvent = [
                'subject' => $request->eventBody,
                'start' => [
                  'dateTime' => $EndTimeStart,
                  'timeZone' => $viewData['userTimeZone']
                ],
                'end' => [
                  'dateTime' => $EndTimeDate,
                  'timeZone' => $viewData['userTimeZone']
                ],
                'body' => [
                  'content' => $request->eventBody,
                  'contentType' => 'text'
                ],
                'categories' => ['Green category']
              ];
              $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();
            }
            else if ('Retro' == $r[2]){
                $newEvent = [
                    'subject' => $request->eventBody,
                    'start' => [
                      'dateTime' => $EndTimeStart,
                      'timeZone' => $viewData['userTimeZone']
                    ],
                    'end' => [
                      'dateTime' => $EndTimeDate,
                      'timeZone' => $viewData['userTimeZone']
                    ],
                    'body' => [
                      'content' => $request->eventBody,
                      'contentType' => 'text'
                    ],
                    'categories' => ['Orange category']
                  ];
                  $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();
                }
                else if ('standup' == $r[2]){
                    $newEvent = [
                        'subject' => $request->eventBody,
                        'start' => [
                          'dateTime' => $EndTimeStart,
                          'timeZone' => $viewData['userTimeZone']
                        ],
                        'end' => [
                          'dateTime' => $EndTimeDate,
                          'timeZone' => $viewData['userTimeZone']
                        ],
                        'body' => [
                          'content' => $request->eventBody,
                          'contentType' => 'text'
                        ],
                        'categories' => ['Yellow category']
                      ];
                      $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();
                    }
      }

  }
//   $EndTimeStart = $result[0]->format('Y-m-d') . "T" . $result[0]->format('H:i');
//   $EndTimeDate = $result[1]->format('Y-m-d') . "T" . $result[1]->format('H:i');
//     // dd($request->eventSubject, $request->eventDay, $request->timeStart, $request->timeEnd, $request->eventBody, $EndTimeDate);
//   // Build the event
//   $newEvent = [
//     'subject' => $request->eventBody,
//     'start' => [
//       'dateTime' => $EndTimeStart,
//       'timeZone' => $viewData['userTimeZone']
//     ],
//     'end' => [
//       'dateTime' => $EndTimeDate,
//       'timeZone' => $viewData['userTimeZone']
//     ],
//     'body' => [
//       'content' => $request->eventBody,
//       'contentType' => 'text'
//     ]
//   ];


//   dd($result);
  //   dd($arrays);
//   dd($arrays);
//   dd($start->format('Y-m-d') . 'T' . $start->format('H:i'), $end->format('Y-m-d') . 'T' . $end->format('H:i'), $startfixed->format('Y-m-d') . 'T' . $startfixed->format('H:i'),
//   $endfixed->format('Y-m-d') . 'T' . $endfixed->format('H:i'));
  return redirect('/calendar')->with('wtf', session('accessToken'));
}
private function getCorrectSprint($days, $rezerve,$sprintHours,$sprintMinutes, $OneSprintLength, $SprintLength){
    $sugeneruotilaikai = [];
    $counter = 1;
    foreach(range(0, count($days)  - 1) as $i){
        if ($i % ($OneSprintLength * 5) == 0){
            $j = 0;
            $checker = true;
            while($checker == true){
                // dd($days[$i][1][$j]);
                $time = $days[$i][1][$j];
                $minutes = $sprintMinutes[1];
                $hours = $sprintHours[1];
                if($time[0]->format("H:i") == "08:00"){
                    $wantedResult = $rezerve + $minutes + $hours * 60;
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'));
                    if ($wantedResult <= $timeResult){
                        $sugeneruotilaikai[] = [$time[0], $ending, "SprintPlanning"];
                        // return [$time[0], $ending];
                        $checker = false;
                    }
                    // dd($timeResult);
                }
                else{
                    if($time[1]->format("H:i") == "19:00"){
                        $wantedResult = $rezerve + $minutes + $hours * 60;
                    }
                    else{
                    $wantedResult = $rezerve * 2 + $minutes + $hours * 60;
                    }
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $starting = $time[0]->add(new DateInterval('PT'.$rezerve.'M'));
                    $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes + $rezerve.'M'));
                    if ($wantedResult <= $timeResult){
                        $sugeneruotilaikai[] = [$starting, $ending, "SprintPlanning"];
                        // return [$starting, $ending];
                        $checker = false;
                    }
                }
                $j = $j + 1;
                if(count($days[$i][1]) <= $j){
                    if($checker == true){
                        $sugeneruotilaikai[] = ["Nera tokio laiko", $days[$i][0]];
                        $checker = false;
                    }
                }

            }
        }
        else if ($i % (($OneSprintLength * 5 * $counter) - 1 ) == 0){
            $lettrue = true;
            $retro = true;
            $counter = $counter + 1;
            $j = count($days[$i][1]) - 1;
            $checker = true;
            while($checker == true){
                // dd($days[$i][1][$j]);
                $time = $days[$i][1][$j];
                $minutes = $sprintMinutes[2];
                $hours = $sprintHours[2];
                if ($time[1]->format("H:i") == "19:00"){
                    $wantedResult = $rezerve + $minutes + $hours * 60;
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $starting = $time[1]->sub(new DateInterval('PT'.$hours.'H'.$minutes.'M'));

                    if ($wantedResult <= $timeResult){
                        // return [$starting, $ending];
                        if ($retro == false){
                            $sugeneruotilaikai[] = [$starting, $time[1], "Sreview"];
                            $checker = false;
                        }
                        if ($retro == true){
                            $sugeneruotilaikai[] = [$starting, $time[1], "Retro"];
                            //$starting = $starting->sub(new DateInterval('PT'.$rezerve.'M'));
                            $days[$i][1][$j][1] = $starting;
                            $ending = $starting;

                            $retro = false;
                        }

                    }else{
                        $lettrue = false;
                    }
                }
                else{
                    if($time[0]->format("H:i") == "08:00"){
                        $wantedResult = $rezerve * 1 + $minutes + $hours * 60;
                    }
                    else{
                        $wantedResult = $rezerve * 2 + $minutes + $hours * 60;
                    }
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $starting = $time[1]->sub(new DateInterval('PT'.$hours.'H'.$minutes + $rezerve.'M'));
                    $ending = $time[1]->sub(new DateInterval('PT'.$rezerve.'M'));
                    // echo " {{$starting->format("Y:m:d H:i")}} {{$ending->format("Y:m:d H:i")}}";
                    // echo " {{$time[0]->format("Y:m:d H:i")}} {{$time[1]->format("Y:m:d H:i")}}";
                    if ($wantedResult <= $timeResult){
                            // return [$starting, $ending];
                            if ($retro == false){
                                $sugeneruotilaikai[] = [$starting, $ending, "Sreview"];
                                $checker = false;
                            }
                            if ($retro == true){
                                $sugeneruotilaikai[] = [$starting, $ending, "Retro"];
                                $starting = $starting->sub(new DateInterval('PT'.$rezerve.'M'));
                                $days[$i][1][$j][1] = $starting;
                                $ending = $starting;
                                $retro = false;
                            }

                    }
                    else{
                        $lettrue = false;
                    }
                }
                if($lettrue == false){
                $j = $j - 1;
                if( 0 >= $j){
                        if($checker == true){
                        $sugeneruotilaikai[] = ["Nera tokio laiko", $days[$i][0]];
                            $checker = false;
                        }
                    }
                }

            }
        }
        else{
            $j = 0;
            $checker = true;
            while($checker == true){
                // dd($days[$i][1][$j]);
                $time = $days[$i][1][$j];
                $minutes = $sprintMinutes[0];
                $hours = $sprintHours[0];
                if($time[0]->format("H:i") == "08:00"){
                    $wantedResult = $rezerve + $minutes + $hours * 60;
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'));
                    if ($wantedResult <= $timeResult){
                        $sugeneruotilaikai[] = [$time[0], $ending, "standup"];
                        // return [$time[0], $ending];
                        $checker = false;
                    }
                    // dd($timeResult);
                }
                else{
                    if($time[1]->format("H:i") == "19:00"){
                        $wantedResult = $rezerve + $minutes + $hours * 60;
                    }
                    else{
                    $wantedResult = $rezerve * 2 + $minutes + $hours * 60;
                    }
                    $timeResult = $time[0]->diff($time[1])->format("%H") * 60 + $time[0]->diff($time[1])->format('%i');
                    $starting = $time[0]->add(new DateInterval('PT'.$rezerve.'M'));
                    $ending = $time[0]->add(new DateInterval('PT'.$hours.'H'.$minutes + $rezerve.'M'));
                    if ($wantedResult <= $timeResult){
                        $sugeneruotilaikai[] = [$starting, $ending, "standup"];
                        // return [$starting, $ending];
                        $checker = false;
                    }
                }
                $j = $j + 1;
                if(count($days[$i][1]) <= $j){
                    if($checker == true){
                        $sugeneruotilaikai[] = ["Nera tokio laiko", $days[$i][0]];
                        $checker = false;
                    }
                }

            }
        }

    }
    return $sugeneruotilaikai;

}
}
