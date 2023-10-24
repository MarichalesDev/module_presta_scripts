<script>
//
{$script_header}

let textArea = document.getElementById("SCRIPT_HEADER");
let button = document.getElementById("BUTTON_SUBMIT");
let textAreaValue;

button.addEventListener("submit", (e)=>{
    e.preventDefault();

    function runScript(){
        textAreaValue = textArea.value
        let getTextToCode = eval(textAreaValue);

        return getTextToCode.replace(/&quot;/g, '\"');
    }
    runScript();
});

console.log('funciona header');
</script>
