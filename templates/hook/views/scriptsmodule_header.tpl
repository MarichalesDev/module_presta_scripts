<script type="text/javascript">
console.log('funciona header');

let textArea = document.getElementById("SCRIPT_HEADER");
let textAreaValue;

        textAreaValue = textArea.value
        let getTextToCode = eval(textAreaValue);
        getTextToCode.replace(/&quot;/g, '\"');

{$script_header}

</script>