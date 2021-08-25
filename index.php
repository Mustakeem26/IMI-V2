<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <title></title>
            <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.0/dist/chart.min.js"></script>
    </head>
    <body>
        <h1>62107677 Mustakeem Laehlong</h1>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <canvas id="myChart" width="400" height="200"></canvas>
                </div>
                <div class="col-6">
                    <canvas id="myChart2" width="400" height="200"></canvas>
                </div>
            </div>    

            <div class="row">
                <div class="col-3">
                    <div class="row">
                        <div class="col-4"><b>Temperature</b></div>
                        <div class="col-8"><span id="LastTemperature"> </span></div>
                    </div>
                    <div class="row">
                        <div class="col-4"><b>Humidity</b></div>
                        <div class="col-8"><span id="LastHumidity"> </span></div>
                    </div>
                    <div class="row">
                        <div class="col-4">Update</div>
                        <div class="col-8"><span id="LastUpdate"> </span></div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
    <script>
        
        function showChart(data){
            var ctx = document.getElementById("myChart").getContext("2d");
            var myChart = new Chart(ctx,{
                type:'line',
                data:{
                    labels:data.xlabel,
                    datasets:[{
                        label:data.label,
                        data:data.data
                    }]
                }
            });
        }

        function showLine(data2){
            var ctxy = document.getElementById("myChart2").getContext("2d");
            var myChart = new Chart(ctxy,{
                type:'line',
                data:{
                    labels:data2.xlabel,
                    datasets:[{
                        label:data2.label,
                        data:data2.data
                    }]
                }
            });
        }

        $(()=>{
            let url = "https://api.thingspeak.com/channels/1458411/feeds.json?results=50";
            $.getJSON(url)
                .done(function(data){
                    let feed = data.feeds;
                    let chan = data.channel;
                    console.log(feed);

                    const d = new Date(feed[0].created_at);
                    const monthNames = ["January","February","March","April","May","July","August","September","October","November","December"];
                    let dateStr = d.getDate()+" "+monthNames[d.getMonth()]+" "+d.getFullYear();
                    dateStr += " "+d.getHours()+":"+d.getMinutes();
                    
                    var plot_data = Object();
                    var xlabel = [];
                    var temp = [];
                    var hum = [];

                    $.each(feed,(k,v)=>{
                        xlabel.push(v.entry_id);
                        hum.push(v.field1);
                        temp.push(v.field2);
                    });
                    var data = new Object();
                    data.xlabel = xlabel;
                    data.data = temp;
                    data.label = chan.field2;
                    showChart(data);

                    var data2 = new Object();
                    data2.xlabel = xlabel;
                    data2.data = hum;
                    data2.label = chan.field1;
                    showLine(data2);

                    $("#LastTemperature").text(feed[0].field2+ " C");
                    $("#LastHumidity").text(feed[0].field1+ " %");
                    $("#LastUpdate").text(dateStr);
                })
                .fail(function(error){

                });
        });
    </script>
</html>


