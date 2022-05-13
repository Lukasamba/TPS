<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use App\TimeZones\TimeZones;

class CalendarController extends Controller
{
    public function openCalendarPage(){
        $viewData = $this->loadViewData();

        $graph = $this->getGraph();
        if (microtime(true) > session('tokenExpires')){
            return redirect('/signin');
        }
        // Get user's timezone
        $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);
        $startOfWeek = new \DateTimeImmutable('sunday -1 week', $timezone);
        $endOfWeek = new \DateTimeImmutable('sunday +1818 day', $timezone);
        $now = new \DateTimeImmutable('now', $timezone);
        $currentMonth = new \DateTimeImmutable('now', $timezone);
        //dd($now);
        $viewData['dateRange'] = $startOfWeek->format('M j, Y').' - '.$endOfWeek->format('M j, Y');

    $queryParams = array(
      'startDateTime' => $startOfWeek->format(\DateTimeInterface::ISO8601),
      'endDateTime' => $endOfWeek->format(\DateTimeInterface::ISO8601),
      // Only request the properties used by the app
      '$select' => 'subject,organizer,start,end,location,bodyPreview,categories',
      // Sort them by start time
      '$orderby' => 'start/dateTime'
      // Limit results to 25
      //'$top' => 25
    );

    // Append query parameters to the '/me/calendarView' url
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

    // dd($events);

    $formevents = [];
    // [
    //     [
    //         'title' => 'petras',
    //         'start' => '2022-05-12',
    //         //'end'=> '2022-05-12T12:30:00'
    //     ],
    //     [
    //         'title' => 'petras2',
    //         'start' => '2022-05-12',
    //         //'end'=> '2022-05-12T16:30:00'
    //     ]
    // ];

