<?php
//starting php coded needed
session_start();
define("BASE_PATH", "");

//required scripts
require_once("php_scripts/helper_functions.php");
require_once("php_scripts/database_queries.php");

$firstName = $lastName = $displayName = $email = $letter = $username = "";
if (logged_in()) {
  $firstName = $_SESSION["firstname"];
  $lastName = $_SESSION["lastname"];
  $displayName = $_SESSION["displayname"];
  $email = $_SESSION["email"];
  $username = $_SESSION["username"];
  $letter = $firstName[0];
}

?>

<?php require_once("basepagetop.php"); ?>
<div class="content">
  <div class="cloud">
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="js/d3.layout.cloud.js"></script>
    <svg></svg>
    <script>
      //https://github.com/jasondavies/d3-cloud
      function getRandomInt(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
      }

      var values = [];
      <?php
      $keywords = get_keyword_count();
      $total = 0;
      while ($keyword = get_row($keywords)) {
        $word = $keyword[0];
        $count = $keyword[1];
        echo ("values.push({text : '$word', size: $count});\n");
        $total += $count;
      }
      ?>
      var width = document.querySelector('.content').offsetWidth;
      var layout = d3.layout.cloud()
        .size([800, 800])
        .words(values.map(function(d) {
          return {
            text: d.text,
            size: d.size * 325 / <?php echo $total;?>,
            test: "haha"
          };
        }))
        .padding(5)
        .font("Impact")
        .fontSize(function(d) {
          return d.size;
        })
        .on("end", draw)
        .spiral("archimedean");

      layout.start();

      function draw(words) {
        d3.select("svg")
          .attr("width", layout.size()[0])
          .attr("height", layout.size()[1])
          .append("g")
          .attr("transform", "translate(" + layout.size()[0] / 2 + "," + layout.size()[1] / 2 + ")")
          .selectAll("text")
          .data(words)
          .enter()
          .append("a")
          .attr("href",function (d){
              return "search.php?search=" + d.text;
          })
          .append("text")
          .style("font-size", function(d) {
            return d.size + "px";
          })
          .style("font-family", "Impact")
          .style("fill", function(d) {
            return "rgb(" + getRandomInt(50, 200) + "," + getRandomInt(50, 200) + "," + getRandomInt(50, 200) + ")";
          })
          .attr("text-anchor", "middle")
          .attr("transform", function(d) {
            return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
          })
          .text(function(d) {
            return d.text;
          });
      }
    </script>
  </div>

</div>
<?php require_once("basepagebottom.php"); ?>
