<select name="datewithin" id="datewithin" class="form-control" onchange="datewithin(this.value)" style="font-size:10px;">
	<option value="5" <?php if($_REQUEST['datewithin']=='5') { echo "selected"; }?>>Any</option>
	<option value="1" <?php if($_REQUEST['datewithin']=='1') { echo "selected"; }?>>Today</option>
	<option value="2" <?php if($_REQUEST['datewithin']=='2') { echo "selected"; }?>>This Week</option>
	<option value="3" <?php if($_REQUEST['datewithin']=='3') { echo "selected"; }?>>This Month</option>
	<option value="4" <?php if($_REQUEST['datewithin']=='4') { echo "selected"; }?>>This Year</option>
</select>
