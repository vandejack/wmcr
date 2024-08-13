<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>jq.Schedule Demo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="/assets/js/jquery.schedule-master/dist/css/style.min.css" />
    
        <style>
            body {
                padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
            #logs{
                border: solid 1px #bbb;
                padding: 16px;
                background: #eee;
            }
            #logs .table{
                margin-bottom: 0;
            }
            #logs .table td,
            #logs .table th{
                border: none;
            }
            #schedule .sc_bar_insert{
                background-color: #ff678a;
            }
            #schedule .example2{
                background-color: #3eb698;
            }
            #schedule .example3{
                color: #2c0000;
                font-weight: bold;
                background-color: #c7ae50;
            }
            #schedule .sc_bar.sc_bar_photo .head,
            #schedule .sc_bar.sc_bar_photo .text{
                padding-left: 60px;
            }
            #schedule .sc_bar.sc_bar_photo .photo{
                position: absolute;
                left: 10px;
                top: 10px;
                width: 38px;
            }
            #schedule .sc_bar.sc_bar_photo .photo img{
                max-width: 100%;
            }
        </style>
    </head>
    <body>
<div id="schedule"></div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="/assets/js/jquery.schedule-master/src/js/jq.schedule.js"></script>
<script type="text/javascript">
    function addLog(type, message){
        var $log = $('<tr />');
        $log.append($('<th />').text(type));
        $log.append($('<td />').text(message ? JSON.stringify(message) : ''));
        $("#logs table").prepend($log);
    }
    $(function(){
        $("#logs").append('<table class="table">');
        var isDraggable = true;
        var isResizable = true;
        var $sc = $("#schedule").timeSchedule({
            startTime: "07:00", // schedule start time(HH:ii)
            endTime: "21:00",   // schedule end time(HH:ii)
            widthTime: 60 * 10,  // cell timestamp example 10 minutes
            timeLineY: 60,       // height(px)
            verticalScrollbar: 20,   // scrollbar (px)
            timeLineBorder: 2,   // border(top and bottom)
            bundleMoveWidth: 6,  // width to move all schedules to the right of the clicked time line cell
            draggable: isDraggable,
            resizable: isResizable,
            resizableLeft: true,
            rows : {
                '0' : {
                    title : 'Title Area1',
                    schedule:[
                        {
                            start: '09:00',
                            end: '12:00',
                            text: 'Text Area',
                            data: {
                            }
                        },
                        {
                            start: '11:00',
                            end: '14:00',
                            text: 'Text Area',
                            data: {
                            }
                        }
                    ]
                },
                '1' : {
                    title : 'Title Area2',
                    schedule:[
                        {
                            start: '16:00',
                            end: '17:00',
                            text: 'Text Area',
                            data: {
                            }
                        }
                    ]
                }
            },
            onChange: function(node, data){
                addLog('onChange', data);
            },
            onInitRow: function(node, data){
                addLog('onInitRow', data);
            },
            onClick: function(node, data){
                addLog('onClick', data);
            },
            onAppendRow: function(node, data){
                addLog('onAppendRow', data);
            },
            onAppendSchedule: function(node, data){
                addLog('onAppendSchedule', data);
                if(data.data.class){
                    node.addClass(data.data.class);
                }
                if(data.data.image){
                    var $img = $('<div class="photo"><img></div>');
                    $img.find('img').attr('src', data.data.image);
                    node.prepend($img);
                    node.addClass('sc_bar_photo');
                }
            },
            onScheduleClick: function(node, time, timeline){
                var start = time;
                var end = $(this).timeSchedule('formatTime', $(this).timeSchedule('calcStringTime', time) + 3600);
                $(this).timeSchedule('addSchedule', timeline, {
                    start: start,
                    end: end,
                    text:'Insert Schedule',
                    data:{
                        class: 'sc_bar_insert'
                    }
                });
                addLog('onScheduleClick', time + ' ' + timeline);
            },
        });
        $('#event_timelineData').on('click', function(){
            addLog('timelineData', $sc.timeSchedule('timelineData'));
        });
        $('#event_scheduleData').on('click', function(){
            addLog('scheduleData', $sc.timeSchedule('scheduleData'));
        });
        $('#event_resetData').on('click', function(){
            $sc.timeSchedule('resetData');
            addLog('resetData');
        });
        $('#event_resetRowData').on('click', function(){
            $sc.timeSchedule('resetRowData');
            addLog('resetRowData');
        });
        $('#event_setDraggable').on('click', function(){
            isDraggable = !isDraggable;
            $sc.timeSchedule('setDraggable', isDraggable);
            addLog('setDraggable', isDraggable ? 'enable' : 'disable');
        });
        $('#event_setResizable').on('click', function(){
            isResizable = !isResizable;
            $sc.timeSchedule('setResizable', isResizable);
            addLog('setResizable', isResizable ? 'enable' : 'disable');
        });
        $('.ajax-data').on('click', function(){
            $.ajax({url: './data/'+$(this).attr('data-target')})
                .done( (data) => {
                    addLog('Ajax GetData', data);
                    $sc.timeSchedule('setRows', data);
                });
        });
        $('#clear-logs').on('click', function(){
            $('#logs .table').empty();
        });
    });
</script>
</body>
</html>
