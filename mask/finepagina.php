<?php
?>
<SCRIPT>
  function urldelete(addr) {
			var avanti = confirm ("<?=_MSGSEISICURO_?>");
			if (avanti==true) {
					document.location.href=addr;
			}
	}
	function fasesearch(nomeform) {
    nomeform.cmd.value="<?=FASESEARCH?>";
    nomeform.action="<?=FILE_CORRENTE?>";
    nomeform.submit();
  }
</SCRIPT>
