<?php
require_once "sparqltemplate.php";


$qt = isset($_GET['qt']) ? $_GET['qt'] : 'SELECT ?s { ?s rdfs:label "{{ name }} {{ "now"|date("m/d/Y") }} ".}';
$ot = isset($_GET['ot']) ? $_GET['ot'] : '';
$p1n = isset($_GET['p1n']) ? $_GET['p1n'] : 'name';
$p1v = isset($_GET['p1v']) ? $_GET['p1v'] : 'World';
$p2n = isset($_GET['p2n']) ? $_GET['p2n'] : '';
$p2v = isset($_GET['p2v']) ? $_GET['p2v'] : '';
$p3n = isset($_GET['p3n']) ? $_GET['p3n'] : '';
$p3v = isset($_GET['p3v']) ? $_GET['p3v'] : '';



?>
<html>
<head>

</head>
<body>
	<form action="" method="GET">
		<p>
		<label for="qt">Query Template</label><br>
		<textarea name="qt" id="qt" rows="10" cols="120"><?php echo htmlspecialchars($qt); ?></textarea>
		</p>

		<p>
		<label for="p1n">Parameter 1 name: <input type="text" name="p1n" id="p1n" value="<?php echo htmlspecialchars($p1n); ?>"></label>
		<label for="p1v">value: <input type="text" name="p1v" id="p1v" value="<?php echo htmlspecialchars($p1v); ?>"></label>
		</p>		
		<p>
		<label for="p2n">Parameter 2 name: <input type="text" name="p2n" id="p2n" value="<?php echo htmlspecialchars($p2n); ?>"></label>
		<label for="p2v">value: <input type="text" name="p2v" id="p2v" value="<?php echo htmlspecialchars($p2v); ?>"></label>
		</p>		
		<p>
		<label for="p3n">Parameter 3 name: <input type="text" name="p3n" id="p3n" value="<?php echo htmlspecialchars($p3n); ?>"></label>
		<label for="p3v">value: <input type="text" name="p3v" id="p3v" value="<?php echo htmlspecialchars($p3v); ?>"></label>
		</p>		
    <p>
    <label for="ot">Output Template</label><br>
    <textarea name="ot" id="ot" rows="10" cols="120"><?php echo htmlspecialchars($ot); ?></textarea>
    </p>

		<p>
		<input type="submit">
		</p>
	</form>

  <p><strong>Hints</strong></p>
  <ul>
    <li>Parameters are substituted using {{param}} notation</li>
    <li>Apply filters to params by using the pipe: {{ param|default('Jim')|title }}</li>
    <li>Filter examples:
      <ul>
        <li><strong>date</strong> filter formats a date: {{ param|date("m/d/Y") }}, use the word "now" to get the current time: {{ "now"|date("d/m/Y") }}. Format strings are as per the <a href="http://uk.php.net/manual/en/function.date.php">PHP date function</a></li>
        <li><strong>format</strong> filter formats a given string like <a href="http://uk.php.net/manual/en/function.printf.php">PHP's printf function</a>: {{ "I like %s and %s."|format(param, "bar") }}</li>
        <li><strong>number_format</strong> filter formats a given string like <a href="http://uk.php.net/manual/en/function.number_format.php">PHP's number_format function</a>: {{ param|number_format(2, ',', '.') }}
        <li><strong>title</strong> filter formats the string as titlecased: {{ param|title) }}</li>
        <li><strong>capitalize</strong> filter capitalizes the first character of a string: {{ param|capitalize) }}</li>
        <li><strong>upper</strong> filter formats the string as all uppercase: {{ param|upper) }}</li>
        <li><strong>lower</strong> filter formats the string as all lowercase: {{ param|lower) }}</li>
        <li><strong>default</strong> filter returns the passed default value if the value is undefined or empty, otherwise the value of the variable: {{ param|default('var is not defined')) }}</li>

    </ul>
    <li>Functions are supported: {{ random(['apple', 'orange', 'citrus']) }}</li>
    <li>Function examples:
      <ul>
        <li><strong>random</strong> function returns a random item from a sequence: {{ random(['apple', 'orange', 'citrus']) }}</li>
      </ul>
    </li>
  </li>

  </ul>


<?php
	if ($qt) {
		$params = array();
		if ($p1n) {
			$params[$p1n] = $p1v;
		}
		if ($p2n) {
			$params[$p2n] = $p2v;
		}
		if ($p3n) {
			$params[$p3n] = $p3v;
		}
    $st = new SparqlTemplate($qt, $ot);
		echo "<h2>Formatted query:</h2>";

    echo "<pre>" . htmlspecialchars($st->format_query($params)) . "</pre>";

    echo "<h2>Generated results:</h2>";
    echo "<pre>" . htmlspecialchars($st->execute($params, 'http://api.kasabi.com/dataset/bricklink/apis/sparql', '9c8c5146bab8150498aef9bdf7075a3209b20abb')) . "</pre>";

	}
?>
</body>

</html>