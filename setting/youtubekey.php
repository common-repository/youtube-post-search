<?php
/* Setting Mega CLient Setting */
		echo "<h1>Youtube API Key</h1>";
		echo '<p>Input <b>Youtube API Key</b> for API Connection.<br/> This is Youtube API Key you can use: AIzaSyDAKDaBy_JDwcScSHqDQimOOLjdPImLanc<br/>But is better to get your own key from <a href="https://developers.google.com/youtube/v3/getting-started" target="_blank">Youtube Key</a>.</p><p>Place &lt;?php  youtubesearch(); ?&gt; in your template or place [youtubesearch] shortcode for search results page.</p>';
?>
<TABLE WIDTH="250px" ALIGN="LEFT" cellspacing="20">
<form method="POST">
   <TR ALIGN="LEFT">
	<TH style="padding:15px; margin:15px; border-radius:5px; border: 1px solid #EEEEEE; background:#DADADA; box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);">
			<label for="awesome_text" style="color:#00408C"><b>Youtube API Key: </b></label><br/>
			<input type="text" name="youtube_key" id="youtube_key" value="<?php echo $youtubekey; ?>"><br/>
            <label for="awesome_text" style="color:#00408C"><b>Number of Results: </b></label><br/>
			<input type="text" name="youtube_num" id="youtube_num" value="<?php echo $youtubenum; ?>"><br/>

	</TH>
   </TR>
<hr/>
   <TR>
      <TH ALIGN="LEFT">
	<input type="submit" value="Save" class="button button-primary button-large">
	</form>
      </TH>
   </TR>
</TABLE>
<hr/>
