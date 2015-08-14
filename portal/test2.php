
<!DOCTYPE html>
<html>
<body>
<?PHP

$a="hello";

?>
<script>
    function echoHello(){
        alert("<?PHP hello(); ?>");
    }
</script>

<?PHP
FUNCTION hello(){
    GLOBAL $a;
    ECHO $a;
}

?>

<button onclick="echoHello()">Say Hello</button>
</body>
</html>