    foreach($events as $event) {
        if ($event->getCategories()[0] == 'Red category'){
            array_push($formevents, [
                // Add the email address in the emailAddress property
                'title' => $event->getSubject(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
                'location' => $event->getLocation()->getDisplayName(),
                'description' => $event->getBodyPreview(),
                'backgroundColor' => 'red',
                'borderColor' => 'red',
                'color' => 'red',
                'eventType' => 'Sprint Review :',
              ]);

        }
        elseif ($event->getCategories()[0] == 'Green category'){
            array_push($formevents, [
                // Add the email address in the emailAddress property
                'title' => $event->getSubject(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
                'location' => $event->getLocation()->getDisplayName(),
                'description' => $event->getBodyPreview(),
                'backgroundColor' => 'green',
                'borderColor' => 'green',
                'color' => 'green',
                'eventType' => 'Sprint Planning :',
              ]);

        }
        elseif ($event->getCategories()[0] == 'Orange category'){
            array_push($formevents, [
                // Add the email address in the emailAddress property
                'title' => $event->getSubject(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
                'location' => $event->getLocation()->getDisplayName(),
                'description' => $event->getBodyPreview(),
                'backgroundColor' => 'orange',
                'borderColor' => 'orange',
                'color' => 'orange',
                'eventType' => 'Retrospektyva :',
              ]);

        }
        elseif ($event->getCategories()[0] == 'Yellow category'){
            array_push($formevents, [
                // Add the email address in the emailAddress property
                'title' => $event->getSubject(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
                'location' => $event->getLocation()->getDisplayName(),
                'description' => $event->getBodyPreview(),
                'backgroundColor' => 'yellow',
                'borderColor' => 'yellow',
                'color' => 'yellow',
                'eventType' => 'Stand-Up :',
              ]);

        }
        else {
            array_push($formevents, [
                // Add the email address in the emailAddress property
                'title' => $event->getSubject(),
                'start' => $event->getStart()->getDateTime(),
                'end' => $event->getEnd()->getDateTime(),
                'location' => $event->getLocation()->getDisplayName(),
                'description' => $event->getBodyPreview(),
                'borderColor' => 'black',

                'eventType' => 'Simple Event :',
              ]);

        }
    }
    // foreach($formevents as $event){

    //     dd($event);
    // }

    // dd($formevents);
        //dd($viewData['events']);
        $viewData['events']=$formevents;
        $viewData['now']=$now->format('Y-m-d\TH:i:s');
        $viewData['currentMonth']=$currentMonth->format('Y-m');
        $viewData['timezone']=$timezone->getName();

        //return view('calendarpage')->with('timezone', $timezone->getName());
        return view('calendarpage', $viewData);
    }
    //\Carbon\Carbon::parse($event->getStart()->getDateTime())->format('n/j/y g:i A')
  public function calendarDummy()
  {
    $viewData = $this->loadViewData();

    $graph = $this->getGraph();
    if (microtime(true) > session('tokenExpires')){
        return redirect('/signin');
    }
    // Get user's timezone
    $timezone = TimeZones::getTzFromWindows($viewData['userTimeZone']);


    // Get start and end of week
    $startOfWeek = new \DateTimeImmutable('sunday -1 week', $timezone);
    $endOfWeek = new \DateTimeImmutable('sunday', $timezone);

    // $viewData['dateRange'] = $startOfWeek->format('M j, Y').' - '.$endOfWeek->format('M j, Y');
    $viewData['dateRange'] = $startOfWeek->format('M j, Y');

    $queryParams = array(
      'startDateTime' => $startOfWeek->format(\DateTimeInterface::ISO8601),
      'endDateTime' => $endOfWeek->format(\DateTimeInterface::ISO8601),
      // Only request the properties used by the app
      '$select' => 'subject,organizer,start,end',
      // Sort them by start time
      '$orderby' => 'start/dateTime',
      // Limit results to 25
      '$top' => 25
    );

    // Append query parameters to the '/me/calendarView' url
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



      $viewData['events'] = $events;
      return view('calendarDummy', $viewData);
  }

  private function getGraph(): Graph
  {
    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);
    return $graph;
  }
  public function getNewEventForm()
{
  $viewData = $this->loadViewData();

  return view('newevent', $viewData);
}
public function createNewEvent(Request $request)
{
  dd($request->eventSubject);
  // Validate required fields
  $request->validate([
    'eventSubject' => 'nullable|string',
    'eventAttendees' => 'nullable|string',
    'eventStart' => 'required|date',
    'eventEnd' => 'required|date',
    'eventBody' => 'nullable|string'
  ]);

  $viewData = $this->loadViewData();

  $graph = $this->getGraph();

  // Attendees from form are a semi-colon delimited list of
  // email addresses
  $attendeeAddresses = explode(';', $request->eventAttendees);

  // The Attendee object in Graph is complex, so build the structure
//   $attendees = [];
//   foreach($attendeeAddresses as $attendeeAddress)
//   {
//     array_push($attendees,
//       // Add the email address in the emailAddress property
//        $attendeeAddress);
//   }
  $attendees = [];
  foreach($attendeeAddresses as $attendeeAddress)
  {
    array_push($attendees, [
      // Add the email address in the emailAddress property
      'emailAddress' => [
        'address' => $attendeeAddress
      ],
      // Set the attendee type to required
      'type' => 'required'
    ]);
  }
  // Build the event
  $newEvent = [
    'subject' => $request->eventSubject,
    'attendees' => $attendees,
    'start' => [
      'dateTime' => $request->eventStart,
      'timeZone' => $viewData['userTimeZone']
    ],
    'end' => [
      'dateTime' => $request->eventEnd,
      'timeZone' => $viewData['userTimeZone']
    ],
    'body' => [
      'content' => $request->eventBody,
      'contentType' => 'text'
    ]
  ];
  // POST /me/events
  $response = $graph->createRequest('POST', '/me/events' )
    ->attachBody($newEvent)
    ->setReturnType(Model\Event::class)
    ->execute();

  return redirect('/calendarpage')->with('wtf', session('accessToken'));
}
}

