<!DOCTYPE html>
<html>
  <head>
    <title>Coffter</title>
    <meta charset="utf-8">
    <script src="node_modules/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="node_modules/chartkick/chartkick.js"></script>
    <script>
      Chartkick.options = {
        discrete: true,
        colors: ["#b00", "#666"],
        label: 'Kuppia',
      }
    </script>
  </head>
  <body>
    <p>tänään <?php echo $today ?></p>
    <p>tällä wiikolla <?php echo $week ?></p>
    <p>tässä kuussa <?php echo $month ?></p>
    <p>vuodessa <?php echo $year ?></p>

    <h1>Week by hour</h1>
    <div id="week" style="height: 300px;"></div>
    <script>
      new Chartkick.LineChart( 'week', <?php echo $charts['week'] ?> );
    </script>
    <h1>Month by day</h1>
    <div id="month" style="height: 300px;"></div>
    <script>
      new Chartkick.LineChart( 'month', <?php echo $charts['month'] ?> );
    </script>
    <h1>Six months by week</h1>
    <div id="halfyear" style="height: 300px;"></div>
    <script>
      new Chartkick.LineChart( 'halfyear', <?php echo $charts['halfyear'] ?> );
    </script>
  </body>
</html>
