<script type="text/javascript">

function run() {
  let textArea = document.getElementById('SCRIPT_HEADER').value;
  eval(textArea.replace(/&quot;/g, '\"'));
}
run();

{$script_header}

</script>
