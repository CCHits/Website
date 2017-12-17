<script>
    $( document ).ready( function() {
        {foreach $chart key=position item=track}{strip}                 
        var ctx = $('#graph-{$position}');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            backgroundColor: 'rgb(255, 0, 0)',
            data: {
                labels: [{foreach from=$track.arrChartData item=item name=sparkline}{if not $smarty.foreach.sparkline.first},{/if}{if $item.intPositionID != 'null'}-{/if}{$item.intPositionID}{/foreach}],
                datasets: [
                    {
                        label: "Chart {$position}", 
                        borderColor: 'rgb(255, 99, 132)', 
                        fill: 'bottom',
                        borderWidth: 2,
                        pointRadius: 0,
                        data: [ {foreach from=$track.arrChartData item=item name=sparkline}{if not $smarty.foreach.sparkline.first},{/if} {$item.intPositionID}{/foreach} ]
                    }
                ]
            },
            options: {
                elements: {
                    line: {
                        tension: 0
                    }
                },
                maintainAspectRatio: false,
                animation: {
                    duration: 0,
                },
                hover: {
                    animationDuration: 0,
                },
                responsiveAnimationDuration: 0,
                legend: {
                    display: false
                },
                title: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        display: false,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 1,
                            reverse: true,
                            suggestedMax: 100
                        }
                    }]
                }
            }
        });
        {/strip}{/foreach}
    } );
</script>
