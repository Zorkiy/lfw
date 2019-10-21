<script language='JavaScript1.1' type='text/javascript'>
<!--
  function tag(text1, text2) 
  { 
     if ((document.selection)) 
     { 
       document.form.message.focus(); 
       document.form.document.selection.createRange().text = text1+document.form.document.selection.createRange().text+text2; 
     } else if(document.forms['form'].elements['message'].selectionStart != undefined) { 
         var element    = document.forms['form'].elements['message']; 
         var str     = element.value; 
         var start    = element.selectionStart; 
         var length    = element.selectionEnd - element.selectionStart; 
         element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length); 
     } else document.form.message.value += text1+text2; 
  }
  function click_link()
  {
    document.form.message.value = document.form.message.value + '<?php echo $str; ?>';
  }

//-->
</script>