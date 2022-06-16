<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

   
    <title>Calendar</title>

</head>
<body>

<script>


$(document).ready(function() {

$('#modal_new_event').click(function(){
        $('#id').val('')
        $('#NewEvent').text('New Event')
        $('#name').val('')
        $('#description').val('')
        $('#date').val('')
        $('#location').val('')
        $('#type').val('')
        $('#save_button').text('Save')
        $('#form').attr('action','{{route("save_event")}}')
})

@foreach ($events as $key=>$event)
  var calendarEl = document.getElementById('calendar_'+'{{$key}}');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    firstDay: 1,
    initialDate:new Date('{{ $event["start"] }}'),
    initialView: 'dayGridMonth',
    showNonCurrentDates:false,
    contentHeight: 'auto',
    fixedWeekCount: false,
    displayEventTime:false,
    headerToolbar: {
        start: '', // will normally be on the left. if RTL, will be on the right
        center: 'title',
        end: '' // will normally be on the right. if RTL, will be on the left
    },
    events: [
        @foreach ($event['events'] as $one_event)
        {
            id: '{{ $one_event->id }}',
            title: '{{ $one_event->name }}',
            start: new Date('{{ $one_event->date }}'),
            description: '{{ $one_event->description }}',
            date_time:'{{ $one_event->date }}',
            location: '{{ $one_event->location }}',
            type: '{{ $one_event->type }}',
            color: '{{ $one_event->type == "expert_visit" ? "red" : ($one_event->type == "conference" ? "green" : ($one_event->type == "vebinar" ? "blue" : "pink")) }}',
           
            //textColor: 'yellow'
        },
        @endforeach
    ],
    eventDisplay:'block',
    eventContent: function(arg){ 
        let italicEl = document.createElement('span')
        let italicE2 = document.createElement('span')
        italicEl.innerHTML = '<span class="edit" style="z-index:999">&#9998</span>'
        italicE2.innerHTML = arg.event.title
        let arrayOfDomNodes = [ italicEl,italicE2 ]
        return { domNodes: arrayOfDomNodes }
     },
    eventMouseEnter: function(info) {
        $(info.el).attr('data-toggle', 'tooltip');
        data = '<span class="name">Name - '+info.event.title+'</span><br>'+
        '<span class="description">Description - '+info.event.extendedProps.description+'</span><br>'+
        '<span class="date">Date - '+info.event.extendedProps.date_time+'</span><br>'+
        '<span class="location">Location - '+info.event.extendedProps.location+'</span><br>'+
        '<span class="type">Type - '+info.event.extendedProps.type.charAt(0).toUpperCase()+ info.event.extendedProps.type.replace('_', ' ').slice(1)+'</span>';
        $(info.el).tooltip({
            html: true,
            title: data,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
        });
    },
    eventClick: function(info) {
        $('#id').val(info.event.id)
        $('#NewEvent').text('Edit Event - '+info.event.title)
        $('#name').val(info.event.title)
        $('#description').val(info.event.extendedProps.description)
        var date = new Date(info.event.extendedProps.date_time);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        $('#date').val(date.toISOString().slice(0,16))
        $('#location').val(info.event.extendedProps.location)
        $('#type').val(info.event.extendedProps.type)
        $('#save_button').text('Edit')
        $('#form').attr('action','{{route("edit_event")}}')
        $('#new_event').modal('show')
    }})
  calendar.render();
  @endforeach
});

</script>
    <div class="row">
        <div class="col-md-12">
            <button type="button" id="modal_new_event" class="btn btn-primary" data-toggle="modal" data-target="#new_event">New Event</button>
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @elseif(session()->has('error'))
                <div class="alert alert-error">
                    {{ session()->get('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="modal fade" id="new_event" tabindex="-1" role="dialog" aria-labelledby="NewEvent" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="NewEvent">New Event</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{route('save_event')}}" id="form" method="post">
                        @csrf
                        <div class="modal-body">
                            <input type="text" name="id" class="form-control" id="id" hidden>
                            <div class="form-group">
                              <label for="name" class="col-form-label">Name:</label>
                              <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                              <label for="description" class="col-form-label">Description:</label>
                              <textarea class="form-control" name="description" id="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date" class="col-form-label">Date:</label>
                                <input type="datetime-local" name="date" class="form-control" id="date">
                            </div>
                            <div class="form-group">
                                <label for="location" class="col-form-label">Location:</label>
                                <input type="text" name="location" class="form-control" id="location">
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-form-label">Type of Event:</label>
                                <select id="type" name="type" class="form-control">
                                    <option selected disabled="disabled">Chose type of Event</option>
                                    <option value="expert_visit">Expert Visit</option>
                                    <option value="ask_answer">Ask Answer</option>
                                    <option value="conference">Conference</option>
                                    <option value="vebinar">Vebinar</option>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" id="save_button" class="btn btn-primary">Save Event</button>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    @foreach ($events as $key=>$event)
        <div class="col-md-4">
            <div id='calendar_{{$key}}'></div>
        </div>
    @endforeach
    </div>



</body>
</html>